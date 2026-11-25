<?php
/**
 * Roupichas-Bonequetes
 * Galeria de Looks Criados pela Comunidade
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/logger.php';

registrarLog('ACESSO_GALERIA', 'Usuário navegou pela galeria de looks.');

require_once __DIR__ . '/includes/header.php';
$filtro = $_GET['filtro'] ?? 'recentes';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Cabeçalho da Galeria -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-bold mb-2">
            <span>🖼️</span> Galeria de Inspirações
        </div>
        <h1 class="font-kawaii text-3xl sm:text-4xl font-extrabold text-pink-700">
            Looks das Bonequetes ♡
        </h1>
        <p class="text-gray-500 text-sm mt-1 max-w-md mx-auto">
            Inspire-se nos figurinos criados pelas estilistas da nossa comunidade!
        </p>

        <!-- Filtros Rápidos -->
        <div class="flex items-center justify-center gap-2 mt-6 flex-wrap">
            <a href="galeria.php?filtro=recentes" class="px-5 py-2 rounded-full text-xs font-bold transition-all <?= $filtro === 'recentes' ? 'bg-pink-500 text-white shadow-md' : 'bg-white text-pink-700 border border-pink-200 hover:bg-pink-50' ?>">
                ✨ Mais Recentes
            </a>
            <a href="galeria.php?filtro=populares" class="px-5 py-2 rounded-full text-xs font-bold transition-all <?= $filtro === 'populares' ? 'bg-pink-500 text-white shadow-md' : 'bg-white text-pink-700 border border-pink-200 hover:bg-pink-50' ?>">
                ❤️ Mais Amados
            </a>
            <?php if (!empty($_SESSION['usuario_id'])): ?>
            <a href="galeria.php?filtro=meus" class="px-5 py-2 rounded-full text-xs font-bold transition-all <?= $filtro === 'meus' ? 'bg-pink-500 text-white shadow-md' : 'bg-white text-pink-700 border border-pink-200 hover:bg-pink-50' ?>">
                🎀 Meus Looks
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Grade de Looks Salvos -->
    <div id="grid-looks" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
        <!-- Renderizado dinamicamente via JS -->
    </div>

    <!-- Mensagem de Estado Vazio -->
    <div id="looks-vazio" class="hidden text-center py-16">
        <div class="text-5xl mb-3 animate-heart">👗</div>
        <h3 class="font-kawaii text-xl font-bold text-pink-700">Nenhum look encontrado!</h3>
        <p class="text-xs text-gray-500 mt-1 mb-4">Seja o primeiro a criar e salvar um look incrível.</p>
        <a href="jogar.php" class="kawaii-btn kawaii-btn-pink px-6 py-2.5 text-xs font-bold inline-flex items-center gap-2">
            <span>▶</span> Montar Minha Bonequete
        </a>
    </div>

</div>

<!-- Modal de Visualização Ampliada do Look -->
<div id="modal-ver-look" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-200">
    <div class="kawaii-modal-card bg-white border-4 border-pink-300 rounded-3xl p-6 max-w-sm w-full text-center shadow-2xl relative">
        <button onclick="Modal.close('modal-ver-look')" class="absolute top-4 right-4 text-pink-400 hover:text-pink-600 text-2xl font-bold leading-none p-1">
            &times;
        </button>
        <h3 id="ver-look-titulo" class="font-kawaii text-lg font-bold text-pink-700 mb-1"></h3>
        <p id="ver-look-autor" class="text-xs text-pink-400 mb-4"></p>
        <div class="w-56 h-80 mx-auto bg-pink-50 rounded-2xl border-2 border-pink-200 overflow-hidden shadow-inner flex items-center justify-center p-2 mb-4">
            <img id="ver-look-img" src="" alt="Look" class="max-h-full max-w-full object-contain">
        </div>
        <div class="flex items-center justify-center gap-3">
            <button id="btn-modal-favoritar" class="kawaii-btn kawaii-btn-outline px-4 py-2 text-xs flex items-center gap-1.5 font-bold">
                <span id="ver-look-fav-icon">❤️</span> <span id="ver-look-fav-count">0</span>
            </button>
            <a id="btn-modal-download" href="#" download="bonequete.png" class="kawaii-btn kawaii-btn-pink px-4 py-2 text-xs flex items-center gap-1.5 font-bold">
                <span>⬇️</span> Baixar Foto
            </a>
        </div>
    </div>
</div>

<script>
    const filtroAtual = "<?= htmlspecialchars($filtro) ?>";

    async function carregarLooks() {
        const grid = document.getElementById('grid-looks');
        const empty = document.getElementById('looks-vazio');

        try {
            const res = await fetch(`api/listar_looks.php?filtro=${filtroAtual}`);
            const data = await res.json();

            if (!data.sucesso || !data.looks || data.looks.length === 0) {
                grid.innerHTML = '';
                empty.classList.remove('hidden');
                return;
            }

            empty.classList.add('hidden');
            grid.innerHTML = '';

            data.looks.forEach(look => {
                const card = document.createElement('div');
                card.className = 'kawaii-card p-3 flex flex-col justify-between hover:border-pink-400 group transition-all';
                card.innerHTML = `
                    <div class="aspect-[290/560] bg-pink-50 rounded-2xl overflow-hidden border border-pink-100 flex items-center justify-center p-1.5 relative cursor-pointer" onclick='abrirVisualizadorLook(${JSON.stringify(look)})'>
                        <img src="${look.imagem_preview}" alt="${look.titulo}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform" onerror="this.src='assets/img/mascote/mascote_chibi.png'">
                        <span class="absolute top-2 right-2 bg-white/90 text-pink-600 font-extrabold text-[10px] px-2 py-0.5 rounded-full shadow-xs flex items-center gap-1">
                            ❤️ ${look.favoritos}
                        </span>
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-xs text-pink-900 truncate" title="${look.titulo}">${look.titulo}</div>
                        <div class="text-[10px] text-pink-400 truncate">Por: ${look.usuario_nome || 'Estilista'}</div>
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-pink-100">
                            <button onclick="favoritarLook(${look.id}, this)" class="text-xs text-pink-600 hover:text-rose-600 font-bold flex items-center gap-1 transition-colors">
                                <span>❤️</span> <span class="fav-count">${look.favoritos}</span>
                            </button>
                            <button onclick='abrirVisualizadorLook(${JSON.stringify(look)})' class="text-[10px] bg-pink-100 hover:bg-pink-200 text-pink-700 px-2 py-1 rounded-full font-bold">
                                Ver ♡
                            </button>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });

        } catch (err) {
            console.error("Erro ao carregar galeria:", err);
            empty.classList.remove('hidden');
        }
    }

    async function favoritarLook(id, btn) {
        const formData = new FormData();
        formData.append('action', 'favoritar');
        formData.append('id', id);

        try {
            const res = await fetch('api/listar_looks.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.sucesso) {
                Toast.success("Look favoritado! ♡");
                const countSpan = btn.querySelector('.fav-count');
                if (countSpan) countSpan.innerText = data.favoritos;
            } else {
                Toast.error(data.mensagem);
            }
        } catch (err) {
            Toast.error("Erro ao favoritar.");
        }
    }

    let lookSelecionadoModal = null;
    function abrirVisualizadorLook(look) {
        lookSelecionadoModal = look;
        document.getElementById('ver-look-titulo').innerText = look.titulo;
        document.getElementById('ver-look-autor').innerText = "Criado com amor por: " + (look.usuario_nome || 'Estilista Anônimo');
        document.getElementById('ver-look-img').src = look.imagem_preview;
        document.getElementById('ver-look-fav-count').innerText = look.favoritos;
        document.getElementById('btn-modal-download').href = look.imagem_preview;

        const btnFav = document.getElementById('btn-modal-favoritar');
        btnFav.onclick = () => favoritarLook(look.id, btnFav);

        Modal.open('modal-ver-look');
    }

    document.addEventListener('DOMContentLoaded', carregarLooks);
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
