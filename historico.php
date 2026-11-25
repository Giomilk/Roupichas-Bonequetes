<?php
/**
 * Roupichas-Bonequetes
 * Página de Auditoria e Histórico de Logs do Sistema
 *
 * Requisito CORE:
 * "O sistema deve ter uma página de históricos e manter sempre os logs de qualquer alteração
 * feita por qualquer usuário, para auditorias futuras."
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/logger.php';

// Log do próprio acesso à auditoria
registrarLog('ACESSO_AUDITORIA', 'Página de histórico e auditoria consultada.');

require_once __DIR__ . '/includes/header.php';

$pdo = getConexao();

// Estatísticas Rápidas de Auditoria
$totalLogs = $pdo->query("SELECT COUNT(*) FROM logs")->fetchColumn();
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalLooks = $pdo->query("SELECT COUNT(*) FROM looks")->fetchColumn();
$totalSprites = $pdo->query("SELECT COUNT(*) FROM itens WHERE is_padrao = 0")->fetchColumn();
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Cabeçalho da Página -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold mb-2">
                <span>🛡️</span> Módulo de Governança e Auditoria
            </div>
            <h1 class="font-kawaii text-3xl font-extrabold text-pink-700">
                Histórico & Logs de Alterações
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Registro cronológico e imutável de todas as ações de usuários para conformidade e segurança.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="carregarTabelaLogs(1)" class="kawaii-btn kawaii-btn-outline px-4 py-2.5 text-xs font-bold flex items-center gap-2">
                <span>🔄</span> Atualizar Logs
            </button>
            <button onclick="exportarLogsCsv()" class="kawaii-btn kawaii-btn-pink px-4 py-2.5 text-xs font-bold flex items-center gap-2">
                <span>📊</span> Exportar Relatório CSV
            </button>
        </div>
    </div>

    <!-- Cards de Métricas de Auditoria -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="kawaii-card p-4 bg-white border-2 border-pink-200">
            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total de Eventos</div>
            <div class="text-2xl font-black text-pink-600 mt-1" id="stat-total-logs"><?= number_format($totalLogs) ?></div>
            <div class="text-[10px] text-pink-400 mt-0.5">Logs registrados no BD</div>
        </div>
        <div class="kawaii-card p-4 bg-white border-2 border-pink-200">
            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Estilistas Cadastrados</div>
            <div class="text-2xl font-black text-purple-600 mt-1"><?= number_format($totalUsuarios) ?></div>
            <div class="text-[10px] text-purple-400 mt-0.5">Senhas com hash Bcrypt</div>
        </div>
        <div class="kawaii-card p-4 bg-white border-2 border-pink-200">
            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Figurinos Salvos</div>
            <div class="text-2xl font-black text-rose-600 mt-1"><?= number_format($totalLooks) ?></div>
            <div class="text-[10px] text-rose-400 mt-0.5">Looks salvos na galeria</div>
        </div>
        <div class="kawaii-card p-4 bg-white border-2 border-pink-200">
            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Sprites Enviadas</div>
            <div class="text-2xl font-black text-blue-600 mt-1"><?= number_format($totalSprites) ?></div>
            <div class="text-[10px] text-blue-400 mt-0.5">Uploads da comunidade</div>
        </div>
    </div>

    <!-- Barra de Filtros e Busca -->
    <div class="kawaii-card p-4 sm:p-5 bg-white border-2 border-pink-200 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <div class="sm:col-span-6 lg:col-span-7">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Buscar por Usuário, IP ou Detalhe</label>
                <div class="relative">
                    <input type="text" id="filtro-busca" placeholder="Digite para pesquisar..." oninput="debounceBusca()" class="w-full pl-9 pr-4 py-2 rounded-xl border border-pink-200 focus:border-pink-500 focus:outline-none text-xs">
                    <span class="absolute left-3 top-2.5 text-gray-400 text-xs">🔍</span>
                </div>
            </div>

            <div class="sm:col-span-4 lg:col-span-3">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Filtrar por Tipo de Ação</label>
                <select id="filtro-acao" onchange="carregarTabelaLogs(1)" class="w-full px-3 py-2 rounded-xl border border-pink-200 focus:border-pink-500 focus:outline-none text-xs bg-white">
                    <option value="">Todas as Ações</option>
                    <option value="LOGIN_SUCESSO">Login Sucesso</option>
                    <option value="LOGIN_FALHA">Login Falha</option>
                    <option value="CADASTRO_USUARIO">Cadastro de Usuário</option>
                    <option value="CRIAR_LOOK">Criação de Look</option>
                    <option value="EXCLUIR_LOOK">Exclusão de Look</option>
                    <option value="FAVORITAR_LOOK">Favoritar Look</option>
                    <option value="UPLOAD_SPRITE">Upload de Sprite PNG</option>
                    <option value="ACESSO_JOGO">Acesso ao Jogo</option>
                </select>
            </div>

            <div class="sm:col-span-2 lg:col-span-2 pt-4 sm:pt-0">
                <button onclick="limparFiltros()" class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition-colors">
                    Limpar
                </button>
            </div>

        </div>
    </div>

    <!-- Tabela Responsiva de Auditoria -->
    <div class="kawaii-card overflow-hidden bg-white border-2 border-pink-200 shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-pink-100/70 border-b-2 border-pink-200 text-pink-900 font-extrabold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Data & Hora</th>
                        <th class="py-3 px-4">Ação</th>
                        <th class="py-3 px-4">Usuário</th>
                        <th class="py-3 px-4">Detalhes do Evento</th>
                        <th class="py-3 px-4">IP</th>
                        <th class="py-3 px-4">Dispositivo</th>
                    </tr>
                </thead>
                <tbody id="tbody-logs" class="divide-y divide-pink-100 font-medium text-gray-700">
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400">
                            Carregando histórico de logs... ✨
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="p-4 bg-pink-50/50 border-t border-pink-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <div id="paginacao-info" class="text-gray-500 font-bold">
                Mostrando logs...
            </div>
            <div class="flex items-center gap-2" id="paginacao-controles">
                <!-- Botões de navegação -->
            </div>
        </div>
    </div>

</div>

<script>
    let paginaAtual = 1;
    let timeoutBusca = null;
    let logsCache = [];

    function debounceBusca() {
        clearTimeout(timeoutBusca);
        timeoutBusca = setTimeout(() => {
            carregarTabelaLogs(1);
        }, 350);
    }

    function limparFiltros() {
        document.getElementById('filtro-busca').value = '';
        document.getElementById('filtro-acao').value = '';
        carregarTabelaLogs(1);
    }

    function getBadgeAcao(acao) {
        const uAcao = (acao || '').toUpperCase();
        let bg = 'bg-gray-100 text-gray-700 border-gray-300';

        if (uAcao.includes('LOGIN_SUCESSO')) bg = 'bg-emerald-100 text-emerald-800 border-emerald-300';
        else if (uAcao.includes('LOGIN_FALHA')) bg = 'bg-rose-100 text-rose-800 border-rose-300';
        else if (uAcao.includes('CADASTRO')) bg = 'bg-purple-100 text-purple-800 border-purple-300';
        else if (uAcao.includes('CRIAR_LOOK')) bg = 'bg-pink-100 text-pink-800 border-pink-300';
        else if (uAcao.includes('EXCLUIR')) bg = 'bg-red-100 text-red-800 border-red-300';
        else if (uAcao.includes('UPLOAD')) bg = 'bg-blue-100 text-blue-800 border-blue-300';
        else if (uAcao.includes('FAVORITAR')) bg = 'bg-amber-100 text-amber-800 border-amber-300';

        return `<span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-black border ${bg}">${uAcao}</span>`;
    }

    function formatarData(dtStr) {
        if (!dtStr) return '-';
        const d = new Date(dtStr.replace(' ', 'T'));
        return d.toLocaleString('pt-BR', { dateStyle: 'short', timeStyle: 'medium' });
    }

    async function carregarTabelaLogs(pagina = 1) {
        paginaAtual = pagina;
        const tbody = document.getElementById('tbody-logs');
        const acao = document.getElementById('filtro-acao').value;
        const busca = document.getElementById('filtro-busca').value.trim();

        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="py-8 text-center text-pink-500 font-bold">
                    Carregando registros... ✨
                </td>
            </tr>
        `;

        try {
            const url = `api/logs.php?pagina=${pagina}&limite=20&acao=${encodeURIComponent(acao)}&busca=${encodeURIComponent(busca)}`;
            const res = await fetch(url);
            const data = await res.json();

            if (!data.sucesso || !data.logs || data.logs.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            Nenhum log encontrado para os critérios selecionados.
                        </td>
                    </tr>
                `;
                document.getElementById('paginacao-info').innerText = '0 registros encontrados';
                document.getElementById('paginacao-controles').innerHTML = '';
                return;
            }

            logsCache = data.logs;
            tbody.innerHTML = '';

            data.logs.forEach(log => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-pink-50/60 transition-colors';
                tr.innerHTML = `
                    <td class="py-3 px-4 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                        ${formatarData(log.data_hora)}
                    </td>
                    <td class="py-3 px-4 whitespace-nowrap">
                        ${getBadgeAcao(log.acao)}
                    </td>
                    <td class="py-3 px-4 font-bold text-pink-900 whitespace-nowrap">
                        ${log.usuario_nome || 'Visitante'} 
                        <span class="text-[10px] font-normal text-gray-400">#${log.usuario_id || 'anon'}</span>
                    </td>
                    <td class="py-3 px-4 max-w-xs md:max-w-md break-words text-gray-600">
                        ${log.detalhes || '-'}
                    </td>
                    <td class="py-3 px-4 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                        ${log.ip || '127.0.0.1'}
                    </td>
                    <td class="py-3 px-4 max-w-[120px] truncate text-[11px] text-gray-400" title="${log.user_agent || ''}">
                        ${log.user_agent ? log.user_agent.substring(0, 30) + '...' : '-'}
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Atualiza Paginação
            const pagInfo = document.getElementById('paginacao-info');
            pagInfo.innerText = `Página ${data.pagina_atual} de ${data.total_paginas} (Total de ${data.total} registros)`;

            const cont = document.getElementById('paginacao-controles');
            cont.innerHTML = `
                <button onclick="carregarTabelaLogs(${Math.max(1, data.pagina_atual - 1)})" ${data.pagina_atual <= 1 ? 'disabled' : ''} class="px-3 py-1 bg-white border border-pink-200 rounded-lg text-pink-700 font-bold disabled:opacity-40">
                    Anterior
                </button>
                <button onclick="carregarTabelaLogs(${Math.min(data.total_paginas, data.pagina_atual + 1)})" ${data.pagina_atual >= data.total_paginas ? 'disabled' : ''} class="px-3 py-1 bg-white border border-pink-200 rounded-lg text-pink-700 font-bold disabled:opacity-40">
                    Próxima
                </button>
            `;

        } catch (err) {
            console.error(err);
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="py-8 text-center text-rose-600 font-bold">
                        Falha ao carregar auditoria. Verifique a conexão com a base de dados.
                    </td>
                </tr>
            `;
        }
    }

    function exportarLogsCsv() {
        if (!logsCache || logsCache.length === 0) {
            Toast.warning("Nenhum log carregado para exportar.");
            return;
        }

        let csv = "ID;DataHora;Acao;Usuario;IP;Detalhes\n";
        logsCache.forEach(l => {
            const linha = [
                l.id,
                `"${l.data_hora}"`,
                `"${l.acao}"`,
                `"${l.usuario_nome}"`,
                `"${l.ip}"`,
                `"${(l.detalhes || '').replace(/"/g, '""')}"`
            ];
            csv += linha.join(';') + "\n";
        });

        const blob = new Blob(["\ufeff" + csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `auditoria_bonequetes_${Date.now()}.csv`;
        link.click();
        Toast.success("Relatório de auditoria exportado em CSV! 📊");
    }

    document.addEventListener('DOMContentLoaded', () => carregarTabelaLogs(1));
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
