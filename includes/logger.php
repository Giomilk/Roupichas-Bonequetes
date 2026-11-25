<?php
/**
 * Roupichas-Bonequetes
 * Sistema de Auditoria e Logs
 *
 * Requisito CORE:
 * "O sistema deve ter uma página de históricos e manter sempre os logs
 * de qualquer alteração feita por qualquer usuário, para auditorias futuras."
 */

require_once __DIR__ . '/../config/bd.php';

/**
 * Registra um evento de auditoria no banco de dados via PDO
 *
 * @param string $acao Nome identificador da ação (ex: CADASTRO, LOGIN, UPLOAD_SPRITE, SALVAR_LOOK)
 * @param string|array $detalhes Informações contextuais adicionais
 * @param int|null $usuarioId ID do usuário (se omitido, busca da sessão atual)
 * @param string|null $usuarioNome Nome do usuário (se omitido, busca da sessão atual)
 * @return bool
 */
function registrarLog($acao, $detalhes = '', $usuarioId = null, $usuarioNome = null) {
    try {
        $pdo = getConexao();

        // Se não fornecido, obtém da sessão ativa
        if ($usuarioId === null && isset($_SESSION['usuario_id'])) {
            $usuarioId = $_SESSION['usuario_id'];
        }
        if ($usuarioNome === null) {
            $usuarioNome = $_SESSION['usuario_nome'] ?? 'Visitante';
        }

        // Se detalhes for array ou objeto, serializa em JSON amigável
        if (is_array($detalhes) || is_object($detalhes)) {
            $detalhes = json_encode($detalhes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        // Captura IP e Navegador
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        // Se houver múltiplos IPs no proxy, pega o primeiro
        if (strpos($ip, ',') !== false) {
            $ip = trim(explode(',', $ip)[0]);
        }
        $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido', 0, 255);

        $stmt = $pdo->prepare("
            INSERT INTO logs (usuario_id, usuario_nome, acao, detalhes, ip, user_agent, data_hora)
            VALUES (:usuario_id, :usuario_nome, :acao, :detalhes, :ip, :user_agent, :data_hora)
        ");

        $dataHora = date('Y-m-d H:i:s');

        return $stmt->execute([
            ':usuario_id'   => $usuarioId,
            ':usuario_nome' => $usuarioNome,
            ':acao'         => strtoupper($acao),
            ':detalhes'     => (string)$detalhes,
            ':ip'           => $ip,
            ':user_agent'   => $userAgent,
            ':data_hora'    => $dataHora
        ]);
    } catch (Exception $e) {
        // Falha silenciosa para não quebrar a aplicação principal caso haja erro no log,
        // mas registrando no error_log do PHP
        error_log("Erro ao gravar log de auditoria: " . $e->getMessage());
        return false;
    }
}
