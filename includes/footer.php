    </main>

    <!-- Rodapé Fofo -->
    <footer class="bg-white/80 border-t-2 border-pink-200 mt-12 py-6 text-center text-xs text-pink-700/80">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <img src="assets/img/logo/laco_logo.png" alt="Laço" class="w-6 h-5 object-contain">
                <span class="font-bold font-kawaii">Roupichas-Bonequetes</span>
                <span>— Vista sonhos, crie histórias! ♡</span>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <a href="historico.php" class="hover:text-pink-900 transition-colors">Auditoria & Logs</a>
                <span>•</span>
                <a href="galeria.php" class="hover:text-pink-900 transition-colors">Galeria</a>
                <span>•</span>
                <a href="sobre.php" class="hover:text-pink-900 transition-colors">Sobre</a>
            </div>
            <div class="text-[11px] text-pink-400">
                Desenvolvido com PHP, PDO MySQL e Tailwind CSS ♡
            </div>
        </div>
    </footer>

    <!-- Inclusão dos Modais Reutilizáveis -->
    <?php require_once __DIR__ . '/modals.php'; ?>

    <!-- Bibliotecas de Toast e Modais -->
    <script src="assets/js/toasts.js"></script>
    <script src="assets/js/modals.js"></script>

    <!-- Script Global de Autenticação e Navegação -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu) menu.classList.toggle('hidden');
        }

        function alternarAbaAuth(aba) {
            const btnLogin = document.getElementById('tab-btn-login');
            const btnCadastro = document.getElementById('tab-btn-cadastro');
            const formLogin = document.getElementById('form-login');
            const formCadastro = document.getElementById('form-cadastro');

            if (aba === 'login') {
                btnLogin.className = "flex-1 pb-2 text-sm font-extrabold text-pink-600 border-b-4 border-pink-500 transition-colors";
                btnCadastro.className = "flex-1 pb-2 text-sm font-bold text-gray-400 hover:text-pink-500 transition-colors";
                formLogin.classList.remove('hidden');
                formCadastro.classList.add('hidden');
            } else {
                btnCadastro.className = "flex-1 pb-2 text-sm font-extrabold text-pink-600 border-b-4 border-pink-500 transition-colors";
                btnLogin.className = "flex-1 pb-2 text-sm font-bold text-gray-400 hover:text-pink-500 transition-colors";
                formCadastro.classList.remove('hidden');
                formLogin.classList.add('hidden');
            }
        }

        async function realizarLogin(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('action', 'login');

            try {
                const res = await fetch('api/auth.php', { method: 'POST', body: formData });
                const json = await res.json();
                if (json.sucesso) {
                    Toast.success(json.mensagem);
                    Modal.close('modal-auth');
                    setTimeout(() => location.reload(), 800);
                } else {
                    Toast.error(json.mensagem);
                }
            } catch (err) {
                Toast.error('Erro de conexão ao entrar. Tente novamente!');
            }
        }

        async function realizarCadastro(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('action', 'cadastro');

            try {
                const res = await fetch('api/auth.php', { method: 'POST', body: formData });
                const json = await res.json();
                if (json.sucesso) {
                    Toast.success(json.mensagem);
                    Modal.close('modal-auth');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Toast.error(json.mensagem);
                }
            } catch (err) {
                Toast.error('Erro de conexão ao cadastrar. Verifique seus dados!');
            }
        }

        async function fazerLogout() {
            const confirmou = await Modal.confirm('Deseja realmente sair da sua conta?', 'Sair do Jogo ♡');
            if (!confirmou) return;

            try {
                const res = await fetch('api/auth.php?action=logout', { method: 'POST' });
                const json = await res.json();
                Toast.info(json.mensagem);
                setTimeout(() => location.reload(), 800);
            } catch (err) {
                location.reload();
            }
        }

        function previewUploadSprite(input) {
            const previewContainer = document.getElementById('upload-preview-container');
            const previewImg = document.getElementById('upload-preview-img');
            const prompt = document.getElementById('upload-dropzone-prompt');
            const filenameLabel = document.getElementById('upload-filename');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (!file.name.toLowerCase().endsWith('.png')) {
                    Toast.error('Por favor, selecione exclusivamente um arquivo PNG!');
                    input.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    filenameLabel.innerText = file.name;
                    previewContainer.classList.remove('hidden');
                    prompt.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        async function enviarSpriteCustomizada(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('upload-submit-btn');

            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Enviando sprite... ✨';

            try {
                const res = await fetch('api/upload_sprite.php', { method: 'POST', body: formData });
                const json = await res.json();
                if (json.sucesso) {
                    Toast.success(json.mensagem);
                    Modal.close('modal-upload-sprite');
                    form.reset();
                    document.getElementById('upload-preview-container').classList.add('hidden');
                    document.getElementById('upload-dropzone-prompt').classList.remove('hidden');
                    
                    // Se estiver no jogo, recarrega a categoria e seleciona o item
                    if (typeof carregarItensCategoria === 'function') {
                        carregarItensCategoria(json.item.categoria);
                    }
                } else {
                    Toast.error(json.mensagem);
                }
            } catch (err) {
                Toast.error('Erro de conexão ao enviar a sprite.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Publicar no Guarda-Roupa ♡';
            }
        }
    </script>
</body>
</html>
