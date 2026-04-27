<?php
/**
 * historico.php
 * Histórico completo de transações com cálculo percentual de despesas
 * Somente acessível para usuários autenticados
 */

require_once 'sessao.php';
require_once 'funcoes.php';

// Protege a página
verificarAutenticacao();

$mensagem = '';

// ── Remover transação individual ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {

    switch ($_POST['acao']) {
        case 'remover':
            $id = $_POST['id'] ?? '';
            if ($id) {
                removerTransacao($id);
                $mensagem = 'Transação removida com sucesso.';
            }
            break;

        case 'zerar':
            limparHistorico();
            $mensagem = 'Histórico zerado com sucesso.';
            break;
    }
}

// ── Dados para exibição ──────────────────────────────────────────────────────
$transacoes    = $_SESSION['transacoes'];
$totalReceitas = calcularReceitas($transacoes);
$totalDespesas = calcularDespesas($transacoes);
$saldo         = calcularSaldo($transacoes);

// Ordenar do mais recente para o mais antigo
$transacoesOrdenadas = array_reverse($transacoes);

$pageTitle = 'Histórico';
require_once 'includes/header.php';
require_once 'includes/nav.php';
?>

<div class="main-content">

    <?php if ($mensagem): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <div class="card">

        <!-- Cabeçalho do histórico com botões de ação -->
        <div class="history-header">
            <h2>Histórico de Movimentações</h2>
            <div class="history-actions">
                <a href="index.php" class="btn btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Voltar
                </a>

                <?php if (!empty($transacoes)): ?>
                <form method="POST" action="historico.php"
                      onsubmit="return confirm('Tem certeza que deseja zerar todo o histórico? Esta ação não pode ser desfeita.');">
                    <input type="hidden" name="acao" value="zerar">
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Zerar Mês
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabela de transações -->
        <?php if (empty($transacoesOrdenadas)): ?>

            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p>Nenhuma transação registrada ainda.</p>
                <p style="margin-top:.5rem;"><a href="index.php" style="color:var(--blue);">Adicionar primeira transação →</a></p>
            </div>

        <?php else: ?>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th style="text-align:right;">Valor</th>
                            <th style="text-align:right;">% do Total Despesas</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transacoesOrdenadas as $t):
                            $isReceita = $t['tipo'] === 'Receita';
                            // Cálculo de relevância percentual (bônus)
                            $pct = (!$isReceita)
                                ? calcularPorcentagemDespesa((float)$t['valor'], $totalDespesas)
                                : null;
                        ?>
                        <tr>
                            <td class="td-date"><?= htmlspecialchars($t['data']) ?></td>
                            <td class="td-desc"><?= htmlspecialchars($t['descricao']) ?></td>
                            <td>
                                <span class="badge <?= $isReceita ? 'badge-receita' : 'badge-despesa' ?>">
                                    <?= htmlspecialchars($t['tipo']) ?>
                                </span>
                            </td>
                            <td class="td-value <?= $isReceita ? 'positivo' : 'negativo' ?>">
                                <?= ($isReceita ? '+ ' : '- ') . formatarMoeda($t['valor']) ?>
                            </td>
                            <td class="td-pct">
                                <?php if ($pct !== null): ?>
                                    <span title="Representa <?= $pct ?>% do total de despesas">
                                        <?= number_format($pct, 1, ',', '.') ?>%
                                    </span>
                                <?php else: ?>
                                    <span style="color:#d1d5db;">—</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;">
                                <form method="POST" action="historico.php"
                                      onsubmit="return confirm('Remover esta transação?');"
                                      style="display:inline;">
                                    <input type="hidden" name="acao" value="remover">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($t['id']) ?>">
                                    <button type="submit" class="btn-remove" title="Remover">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Rodapé da tabela com totais -->
            <div style="display:flex; gap:2rem; padding:1rem 1.5rem; border-top:1.5px solid var(--border);
                        background:#f8fafc; font-size:.875rem; flex-wrap:wrap;">
                <span>
                    <strong><?= count($transacoes) ?></strong> transação(ões) registrada(s)
                </span>
                <span style="color:var(--green); font-weight:700;">
                    Receitas: + <?= formatarMoeda($totalReceitas) ?>
                </span>
                <span style="color:var(--red); font-weight:700;">
                    Despesas: - <?= formatarMoeda($totalDespesas) ?>
                </span>
                <span style="color:var(--blue); font-weight:700; margin-left:auto;">
                    Saldo: <?= formatarMoeda($saldo) ?>
                </span>
            </div>

        <?php endif; ?>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
