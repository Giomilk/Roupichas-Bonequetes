<?php
/**
 * Roupichas-Bonequetes
 * Cabeçalho Global Responsivo (Mobile-First) com Tailwind CSS
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuarioLogado = !empty($_SESSION['usuario_id']);
$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Visitante';
$fotoUsuario = $_SESSION['usuario_foto'] ?? 'assets/img/mascote/mascote_chibi.png';
$paginaAtual = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Roupitchas Bonequetes - Crie seu estilo, do seu jeito! ♡</title>
    <link rel="icon" type="image/png" href="assets/img/logo/laco_logo.png">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kawaii: {
                            50: '#fdf2f4',
                            100: '#fce7f3',
                            200: '#fbcfe8',
                            300: '#f9a8d4',
                            400: '#f472b6',
                            500: '#ec4899',
                            600: '#db2777',
                            700: '#be185d',
                            800: '#9d174d',
                            900: '#831843',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Estilos Customizados Kawaii -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="flex flex-col min-h-screen">

    <!-- Container Global de Notificações Toast -->
    <div id="toast-container" class="fixed top-4 right-4 left-4 sm:left-auto sm:w-96 z-50 flex flex-col gap-3 pointer-events-none"></div>

    <!-- Header Principal -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b-2 border-pink-200 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo do Site com Laço Fofo -->
                <a href="index.php" class="flex items-center gap-3 group">
                    <img src="assets/img/logo/laco_logo.png" alt="Laço" class="w-12 h-10 object-contain transform group-hover:scale-110 transition-transform">
                    <div class="flex flex-col">
                        <span class="font-kawaii text-xl sm:text-2xl font-black tracking-tight text-pink-600 group-hover:text-pink-700 transition-colors">
                            Roupitchas <span class="text-pink-400 font-extrabold">Bonequetes</span>
                        </span>
                        <span class="text-[11px] text-pink-400 font-bold hidden sm:inline tracking-wide">
                            Crie seu estilo, do seu jeito! ♡
                        </span>
                    </div>
                </a>

                <!-- Navegação Desktop -->
                <nav class="hidden md:flex items-center gap-2">
                    <a href="index.php" class="px-4 py-2 rounded-full text-sm font-bold transition-all <?= $paginaAtual === 'index' ? 'bg-pink-100 text-pink-700 shadow-inner' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' ?>">
                        Início
                    </a>
                    <a href="jogar.php" class="px-5 py-2 rounded-full text-sm font-extrabold transition-all <?= $paginaAtual === 'jogar' ? 'bg-pink-500 text-white shadow-md' : 'kawaii-btn kawaii-btn-pink' ?>">
                        ▶ Jogar
                    </a>
                    <a href="galeria.php" class="px-4 py-2 rounded-full text-sm font-bold transition-all <?= $paginaAtual === 'galeria' ? 'bg-pink-100 text-pink-700 shadow-inner' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' ?>">
                        🖼️ Galeria
                    </a>
                    <a href="historico.php" class="px-4 py-2 rounded-full text-sm font-bold transition-all <?= $paginaAtual === 'historico' ? 'bg-pink-100 text-pink-700 shadow-inner' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' ?>">
                        📜 Histórico & Logs
                    </a>
                    <a href="sobre.php" class="px-4 py-2 rounded-full text-sm font-bold transition-all <?= $paginaAtual === 'sobre' ? 'bg-pink-100 text-pink-700 shadow-inner' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' ?>">
                        ℹ️ Sobre
                    </a>
                </nav>

                <!-- Botões de Ação à Direita: Upload Sprite, Favoritos e Perfil -->
                <div class="flex items-center gap-2 sm:gap-3">
                    
                    <!-- Botão de Upload de Sprite PNG (Acessível a qualquer um) -->
                    <button onclick="Modal.open('modal-upload-sprite')" class="px-3 sm:px-4 py-2 bg-rose-100 hover:bg-rose-200 text-rose-700 border-2 border-rose-300 rounded-full text-xs font-black flex items-center gap-1.5 shadow-sm transition-transform active:scale-95" title="Enviar Sprite PNG">
                        <span>➕</span>
                        <span class="hidden sm:inline">Enviar Sprite</span>
                        <span class="sm:hidden">Sprite</span>
                    </button>

                    <!-- Link para Galeria / Favoritos -->
                    <a href="galeria.php?filtro=populares" class="p-2 sm:p-2.5 bg-pink-50 hover:bg-pink-100 text-pink-600 border border-pink-200 rounded-full transition-colors flex items-center justify-center shadow-sm" title="Favoritos">
                        <span class="text-lg">❤️</span>
                    </a>

                    <!-- Status de Autenticação / Perfil -->
                    <?php if ($usuarioLogado): ?>
                        <div class="flex items-center gap-2 pl-2 border-l-2 border-pink-100">
                            <img src="<?= htmlspecialchars($fotoUsuario) ?>" alt="Perfil" class="w-8 h-8 rounded-full border-2 border-pink-400 object-cover shadow-sm">
                            <span class="text-xs font-bold text-pink-800 hidden lg:inline max-w-[100px] truncate">
                                <?= htmlspecialchars($nomeUsuario) ?>
                            </span>
                            <button onclick="fazerLogout()" class="p-2 text-gray-400 hover:text-rose-600 text-xs font-bold rounded-full transition-colors" title="Sair">
                                🚪
                            </button>
                        </div>
                    <?php else: ?>
                        <button onclick="Modal.open('modal-auth')" class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-full text-xs font-extrabold shadow-md transition-all active:scale-95">
                            Entrar ♡
                        </button>
                    <?php endif; ?>

                    <!-- Botão Menu Mobile (Hamburguer) -->
                    <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-xl text-pink-600 hover:bg-pink-100 transition-colors" aria-label="Abrir Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Menu Mobile Drawer -->
        <div id="mobile-menu" class="hidden md:hidden bg-pink-50/95 border-b-2 border-pink-200 px-4 pt-2 pb-5 space-y-2">
            <a href="index.php" class="block px-4 py-2.5 rounded-2xl text-sm font-bold <?= $paginaAtual === 'index' ? 'bg-pink-200 text-pink-800' : 'text-gray-700 hover:bg-pink-100' ?>">
                🏠 Início
            </a>
            <a href="jogar.php" class="block px-4 py-2.5 rounded-2xl text-sm font-extrabold bg-pink-500 text-white shadow">
                ▶ Jogar Agora
            </a>
            <a href="galeria.php" class="block px-4 py-2.5 rounded-2xl text-sm font-bold <?= $paginaAtual === 'galeria' ? 'bg-pink-200 text-pink-800' : 'text-gray-700 hover:bg-pink-100' ?>">
                🖼️ Galeria de Looks
            </a>
            <a href="historico.php" class="block px-4 py-2.5 rounded-2xl text-sm font-bold <?= $paginaAtual === 'historico' ? 'bg-pink-200 text-pink-800' : 'text-gray-700 hover:bg-pink-100' ?>">
                📜 Histórico de Alterações & Logs
            </a>
            <a href="sobre.php" class="block px-4 py-2.5 rounded-2xl text-sm font-bold <?= $paginaAtual === 'sobre' ? 'bg-pink-200 text-pink-800' : 'text-gray-700 hover:bg-pink-100' ?>">
                ℹ️ Sobre o Jogo
            </a>
        </div>
    </header>

    <!-- Conteúdo Principal Dinâmico -->
    <main class="flex-grow">
