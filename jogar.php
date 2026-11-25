<?php
/**
 * Roupichas-Bonequetes
 * Tela do Jogo - Fluxo em 2 Telas:
 * 1. Seleção de Bonequinhos "Avatares"
 * 2. Catálogo de Roupas e Acessórios para Personalizar
 *
 * Conforme Requisitos:
 * "- o usuário clica em jogar, então abre uma seleção de bonequinhos 'avatares'."
 * "- em outra tela vai ter um catalogo de roupas e acessórios para personalizar."
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/logger.php';

registrarLog('ACESSO_JOGO', 'Usuário entrou na seleção de bonequinhos do jogo.');

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- =======================================================
         TELA 1: SELEÇÃO DE BONEQUINHOS (AVATARES)
         ======================================================= -->
    <div id="tela-selecao-avatar" class="transition-all duration-300">
        
        <div class="text-center max-w-2xl mx-auto mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-bold mb-3 shadow-xs">
                <span>🎀 Passo 1 de 2</span>
            </div>
            <h1 class="font-kawaii text-3xl sm:text-4xl font-black text-pink-700">
                Escolha seu bonequinho ♡
            </h1>
            <p class="text-xs sm:text-sm text-pink-500 mt-2 font-medium">
                Selecione o gênero e o tom de pele do seu avatar para começar a vestir!
            </p>

            <!-- Seletor de Gênero Feminino / Masculino -->
            <div class="inline-flex p-1.5 bg-pink-100 rounded-full mt-5 border-2 border-pink-200 shadow-inner">
                <button type="button" onclick="selecionarGenero('feminino')" id="btn-gen-fem" class="btn-genero px-6 py-2 rounded-full text-xs font-extrabold transition-all bg-pink-500 text-white shadow" data-genero="feminino">
                    👧 Feminino
                </button>
                <button type="button" onclick="selecionarGenero('masculino')" id="btn-gen-masc" class="btn-genero px-6 py-2 rounded-full text-xs font-bold transition-all text-pink-600 hover:text-pink-800" data-genero="masculino">
                    👦 Masculino
                </button>
            </div>
        </div>

        <!-- Grade de Bonequinhos (Avatares) com Prévia Completa -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 max-w-4xl mx-auto mb-10">
            
            <!-- Avatar Tom Claro -->
            <div onclick="selecionarTomPele('claro')" id="card-avatar-claro" class="card-avatar cursor-pointer kawaii-card p-4 text-center hover:border-pink-500 transition-all group ring-4 ring-pink-500 shadow-md" data-tom="claro">
                <div class="w-24 h-40 sm:h-48 mx-auto overflow-hidden bg-pink-50/70 rounded-2xl mb-3 flex items-center justify-center border border-pink-100 p-2">
                    <img id="img-avatar-claro" src="assets/img/bases/base_feminino_claro.png" alt="Pele Clara" class="h-full object-contain group-hover:scale-105 transition-transform">
                </div>
                <div class="text-xs sm:text-sm font-extrabold text-pink-800">Pele Clara</div>
                <div class="text-[10px] text-pink-400">Pêssego Natural</div>
                <div class="mt-2 text-[11px] font-bold text-pink-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    Selecionar ♡
                </div>
            </div>

            <!-- Avatar Tom Médio -->
            <div onclick="selecionarTomPele('medio')" id="card-avatar-medio" class="card-avatar cursor-pointer kawaii-card p-4 text-center hover:border-pink-500 transition-all group shadow-sm" data-tom="medio">
                <div class="w-24 h-40 sm:h-48 mx-auto overflow-hidden bg-pink-50/70 rounded-2xl mb-3 flex items-center justify-center border border-pink-100 p-2">
                    <img id="img-avatar-medio" src="assets/img/bases/base_feminino_medio.png" alt="Pele Média" class="h-full object-contain group-hover:scale-105 transition-transform">
                </div>
                <div class="text-xs sm:text-sm font-extrabold text-pink-800">Pele Morena</div>
                <div class="text-[10px] text-pink-400">Bronze Radiante</div>
                <div class="mt-2 text-[11px] font-bold text-pink-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    Selecionar ♡
                </div>
            </div>

            <!-- Avatar Tom Escuro -->
            <div onclick="selecionarTomPele('escuro')" id="card-avatar-escuro" class="card-avatar cursor-pointer kawaii-card p-4 text-center hover:border-pink-500 transition-all group shadow-sm" data-tom="escuro">
                <div class="w-24 h-40 sm:h-48 mx-auto overflow-hidden bg-pink-50/70 rounded-2xl mb-3 flex items-center justify-center border border-pink-100 p-2">
                    <img id="img-avatar-escuro" src="assets/img/bases/base_feminino_escuro.png" alt="Pele Escura" class="h-full object-contain group-hover:scale-105 transition-transform">
                </div>
                <div class="text-xs sm:text-sm font-extrabold text-pink-800">Pele Negra</div>
                <div class="text-[10px] text-pink-400">Ébano Elegante</div>
                <div class="mt-2 text-[11px] font-bold text-pink-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    Selecionar ♡
                </div>
            </div>

            <!-- Avatar Tom Porcelana -->
            <div onclick="selecionarTomPele('porcelana')" id="card-avatar-porcelana" class="card-avatar cursor-pointer kawaii-card p-4 text-center hover:border-pink-500 transition-all group shadow-sm" data-tom="porcelana">
                <div class="w-24 h-40 sm:h-48 mx-auto overflow-hidden bg-pink-50/70 rounded-2xl mb-3 flex items-center justify-center border border-pink-100 p-2">
                    <img id="img-avatar-porcelana" src="assets/img/bases/base_feminino_porcelana.png" alt="Pele Porcelana" class="h-full object-contain group-hover:scale-105 transition-transform">
                </div>
                <div class="text-xs sm:text-sm font-extrabold text-pink-800">Porcelana</div>
                <div class="text-[10px] text-pink-400">Claro Suave</div>
                <div class="mt-2 text-[11px] font-bold text-pink-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    Selecionar ♡
                </div>
            </div>

        </div>

        <!-- Botão de Ação: Avançar para a Tela do Guarda-Roupa -->
        <div class="text-center">
            <button type="button" onclick="avancarParaEditor()" class="kawaii-btn kawaii-btn-pink px-10 py-4 text-base font-black inline-flex items-center gap-3 shadow-xl transform hover:scale-105 transition-transform">
                <span>Continuar para o Guarda-Roupa ♡</span>
                <span class="text-xl">➔</span>
            </button>
        </div>

    </div>


    <!-- =======================================================
         TELA 2: CATÁLOGO DE ROUPAS & ACESSÓRIOS (EDITOR)
         ======================================================= -->
    <div id="tela-editor-roupas" class="hidden transition-all duration-300">
        
        <!-- Barra de Navegação do Jogo (Voltar / Salvar Rápido) -->
        <div class="flex items-center justify-between gap-4 mb-5 p-3 bg-pink-50 rounded-2xl border border-pink-200">
            <button type="button" onclick="voltarParaSelecaoAvatar()" class="px-4 py-2 bg-white hover:bg-pink-100 text-pink-700 border border-pink-300 rounded-full text-xs font-extrabold flex items-center gap-1.5 shadow-sm transition-all">
                <span>◀</span> Trocar Bonequinho
            </button>

            <div class="flex items-center gap-2">
                <span id="badge-avatar-atual" class="text-xs font-bold text-pink-800 bg-white px-3 py-1 rounded-full border border-pink-200 hidden sm:inline">
                    👧 Feminino (Claro)
                </span>
                <button type="button" onclick="Modal.open('modal-upload-sprite')" class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-full text-xs font-extrabold flex items-center gap-1.5 shadow-sm">
                    <span>➕</span> Enviar Roupinha PNG
                </button>
            </div>
        </div>

        <!-- Editor Principal (Palco + Catálogo de Roupas) -->
        <div class="kawaii-card p-4 sm:p-6 bg-white border-4 border-pink-200">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- COLUNA ESQUERDA: CATEGORIAS -->
                <div class="lg:col-span-3 space-y-2 order-2 lg:order-1">
                    <div class="p-3 bg-pink-100/70 border-2 border-pink-200 rounded-2xl flex items-center justify-between mb-3">
                        <span class="font-kawaii text-sm font-extrabold text-pink-700 flex items-center gap-2">
                            <span>🎀</span> Categorias
                        </span>
                        <span class="text-[10px] text-pink-500 font-bold">Toque p/ abrir</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-1 gap-2">
                        <button type="button" onclick="carregarItensCategoria('cabelos')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-pink-500 text-white shadow-md" data-categoria="cabelos">
                            <span class="text-xl">💇</span>
                            <span class="flex-1">Cabelos</span>
                        </button>
                        <button type="button" onclick="carregarItensCategoria('rostos')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="rostos">
                            <span class="text-xl">👶</span>
                            <span class="flex-1">Rostos (Roblox)</span>
                        </button>
                        <button type="button" onclick="carregarItensCategoria('blusas')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="blusas">
                            <span class="text-xl">👕</span>
                            <span class="flex-1">Blusas & Tops</span>
                        </button>
                        <button type="button" onclick="carregarItensCategoria('calcas')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="calcas">
                            <span class="text-xl">👗</span>
                            <span class="flex-1">Calças / Saias</span>
                        </button>
                        <button type="button" onclick="carregarItensCategoria('vestidos')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="vestidos">
                            <span class="text-xl">✨</span>
                            <span class="flex-1">Vestidos</span>
                        </button>
                        <button type="button" onclick="carregarItensCategoria('sapatos')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="sapatos">
                            <span class="text-xl">👠</span>
                            <span class="flex-1">Sapatos</span>
                        </button>
                        <button type="button" onclick="carregarItensCategoria('acessorios')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="acessorios">
                            <span class="text-xl">🎀</span>
                            <span class="flex-1">Acessórios</span>
                        </button>
                        <button type="button" onclick="carregarItensCategoria('cenarios')" class="cat-btn w-full p-3 text-left rounded-2xl font-bold text-xs sm:text-sm flex items-center gap-3 border-2 border-pink-200 transition-all bg-white text-pink-700 hover:bg-pink-50" data-categoria="cenarios">
                            <span class="text-xl">🖼️</span>
                            <span class="flex-1">Cenários</span>
                        </button>
                    </div>
                </div>

                <!-- COLUNA CENTRAL: PALCO DO PERSONAGEM -->
                <div class="lg:col-span-5 flex flex-col items-center order-1 lg:order-2">
                    <div class="w-full text-center pb-2 flex items-center justify-between">
                        <span class="text-xs font-bold text-pink-500">♡ Seu bonequinho ♡</span>
                        <span class="text-[11px] bg-pink-100 text-pink-700 px-2 py-0.5 rounded-full font-bold">Ao vivo</span>
                    </div>

                    <!-- Moldura do Personagem -->
                    <div class="relative w-full max-w-[320px] aspect-[290/560] bg-pink-50/50 rounded-3xl border-4 border-pink-300 shadow-xl overflow-hidden flex items-center justify-center p-2">
                        
                        <!-- Camadas do Avatar -->
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

                        <!-- Botões Flutuantes de Controle -->
                        <div class="absolute right-2 top-4 flex flex-col gap-2 z-30">
                            <button type="button" onclick="zoomIn()" class="w-9 h-9 bg-white/95 text-pink-700 hover:bg-pink-100 border border-pink-300 rounded-full flex flex-col items-center justify-center shadow-md text-xs font-bold transition-transform active:scale-90" title="Zoom +">
                                <span class="text-sm font-black leading-none">+</span>
                                <span class="text-[8px] font-bold leading-none">Zoom</span>
                            </button>
                            <button type="button" onclick="zoomOut()" class="w-9 h-9 bg-white/95 text-pink-700 hover:bg-pink-100 border border-pink-300 rounded-full flex flex-col items-center justify-center shadow-md text-xs font-bold transition-transform active:scale-90" title="Zoom -">
                                <span class="text-sm font-black leading-none">-</span>
                                <span class="text-[8px] font-bold leading-none">Zoom</span>
                            </button>
                            <button type="button" onclick="girarBoneco()" class="w-9 h-9 bg-white/95 text-pink-700 hover:bg-pink-100 border border-pink-300 rounded-full flex flex-col items-center justify-center shadow-md text-xs font-bold transition-transform active:scale-90" title="Girar / Espelhar">
                                <span class="text-sm leading-none">🔄</span>
                                <span class="text-[8px] font-bold leading-none">Girar</span>
                            </button>
                            <button type="button" onclick="lookAleatorio()" class="w-9 h-9 bg-white/95 text-pink-700 hover:bg-pink-100 border border-pink-300 rounded-full flex flex-col items-center justify-center shadow-md text-xs font-bold transition-transform active:scale-90" title="Look Aleatório">
                                <span class="text-sm leading-none">🎲</span>
                                <span class="text-[8px] font-bold leading-none">Sorte</span>
                            </button>
                        </div>
                    </div>

                    <!-- Botão Salvar Look -->
                    <button type="button" onclick="abrirModalSalvar()" class="kawaii-btn kawaii-btn-pink w-full max-w-[320px] py-3 mt-4 text-sm font-extrabold flex items-center justify-center gap-2 shadow-lg">
                        <span class="text-lg">💖</span> Salvar look ♡
                    </button>
                </div>

                <!-- COLUNA DIREITA: CATÁLOGO DE ITENS E CORES -->
                <div class="lg:col-span-4 space-y-4 order-3">
                    
                    <div class="kawaii-card p-4 border-2 border-pink-200">
                        <div class="flex items-center justify-between border-b-2 border-pink-100 pb-2 mb-3">
                            <span class="font-kawaii text-sm font-extrabold text-pink-700 flex items-center gap-1.5">
                                <span>✨</span> Catálogo de Itens ♡
                            </span>
                            <span class="text-[10px] text-pink-400 font-bold uppercase tracking-wider">Toque para vestir</span>
                        </div>

                        <!-- Grade de Itens Dinâmicos -->
                        <div id="grid-itens-catalogo" class="grid grid-cols-3 sm:grid-cols-4 gap-2.5 max-h-[360px] overflow-y-auto pr-1">
                            <!-- Inserido dinamicamente pelo app.js -->
                        </div>
                    </div>

                    <!-- Paleta de Cores de Cabelo -->
                    <div id="container-seletor-cores" class="kawaii-card p-4 border-2 border-pink-200">
                        <div class="text-xs font-bold text-pink-800 flex items-center gap-1.5 mb-2.5">
                            <span>🎨</span> Cores do Cabelo
                        </div>
                        <div id="paleta-cores-cabelo" class="flex flex-wrap gap-2.5 justify-center">
                            <!-- Bolinhas de cores inseridas pelo app.js -->
                        </div>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="grid grid-cols-3 gap-2 pt-1">
                        <button type="button" onclick="reiniciarLook()" class="p-3 bg-white hover:bg-pink-50 border-2 border-pink-200 rounded-2xl flex flex-col items-center justify-center text-xs font-bold text-gray-600 transition-all shadow-sm active:scale-95" title="Reiniciar">
                            <span class="text-xl mb-0.5">🔄</span>
                            <span>Reiniciar</span>
                        </button>
                        <button type="button" onclick="abrirModalSalvar()" class="p-3 bg-white hover:bg-pink-50 border-2 border-pink-200 rounded-2xl flex flex-col items-center justify-center text-xs font-bold text-pink-600 transition-all shadow-sm active:scale-95" title="Salvar">
                            <span class="text-xl mb-0.5">📥</span>
                            <span>Salvar</span>
                        </button>
                        <button type="button" onclick="tirarFoto()" class="p-3 bg-white hover:bg-pink-50 border-2 border-pink-200 rounded-2xl flex flex-col items-center justify-center text-xs font-bold text-pink-600 transition-all shadow-sm active:scale-95" title="Tirar foto">
                            <span class="text-xl mb-0.5">📷</span>
                            <span>Tirar print</span>
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

<!-- Engine do Jogo -->
<script src="assets/js/app.js"></script>

<script>
    // Gerenciamento das 2 Telas do Jogo
    let generoSelecionado = 'feminino';
    let tomPeleSelecionado = 'claro';

    function selecionarGenero(genero) {
        generoSelecionado = genero;
        
        const btnFem = document.getElementById('btn-gen-fem');
        const btnMasc = document.getElementById('btn-gen-masc');
        if (genero === 'feminino') {
            btnFem.className = 'btn-genero px-6 py-2 rounded-full text-xs font-extrabold transition-all bg-pink-500 text-white shadow';
            btnMasc.className = 'btn-genero px-6 py-2 rounded-full text-xs font-bold transition-all text-pink-600 hover:text-pink-800';
        } else {
            btnMasc.className = 'btn-genero px-6 py-2 rounded-full text-xs font-extrabold transition-all bg-pink-500 text-white shadow';
            btnFem.className = 'btn-genero px-6 py-2 rounded-full text-xs font-bold transition-all text-pink-600 hover:text-pink-800';
        }

        // Atualiza imagens dos cards de seleção
        ['claro', 'medio', 'escuro', 'porcelana'].forEach(tom => {
            const img = document.getElementById(`img-avatar-${tom}`);
            if (img) img.src = `assets/img/bases/base_${genero}_${tom}.png`;
        });

        BonequeteApp.setBase(generoSelecionado, tomPeleSelecionado);
        atualizarBadge();
    }

    function selecionarTomPele(tom) {
        tomPeleSelecionado = tom;
        document.querySelectorAll('.card-avatar').forEach(card => {
            if (card.dataset.tom === tom) {
                card.classList.add('ring-4', 'ring-pink-500', 'shadow-md');
            } else {
                card.classList.remove('ring-4', 'ring-pink-500', 'shadow-md');
            }
        });

        BonequeteApp.setBase(generoSelecionado, tomPeleSelecionado);
        atualizarBadge();
        Toast.info(`Avatar selecionado: ${generoSelecionado} (${tom}) ♡`, 1200);
    }

    function atualizarBadge() {
        const badge = document.getElementById('badge-avatar-atual');
        if (badge) {
            const genNome = generoSelecionado === 'feminino' ? '👧 Feminino' : '👦 Masculino';
            badge.innerText = `${genNome} (${tomPeleSelecionado})`;
        }
    }

    function avancarParaEditor() {
        document.getElementById('tela-selecao-avatar').classList.add('hidden');
        document.getElementById('tela-editor-roupas').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
        Toast.success("Bonequinho pronto! Agora monte o seu figurino no catálogo ♡");
    }

    function voltarParaSelecaoAvatar() {
        document.getElementById('tela-editor-roupas').classList.add('hidden');
        document.getElementById('tela-selecao-avatar').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
