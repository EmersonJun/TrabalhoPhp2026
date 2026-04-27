<?php
/**
 * index.php
 * Dashboard principal do sistema MyWallet
 * Exibe saldo, receitas, despesas e formulário para nova transação
 */

require_once 'sessao.php';
require_once 'funcoes.php';

// Protege a página — redireciona se não estiver logado
verificarAutenticacao();

$erro    = '';
$sucesso = '';

// ── Processar nova transação via POST ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {

    $descricao = trim($_POST['descricao'] ?? '');
    $valor     = str_replace(',', '.', trim($_POST['valor'] ?? ''));
    $tipo      = $_POST['tipo'] ?? '';

    // Validações
    if (empty($descricao)) {
        $erro = 'A descrição da transação é obrigatória.';
    } elseif (!is_numeric($valor) || (float)$valor <= 0) {
        $erro = 'Informe um valor numérico maior que zero.';
    } elseif (!in_array($tipo, ['Receita', 'Despesa'], true)) {
        $erro = 'Selecione um tipo válido: Receita ou Despesa.';
    } else {
        adicionarTransacao($descricao, (float)$valor, $tipo);
        $sucesso = 'Transação "' . sanitizarTexto($descricao) . '" adicionada com sucesso!';
    }
}

// ── Calcular totais ──────────────────────────────────────────────────────────
$transacoes    = $_SESSION['transacoes'];
$totalReceitas = calcularReceitas($transacoes);
$totalDespesas = calcularDespesas($transacoes);
$saldo         = calcularSaldo($transacoes);

$pageTitle = 'Dashboard';
require_once 'includes/header.php';
require_once 'includes/nav.php';
?>

<div class="main-content">

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <?php if ($sucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <!-- ── Cards de resumo ── -->
    <div class="summary-grid">

        <div class="summary-card receitas">
            <div class="summary-label">Total Receitas</div>
            <div class="summary-value"><?= formatarMoeda($totalReceitas) ?></div>
        </div>

        <div class="summary-card despesas">
            <div class="summary-label">Total Despesas</div>
            <div class="summary-value"><?= formatarMoeda($totalDespesas) ?></div>
        </div>

        <div class="summary-card saldo">
            <div class="summary-label">Saldo Disponível</div>
            <div class="summary-value"><?= formatarMoeda($saldo) ?></div>
        </div>

    </div>

    <!-- ── Formulário Nova Transação ── -->
    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-header">Nova Transação</div>
        <div class="card-body">
            <form method="POST" action="index.php">
                <input type="hidden" name="acao" value="adicionar">
                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label" for="descricao">Descrição</label>
                        <input type="text" id="descricao" name="descricao" class="form-input"
                               placeholder="Ex: Salário, Aluguel..."
                               value="<?= htmlspecialchars($_POST['descricao'] ?? '', ENT_QUOTES) ?>"
                               maxlength="100" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="valor">Valor</label>
                        <input type="number" id="valor" name="valor" class="form-input"
                               placeholder="0,00" min="0.01" step="0.01"
                               value="<?= htmlspecialchars($_POST['valor'] ?? '', ENT_QUOTES) ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" class="form-select">
                            <option value="Receita" <?= (($_POST['tipo'] ?? '') === 'Receita') ? 'selected' : '' ?>>Receita</option>
                            <option value="Despesa" <?= (($_POST['tipo'] ?? '') === 'Despesa') ? 'selected' : '' ?>>Despesa</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn-add">Adicionar</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- ── Botão Ver Histórico ── -->
    <div class="view-history-wrap">
        <a href="historico.php" class="btn btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Ver Detalhes do Histórico
        </a>
    </div>

    <!-- ── Últimas transações (prévia) ── -->
    <?php if (!empty($transacoes)): ?>
        <div class="card" style="margin-top:1.5rem;">
            <div class="card-header">Últimas Transações</div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th style="text-align:right;">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Mostra as últimas 5 transações em ordem inversa
                        $preview = array_slice(array_reverse($transacoes), 0, 5);
                        foreach ($preview as $t):
                            $isReceita = $t['tipo'] === 'Receita';
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
                                <?= ($isReceita ? '+' : '-') . ' ' . formatarMoeda($t['valor']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>
