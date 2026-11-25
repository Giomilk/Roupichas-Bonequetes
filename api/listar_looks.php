<?php
/**
 * Roupichas-Bonequetes
 * API para Listar, Favoritar e Excluir Looks
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/logger.php';

try {
    $pdo = getConexao();
    $metodo = $_SERVER['REQUEST_METHOD'];
    $action = $_REQUEST['action'] ?? 'listar';

    // 1. FAVORITAR LOOK
    if ($action === 'favoritar' && $metodo === 'POST') {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID de look inválido.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE looks SET favoritos = favoritos + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $usuarioId = $_SESSION['usuario_id'] ?? null;
        $usuarioNome = $_SESSION['usuario_nome'] ?? 'Visitante';
        registrarLog('FAVORITAR_LOOK', "Look #{$id} recebeu um favorito ♡", $usuarioId, $usuarioNome);

        $stmtFav = $pdo->prepare("SELECT favoritos FROM looks WHERE id = :id");
        $stmtFav->execute([':id' => $id]);
        $totalFav = $stmtFav->fetchColumn();

        echo json_encode(['sucesso' => true, 'mensagem' => 'Look favoritado com carinho! ♡', 'favoritos' => $totalFav], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 2. EXCLUIR LOOK
    if ($action === 'excluir' && $metodo === 'POST') {
        $id = intval($_POST['id'] ?? 0);
        $usuarioId = $_SESSION['usuario_id'] ?? null;

        $stmt = $pdo->prepare("SELECT id, usuario_id, titulo FROM looks WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $look = $stmt->fetch();

        if (!$look) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Look não encontrado.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Permite excluir se for o dono ou admin
        $ehDono = ($usuarioId && $look['usuario_id'] == $usuarioId);
        $ehAdmin = (isset($_SESSION['usuario_email']) && $_SESSION['usuario_email'] === 'admin@bonequetes.com');

        if (!$ehDono && !$ehAdmin && $look['usuario_id'] !== null) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Você não tem permissão para excluir este look.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $stmtDel = $pdo->prepare("DELETE FROM looks WHERE id = :id");
        $stmtDel->execute([':id' => $id]);

        registrarLog('EXCLUIR_LOOK', "Look #{$id} ('{$look['titulo']}') foi excluído.", $usuarioId, $_SESSION['usuario_nome'] ?? 'Visitante');

        echo json_encode(['sucesso' => true, 'mensagem' => 'Look removido da galeria.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 3. LISTAR LOOKS
    $filtro = $_GET['filtro'] ?? 'recentes';
    $usuarioLogadoId = $_SESSION['usuario_id'] ?? null;

    $sql = "SELECT id, usuario_id, usuario_nome, titulo, base_avatar, tom_pele, genero, config_json, imagem_preview, favoritos, criado_em FROM looks ";

    if ($filtro === 'meus' && $usuarioLogadoId) {
        $sql .= "WHERE usuario_id = " . intval($usuarioLogadoId) . " ";
    }

    if ($filtro === 'populares') {
        $sql .= "ORDER BY favoritos DESC, id DESC LIMIT 50";
    } else {
        $sql .= "ORDER BY id DESC LIMIT 50";
    }

    $stmt = $pdo->query($sql);
    $looks = $stmt->fetchAll();

    echo json_encode([
        'sucesso' => true,
        'total'   => count($looks),
        'looks'   => $looks
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao listar looks: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
