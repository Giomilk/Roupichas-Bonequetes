<?php
/**
 * Roupichas-Bonequetes
 * API de Catálogo de Itens Personalizáveis
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/bd.php';

try {
    $pdo = getConexao();

    $categoria = trim($_GET['categoria'] ?? '');
    $categoriasValidas = ['cabelos', 'rostos', 'blusas', 'calcas', 'vestidos', 'sapatos', 'acessorios', 'cenarios'];

    if (!empty($categoria) && in_array($categoria, $categoriasValidas)) {
        $stmt = $pdo->prepare("
            SELECT id, nome, categoria, subcategoria, imagem, miniatura, usuario_id, is_padrao
            FROM itens
            WHERE categoria = :categoria AND ativo = 1
            ORDER BY is_padrao DESC, id DESC
        ");
        $stmt->execute([':categoria' => $categoria]);
    } else {
        // Retorna agrupado por categoria
        $stmt = $pdo->query("
            SELECT id, nome, categoria, subcategoria, imagem, miniatura, usuario_id, is_padrao
            FROM itens
            WHERE ativo = 1
            ORDER BY categoria ASC, is_padrao DESC, id DESC
        ");
    }

    $itens = $stmt->fetchAll();

    echo json_encode([
        'sucesso' => true,
        'total'   => count($itens),
        'itens'   => $itens
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso'  => false,
        'mensagem' => 'Erro ao carregar itens: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
