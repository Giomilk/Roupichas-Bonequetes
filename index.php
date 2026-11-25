<?php
/**
 * Roupichas-Bonequetes
 * Página Inicial e Fluxo Completo do Jogo
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/logger.php';

// Registra log de visita
registrarLog('ACESSO_INICIO', 'Usuário acessou a página inicial.');

require_once __DIR__ . '/includes/header.php';
?>

<!-- ==========================================
     1. TELA DE BOAS-VINDAS / HERO (Mobile & Desktop)
     ========================================== -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

    <!-- Card de Boas-Vindas com Mascote Anime -->
    <div class="kawaii-card p-6 sm:p-10 mb-8 sm:mb-12 relative overflow-hidden bg-gradient-to-r from-pink-50 via-white to-pink-50">
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <img src="assets/img/logo/laco_logo.png" class="w-96" alt="Fundo">
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
            <!-- Texto de Apresentação -->
            <div class="flex-1 text-center md:text-left space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-bold shadow-xs">
                    <span class="animate-heart">💖</span>
                    <span>O criador de bonequetes mais fofo da internet!</span>
                </div>
                <h1 class="font-kawaii text-3xl sm:text-5xl font-black text-pink-700 tracking-tight leading-tight">
                    Crie seu estilo, <br class="hidden sm:inline">do seu jeito! ♡
                </h1>
                <p class="text-gray-600 text-sm sm:text-base max-w-lg leading-relaxed">
                    Personalize cabelos, rostinhos divertidos do Roblox, blusas, saias, vestidos, sapatinhos e envie até as suas próprias sprites em PNG!
                </p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 pt-2">
                    <a href="jogar.php" class="kawaii-btn kawaii-btn-pink px-8 py-3.5 text-base font-extrabold flex items-center gap-2 shadow-lg">
                        <span>▶</span> Começar a Vestir
                    </a>
                    <button onclick="Modal.open('modal-upload-sprite')" class="kawaii-btn kawaii-btn-outline px-6 py-3.5 text-sm font-bold flex items-center gap-2">
                        <span>➕</span> Enviar Roupinha PNG
                    </button>
                    <a href="galeria.php" class="px-5 py-3.5 bg-pink-50 hover:bg-pink-100 text-pink-700 rounded-full text-sm font-bold border border-pink-200 transition-colors">
                        🖼️ Ver Looks Salvos
                    </a>
                </div>
            </div>

            <!-- Mascote Anime Chibi dos Mockups -->
            <div class="flex-shrink-0 text-center relative">
                <div class="w-48 h-48 sm:w-60 sm:h-60 mx-auto rounded-3xl bg-pink-100/80 p-3 border-4 border-pink-300 shadow-xl animate-float">
                    <img src="assets/img/mascote/mascote_chibi.png" alt="Mascote Chibi" class="w-full h-full object-cover rounded-2xl">
                </div>
                <div class="mt-3 inline-block bg-white/90 border border-pink-200 px-4 py-1.5 rounded-full shadow-sm text-xs font-bold text-pink-600">
                    ✨ Vista sonhos, crie histórias! ♡
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         2. PASSO A PASSO: ESCOLHA DO BONEQUINHO (Bases)
         ========================================== -->
    <div class="mb-10 text-center">
        <h2 class="font-kawaii text-2xl sm:text-3xl font-extrabold text-pink-800 flex items-center justify-center gap-2">
            <span>✨</span> Escolha seu bonequinho ♡ <span>✨</span>
        </h2>
        <p class="text-xs sm:text-sm text-pink-500 mt-1">Selecione o gênero e o tom de pele do seu avatar base</p>

        <!-- Seletor de Gênero Feminino / Masculino -->
        <div class="inline-flex p-1.5 bg-pink-100 rounded-full mt-4 border-2 border-pink-200 shadow-inner">
            <button onclick="BonequeteApp.setBase('feminino', null)" class="btn-genero px-6 py-2 rounded-full text-xs font-extrabold transition-all bg-pink-500 text-white shadow" data-genero="feminino">
                👧 Feminino
            </button>
            <button onclick="BonequeteApp.setBase('masculino', null)" class="btn-genero px-6 py-2 rounded-full text-xs font-bold transition-all text-pink-600 hover:text-pink-800" data-genero="masculino">
                👦 Masculino
            </button>
        </div>

        <!-- Grade de Tons de Pele (Cards com Miniatura) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl mx-auto mt-6">
            
            <!-- Tom Claro -->
            <div onclick="BonequeteApp.setBase(null, 'claro')" class="btn-tom-pele cursor-pointer kawaii-card p-3 text-center hover:border-pink-500 transition-all group ring-4 ring-pink-500" data-tom="claro">
                <div class="w-16 h-24 mx-auto overflow-hidden bg-pink-50 rounded-xl mb-2 flex items-center justify-center border border-pink-100">
                    <img src="assets/img/bases/base_feminino_claro.png" alt="Pele Clara" class="h-28 object-contain group-hover:scale-110 transition-transform">
                </div>
                <div class="text-xs font-extrabold text-pink-800">Pele Clara</div>
                <div class="text-[10px] text-pink-400">Pêssego Natural</div>
            </div>

            <!-- Tom Médio -->
            <div onclick="BonequeteApp.setBase(null, 'medio')" class="btn-tom-pele cursor-pointer kawaii-card p-3 text-center hover:border-pink-500 transition-all group" data-tom="medio">
                <div class="w-16 h-24 mx-auto overflow-hidden bg-pink-50 rounded-xl mb-2 flex items-center justify-center border border-pink-100">
                    <img src="assets/img/bases/base_feminino_medio.png" alt="Pele Média" class="h-28 object-contain group-hover:scale-110 transition-transform">
                </div>
                <div class="text-xs font-extrabold text-pink-800">Pele Morena</div>
                <div class="text-[10px] text-pink-400">Bronze Radiante</div>
            </div>

            <!-- Tom Escuro -->
            <div onclick="BonequeteApp.setBase(null, 'escuro')" class="btn-tom-pele cursor-pointer kawaii-card p-3 text-center hover:border-pink-500 transition-all group" data-tom="escuro">
                <div class="w-16 h-24 mx-auto overflow-hidden bg-pink-50 rounded-xl mb-2 flex items-center justify-center border border-pink-100">
                    <img src="assets/img/bases/base_feminino_escuro.png" alt="Pele Escura" class="h-28 object-contain group-hover:scale-110 transition-transform">
                </div>
                <div class="text-xs font-extrabold text-pink-800">Pele Negra</div>
                <div class="text-[10px] text-pink-400">Ébano Elegante</div>
            </div>

            <!-- Tom Porcelana -->
            <div onclick="BonequeteApp.setBase(null, 'porcelana')" class="btn-tom-pele cursor-pointer kawaii-card p-3 text-center hover:border-pink-500 transition-all group" data-tom="porcelana">
                <div class="w-16 h-24 mx-auto overflow-hidden bg-pink-50 rounded-xl mb-2 flex items-center justify-center border border-pink-100">
                    <img src="assets/img/bases/base_feminino_porcelana.png" alt="Pele Porcelana" class="h-28 object-contain group-hover:scale-110 transition-transform">
                </div>
                <div class="text-xs font-extrabold text-pink-800">Porcelana</div>
                <div class="text-[10px] text-pink-400">Claro Suave</div>
            </div>

        </div>
    </div>

    <!-- ==========================================
         3. ÁREA PRINCIPAL: EDITOR DE PERSONAGEM
         Layout idêntico ao wireframe WhatsApp!
         ========================================== -->
    <div id="area-editor" class="kawaii-card p-4 sm:p-8 bg-white border-4 border-pink-200">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- COLUNA ESQUERDA (3 colunas): MENU DE CATEGORIAS -->
            <div class="lg:col-span-3 space-y-2 order-2 lg:order-1">
                <div class="p-3 bg-pink-100/70 border-2 border-pink-200 rounded-2xl flex items-center justify-between mb-3">
                    <span class="font-kawaii text-sm font-extrabold text-pink-700 flex items-center gap-2">
                        <span>🎀</span> Categorias
                    </span>
                    <button onclick="Modal.open('modal-upload-sprite')" class="text-[10px] bg-pink-500 text-white font-extrabold px-2.5 py-1 rounded-full shadow hover:bg-pink-600 transition-colors" title="Adicionar Peça">
                        + Sprite
                    </button>
                </div>

                <!-- Botões de Categoria -->
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-1 gap-2">
                    <button onclick="carregarItensCategoria('cabelos')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-pink-500 text-white shadow-md" data-categoria="cabelos">
                        <span class="text-xl">💇</span>
                        <span class="flex-1">Cabelos</span>
                    </button>
                    <button onclick="carregarItensCategoria('rostos')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="rostos">
                        <span class="text-xl">👶</span>
                        <span class="flex-1">Rostos (Roblox)</span>
                    </button>
                    <button onclick="carregarItensCategoria('blusas')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="blusas">
                        <span class="text-xl">👕</span>
                        <span class="flex-1">Blusas & Tops</span>
                    </button>
                    <button onclick="carregarItensCategoria('calcas')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="calcas">
                        <span class="text-xl">👗</span>
                        <span class="flex-1">Calças / Saias</span>
                    </button>
                    <button onclick="carregarItensCategoria('vestidos')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="vestidos">
                        <span class="text-xl">✨</span>
                        <span class="flex-1">Vestidos</span>
                    </button>
                    <button onclick="carregarItensCategoria('sapatos')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="sapatos">
                        <span class="text-xl">👠</span>
                        <span class="flex-1">Sapatos</span>
                    </button>
                    <button onclick="carregarItensCategoria('acessorios')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="acessorios">
                        <span class="text-xl">🎀</span>
                        <span class="flex-1">Acessórios</span>
                    </button>
                    <button onclick="carregarItensCategoria('cenarios')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="cenarios">
                        <span class="text-xl">🖼️</span>
                        <span class="flex-1">Cenários</span>
                    </button>
                </div>

                <div class="hidden lg:block p-4 bg-pink-50 rounded-2xl border border-pink-200 text-center text-xs text-pink-600 mt-4 leading-relaxed font-semibold">
                    🎀 "Crie suas bonequetes favoritas e compartilhe com as amigas!" ♡
                </div>
            </div>

            <!-- COLUNA CENTRAL (5 colunas): PALCO DO PERSONAGEM -->
            <div class="lg:col-span-5 flex flex-col items-center order-1 lg:order-2">
                
                <div class="w-full text-center pb-2 flex items-center justify-between">
                    <span class="text-xs font-bold text-pink-500">♡ Seu bonequinho ♡</span>
                    <span class="text-[11px] bg-pink-100 text-pink-700 px-2 py-0.5 rounded-full font-bold">Ao vivo</span>
                </div>

                <!-- Contêiner do Palco com Moldura -->
                <div class="relative w-full max-w-[320px] aspect-[290/560] bg-pink-50/50 rounded-3xl border-4 border-pink-300 shadow-xl overflow-hidden flex items-center justify-center p-2">
                    
                    <!-- Palco do Avatar com Camadas -->
                    <div id="avatar-character-stage" class="avatar-stage">
                        <img id="layer-cenario" class="avatar-layer layer-cenario" src="assets/img/cenarios/cenario_quarto_pastel.svg" alt="Cenário">
                        <img id="layer-base" class="avatar-layer layer-base" src="assets/img/bases/base_feminino_claro.png" alt="Base">
                        <img id="layer-rosto" class="avatar-layer layer-rosto" src="assets/img/rostos/rosto_roblox_mulher.png" alt="Rosto">
                        <img id="layer-calca" class="avatar-layer layer-calca" src="assets/img/calcas/calca_saia_plissada_rosa.svg" alt="Calça/Saia">
                        <img id="layer-blusa" class="avatar-layer layer-blusa" src="assets/img/blusas/blusa_cardigan_rosa.svg" alt="Blusa">
                        <img id="layer-vestido" class="avatar-layer layer-vestido" src="" alt="Vestido" style="display:none;">
                        <img id="layer-sapato" class="avatar-layer layer-sapato" src="assets/img/sapatos/sapato_boneca_maryjane.svg" alt="Sapato">
                        <img id="layer-cabelo" class="avatar-layer layer-cabelo" src="assets/img/cabelos/cabelo_ondulado_castanho.png" alt="Cabelo">
                        <img id="layer-acessorio" class="avatar-layer layer-acessorio" src="assets/img/acessorios/acessorio_laco_gigante.svg" alt="Acessório">
                    </div>

                    <!-- Botões de Controle Flutuantes à Direita do Palco (Como no Mockup!) -->
                    <div class="absolute right-2 top-4 flex flex-col gap-2 z-30">
                        <button onclick="zoomIn()" class="w-9 h-9 bg-white/95 text-pink-700 hover:bg-pink-100 border border-pink-300 rounded-full flex flex-col items-center justify-center shadow-md text-xs font-bold transition-transform active:scale-90" title="Zoom +">
                            <span class="text-sm font-black leading-none">+</span>
                            <span class="text-[8px] font-bold leading-none">Zoom</span>
                        </button>
                        <button onclick="zoomOut()" class="w-9 h-9 bg-white/95 text-pink-700 hover:bg-pink-100 border border-pink-300 rounded-full flex flex-col items-center justify-center shadow-md text-xs font-bold transition-transform active:scale-90" title="Zoom -">
                            <span class="text-sm font-black leading-none">-</span>
                            <span class="text-[8px] font-bold leading-none">Zoom</span>
                        </button>
                        <button onclick="girarBoneco()" class="w-9 h-9 bg-white/95 text-pink-700 hover:bg-pink-100 border border-pink-300 rounded-full flex flex-col items-center justify-center shadow-md text-xs font-bold transition-transform active:scale-90" title="Girar / Espelhar">
                            <span class="text-sm leading-none">🔄</span>
                            <span class="text-[8px] font-bold leading-none">Girar</span>
                        </button>
                        <button onclick="lookAleatorio()" class="w-9 h-9 bg-white/95 text-pink-700 hover:bg-pink-100 border border-pink-300 rounded-full flex flex-col items-center justify-center shadow-md text-xs font-bold transition-transform active:scale-90" title="Look Aleatório">
                            <span class="text-sm leading-none">🎲</span>
                            <span class="text-[8px] font-bold leading-none">Sorte</span>
                        </button>
                    </div>

                </div>

                <!-- Botão Grande: Salvar Look ♡ -->
                <button onclick="abrirModalSalvar()" class="kawaii-btn kawaii-btn-pink w-full max-w-[320px] py-3 mt-4 text-sm font-extrabold flex items-center justify-center gap-2 shadow-lg">
                    <span class="text-lg">💖</span> Salvar look ♡
                </button>

            </div>

            <!-- COLUNA DIREITA (4 colunas): CATÁLOGO DE ITENS E CORES -->
            <div class="lg:col-span-4 space-y-4 order-3">
                
                <!-- Card de Seleção dos Itens -->
                <div class="kawaii-card p-4 border-2 border-pink-200">
                    <div class="flex items-center justify-between border-b-2 border-pink-100 pb-2 mb-3">
                        <span class="font-kawaii text-sm font-extrabold text-pink-700 flex items-center gap-1.5">
                            <span>✨</span> Escolha os itens ♡
                        </span>
                        <span class="text-[10px] text-pink-400 font-bold uppercase tracking-wider">Toque para vestir</span>
                    </div>

                    <!-- Grade de Itens (com scroll suave) -->
                    <div id="grid-itens-catalogo" class="grid grid-cols-3 sm:grid-cols-4 gap-2.5 max-h-[360px] overflow-y-auto pr-1">
                        <!-- Itens inseridos dinamicamente pelo app.js -->
                    </div>
                </div>

                <!-- Card de Cores de Cabelo (Paleta Dinâmica) -->
                <div id="container-seletor-cores" class="kawaii-card p-4 border-2 border-pink-200">
                    <div class="text-xs font-bold text-pink-800 flex items-center gap-1.5 mb-2.5">
                        <span>🎨</span> Cores do Cabelo
                    </div>
                    <div id="paleta-cores-cabelo" class="flex flex-wrap gap-2.5 justify-center">
                        <!-- Bolinhas de cores inseridas pelo app.js -->
                    </div>
                </div>

                <!-- Card de Ações Principais (Reiniciar, Salvar, Tirar Print) -->
                <div class="grid grid-cols-3 gap-2 pt-1">
                    <button onclick="reiniciarLook()" class="p-3 bg-white hover:bg-pink-50 border-2 border-pink-200 rounded-2xl flex flex-col items-center justify-center text-xs font-bold text-gray-600 transition-all shadow-sm active:scale-95" title="Reiniciar">
                        <span class="text-xl mb-0.5">🔄</span>
                        <span>Reiniciar</span>
                    </button>
                    <button onclick="abrirModalSalvar()" class="p-3 bg-white hover:bg-pink-50 border-2 border-pink-200 rounded-2xl flex flex-col items-center justify-center text-xs font-bold text-pink-600 transition-all shadow-sm active:scale-95" title="Salvar">
                        <span class="text-xl mb-0.5">📥</span>
                        <span>Salvar</span>
                    </button>
                    <button onclick="tirarFoto()" class="p-3 bg-white hover:bg-pink-50 border-2 border-pink-200 rounded-2xl flex flex-col items-center justify-center text-xs font-bold text-pink-600 transition-all shadow-sm active:scale-95" title="Tirar foto">
                        <span class="text-xl mb-0.5">📷</span>
                        <span>Tirar print</span>
                    </button>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- Engine do Jogo -->
<script src="assets/js/app.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
