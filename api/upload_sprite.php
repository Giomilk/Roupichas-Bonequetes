<?php
/**
 * Roupichas-Bonequetes
 * API de Upload de Sprites PNG Customizadas
 *
 * Requisito CORE:
 * "QUALQUER PESSOA PODE ENVIAR AS SPRITES EM PNG PRA PERSONALIZAR TODOS OS
 * ITENS PERSONALIZAVEIS. NÃO SE LIMITANDO AOS ITENS JA ACRESCIDOS NO JOGO."
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/logger.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido. Use POST.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo = getConexao();

    $nome = trim($_POST['nome'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $subcategoria = trim($_POST['subcategoria'] ?? 'custom');

    $categoriasValidas = ['cabelos', 'rostos', 'blusas', 'calcas', 'vestidos', 'sapatos', 'acessorios', 'cenarios'];

    if (empty($nome) || mb_strlen($nome) < 2) {
        http_response_code(400);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Dê um nome fofinho para a sua sprite (mínimo 2 letras)!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!in_array($categoria, $categoriasValidas)) {
        http_response_code(400);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Selecione uma categoria válida para a sua peça.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!isset($_FILES['sprite_file']) || $_FILES['sprite_file']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        $erroMsg = 'Nenhum arquivo enviado ou erro no upload.';
        if (isset($_FILES['sprite_file']['error'])) {
            switch ($_FILES['sprite_file']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $erroMsg = 'A imagem é muito grande! Envie um PNG de até 10MB.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $erroMsg = 'Selecione um arquivo PNG para enviar.';
                    break;
            }
        }
        echo json_encode(['sucesso' => false, 'mensagem' => $erroMsg], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $file = $_FILES['sprite_file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // REQUISITO: OBRIGATÓRIO SER PNG
    if ($ext !== 'png') {
        http_response_code(400);
        echo json_encode(['sucesso' => false, 'mensagem' => 'O arquivo DEVE ser uma sprite no formato PNG transparente!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Valida MIME Type real
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if ($mimeType !== 'image/png') {
        http_response_code(400);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Arquivo inválido. O conteúdo deve ser uma imagem PNG legítima.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Diretório de destino
    $uploadDir = __DIR__ . '/../uploads/sprites/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Nome único seguro
    $safeName = preg_replace('/[^a-z0-9_-]/i', '_', $nome);
    $novoNomeArquivo = 'sprite_' . $categoria . '_' . uniqid() . '_' . substr($safeName, 0, 30) . '.png';
    $caminhoDestino = $uploadDir . $novoNomeArquivo;

    if (!move_uploaded_file($file['tmp_name'], $caminhoDestino)) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Falha ao salvar a imagem no servidor.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $caminhoRelativo = 'uploads/sprites/' . $novoNomeArquivo;
    $usuarioId = $_SESSION['usuario_id'] ?? null;
    $usuarioNome = $_SESSION['usuario_nome'] ?? 'Visitante';

    // Insere no Catálogo
    $stmt = $pdo->prepare("
        INSERT INTO itens (nome, categoria, subcategoria, imagem, miniatura, usuario_id, is_padrao)
        VALUES (:nome, :categoria, :subcategoria, :imagem, :miniatura, :usuario_id, 0)
    ");

    $stmt->execute([
        ':nome'         => $nome,
        ':categoria'    => $categoria,
        ':subcategoria' => $subcategoria,
        ':imagem'       => $caminhoRelativo,
        ':miniatura'    => $caminhoRelativo,
        ':usuario_id'   => $usuarioId
    ]);

    $itemId = $pdo->lastInsertId();

    // Auditoria obrigatória
    registrarLog(
        'UPLOAD_SPRITE',
        "Nova sprite enviada: '{$nome}' na categoria '{$categoria}'. Arquivo: {$novoNomeArquivo}",
        $usuarioId,
        $usuarioNome
    );

    echo json_encode([
        'sucesso'  => true,
        'mensagem' => "Sua sprite '{$nome}' foi adicionada com sucesso ao guarda-roupa! ♡",
        'item'     => [
            'id'           => $itemId,
            'nome'         => $nome,
            'categoria'    => $categoria,
            'subcategoria' => $subcategoria,
            'imagem'       => $caminhoRelativo,
            'miniatura'    => $caminhoRelativo,
            'is_padrao'    => 0
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno ao processar upload: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
