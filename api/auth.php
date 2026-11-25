<?php
/**
 * Roupichas-Bonequetes
 * API de Autenticação de Usuários
 *
 * Requisito CORE:
 * "Tratar senhas de usuários com hash bcrypt"
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/logger.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$action = $_REQUEST['action'] ?? '';

// Função auxiliar de resposta padronizada
function jsonResponse($success, $message, $data = [], $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode([
        'sucesso'  => $success,
        'mensagem' => $message,
        'dados'    => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo = getConexao();

    // 1. CHECAGEM DE SESSÃO ATIVA
    if ($action === 'check') {
        if (!empty($_SESSION['usuario_id'])) {
            jsonResponse(true, 'Usuário autenticado.', [
                'autenticado' => true,
                'usuario' => [
                    'id'    => $_SESSION['usuario_id'],
                    'nome'  => $_SESSION['usuario_nome'],
                    'email' => $_SESSION['usuario_email'],
                    'foto'  => $_SESSION['usuario_foto'] ?? 'assets/img/mascote/mascote_chibi.png'
                ]
            ]);
        } else {
            jsonResponse(true, 'Nenhuma sessão ativa.', [
                'autenticado' => false,
                'usuario' => null
            ]);
        }
    }

    // 2. CADASTRO DE NOVO USUÁRIO (com Hash BCRYPT)
    if ($action === 'cadastro' && $metodo === 'POST') {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
        $senha = $_POST['senha'] ?? '';

        if (empty($nome) || mb_strlen($nome) < 3) {
            jsonResponse(false, 'Por favor, informe seu nome completo (mínimo 3 caracteres).', [], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            jsonResponse(false, 'Por favor, informe um endereço de e-mail válido.', [], 400);
        }

        if (empty($senha) || strlen($senha) < 6) {
            jsonResponse(false, 'A senha deve conter pelo menos 6 caracteres.', [], 400);
        }

        // Verifica se o e-mail já existe
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmtCheck->execute([':email' => $email]);
        if ($stmtCheck->fetch()) {
            jsonResponse(false, 'Este endereço de e-mail já está cadastrado no sistema.', [], 409);
        }

        // HASH BCRYPT OBRIGATÓRIO (Segurança de senhas)
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 10]);

        $stmtInsert = $pdo->prepare("
            INSERT INTO usuarios (nome, email, senha, foto_perfil)
            VALUES (:nome, :email, :senha, :foto)
        ");

        $foto = 'assets/img/mascote/mascote_chibi.png';
        $stmtInsert->execute([
            ':nome'  => $nome,
            ':email' => $email,
            ':senha' => $senhaHash,
            ':foto'  => $foto
        ]);

        $novoId = $pdo->lastInsertId();

        // Autentica o usuário na sessão
        $_SESSION['usuario_id'] = $novoId;
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_email'] = $email;
        $_SESSION['usuario_foto'] = $foto;

        // Auditoria: registra criação de conta
        registrarLog('CADASTRO_USUARIO', "Novo estilista cadastrado: {$nome} ({$email})", $novoId, $nome);

        jsonResponse(true, "Bem-vindo ao Roupichas-Bonequetes, {$nome}! Sua conta foi criada com sucesso ♡", [
            'id'    => $novoId,
            'nome'  => $nome,
            'email' => $email,
            'foto'  => $foto
        ], 201);
    }

    // 3. LOGIN DE USUÁRIO (com Validação BCRYPT)
    if ($action === 'login' && $metodo === 'POST') {
        $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            jsonResponse(false, 'Preencha o e-mail e a senha para entrar.', [], 400);
        }

        $stmt = $pdo->prepare("SELECT id, nome, email, senha, foto_perfil FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        // Verifica o hash da senha
        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            registrarLog('LOGIN_FALHA', "Tentativa falha de login para o e-mail: {$email}", null, 'Desconhecido');
            jsonResponse(false, 'E-mail ou senha incorretos. Tente novamente!', [], 401);
        }

        // Cria sessão nativa
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_foto'] = $usuario['foto_perfil'] ?? 'assets/img/mascote/mascote_chibi.png';

        // Auditoria: registra login bem-sucedido
        registrarLog('LOGIN_SUCESSO', "Estilista logado com sucesso.", $usuario['id'], $usuario['nome']);

        jsonResponse(true, "Que bom te ver de novo, {$usuario['nome']}! ♡", [
            'id'    => $usuario['id'],
            'nome'  => $usuario['nome'],
            'email' => $usuario['email'],
            'foto'  => $_SESSION['usuario_foto']
        ]);
    }

    // 4. LOGOUT
    if ($action === 'logout') {
        $id = $_SESSION['usuario_id'] ?? null;
        $nome = $_SESSION['usuario_nome'] ?? 'Visitante';

        if ($id) {
            registrarLog('LOGOUT', "Usuário encerrou a sessão.", $id, $nome);
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        jsonResponse(true, 'Você saiu do jogo. Volte logo para criar mais looks! ♡');
    }

    jsonResponse(false, 'Ação de autenticação não reconhecida.', [], 400);

} catch (Exception $e) {
    jsonResponse(false, 'Erro interno de autenticação: ' . $e->getMessage(), [], 500);
}
