/**
 * Roupichas-Bonequetes - Engine do Jogo e Editor de Personagem
 *
 * Implementa:
 * - Renderização modular em camadas
 * - Zoom (+/-), Rotação/Flip horizontal, Look aleatório
 * - Paleta dinâmica de cores para cabelos
 * - Seleção de tons de pele e gênero (Feminino / Masculino)
 * - Renderização e snapshot em HTML5 Canvas de alta resolução
 * - Integração com APIs de Itens, Upload de Sprites e Salvar Looks
 */

const BonequeteApp = (function() {

    // Estado Atual do Personagem
    const state = {
        genero: 'feminino',
        tom_pele: 'claro',
        base_avatar: 'assets/img/bases/base_feminino_claro.png',
        cenario: 'assets/img/cenarios/cenario_quarto_pastel.svg',
        cenario_id: null,
        cabelo: 'assets/img/cabelos/cabelo_ondulado_castanho.png',
        cabelo_id: 1,
        cabelo_cor: '#4a2c11',
        rosto: 'assets/img/rostos/rosto_roblox_mulher.png',
        rosto_id: 11,
        blusa: 'assets/img/blusas/blusa_cardigan_rosa.svg',
        blusa_id: 15,
        calca: 'assets/img/calcas/calca_saia_plissada_rosa.svg',
        calca_id: 20,
        vestido: null,
        vestido_id: null,
        sapato: 'assets/img/sapatos/sapato_boneca_maryjane.svg',
        sapato_id: 27,
        acessorio: 'assets/img/acessorios/acessorio_laco_gigante.svg',
        acessorio_id: 28,
        
        // Transformações
        zoom: 1.0,
        flip: 1, // 1 ou -1
        
        // Categoria Ativa no Catálogo
        categoriaAtiva: 'cabelos',
        catalogo: {}
    };

    // Paleta de Cores de Cabelo (com filtros CSS de matiz/brilho)
    const paletaCoresCabelo = [
        { nome: 'Castanho Natural', hex: '#4a2c11', filter: 'none' },
        { nome: 'Loiro Mel', hex: '#fcd34d', filter: 'sepia(0.8) saturate(2) brightness(1.3) hue-rotate(10deg)' },
        { nome: 'Preto Ônix', hex: '#18181b', filter: 'brightness(0.3) saturate(0.2)' },
        { nome: 'Rosa Algodão Doce', hex: '#f472b6', filter: 'sepia(0.7) saturate(3) hue-rotate(290deg) brightness(1.15)' },
        { nome: 'Azul Pastel', hex: '#60a5fa', filter: 'sepia(0.7) saturate(3) hue-rotate(180deg) brightness(1.1)' },
        { nome: 'Lilás Fofo', hex: '#c084fc', filter: 'sepia(0.7) saturate(3) hue-rotate(240deg) brightness(1.1)' },
        { nome: 'Ruivo Fogo', hex: '#ea580c', filter: 'sepia(0.9) saturate(4) hue-rotate(345deg) brightness(1.05)' },
        { nome: 'Platina Neve', hex: '#f1f5f9', filter: 'brightness(1.8) saturate(0.1)' }
    ];

    // Inicialização da Aplicação
    async function init() {
        console.log("Inicializando Roupichas-Bonequetes Engine...");
        renderizarPaletaCores();
        await carregarCatalogoCompleto();
        atualizarBasesVisuais();
        aplicarTransformacoes();
        renderizarTodasCamadas();
    }

    // Carrega todo o catálogo de itens da API
    async function carregarCatalogoCompleto() {
        try {
            const res = await fetch('api/itens.php');
            const data = await res.json();
            if (data.sucesso && data.itens) {
                // Agrupa por categoria
                state.catalogo = {};
                data.itens.forEach(item => {
                    if (!state.catalogo[item.categoria]) {
                        state.catalogo[item.categoria] = [];
                    }
                    state.catalogo[item.categoria].push(item);
                });
                renderizarGradeItens(state.categoriaAtiva);
            }
        } catch (err) {
            console.error("Erro ao carregar catálogo:", err);
            Toast.error("Não foi possível carregar o catálogo de roupas.");
        }
    }

    // Renderiza a grade de itens da categoria ativa
    function renderizarGradeItens(categoria) {
        state.categoriaAtiva = categoria;
        const container = document.getElementById('grid-itens-catalogo');
        if (!container) return;

        // Atualiza botões de categoria ativos
        document.querySelectorAll('.cat-btn').forEach(btn => {
            if (btn.dataset.categoria === categoria) {
                btn.classList.add('bg-pink-500', 'text-white', 'shadow-md');
                btn.classList.remove('bg-white', 'text-pink-700');
            } else {
                btn.classList.remove('bg-pink-500', 'text-white', 'shadow-md');
                btn.classList.add('bg-white', 'text-pink-700');
            }
        });

        // Mostra ou esconde seletor de cores de cabelo
        const seletorCores = document.getElementById('container-seletor-cores');
        if (seletorCores) {
            if (categoria === 'cabelos') {
                seletorCores.classList.remove('hidden');
            } else {
                seletorCores.classList.add('hidden');
            }
        }

        container.innerHTML = '';
        const itens = state.catalogo[categoria] || [];

        if (itens.length === 0) {
            container.innerHTML = `
                <div class="col-span-full py-8 text-center text-gray-400 text-xs">
                    <div class="text-3xl mb-1">📦</div>
                    Nenhum item nesta categoria ainda.<br>
                    <button onclick="Modal.open('modal-upload-sprite')" class="mt-2 text-pink-600 font-bold underline">
                        Clique aqui para enviar uma sprite PNG!
                    </button>
                </div>
            `;
            return;
        }

        // Adiciona opção de "Desequipar"
        const slotNenhum = document.createElement('div');
        slotNenhum.className = 'item-slot hover:border-rose-400';
        slotNenhum.title = 'Desequipar / Nenhum';
        slotNenhum.innerHTML = `
            <div class="flex flex-col items-center justify-center text-gray-400 hover:text-rose-500">
                <span class="text-2xl leading-none">🚫</span>
                <span class="text-[9px] font-bold mt-1">Tirar</span>
            </div>
        `;
        slotNenhum.onclick = () => desequiparItem(categoria);
        container.appendChild(slotNenhum);

        const prop = catToProp[categoria] || categoria;

        // Renderiza itens
        itens.forEach(item => {
            const slot = document.createElement('div');
            const estaEquipado = (state[prop + '_id'] == item.id || state[prop] == item.imagem);
            
            slot.className = `item-slot group ${estaEquipado ? 'selected' : ''}`;
            slot.title = item.nome;

            // Thumbnail do item
            const imgSrc = item.miniatura || item.imagem;
            slot.innerHTML = `
                <img src="${imgSrc}" alt="${item.nome}" class="w-full h-full object-contain p-1 group-hover:scale-110 transition-transform">
                <span class="absolute bottom-1 left-1 right-1 text-[8px] bg-white/90 text-pink-900 font-bold px-1 rounded truncate text-center shadow-xs">
                    ${item.nome}
                </span>
            `;

            slot.onclick = () => equiparItem(item);
            container.appendChild(slot);
        });
    }

    // Mapeamento de categorias no plural para chaves do estado
    const catToProp = {
        'cabelos': 'cabelo',
        'rostos': 'rosto',
        'blusas': 'blusa',
        'calcas': 'calca',
        'vestidos': 'vestido',
        'sapatos': 'sapato',
        'acessorios': 'acessorio',
        'cenarios': 'cenario'
    };

    // Renderiza a paleta de cores para cabelo
    function renderizarPaletaCores() {
        const container = document.getElementById('paleta-cores-cabelo');
        if (!container) return;
        container.innerHTML = '';

        paletaCoresCabelo.forEach(c => {
            const dot = document.createElement('div');
            dot.className = `color-dot ${state.cabelo_cor === c.hex ? 'active' : ''}`;
            dot.style.backgroundColor = c.hex;
            dot.title = c.nome;
            dot.onclick = () => {
                state.cabelo_cor = c.hex;
                aplicarCorCabelo(c.filter);
                document.querySelectorAll('.color-dot').forEach(d => d.classList.remove('active'));
                dot.classList.add('active');
                Toast.info(`Cor de cabelo alterada para ${c.nome}! ✨`, 1500);
            };
            container.appendChild(dot);
        });
    }

    function aplicarCorCabelo(filtroCss) {
        const layer = document.getElementById('layer-cabelo');
        if (layer) {
            layer.style.filter = filtroCss;
        }
    }

    // Equipa um item no bonequinho
    function equiparItem(item) {
        const cat = item.categoria;
        const prop = catToProp[cat] || cat;

        // Se for vestido, desequipa blusa e calça para evitar sobreposição estranha
        if (cat === 'vestidos') {
            state.vestido = item.imagem;
            state.vestido_id = item.id;
            state.blusa = null;
            state.blusa_id = null;
            state.calca = null;
            state.calca_id = null;
        } else if (cat === 'blusas' || cat === 'calcas') {
            // Se vestir blusa ou calça, remove o vestido
            state.vestido = null;
            state.vestido_id = null;
            state[prop] = item.imagem;
            state[prop + '_id'] = item.id;
        } else {
            state[prop] = item.imagem;
            state[prop + '_id'] = item.id;
        }

        renderizarTodasCamadas();
        renderizarGradeItens(state.categoriaAtiva);
        Toast.success(`Você vestiu: ${item.nome}! ♡`, 1800);
    }

    // Desequipa um item de determinada categoria
    function desequiparItem(categoria) {
        const prop = catToProp[categoria] || categoria;
        state[prop] = null;
        state[prop + '_id'] = null;
        renderizarTodasCamadas();
        renderizarGradeItens(state.categoriaAtiva);
        Toast.info(`Item removido!`, 1500);
    }

    // Atualiza as camadas no palco (DOM)
    function renderizarTodasCamadas() {
        const camadas = [
            { id: 'layer-cenario', src: state.cenario },
            { id: 'layer-base', src: state.base_avatar },
            { id: 'layer-calca', src: state.calca },
            { id: 'layer-blusa', src: state.blusa },
            { id: 'layer-vestido', src: state.vestido },
            { id: 'layer-sapato', src: state.sapato },
            { id: 'layer-cabelo', src: state.cabelo },
            { id: 'layer-rosto', src: state.rosto },
            { id: 'layer-acessorio', src: state.acessorio }
        ];

        camadas.forEach(c => {
            const el = document.getElementById(c.id);
            if (el) {
                if (c.src) {
                    el.src = c.src;
                    el.style.display = 'block';
                } else {
                    el.style.display = 'none';
                    el.src = '';
                }
            }
        });
    }

    // Troca o tom de pele ou gênero da base
    function definirBase(genero, tomPele) {
        state.genero = genero || state.genero;
        state.tom_pele = tomPele || state.tom_pele;
        state.base_avatar = `assets/img/bases/base_${state.genero}_${state.tom_pele}.png`;
        renderizarTodasCamadas();
        atualizarBasesVisuais();
        Toast.info(`Base alterada: ${state.genero} (${state.tom_pele}) ♡`, 1500);
    }

    function atualizarBasesVisuais() {
        document.querySelectorAll('.btn-tom-pele').forEach(btn => {
            if (btn.dataset.tom === state.tom_pele) {
                btn.classList.add('ring-4', 'ring-pink-500');
            } else {
                btn.classList.remove('ring-4', 'ring-pink-500');
            }
        });
        document.querySelectorAll('.btn-genero').forEach(btn => {
            if (btn.dataset.genero === state.genero) {
                btn.classList.add('bg-pink-500', 'text-white');
                btn.classList.remove('bg-white', 'text-pink-600');
            } else {
                btn.classList.remove('bg-pink-500', 'text-white');
                btn.classList.add('bg-white', 'text-pink-600');
            }
        });
    }

    // Zoom Controls
    function ajustarZoom(delta) {
        state.zoom = Math.min(1.5, Math.max(0.75, state.zoom + delta));
        aplicarTransformacoes();
    }

    // Girar / Flip Horizontal
    function girarBoneco() {
        state.flip = state.flip === 1 ? -1 : 1;
        aplicarTransformacoes();
        Toast.info("Bonequinho girado!", 1200);
    }

    function aplicarTransformacoes() {
        const stage = document.getElementById('avatar-character-stage');
        if (stage) {
            stage.style.transform = `scale(${state.zoom}) scaleX(${state.flip})`;
            stage.style.transformOrigin = 'center center';
            stage.style.transition = 'transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1)';
        }
    }

    // Look Aleatório (Shuffle)
    function lookAleatorio() {
        // Escolhe tom de pele e gênero
        const tons = ['claro', 'medio', 'escuro', 'porcelana'];
        const gens = ['feminino', 'masculino'];
        state.tom_pele = tons[Math.floor(Math.random() * tons.length)];
        state.genero = gens[Math.floor(Math.random() * gens.length)];
        state.base_avatar = `assets/img/bases/base_${state.genero}_${state.tom_pele}.png`;

        // Itens aleatórios para cada categoria
        const sorteia = (cat) => {
            const lista = state.catalogo[cat] || [];
            if (lista.length === 0) return null;
            return lista[Math.floor(Math.random() * lista.length)];
        };

        const randomHair = sorteia('cabelos');
        if (randomHair) { state.cabelo = randomHair.imagem; state.cabelo_id = randomHair.id; }

        const randomFace = sorteia('rostos');
        if (randomFace) { state.rosto = randomFace.imagem; state.rosto_id = randomFace.id; }

        const usaVestido = Math.random() > 0.6;
        if (usaVestido) {
            const randomVest = sorteia('vestidos');
            if (randomVest) {
                state.vestido = randomVest.imagem;
                state.vestido_id = randomVest.id;
                state.blusa = null; state.blusa_id = null;
                state.calca = null; state.calca_id = null;
            }
        } else {
            state.vestido = null; state.vestido_id = null;
            const randomTop = sorteia('blusas');
            if (randomTop) { state.blusa = randomTop.imagem; state.blusa_id = randomTop.id; }
            const randomBottom = sorteia('calcas');
            if (randomBottom) { state.calca = randomBottom.imagem; state.calca_id = randomBottom.id; }
        }

        const randomShoes = sorteia('sapatos');
        if (randomShoes) { state.sapato = randomShoes.imagem; state.sapato_id = randomShoes.id; }

        const randomAcc = sorteia('acessorios');
        if (randomAcc) { state.acessorio = randomAcc.imagem; state.acessorio_id = randomAcc.id; }

        const randomScene = sorteia('cenarios');
        if (randomScene) { state.cenario = randomScene.imagem; state.cenario_id = randomScene.id; }

        // Cor aleatória de cabelo
        const randomCor = paletaCoresCabelo[Math.floor(Math.random() * paletaCoresCabelo.length)];
        state.cabelo_cor = randomCor.hex;
        aplicarCorCabelo(randomCor.filter);

        renderizarTodasCamadas();
        renderizarGradeItens(state.categoriaAtiva);
        atualizarBasesVisuais();
        Toast.success("Look aleatório montado com sucesso! ✨🎲", 2000);
    }

    // Reinicia o visual do bonequinho
    function reiniciarLook() {
        Modal.confirm("Deseja tirar todas as roupas do bonequinho e começar do zero?", "Reiniciar Look ♡")
            .then(sim => {
                if (!sim) return;
                state.blusa = null; state.blusa_id = null;
                state.calca = null; state.calca_id = null;
                state.vestido = null; state.vestido_id = null;
                state.sapato = null; state.sapato_id = null;
                state.acessorio = null; state.acessorio_id = null;
                renderizarTodasCamadas();
                renderizarGradeItens(state.categoriaAtiva);
                Toast.info("Look reiniciado!");
            });
    }

    // Renderiza o boneco completo em um Canvas HTML5 para download ou salvamento
    async function gerarSnapshotCanvas() {
        const canvas = document.createElement('canvas');
        canvas.width = 290;
        canvas.height = 560;
        const ctx = canvas.getContext('2d');

        const corSelecionada = paletaCoresCabelo.find(c => c.hex === state.cabelo_cor);
        const filtroCabelo = corSelecionada ? corSelecionada.filter : 'none';

        // Ordem lógica das camadas
        const camadas = [
            { src: state.cenario, isCenario: true },
            { src: state.base_avatar },
            { src: state.calca },
            { src: state.blusa },
            { src: state.vestido },
            { src: state.sapato },
            { src: state.cabelo, filter: filtroCabelo },
            { src: state.rosto },
            { src: state.acessorio }
        ].filter(c => Boolean(c.src));

        ctx.save();
        if (state.flip === -1) {
            ctx.translate(290, 0);
            ctx.scale(-1, 1);
        }

        for (const camada of camadas) {
            await new Promise((resolve) => {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = () => {
                    ctx.save();
                    if (camada.filter && camada.filter !== 'none') {
                        ctx.filter = camada.filter;
                    }
                    if (camada.isCenario && img.width && img.height) {
                        const scale = Math.max(290 / img.width, 560 / img.height);
                        const dw = img.width * scale;
                        const dh = img.height * scale;
                        ctx.drawImage(img, (290 - dw) / 2, (560 - dh) / 2, dw, dh);
                    } else {
                        ctx.drawImage(img, 0, 0, 290, 560);
                    }
                    ctx.restore();
                    resolve();
                };
                img.onerror = () => resolve();
                img.src = camada.src;
            });
        }

        ctx.restore();
        return canvas;
    }

    // Tirar Print / Download PNG direto
    async function tirarPrint() {
        Toast.info("Preparando sua foto fofinha em alta resolução... 📸", 1500);
        const canvas = await gerarSnapshotCanvas();
        const link = document.createElement('a');
        link.download = `bonequete_${state.genero}_${Date.now()}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
        Toast.success("Foto baixada com sucesso! Compartilhe com suas amigas ♡", 3000);
    }

    // Abre modal para salvar look na base
    async function abrirModalSalvarLook() {
        const canvas = await gerarSnapshotCanvas();
        const dataUrl = canvas.toDataURL('image/png');
        
        const previewImg = document.getElementById('salvar-look-preview-img');
        if (previewImg) previewImg.src = dataUrl;

        Modal.open('modal-salvar-look');
    }

    // Confirma envio do look para a API
    async function confirmarSalvarLook() {
        const titulo = document.getElementById('salvar-look-titulo').value.trim() || 'Meu Look Fofinho ♡';
        const canvas = await gerarSnapshotCanvas();
        const base64Img = canvas.toDataURL('image/png');

        const btn = document.getElementById('btn-confirmar-salvar');
        btn.disabled = true;
        btn.innerHTML = 'Salvando look... ✨';

        const payload = {
            titulo: titulo,
            base_avatar: state.base_avatar,
            tom_pele: state.tom_pele,
            genero: state.genero,
            cabelo_id: state.cabelo_id,
            cabelo_cor: state.cabelo_cor,
            rosto_id: state.rosto_id,
            blusa_id: state.blusa_id,
            calca_id: state.calca_id,
            vestido_id: state.vestido_id,
            sapato_id: state.sapato_id,
            acessorio_id: state.acessorio_id,
            cenario_id: state.cenario_id,
            config_json: state,
            imagem_preview: base64Img
        };

        try {
            const res = await fetch('api/salvar_look.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            if (json.sucesso) {
                Toast.success(json.mensagem);
                Modal.close('modal-salvar-look');
            } else {
                Toast.error(json.mensagem);
            }
        } catch (err) {
            Toast.error("Erro ao salvar look no servidor.");
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span>💖</span> Salvar na Galeria';
        }
    }

    function baixarSnapshotLook() {
        tirarPrint();
    }

    return {
        init: init,
        setBase: definirBase,
        zoomIn: () => ajustarZoom(0.15),
        zoomOut: () => ajustarZoom(-0.15),
        flip: girarBoneco,
        random: lookAleatorio,
        reset: reiniciarLook,
        screenshot: tirarPrint,
        openSave: abrirModalSalvarLook,
        confirmSave: confirmarSalvarLook,
        downloadSnapshot: baixarSnapshotLook,
        setCategory: renderizarGradeItens,
        getState: () => ({ ...state })
    };
})();

// Inicializa quando a página carregar
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('avatar-character-stage')) {
        BonequeteApp.init();
    }
});

// Helpers globais
window.carregarItensCategoria = (cat) => BonequeteApp.setCategory(cat);
window.abrirModalSalvar = () => BonequeteApp.openSave();
window.confirmarSalvarLook = () => BonequeteApp.confirmSave();
window.baixarSnapshotLook = () => BonequeteApp.downloadSnapshot();
window.tirarFoto = () => BonequeteApp.screenshot();
window.lookAleatorio = () => BonequeteApp.random();
window.reiniciarLook = () => BonequeteApp.reset();
window.girarBoneco = () => BonequeteApp.flip();
window.zoomIn = () => BonequeteApp.zoomIn();
window.zoomOut = () => BonequeteApp.zoomOut();
window.selecionarBase = (gen, tom) => BonequeteApp.setBase(gen, tom);
