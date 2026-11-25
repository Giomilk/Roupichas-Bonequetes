<?php
/**
 * Roupichas-Bonequetes
 * API de Histórico e Auditoria
 *
 * Requisito CORE:
 * "O sistema deve ter uma página de históricos e manter sempre os logs de qualquer alteração
 * feita por qualquer usuário, para auditorias futuras."
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/bd.php';

try {
    $pdo = getConexao();

    $busca = trim($_GET['busca'] ?? '');
    $acao = trim($_GET['acao'] ?? '');
    $limite = min(100, max(10, intval($_GET['limite'] ?? 50)));
    $pagina = max(1, intval($_GET['pagina'] ?? 1));
    $offset = ($pagina - 1) * $limite;

    $where = [];
    $params = [];

    if (!empty($acao)) {
        $where[] = "acao = :acao";
        $params[':acao'] = strtoupper($acao);
    }

    if (!empty($busca)) {
        $where[] = "(usuario_nome LIKE :busca OR detalhes LIKE :busca OR ip LIKE :busca)";
        $params[':busca'] = "%{$busca}%";
    }

    $whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    // Contagem total
    $countSql = "SELECT COUNT(*) FROM logs {$whereSql}";
    $stmtCount = $pdo->prepare($countSql);
    $stmtCount->execute($params);
    $totalRegistros = $stmtCount->fetchColumn();

    // Registros paginados
    $sql = "SELECT id, usuario_id, usuario_nome, acao, detalhes, ip, user_agent, data_hora 
            FROM logs 
            {$whereSql} 
            ORDER BY id DESC 
            LIMIT {$limite} OFFSET {$offset}";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $logs = $stmt->fetchAll();

    // Lista de ações distintas disponíveis para filtros
    $stmtAcoes = $pdo->query("SELECT DISTINCT acao FROM logs ORDER BY acao ASC");
    $acoesDisponiveis = $stmtAcoes->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'sucesso'          => true,
        'total'            => intval($totalRegistros),
        'pagina_atual'     => $pagina,
        'total_paginas'    => ceil($totalRegistros / $limite),
        'acoes_disponiveis'=> $acoesDisponiveis,
        'logs'             => $logs
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao consultar auditoria: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
