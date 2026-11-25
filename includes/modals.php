<!-- Modais Reutilizáveis (Substitutos de Diálogos Nativos) -->

<!-- 1. MODAL ALERT -->
<div id="modal-alert-dialog" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm transition-all duration-200">
    <div class="kawaii-modal-card bg-white border-4 border-pink-300 rounded-3xl p-6 max-w-sm w-full text-center shadow-2xl transform scale-95 opacity-0 transition-all duration-200">
        <div class="text-4xl mb-3 animate-heart">🎀</div>
        <h3 id="modal-alert-title" class="font-kawaii text-xl font-bold text-pink-700 mb-2">Aviso Fofinho</h3>
        <p id="modal-alert-message" class="text-gray-600 text-sm mb-6 leading-relaxed"></p>
        <button id="modal-alert-btn-ok" class="kawaii-btn kawaii-btn-pink w-full py-2.5 text-sm uppercase tracking-wider">
            Entendi ♡
        </button>
    </div>
</div>

<!-- 2. MODAL CONFIRM -->
<div id="modal-confirm-dialog" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm transition-all duration-200">
    <div class="kawaii-modal-card bg-white border-4 border-pink-300 rounded-3xl p-6 max-w-sm w-full text-center shadow-2xl transform scale-95 opacity-0 transition-all duration-200">
        <div class="text-4xl mb-3 animate-heart">❓</div>
        <h3 id="modal-confirm-title" class="font-kawaii text-xl font-bold text-pink-700 mb-2">Você tem certeza?</h3>
        <p id="modal-confirm-message" class="text-gray-600 text-sm mb-6 leading-relaxed"></p>
        <div class="grid grid-cols-2 gap-3">
            <button id="modal-confirm-btn-nao" class="kawaii-btn kawaii-btn-outline py-2.5 text-sm">
                Cancelar
            </button>
            <button id="modal-confirm-btn-sim" class="kawaii-btn kawaii-btn-pink py-2.5 text-sm">
                Sim, confirmar ♡
            </button>
        </div>
    </div>
</div>

<!-- 3. MODAL LOGIN & CADASTRO -->
<div id="modal-auth" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/45 backdrop-blur-sm transition-all duration-200">
    <div class="kawaii-modal-card bg-white border-4 border-pink-300 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl transform scale-95 opacity-0 transition-all duration-200 relative">
        <button onclick="Modal.close('modal-auth')" class="absolute top-4 right-4 text-pink-400 hover:text-pink-600 text-2xl font-bold leading-none p-1">
            &times;
        </button>

        <div class="text-center mb-6">
            <div class="inline-block p-2 bg-pink-100 rounded-full mb-2">
                <img src="assets/img/mascote/mascote_chibi.png" alt="Mascote" class="w-16 h-16 rounded-full object-cover border-2 border-pink-300 shadow">
            </div>
            <h3 class="font-kawaii text-2xl font-bold text-pink-700">Cantinho do Estilista</h3>
            <p class="text-xs text-pink-500 font-semibold">Senhas com criptografia Bcrypt de alta segurança</p>
        </div>

        <!-- Abas de Alternância -->
        <div class="flex border-b-2 border-pink-100 mb-6">
            <button id="tab-btn-login" onclick="alternarAbaAuth('login')" class="flex-1 pb-2 text-sm font-extrabold text-pink-600 border-b-4 border-pink-500 transition-colors">
                Já tenho conta ♡
            </button>
            <button id="tab-btn-cadastro" onclick="alternarAbaAuth('cadastro')" class="flex-1 pb-2 text-sm font-bold text-gray-400 hover:text-pink-500 transition-colors">
                Quero me cadastrar ✨
            </button>
        </div>

        <!-- Formulário de Login -->
        <form id="form-login" onsubmit="realizarLogin(event)" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">E-mail</label>
                <input type="email" name="email" required placeholder="seuemail@exemplo.com" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Senha</label>
                <input type="password" name="senha" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm transition-colors">
            </div>
            <button type="submit" class="kawaii-btn kawaii-btn-pink w-full py-3 text-sm font-bold uppercase tracking-wider mt-2">
                Entrar no Jogo ♡
            </button>
        </form>

        <!-- Formulário de Cadastro -->
        <form id="form-cadastro" onsubmit="realizarCadastro(event)" class="space-y-4 hidden">
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Seu Nome de Estilista</label>
                <input type="text" name="nome" required placeholder="Ex: Aninha Fashion" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">E-mail</label>
                <input type="email" name="email" required placeholder="seuemail@exemplo.com" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Senha (Mínimo 6 caracteres)</label>
                <input type="password" name="senha" minlength="6" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm transition-colors">
            </div>
            <button type="submit" class="kawaii-btn kawaii-btn-pink w-full py-3 text-sm font-bold uppercase tracking-wider mt-2">
                Criar Minha Conta ✨
            </button>
        </form>
    </div>
</div>

<!-- 4. MODAL UPLOAD SPRITE PNG CUSTOMIZADA -->
<div id="modal-upload-sprite" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/45 backdrop-blur-sm transition-all duration-200">
    <div class="kawaii-modal-card bg-white border-4 border-pink-300 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl transform scale-95 opacity-0 transition-all duration-200 relative max-h-[90vh] overflow-y-auto">
        <button onclick="Modal.close('modal-upload-sprite')" class="absolute top-4 right-4 text-pink-400 hover:text-pink-600 text-2xl font-bold leading-none p-1">
            &times;
        </button>

        <div class="text-center mb-5">
            <div class="text-4xl mb-1">🎨</div>
            <h3 class="font-kawaii text-2xl font-bold text-pink-700">Enviar Sua Sprite (PNG)</h3>
            <p class="text-xs text-gray-500 mt-1">
                Qualquer pessoa pode adicionar novas peças de roupa, rostinhos ou cabelos ao jogo!
            </p>
        </div>

        <form id="form-upload-sprite" onsubmit="enviarSpriteCustomizada(event)" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Nome da Peça</label>
                <input type="text" name="nome" id="upload-nome" required placeholder="Ex: Vestido Gotinha Mágica" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Categoria</label>
                    <select name="categoria" id="upload-categoria" required class="w-full px-3 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm bg-white">
                        <option value="cabelos">💇 Cabelos</option>
                        <option value="rostos">👶 Rostos</option>
                        <option value="blusas" selected>👕 Blusas / Tops</option>
                        <option value="calcas">👗 Calças / Saias</option>
                        <option value="vestidos">👗 Vestidos</option>
                        <option value="sapatos">👠 Sapatos</option>
                        <option value="acessorios">🎀 Acessórios</option>
                        <option value="cenarios">🖼️ Cenários</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Subcategoria (Opcional)</label>
                    <input type="text" name="subcategoria" placeholder="Ex: saia, cardigan, óculos" class="w-full px-3 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Arquivo PNG Transparente</label>
                <div class="border-2 border-dashed border-pink-300 rounded-2xl p-4 text-center hover:bg-pink-50/50 transition-colors relative cursor-pointer">
                    <input type="file" name="sprite_file" id="upload-file-input" accept="image/png" required onchange="previewUploadSprite(this)" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                    <div id="upload-dropzone-prompt">
                        <div class="text-3xl text-pink-400 mb-1">📁</div>
                        <p class="text-xs font-bold text-pink-700">Clique para escolher o arquivo PNG</p>
                        <p class="text-[10px] text-gray-400">Recomendado formato PNG transparente (Dimensões base: 290x560)</p>
                    </div>
                    <div id="upload-preview-container" class="hidden mt-2">
                        <img id="upload-preview-img" src="#" alt="Preview" class="max-h-36 mx-auto object-contain border border-pink-200 rounded-xl bg-pink-50/50 p-2">
                        <p id="upload-filename" class="text-xs font-bold text-pink-600 mt-1"></p>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-pink-50 rounded-xl border border-pink-200 text-xs text-pink-800 leading-relaxed">
                💡 <b>Dica:</b> Para roupas e cabelos que encaixem perfeitamente no corpinho, envie uma imagem com fundo transparente na mesma proporção do bonequinho (290x560).
            </div>

            <button type="submit" id="upload-submit-btn" class="kawaii-btn kawaii-btn-pink w-full py-3 text-sm font-bold uppercase tracking-wider">
                Publicar no Guarda-Roupa ♡
            </button>
        </form>
    </div>
</div>

<!-- 5. MODAL SALVAR LOOK -->
<div id="modal-salvar-look" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/45 backdrop-blur-sm transition-all duration-200">
    <div class="kawaii-modal-card bg-white border-4 border-pink-300 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl transform scale-95 opacity-0 transition-all duration-200 relative text-center">
        <button onclick="Modal.close('modal-salvar-look')" class="absolute top-4 right-4 text-pink-400 hover:text-pink-600 text-2xl font-bold leading-none p-1">
            &times;
        </button>

        <div class="text-4xl mb-2 animate-heart">📸</div>
        <h3 class="font-kawaii text-2xl font-bold text-pink-700 mb-1">Salvar Seu Look</h3>
        <p class="text-xs text-gray-500 mb-4">Dê um nome para a sua criação e guarde na sua galeria!</p>

        <!-- Preview do Canvas -->
        <div class="w-44 h-64 mx-auto mb-4 bg-pink-50 rounded-2xl border-2 border-pink-200 overflow-hidden shadow-inner flex items-center justify-center p-2">
            <img id="salvar-look-preview-img" src="assets/img/mascote/mascote_chibi.png" alt="Look Preview" class="max-h-full max-w-full object-contain">
        </div>

        <div class="text-left mb-5">
            <label class="block text-xs font-bold text-pink-800 uppercase mb-1">Título do Look</label>
            <input type="text" id="salvar-look-titulo" value="Look Princesa Pastel ♡" class="w-full px-4 py-2.5 rounded-xl border-2 border-pink-200 focus:border-pink-500 focus:outline-none text-sm transition-colors">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <button onclick="baixarSnapshotLook()" class="kawaii-btn kawaii-btn-outline py-2.5 text-xs font-bold flex items-center justify-center gap-1.5">
                <span>⬇️</span> Baixar PNG
            </button>
            <button onclick="confirmarSalvarLook()" id="btn-confirmar-salvar" class="kawaii-btn kawaii-btn-pink py-2.5 text-xs font-bold flex items-center justify-center gap-1.5">
                <span>💖</span> Salvar na Galeria
            </button>
        </div>
    </div>
</div>
