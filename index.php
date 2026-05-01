<?php

    session_start();
    require "funcoes.php";

    $fezLogin = $_SESSION['logado'] ?? null;
    if(!$fezLogin){
        header("Location: login.php");
    }

    $usuario = $_SESSION['usuario'] ?? null;

    
    if(!isset($_SESSION['transacoes'])){
        $_SESSION['transacoes'] = [];
    }

    
    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $descricao = $_POST['descricao'] ?? null;
        $valor     = $_POST['valor']     ?? null;
        $tipo      = $_POST['tipo']      ?? null;

        if(varValida($descricao) && varValida($valor) && varValida($tipo)){

            if($valor > 0){
                $_SESSION['transacoes'][] = [
                    "id"        => uniqid(),
                    "descricao" => ucfirst($descricao),
                    "valor"     => (float) $valor,
                    "tipo"      => $tipo,
                    "data"      => date("d/m/Y H:i"),
                ];
                $sucesso = "Transação adicionada com sucesso!";
            }else{
                $erro = "O valor deve ser maior que zero.";
            }

        }else{
            $erro = "Preencha todos os campos.";
        }
    }

    
    $transacoes    = $_SESSION['transacoes'];
    $totalReceitas = calcularReceitas($transacoes);
    $totalDespesas = calcularDespesas($transacoes);
    $saldo         = calcularSaldo($transacoes);

?>
<?php require_once "includes/header.php"; ?>
<?php require_once "includes/nav.php"; ?>

<div class="main-content">

    <?php if(isset($erro)): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>

    <?php if(isset($sucesso)): ?>
        <div class="alert alert-success"><?= $sucesso ?></div>
    <?php endif; ?>

    <div class="summary-grid">

        <div class="summary-card receitas">
            <div class="summary-label">Total Receitas</div>
            <div class="summary-value">R$ <?= formatarMoeda($totalReceitas) ?></div>
        </div>

        <div class="summary-card despesas">
            <div class="summary-label">Total Despesas</div>
            <div class="summary-value">R$ <?= formatarMoeda($totalDespesas) ?></div>
        </div>

        <div class="summary-card saldo">
            <div class="summary-label">Saldo Disponível</div>
            <div class="summary-value">R$ <?= formatarMoeda($saldo) ?></div>
        </div>

    </div>

    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-header">Nova Transação</div>
        <div class="card-body">
            <form action="" method="post">
                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">Descrição</label>
                        <input type="text" name="descricao" class="form-input"
                               placeholder="Ex: Salário, Aluguel..." maxlength="100">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Valor</label>
                        <input type="number" name="valor" class="form-input"
                               placeholder="0,00" min="0.01" step="0.01">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="Receita">Receita</option>
                            <option value="Despesa">Despesa</option>
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

    <div class="view-history-wrap">
        <a href="historico.php" class="btn btn-outline">
            Ver Detalhes do Histórico
        </a>
    </div>

    <?php if(!empty($transacoes)): ?>
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
                            $ultimas = array_slice(array_reverse($transacoes), 0, 5);
                            foreach ($ultimas as $t):
                        ?>
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
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php require_once "includes/footer.php"; ?>
