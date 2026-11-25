/**
 * Roupichas-Bonequetes
 * Sistema de Notificações Toast
 *
 * Requisito: "Retorne sempre as mensagens de erros de forma claras na interface para o usuário (TOAST)"
 */

const Toast = (function() {
    let container = null;

    function getContainer() {
        if (!container) {
            container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'fixed top-5 right-5 left-5 sm:left-auto sm:w-96 z-50 flex flex-col gap-3 pointer-events-none';
                document.body.appendChild(container);
            }
        }
        return container;
    }

    function show(message, type = 'info', duration = 4500) {
        const c = getContainer();

        const toast = document.createElement('div');
        toast.className = 'pointer-events-auto transform transition-all duration-300 ease-out translate-y-[-20px] opacity-0 flex items-center gap-3 p-4 rounded-2xl shadow-xl border-2';

        let icon = '🎀';
        let bg = 'bg-white';
        let border = 'border-pink-300';
        let textColor = 'text-pink-900';

        switch (type) {
            case 'error':
                icon = '💔';
                bg = 'bg-rose-50';
                border = 'border-rose-400';
                textColor = 'text-rose-900';
                break;
            case 'success':
                icon = '💖';
                bg = 'bg-pink-50';
                border = 'border-pink-400';
                textColor = 'text-pink-900';
                break;
            case 'warning':
                icon = '⚠️';
                bg = 'bg-amber-50';
                border = 'border-amber-400';
                textColor = 'text-amber-900';
                break;
            case 'info':
            default:
                icon = '✨';
                bg = 'bg-fuchsia-50';
                border = 'border-fuchsia-300';
                textColor = 'text-fuchsia-900';
                break;
        }

        toast.className += ` ${bg} ${border} ${textColor}`;

        toast.innerHTML = `
            <div class="text-2xl flex-shrink-0 animate-bounce">${icon}</div>
            <div class="flex-1 text-sm font-bold leading-snug">${message}</div>
            <button type="button" class="text-pink-400 hover:text-pink-700 text-lg leading-none p-1 rounded-full transition-colors" aria-label="Fechar">
                &times;
            </button>
        `;

        const closeBtn = toast.querySelector('button');
        closeBtn.onclick = () => dismiss(toast);

        c.appendChild(toast);

        // Animação de entrada
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-[-20px]', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        });

        // Timer de auto-fechamento
        if (duration > 0) {
            setTimeout(() => {
                dismiss(toast);
            }, duration);
        }

        return toast;
    }

    function dismiss(toast) {
        if (!toast || !toast.parentNode) return;
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-[-10px]', 'opacity-0', 'scale-95');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    return {
        show: show,
        success: (msg, dur) => show(msg, 'success', dur),
        error: (msg, dur) => show(msg, 'error', dur || 5000),
        info: (msg, dur) => show(msg, 'info', dur),
        warning: (msg, dur) => show(msg, 'warning', dur)
    };
})();

// Expõe globalmente
window.showToast = Toast.show;
window.Toast = Toast;
