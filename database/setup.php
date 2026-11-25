<?php
/**
 * Roupichas-Bonequetes
 * Script de Inicialização e Migração do Banco de Dados
 *
 * Cria as tabelas necessárias e popula o catálogo de itens padrão,
 * usuário de demonstração com hash bcrypt e log inicial de auditoria.
 */

require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../includes/logger.php';

function inicializarBancoDeDados($pdo = null, $silencioso = false) {
    if ($pdo === null) {
        $pdo = getConexao();
    }

    try {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        // 1. Criação das Tabelas de acordo com o SGBD (MySQL ou SQLite)
        if ($driver === 'sqlite') {
            // Tabelas SQLite
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS usuarios (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    nome TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    senha TEXT NOT NULL,
                    foto_perfil TEXT DEFAULT 'assets/img/mascote/mascote_chibi.png',
                    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
                    atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP
                );

                CREATE TABLE IF NOT EXISTS itens (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    nome TEXT NOT NULL,
                    categoria TEXT NOT NULL,
                    subcategoria TEXT DEFAULT NULL,
                    imagem TEXT NOT NULL,
                    miniatura TEXT DEFAULT NULL,
                    usuario_id INTEGER NULL,
                    is_padrao INTEGER DEFAULT 1,
                    ativo INTEGER DEFAULT 1,
                    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
                );

                CREATE TABLE IF NOT EXISTS looks (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    usuario_id INTEGER NULL,
                    usuario_nome TEXT DEFAULT 'Estilista Anônimo',
                    titulo TEXT NOT NULL DEFAULT 'Meu Look Fofinho',
                    base_avatar TEXT NOT NULL DEFAULT 'base_feminino_claro.png',
                    tom_pele TEXT DEFAULT 'claro',
                    genero TEXT DEFAULT 'feminino',
                    cabelo_id INTEGER NULL,
                    cabelo_cor TEXT DEFAULT '#4a2c11',
                    rosto_id INTEGER NULL,
                    blusa_id INTEGER NULL,
                    calca_id INTEGER NULL,
                    vestido_id INTEGER NULL,
                    sapato_id INTEGER NULL,
                    acessorio_id INTEGER NULL,
                    cenario_id INTEGER NULL,
                    config_json TEXT NOT NULL,
                    imagem_preview TEXT NOT NULL,
                    favoritos INTEGER DEFAULT 0,
                    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
                );

                CREATE TABLE IF NOT EXISTS logs (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    usuario_id INTEGER NULL,
                    usuario_nome TEXT DEFAULT 'Visitante',
                    acao TEXT NOT NULL,
                    detalhes TEXT,
                    ip TEXT,
                    user_agent TEXT,
                    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
                );
            ");
        } else {
            // Tabelas MySQL
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `usuarios` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `nome` VARCHAR(100) NOT NULL,
                    `email` VARCHAR(150) NOT NULL UNIQUE,
                    `senha` VARCHAR(255) NOT NULL,
                    `foto_perfil` VARCHAR(255) DEFAULT 'assets/img/mascote/mascote_chibi.png',
                    `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

                CREATE TABLE IF NOT EXISTS `itens` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `nome` VARCHAR(100) NOT NULL,
                    `categoria` VARCHAR(50) NOT NULL,
                    `subcategoria` VARCHAR(50) DEFAULT NULL,
                    `imagem` VARCHAR(255) NOT NULL,
                    `miniatura` VARCHAR(255) DEFAULT NULL,
                    `usuario_id` INT NULL,
                    `is_padrao` TINYINT(1) DEFAULT 1,
                    `ativo` TINYINT(1) DEFAULT 1,
                    `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
            ");
        }

        // 2. Criação do Usuário Inicial (com senha em hash BCRYPT)
        $stmtUser = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmtUser->execute([':email' => 'admin@bonequetes.com']);
        $user = $stmtUser->fetch();

        if (!$user) {
            $senhaHash = password_hash('123456', PASSWORD_BCRYPT);
            $insertUser = $pdo->prepare("
                INSERT INTO usuarios (nome, email, senha, foto_perfil)
                VALUES (:nome, :email, :senha, :foto)
            ");
            $insertUser->execute([
                ':nome'  => 'Giomilk Estilista',
                ':email' => 'admin@bonequetes.com',
                ':senha' => $senhaHash,
                ':foto'  => 'assets/img/mascote/mascote_chibi.png'
            ]);
            $adminId = $pdo->lastInsertId();
        } else {
            $adminId = $user['id'];
        }

        // 3. Catálogo de Itens Padrão
        $itensPadrao = [
            // CABELOS
            ['nome' => 'Ondulado Castanho Chique', 'categoria' => 'cabelos', 'subcategoria' => 'longo', 'imagem' => 'assets/img/cabelos/cabelo_ondulado_castanho.png', 'miniatura' => 'assets/img/cabelos/thumb_cabelo_ondulado_castanho.png'],
            ['nome' => 'Loiro com Tiara Rosa', 'categoria' => 'cabelos', 'subcategoria' => 'tiara', 'imagem' => 'assets/img/cabelos/cabelo_tiara_loiro.png', 'miniatura' => 'assets/img/cabelos/thumb_cabelo_tiara_loiro.png'],
            ['nome' => 'Coque Alto Elegante', 'categoria' => 'cabelos', 'subcategoria' => 'coque', 'imagem' => 'assets/img/cabelos/cabelo_coque_elegante.png', 'miniatura' => 'assets/img/cabelos/thumb_cabelo_coque_elegante.png'],
            ['nome' => 'Longo Sedoso Moreno', 'categoria' => 'cabelos', 'subcategoria' => 'longo', 'imagem' => 'assets/img/cabelos/cabelo_longo_moreno.png', 'miniatura' => 'assets/img/cabelos/thumb_cabelo_longo_moreno.png'],
            ['nome' => 'Tranças Duplas Românticas', 'categoria' => 'cabelos', 'subcategoria' => 'tranca', 'imagem' => 'assets/img/cabelos/cabelo_trancas_duplas.png', 'miniatura' => 'assets/img/cabelos/thumb_cabelo_trancas_duplas.png'],
            ['nome' => 'Ondas Pretas Volumosas', 'categoria' => 'cabelos', 'subcategoria' => 'longo', 'imagem' => 'assets/img/cabelos/cabelo_ondulado_preto.png', 'miniatura' => 'assets/img/cabelos/thumb_cabelo_ondulado_preto.png'],
            ['nome' => 'Space Buns Duplos', 'categoria' => 'cabelos', 'subcategoria' => 'coque', 'imagem' => 'assets/img/cabelos/cabelo_space_buns.png', 'miniatura' => 'assets/img/cabelos/thumb_cabelo_space_buns.png'],
            ['nome' => 'Chanel Reto Moderno', 'categoria' => 'cabelos', 'subcategoria' => 'curto', 'imagem' => 'assets/img/cabelos/cabelo_chanel_reto.png', 'miniatura' => 'assets/img/cabelos/thumb_cabelo_chanel_reto.png'],

            // ROSTOS
            ['nome' => 'Roblox Man Face Clássico', 'categoria' => 'rostos', 'subcategoria' => 'roblox', 'imagem' => 'assets/img/rostos/rosto_roblox_manface.png', 'miniatura' => 'assets/img/rostos/thumb_roblox_manface.png'],
            ['nome' => 'Roblox Laura Face Fofa', 'categoria' => 'rostos', 'subcategoria' => 'roblox', 'imagem' => 'assets/img/rostos/rosto_roblox_laura.png', 'miniatura' => 'assets/img/rostos/thumb_roblox_laura.png'],
            ['nome' => 'Roblox Mulher Charmosa', 'categoria' => 'rostos', 'subcategoria' => 'roblox', 'imagem' => 'assets/img/rostos/rosto_roblox_mulher.png', 'miniatura' => 'assets/img/rostos/thumb_roblox_mulher.png'],
            ['nome' => 'Melanie Martinez Makeup', 'categoria' => 'rostos', 'subcategoria' => 'maquiagem', 'imagem' => 'assets/img/rostos/rosto_melanie_makeup.png', 'miniatura' => 'assets/img/rostos/thumb_melanie_makeup.png'],
            ['nome' => 'Kono Dio Da! Anime Meme', 'categoria' => 'rostos', 'subcategoria' => 'anime', 'imagem' => 'assets/img/rostos/rosto_kono_dio_da.png', 'miniatura' => 'assets/img/rostos/thumb_kono_dio_da.png'],
            ['nome' => 'Smug Meme Debochado', 'categoria' => 'rostos', 'subcategoria' => 'meme', 'imagem' => 'assets/img/rostos/rosto_smug_meme.png', 'miniatura' => 'assets/img/rostos/thumb_smug_meme.png'],

            // BLUSAS
            ['nome' => 'Cardigan Rosa Doce', 'categoria' => 'blusas', 'subcategoria' => 'cardigan', 'imagem' => 'assets/img/blusas/blusa_cardigan_rosa.svg', 'miniatura' => 'assets/img/blusas/blusa_cardigan_rosa.svg'],
            ['nome' => 'T-Shirt Coração Kawaii', 'categoria' => 'blusas', 'subcategoria' => 'camiseta', 'imagem' => 'assets/img/blusas/blusa_camiseta_coracao.svg', 'miniatura' => 'assets/img/blusas/blusa_camiseta_coracao.svg'],
            ['nome' => 'Moletom Confort LOVE', 'categoria' => 'blusas', 'subcategoria' => 'moletom', 'imagem' => 'assets/img/blusas/blusa_moletom_love.svg', 'miniatura' => 'assets/img/blusas/blusa_moletom_love.svg'],
            ['nome' => 'Regata Listrada Navy', 'categoria' => 'blusas', 'subcategoria' => 'regata', 'imagem' => 'assets/img/blusas/blusa_regata_listrada.svg', 'miniatura' => 'assets/img/blusas/blusa_regata_listrada.svg'],
            ['nome' => 'Cropped Preto Romântico', 'categoria' => 'blusas', 'subcategoria' => 'cropped', 'imagem' => 'assets/img/blusas/blusa_cropped_preto.svg', 'miniatura' => 'assets/img/blusas/blusa_cropped_preto.svg'],

            // CALÇAS / SAIAS
            ['nome' => 'Saia Plissada Colegial', 'categoria' => 'calcas', 'subcategoria' => 'saia', 'imagem' => 'assets/img/calcas/calca_saia_plissada_rosa.svg', 'miniatura' => 'assets/img/calcas/calca_saia_plissada_rosa.svg'],
            ['nome' => 'Shorts Jeans Desfiado', 'categoria' => 'calcas', 'subcategoria' => 'shorts', 'imagem' => 'assets/img/calcas/calca_shorts_jeans.svg', 'miniatura' => 'assets/img/calcas/calca_shorts_jeans.svg'],
            ['nome' => 'Saia Xadrez Harajuku', 'categoria' => 'calcas', 'subcategoria' => 'saia', 'imagem' => 'assets/img/calcas/calca_saia_xadrez.svg', 'miniatura' => 'assets/img/calcas/calca_saia_xadrez.svg'],
            ['nome' => 'Calça Jeans Reta Estilosa', 'categoria' => 'calcas', 'subcategoria' => 'calca', 'imagem' => 'assets/img/calcas/calca_jeans_reta.svg', 'miniatura' => 'assets/img/calcas/calca_jeans_reta.svg'],

            // VESTIDOS
            ['nome' => 'Vestido Lolita Doce com Babados', 'categoria' => 'vestidos', 'subcategoria' => 'lolita', 'imagem' => 'assets/img/vestidos/vestido_lolita_doce.svg', 'miniatura' => 'assets/img/vestidos/vestido_lolita_doce.svg'],
            ['nome' => 'Vestido Jardineira Jeans', 'categoria' => 'vestidos', 'subcategoria' => 'jardineira', 'imagem' => 'assets/img/vestidos/vestido_jardineira_jeans.svg', 'miniatura' => 'assets/img/vestidos/vestido_jardineira_jeans.svg'],

            // SAPATOS
            ['nome' => 'Tênis Chunky com Meias Altas', 'categoria' => 'sapatos', 'subcategoria' => 'tenis', 'imagem' => 'assets/img/sapatos/sapato_tenis_chunky.svg', 'miniatura' => 'assets/img/sapatos/sapato_tenis_chunky.svg'],
            ['nome' => 'Sapatilha Mary Jane Boneca', 'categoria' => 'sapatos', 'subcategoria' => 'sapatilha', 'imagem' => 'assets/img/sapatos/sapato_boneca_maryjane.svg', 'miniatura' => 'assets/img/sapatos/sapato_boneca_maryjane.svg'],

            // ACESSÓRIOS
            ['nome' => 'Laço de Cabelo Rosa Gigante', 'categoria' => 'acessorios', 'subcategoria' => 'cabelo', 'imagem' => 'assets/img/acessorios/acessorio_laco_gigante.svg', 'miniatura' => 'assets/img/acessorios/acessorio_laco_gigante.svg'],
            ['nome' => 'Tiara Orelhas de Gatinho', 'categoria' => 'acessorios', 'subcategoria' => 'tiara', 'imagem' => 'assets/img/acessorios/acessorio_tiara_gatinho.svg', 'miniatura' => 'assets/img/acessorios/acessorio_tiara_gatinho.svg'],
            ['nome' => 'Óculos Redondos Dourados', 'categoria' => 'acessorios', 'subcategoria' => 'oculos', 'imagem' => 'assets/img/acessorios/acessorio_oculos_redondo.svg', 'miniatura' => 'assets/img/acessorios/acessorio_oculos_redondo.svg'],
            ['nome' => 'Bolsa Tiracolo Coração', 'categoria' => 'acessorios', 'subcategoria' => 'bolsa', 'imagem' => 'assets/img/acessorios/acessorio_bolsa_coracao.svg', 'miniatura' => 'assets/img/acessorios/acessorio_bolsa_coracao.svg'],
            ['nome' => 'Headset Gamer Orelhinhas LED', 'categoria' => 'acessorios', 'subcategoria' => 'headset', 'imagem' => 'assets/img/acessorios/acessorio_fone_gamer.svg', 'miniatura' => 'assets/img/acessorios/acessorio_fone_gamer.svg'],

            // CENÁRIOS
            ['nome' => 'Quarto Pastel dos Sonhos', 'categoria' => 'cenarios', 'subcategoria' => 'quarto', 'imagem' => 'assets/img/cenarios/cenario_quarto_pastel.svg', 'miniatura' => 'assets/img/cenarios/cenario_quarto_pastel.svg'],
            ['nome' => 'Closet Fashion Kawaii', 'categoria' => 'cenarios', 'subcategoria' => 'closet', 'imagem' => 'assets/img/cenarios/cenario_closet_chic.svg', 'miniatura' => 'assets/img/cenarios/cenario_closet_chic.svg'],
            ['nome' => 'Passarela de Moda Sparkle', 'categoria' => 'cenarios', 'subcategoria' => 'evento', 'imagem' => 'assets/img/cenarios/cenario_passarela.svg', 'miniatura' => 'assets/img/cenarios/cenario_passarela.svg'],
            ['nome' => 'Jardim Florido Encantado', 'categoria' => 'cenarios', 'subcategoria' => 'natureza', 'imagem' => 'assets/img/cenarios/cenario_jardim.svg', 'miniatura' => 'assets/img/cenarios/cenario_jardim.svg']
        ];

        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM itens WHERE imagem = :imagem");
        $stmtInsertItem = $pdo->prepare("
            INSERT INTO itens (nome, categoria, subcategoria, imagem, miniatura, is_padrao)
            VALUES (:nome, :categoria, :subcategoria, :imagem, :miniatura, 1)
        ");

        $adicionados = 0;
        foreach ($itensPadrao as $it) {
            $stmtCheck->execute([':imagem' => $it['imagem']]);
            if ($stmtCheck->fetchColumn() == 0) {
                $stmtInsertItem->execute([
                    ':nome'         => $it['nome'],
                    ':categoria'    => $it['categoria'],
                    ':subcategoria' => $it['subcategoria'],
                    ':imagem'       => $it['imagem'],
                    ':miniatura'    => $it['miniatura'] ?? $it['imagem']
                ]);
                $adicionados++;
            }
        }

        // 4. Log Inicial de Auditoria se foram adicionados
        if ($adicionados > 0) {
            registrarLog(
                'SISTEMA_INICIALIZADO',
                "Banco de dados inicializado com sucesso via driver '{$driver}'. Total de {$adicionados} itens sincronizados.",
                $adminId,
                'Giomilk Estilista'
            );
        }

        if (!$silencioso) {
            echo "Sucesso: Banco de dados ({$driver}) configurado! {$adicionados} itens cadastrados.\n";
        }
        return true;

    } catch (Exception $e) {
        if (!$silencioso) {
            die("Erro no setup do banco: " . $e->getMessage() . "\n");
        }
        return false;
    }
}

// Execução direta
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === 'setup.php') {
    inicializarBancoDeDados(null, false);
}
