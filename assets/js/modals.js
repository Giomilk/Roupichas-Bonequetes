/**
 * Roupichas-Bonequetes
 * Sistema de Gerenciamento de Modais Customizados
 *
 * Requisito: "sempre trate as mensagens de caixa de mensagens nativas do navegador em um MODAL"
 */

const Modal = (function() {
    // Intercepta e substitui as caixas de diálogo nativas do navegador
    window.alert = function(message) {
        return Modal.alert(message);
    };

    window.confirm = function(message) {
        console.warn("Navegador tentou chamar window.confirm nativo. Use Modal.confirmAsync()");
        return Modal.confirm(message);
    };

    function open(modalId) {
        const el = document.getElementById(modalId);
        if (el) {
            el.classList.remove('hidden');
            el.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            
            // Foco e animação suave
            const card = el.querySelector('.kawaii-modal-card');
            if (card) {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }
        }
    }

    function close(modalId) {
        const el = document.getElementById(modalId);
        if (el) {
            const card = el.querySelector('.kawaii-modal-card');
            if (card) {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                el.classList.add('hidden');
                el.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }, 200);
        }
    }

    // Modal customizado para substituir alert()
    function customAlert(message, title = 'Aviso Fofinho ♡') {
        return new Promise((resolve) => {
            const modalEl = document.getElementById('modal-alert-dialog');
            if (!modalEl) {
                resolve();
                return;
            }

            document.getElementById('modal-alert-title').innerText = title;
            document.getElementById('modal-alert-message').innerText = message;

            const btnOk = document.getElementById('modal-alert-btn-ok');
            const handler = function() {
                close('modal-alert-dialog');
                btnOk.removeEventListener('click', handler);
                resolve(true);
            };
            btnOk.addEventListener('click', handler);

            open('modal-alert-dialog');
        });
    }

    // Modal customizado para substituir confirm()
    function customConfirm(message, title = 'Confirmação ♡') {
        return new Promise((resolve) => {
            const modalEl = document.getElementById('modal-confirm-dialog');
            if (!modalEl) {
                resolve(false);
                return;
            }

            document.getElementById('modal-confirm-title').innerText = title;
            document.getElementById('modal-confirm-message').innerText = message;

            const btnSim = document.getElementById('modal-confirm-btn-sim');
            const btnNao = document.getElementById('modal-confirm-btn-nao');

            const simHandler = function() {
                close('modal-confirm-dialog');
                cleanup();
                resolve(true);
            };

            const naoHandler = function() {
                close('modal-confirm-dialog');
                cleanup();
                resolve(false);
            };

            function cleanup() {
                btnSim.removeEventListener('click', simHandler);
                btnNao.removeEventListener('click', naoHandler);
            }

            btnSim.addEventListener('click', simHandler);
            btnNao.addEventListener('click', naoHandler);

            open('modal-confirm-dialog');
        });
    }

    return {
        open: open,
        close: close,
        alert: customAlert,
        confirm: customConfirm
    };
})();

// Expõe globalmente
window.Modal = Modal;
