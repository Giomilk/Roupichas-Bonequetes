<?php
/**
 * Roupichas-Bonequetes
 * Módulo de Conexão com o Banco de Dados via PDO
 *
 * Requisitos:
 * - Uso obrigatório de PDO para segurança e prevenção contra SQL Injections.
 * - Arquivo isolado bd.php para desacoplamento de infraestrutura.
 */

// Inicia sessão nativa caso ainda não tenha sido iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações do MySQL (Padrão de ambientes XAMPP/WAMP/Docker)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'roupichas_bonequetes');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Retorna a instância única da conexão PDO
 * @return PDO
 */
function getConexao() {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // 1. Tenta conectar ao MySQL
    try {
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Se a base MySQL não existe, tenta criá-la se o servidor MySQL estiver online
        try {
            $dsnNoDb = sprintf('mysql:host=%s;port=%s;charset=%s', DB_HOST, DB_PORT, DB_CHARSET);
            $tempPdo = new PDO($dsnNoDb, DB_USER, DB_PASS, $options);
            $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $tempPdo = null;

            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            return $pdo;
        } catch (PDOException $e2) {
            // 2. Fallback inteligente: se o serviço MySQL não estiver rodando na máquina local,
            // utiliza SQLite PDO local para garantir funcionamento 100% imediato e zero-config!
            $sqlitePath = dirname(__DIR__) . '/database/database.sqlite';
            $dsnSqlite = 'sqlite:' . $sqlitePath;
            $pdo = new PDO($dsnSqlite, null, null, $options);
            
            // Ativa foreign keys no SQLite
            $pdo->exec('PRAGMA foreign_keys = ON;');
            return $pdo;
        }
    }
}

// Inicializa a variável global $pdo para scripts que esperam o objeto diretamente
try {
    $pdo = getConexao();

    // Auto-inicialização de segurança: garante que as tabelas existam sem intervenção manual
    try {
        $stmtCheck = $pdo->query("SELECT COUNT(*) FROM itens");
        if ($stmtCheck->fetchColumn() == 0) {
            require_once dirname(__DIR__) . '/database/setup.php';
            inicializarBancoDeDados($pdo, true);
        }
    } catch (Exception $eTabelas) {
        require_once dirname(__DIR__) . '/database/setup.php';
        inicializarBancoDeDados($pdo, true);
    }
} catch (Exception $e) {
    die("Erro crítico de banco de dados: " . htmlspecialchars($e->getMessage()));
}
