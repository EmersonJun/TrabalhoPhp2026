<?php

    session_start();
    require "funcoes.php";

    $fezLogin = $_SESSION['logado'] ?? null;
    if(!$fezLogin){
        header("Location: login.php");
    }

    if(!isset($_SESSION['transacoes'])){
        $_SESSION['transacoes'] = [];
    }
    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $acao = $_POST['acao'] ?? null;

        if($acao === "remover"){
            $id = $_POST['id'] ?? null;
            if(!is_null($id)){
                $nova = [];
                foreach ($_SESSION['transacoes'] as $t) {
                    if($t['id'] !== $id){
                        $nova[] = $t;
                    }
                }
                $_SESSION['transacoes'] = $nova;
                $sucesso = "Transação removida.";
            }
        }

        if($acao === "zerar"){
            $_SESSION['transacoes'] = [];
            $sucesso = "Histórico zerado com sucesso.";
        }
    }

    $transacoes    = $_SESSION['transacoes'];
    $totalReceitas = calcularReceitas($transacoes);
    $totalDespesas = calcularDespesas($transacoes);
    $saldo         = calcularSaldo($transacoes);

    $lista = array_reverse($transacoes);

?>
<?php require_once "includes/header.php"; ?>
<?php require_once "includes/nav.php"; ?>

<div class="main-content">

    <?php if(isset($sucesso)): ?>
        <div class="alert alert-success"><?= $sucesso ?></div>
    <?php endif; ?>

    <div class="card">

        <div class="history-header">
            <h2>Histórico de Movimentações</h2>
            <div class="history-actions">
                <a href="index.php" class="btn btn-outline">&#8592; Voltar</a>

                <?php if(!empty($transacoes)): ?>
                <form action="" method="post" onsubmit="return confirm('Zerar todo o histórico?')">
                    <input type="hidden" name="acao" value="zerar">
                    <button type="submit" class="btn btn-danger">&#128465; Zerar</button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if(empty($lista)): ?>

            <div class="empty-state">
                <p>Nenhuma transação registrada ainda.</p>
                <p><a href="index.php" style="color:var(--blue);">Adicionar primeira transação →</a></p>
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
                            <th style="text-align:right;">% Despesas</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $t): ?>
                        <tr>
                            <td class="td-date"><?= $t['data'] ?></td>
                            <td class="td-desc"><?= $t['descricao'] ?></td>
                            <td>
                                <?php if($t['tipo'] === 'Receita'): ?>
                                    <span class="badge badge-receita">Receita</span>
                                <?php else: ?>
                                    <span class="badge badge-despesa">Despesa</span>
                                <?php endif; ?>
                            </td>
                            <td class="td-value <?= $t['tipo'] === 'Receita' ? 'positivo' : 'negativo' ?>">
                                <?= $t['tipo'] === 'Receita' ? '+' : '-' ?> R$ <?= formatarMoeda($t['valor']) ?>
                            </td>
                            <td class="td-pct">
                                <?php if($t['tipo'] === 'Despesa'): ?>
                                    <?= calcularPorcentagem($t['valor'], $totalDespesas) ?>%
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;">
                                <form action="" method="post"
                                      onsubmit="return confirm('Remover esta transação?')">
                                    <input type="hidden" name="acao" value="remover">
                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                    <button type="submit" class="btn-remove" title="Remover">&#10006;</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="display:flex; gap:2rem; padding:1rem 1.5rem;
                        border-top:1.5px solid var(--border); background:#f8fafc;
                        font-size:.875rem; flex-wrap:wrap;">
                <span><strong><?= count($transacoes) ?></strong> transação(ões)</span>
                <span style="color:var(--green); font-weight:700;">
                    Receitas: + R$ <?= formatarMoeda($totalReceitas) ?>
                </span>
                <span style="color:var(--red); font-weight:700;">
                    Despesas: - R$ <?= formatarMoeda($totalDespesas) ?>
                </span>
                <span style="color:var(--blue); font-weight:700; margin-left:auto;">
                    Saldo: R$ <?= formatarMoeda($saldo) ?>
                </span>
            </div>

        <?php endif; ?>
    </div>

</div>

<?php require_once "includes/footer.php"; ?>
