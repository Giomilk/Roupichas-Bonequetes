<?php
/**
 * Roupichas-Bonequetes
 * Página Sobre o Jogo, Arquitetura e Instruções
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/logger.php';

registrarLog('ACESSO_SOBRE', 'Usuário visualizou a página Sobre.');

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Card de Introdução -->
    <div class="kawaii-card p-6 sm:p-8 text-center bg-gradient-to-b from-pink-50 to-white">
        <img src="assets/img/logo/laco_logo.png" alt="Laço" class="w-16 h-14 mx-auto mb-3 object-contain">
        <h1 class="font-kawaii text-3xl font-extrabold text-pink-700">
            Sobre o Roupichas-Bonequetes ♡
        </h1>
        <p class="text-xs sm:text-sm text-pink-500 font-bold mt-1">
            "Um jogo para passar o tempo e se divertir com suas bonequetes S2"
        </p>
        <p class="text-gray-600 text-sm mt-4 max-w-xl mx-auto leading-relaxed">
            Bem-vindo ao cantinho mais fofo e criativo da internet! Aqui você pode soltar sua imaginação montando figurinos incríveis, combinando cabelos, roupinhas modulares, sapatos, acessórios, cenários e até os rostinhos clássicos e divertidos do Roblox.
        </p>
    </div>

    <!-- Regras e Funcionalidades Atendidas -->
    <div class="kawaii-card p-6 sm:p-8 space-y-6">
        <h2 class="font-kawaii text-xl font-bold text-pink-800 flex items-center gap-2 border-b border-pink-100 pb-3">
            <span>🎀</span> Funcionalidades do Jogo (Regras CORE)
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="p-4 bg-pink-50 rounded-2xl border border-pink-200">
                <div class="font-extrabold text-pink-900 text-sm mb-1 flex items-center gap-1.5">
                    <span>👧👦</span> Bonequinhos & Tons de Pele
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Escolha entre base feminina e masculina, com múltiplos tons de pele (Claro, Morena, Negra e Porcelana) desenhados no estilo chibi.
                </p>
            </div>

            <div class="p-4 bg-pink-50 rounded-2xl border border-pink-200">
                <div class="font-extrabold text-pink-900 text-sm mb-1 flex items-center gap-1.5">
                    <span>👶</span> Rostos do Roblox & Expressões
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Equipe rostinhos icônicos como o <b>Man Face</b>, <b>Laura</b>, <b>Melanie Martinez Makeup</b>, <b>Smug Meme</b> e o lendário <b>Kono Dio Da!</b>.
                </p>
            </div>

            <div class="p-4 bg-pink-50 rounded-2xl border border-pink-200">
                <div class="font-extrabold text-pink-900 text-sm mb-1 flex items-center gap-1.5">
                    <span>🎨</span> Cores de Cabelo Personalizáveis
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Penteados variados (ondulados, tranças, coques, space buns) com paleta dinâmica de tintura ao vivo.
                </p>
            </div>

            <div class="p-4 bg-pink-50 rounded-2xl border border-pink-200">
                <div class="font-extrabold text-pink-900 text-sm mb-1 flex items-center gap-1.5">
                    <span>👗</span> Roupinhas Modulares
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Combine blusas, cardigans, calças, saias plissadas colegiais, shorts, sapatos e vestidos com camadas independentes.
                </p>
            </div>

            <div class="p-4 bg-pink-50 rounded-2xl border border-pink-200">
                <div class="font-extrabold text-pink-900 text-sm mb-1 flex items-center gap-1.5">
                    <span>➕</span> Envio de Sprites PNG por Qualquer Usuário
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Qualquer estilista pode fazer upload de novas peças PNG transparentes para enriquecer o guarda-roupa da comunidade!
                </p>
            </div>

            <div class="p-4 bg-pink-50 rounded-2xl border border-pink-200">
                <div class="font-extrabold text-pink-900 text-sm mb-1 flex items-center gap-1.5">
                    <span>🛡️</span> Auditoria & Hash Bcrypt
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Todas as senhas são criptografadas com o algoritmo Bcrypt e cada alteração no sistema gera logs auditáveis para segurança total.
                </p>
            </div>
        </div>
    </div>

    <!-- Guia: Como Criar sua Própria Roupinha PNG -->
    <div class="kawaii-card p-6 sm:p-8 bg-pink-50/70 border-2 border-pink-300">
        <h3 class="font-kawaii text-lg font-bold text-pink-800 mb-2 flex items-center gap-2">
            <span>✨</span> Como Enviar Novas Peças PNG para o Jogo?
        </h3>
        <ol class="list-decimal list-inside space-y-2 text-xs text-gray-700 leading-relaxed">
            <li>Crie ou desenhe sua peça em qualquer editor de imagens (Photoshop, Canva, IbisPaint, etc.).</li>
            <li>Certifique-se de exportar com <b>fundo transparente</b> no formato <b>PNG</b>.</li>
            <li>A proporção padrão do bonequinho é de <b>290 x 560 pixels</b> para encaixe perfeito nas camadas.</li>
            <li>Clique no botão <b>➕ Enviar Sprite</b> no topo da página, dê um nome, escolha a categoria e pronto! Sua peça aparecerá imediatamente no catálogo para você e todos os jogadores vestirem!</li>
        </ol>
        <div class="mt-4 pt-3 border-t border-pink-200 text-center">
            <button onclick="Modal.open('modal-upload-sprite')" class="kawaii-btn kawaii-btn-pink px-6 py-2.5 text-xs font-bold">
                Enviar Minha Sprite Agora ♡
            </button>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
