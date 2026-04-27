<?php
/**
 * funcoes.php
 * Funções auxiliares reutilizáveis para o sistema MyWallet
 */

/**
 * Formata um valor numérico para o padrão monetário brasileiro (R$)
 */
function formatarMoeda(float $valor): string {
    return 'R$ ' . number_format(abs($valor), 2, ',', '.');
}

/**
 * Calcula o total de receitas a partir do array de transações
 */
function calcularReceitas(array $transacoes): float {
    $total = 0.0;
    foreach ($transacoes as $t) {
        if ($t['tipo'] === 'Receita') {
            $total += (float) $t['valor'];
        }
    }
    return $total;
}

/**
 * Calcula o total de despesas a partir do array de transações
 */
function calcularDespesas(array $transacoes): float {
    $total = 0.0;
    foreach ($transacoes as $t) {
        if ($t['tipo'] === 'Despesa') {
            $total += (float) $t['valor'];
        }
    }
    return $total;
}

/**
 * Calcula o saldo disponível (receitas - despesas)
 */
function calcularSaldo(array $transacoes): float {
    return calcularReceitas($transacoes) - calcularDespesas($transacoes);
}

/**
 * Calcula a relevância percentual de uma despesa em relação ao total de despesas
 * Retorna 0 se não houver despesas
 */
function calcularPorcentagemDespesa(float $valorDespesa, float $totalDespesas): float {
    if ($totalDespesas <= 0) return 0.0;
    return round(($valorDespesa / $totalDespesas) * 100, 2);
}

/**
 * Valida e sanitiza o nome/descrição de uma transação
 */
function sanitizarTexto(string $texto): string {
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

/**
 * Gera um ID único para cada transação
 */
function gerarId(): string {
    return uniqid('txn_', true);
}
