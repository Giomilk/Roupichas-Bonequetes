<?php
/**
 * Roupichas-Bonequetes
 * Página Direta de Login / Cadastro
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/logger.php';

if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-md mx-auto px-4 py-12">
    <div class="kawaii-card p-6 sm:p-8 bg-white border-4 border-pink-300">
        <div class="text-center mb-6">
            <img src="assets/img/mascote/mascote_chibi.png" alt="Mascote" class="w-20 h-20 mx-auto rounded-full object-cover border-4 border-pink-300 shadow-md mb-2">
            <h1 class="font-kawaii text-2xl font-bold text-pink-700">Entrar no Jogo ♡</h1>
            <p class="text-xs text-pink-500 font-semibold">Senhas com criptografia Bcrypt de alta segurança</p>
        </div>

        <!-- Abas -->
        <div class="flex border-b-2 border-pink-100 mb-6">
            <button id="page-tab-login" onclick="mudarAbaPagina('login')" class="flex-1 pb-2 text-sm font-extrabold text-pink-600 border-b-4 border-pink-500 transition-colors">
                Já tenho conta ♡
            </button>
            <button id="page-tab-cad" onclick="mudarAbaPagina('cadastro')" class="flex-1 pb-2 text-sm font-bold text-gray-400 hover:text-pink-500 transition-colors">
                Criar Conta ✨
            </button>
        </div>

        <form id="page-form-login" onsubmit="realizarLoginPagina(event)" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">E-mail</label>
                <input type="email" name="email" required placeholder="seuemail@exemplo.com" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Senha</label>
                <input type="password" name="senha" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm">
            </div>
            <button type="submit" class="kawaii-btn kawaii-btn-pink w-full py-3 text-sm font-bold uppercase tracking-wider mt-2">
                Entrar no Jogo ♡
            </button>
        </form>

        <form id="page-form-cad" onsubmit="realizarCadastroPagina(event)" class="space-y-4 hidden">
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Seu Nome de Estilista</label>
                <input type="text" name="nome" required placeholder="Ex: Mariazinha Estilosa" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">E-mail</label>
                <input type="email" name="email" required placeholder="seuemail@exemplo.com" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Senha (Mínimo 6 caracteres)</label>
                <input type="password" name="senha" minlength="6" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm">
            </div>
            <button type="submit" class="kawaii-btn kawaii-btn-pink w-full py-3 text-sm font-bold uppercase tracking-wider mt-2">
                Cadastrar Agora ✨
            </button>
        </form>
    </div>
</div>

<script>
    function mudarAbaPagina(aba) {
        const tLog = document.getElementById('page-tab-login');
        const tCad = document.getElementById('page-tab-cad');
        const fLog = document.getElementById('page-form-login');
        const fCad = document.getElementById('page-form-cad');

        if (aba === 'login') {
            tLog.className = "flex-1 pb-2 text-sm font-extrabold text-pink-600 border-b-4 border-pink-500 transition-colors";
            tCad.className = "flex-1 pb-2 text-sm font-bold text-gray-400 hover:text-pink-500 transition-colors";
            fLog.classList.remove('hidden');
            fCad.classList.add('hidden');
        } else {
            tCad.className = "flex-1 pb-2 text-sm font-extrabold text-pink-600 border-b-4 border-pink-500 transition-colors";
            tLog.className = "flex-1 pb-2 text-sm font-bold text-gray-400 hover:text-pink-500 transition-colors";
            fCad.classList.remove('hidden');
            fLog.classList.add('hidden');
        }
    }

    async function realizarLoginPagina(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('action', 'login');
        try {
            const res = await fetch('api/auth.php', { method: 'POST', body: formData });
            const json = await res.json();
            if (json.sucesso) {
                Toast.success(json.mensagem);
                setTimeout(() => window.location.href = 'index.php', 800);
            } else {
                Toast.error(json.mensagem);
            }
        } catch (err) {
            Toast.error('Erro de conexão ao entrar.');
        }
    }

    async function realizarCadastroPagina(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('action', 'cadastro');
        try {
            const res = await fetch('api/auth.php', { method: 'POST', body: formData });
            const json = await res.json();
            if (json.sucesso) {
                Toast.success(json.mensagem);
                setTimeout(() => window.location.href = 'index.php', 1000);
            } else {
                Toast.error(json.mensagem);
            }
        } catch (err) {
            Toast.error('Erro de conexão ao cadastrar.');
        }
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
