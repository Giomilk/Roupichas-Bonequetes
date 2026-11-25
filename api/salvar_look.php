<?php
/**
 * Roupichas-Bonequetes
 * API para Salvar Looks Criados
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/logger.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo = getConexao();

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        $input = $_POST;
    }

    $titulo = trim($input['titulo'] ?? 'Meu Look Fofinho ♡');
    $baseAvatar = trim($input['base_avatar'] ?? 'base_feminino_claro.png');
    $tomPele = trim($input['tom_pele'] ?? 'claro');
    $genero = trim($input['genero'] ?? 'feminino');

    $cabeloId = !empty($input['cabelo_id']) ? intval($input['cabelo_id']) : null;
    $cabeloCor = trim($input['cabelo_cor'] ?? '#4a2c11');
    $rostoId = !empty($input['rosto_id']) ? intval($input['rosto_id']) : null;
    $blusaId = !empty($input['blusa_id']) ? intval($input['blusa_id']) : null;
    $calcaId = !empty($input['calca_id']) ? intval($input['calca_id']) : null;
    $vestidoId = !empty($input['vestido_id']) ? intval($input['vestido_id']) : null;
    $sapatoId = !empty($input['sapato_id']) ? intval($input['sapato_id']) : null;
    $acessorioId = !empty($input['acessorio_id']) ? intval($input['acessorio_id']) : null;
    $cenarioId = !empty($input['cenario_id']) ? intval($input['cenario_id']) : null;

    $configJson = is_array($input['config_json'] ?? null) 
        ? json_encode($input['config_json'], JSON_UNESCAPED_UNICODE) 
        : ($input['config_json'] ?? '{}');

    // Processa imagem preview base64 se enviada
    $imagemPreview = 'assets/img/mascote/mascote_chibi.png';
    $base64Img = $input['imagem_preview'] ?? '';

    if (!empty($base64Img) && strpos($base64Img, 'base64,') !== false) {
        $partes = explode('base64,', $base64Img);
        $imgData = base64_decode($partes[1]);
        if ($imgData !== false) {
            $looksDir = __DIR__ . '/../uploads/looks/';
            if (!is_dir($looksDir)) {
                mkdir($looksDir, 0777, true);
            }
            $nomePreview = 'look_' . uniqid() . '.png';
            file_put_contents($looksDir . $nomePreview, $imgData);
            $imagemPreview = 'uploads/looks/' . $nomePreview;
        }
    }

    $usuarioId = $_SESSION['usuario_id'] ?? null;
    $usuarioNome = $_SESSION['usuario_nome'] ?? 'Estilista Anônimo';

    $stmt = $pdo->prepare("
        INSERT INTO looks (
            usuario_id, usuario_nome, titulo, base_avatar, tom_pele, genero,
            cabelo_id, cabelo_cor, rosto_id, blusa_id, calca_id, vestido_id,
            sapato_id, acessorio_id, cenario_id, config_json, imagem_preview
        ) VALUES (
            :usuario_id, :usuario_nome, :titulo, :base_avatar, :tom_pele, :genero,
            :cabelo_id, :cabelo_cor, :rosto_id, :blusa_id, :calca_id, :vestido_id,
            :sapato_id, :acessorio_id, :cenario_id, :config_json, :imagem_preview
        )
    ");

    $stmt->execute([
        ':usuario_id'     => $usuarioId,
        ':usuario_nome'   => $usuarioNome,
        ':titulo'         => $titulo,
        ':base_avatar'    => $baseAvatar,
        ':tom_pele'       => $tomPele,
        ':genero'         => $genero,
        ':cabelo_id'      => $cabeloId,
        ':cabelo_cor'     => $cabeloCor,
        ':rosto_id'       => $rostoId,
        ':blusa_id'       => $blusaId,
        ':calca_id'       => $calcaId,
        ':vestido_id'     => $vestidoId,
        ':sapato_id'      => $sapatoId,
        ':acessorio_id'   => $acessorioId,
        ':cenario_id'     => $cenarioId,
        ':config_json'    => $configJson,
        ':imagem_preview' => $imagemPreview
    ]);

    $lookId = $pdo->lastInsertId();

    // Auditoria
    registrarLog(
        'CRIAR_LOOK',
        "Look salvo: '{$titulo}' por {$usuarioNome}. ID: {$lookId}",
        $usuarioId,
        $usuarioNome
    );

    echo json_encode([
        'sucesso'  => true,
        'mensagem' => "Look '{$titulo}' salvo com amor na sua galeria! ♡",
        'look_id'  => $lookId,
        'preview'  => $imagemPreview
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar look: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
