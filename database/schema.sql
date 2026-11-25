-- ========================================================
-- Roupichas-Bonequetes - Esquema do Banco de Dados (MySQL)
-- ========================================================

CREATE DATABASE IF NOT EXISTS `if0_43449383_rouptchas` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `if0_43449383_rouptchas`;

-- Tabela de Usuários (senhas tratadas com bcrypt)
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `foto_perfil` VARCHAR(255) DEFAULT 'assets/img/mascote/mascote_chibi.png',
    `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Itens Personalizáveis (Catálogo do Jogo e Sprites de Usuários)
CREATE TABLE IF NOT EXISTS `itens` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `categoria` ENUM('cabelos', 'rostos', 'blusas', 'calcas', 'vestidos', 'sapatos', 'acessorios', 'cenarios') NOT NULL,
    `subcategoria` VARCHAR(50) DEFAULT NULL,
    `imagem` VARCHAR(255) NOT NULL,
    `miniatura` VARCHAR(255) DEFAULT NULL,
    `usuario_id` INT NULL,
    `is_padrao` TINYINT(1) DEFAULT 1,
    `ativo` TINYINT(1) DEFAULT 1,
    `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Looks Salvos pelos Usuários
CREATE TABLE IF NOT EXISTS `looks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT NULL,
    `usuario_nome` VARCHAR(100) DEFAULT 'Estilista Anônimo',
    `titulo` VARCHAR(100) NOT NULL DEFAULT 'Meu Look Fofinho',
    `base_avatar` VARCHAR(100) NOT NULL DEFAULT 'base_feminino_claro.png',
    `tom_pele` VARCHAR(50) DEFAULT 'claro',
    `genero` VARCHAR(20) DEFAULT 'feminino',
    `cabelo_id` INT NULL,
    `cabelo_cor` VARCHAR(30) DEFAULT '#4a2c11',
    `rosto_id` INT NULL,
    `blusa_id` INT NULL,
    `calca_id` INT NULL,
    `vestido_id` INT NULL,
    `sapato_id` INT NULL,
    `acessorio_id` INT NULL,
    `cenario_id` INT NULL,
    `config_json` TEXT NOT NULL,
    `imagem_preview` VARCHAR(255) NOT NULL,
    `favoritos` INT DEFAULT 0,
    `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Auditoria e Logs (REQUISITO CORE: manter histórico de qualquer alteração)
CREATE TABLE IF NOT EXISTS `logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` INT NULL,
    `usuario_nome` VARCHAR(100) DEFAULT 'Visitante',
    `acao` VARCHAR(100) NOT NULL,
    `detalhes` TEXT,
    `ip` VARCHAR(45),
    `user_agent` VARCHAR(255),
    `data_hora` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices para otimização de auditoria e buscas
CREATE INDEX IF NOT EXISTS idx_logs_data ON `logs` (`data_hora`);
CREATE INDEX IF NOT EXISTS idx_logs_usuario ON `logs` (`usuario_id`);
CREATE INDEX IF NOT EXISTS idx_itens_categoria ON `itens` (`categoria`);
CREATE INDEX IF NOT EXISTS idx_looks_usuario ON `looks` (`usuario_id`);
