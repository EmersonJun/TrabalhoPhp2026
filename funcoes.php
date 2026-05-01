<?php
    function varValida($umaVar){
        return !is_null($umaVar) && !empty($umaVar);
    }

    function formatarMoeda($valor){
        return number_format($valor, 2, ',', '.');
    }

    function calcularReceitas($transacoes){
        $total = 0;
        foreach ($transacoes as $transacao) {
            if($transacao['tipo'] === 'Receita'){
                $total += $transacao['valor'];
            }
        }
        return $total;
    }

    function calcularDespesas($transacoes){
        $total = 0;
        foreach ($transacoes as $transacao) {
            if($transacao['tipo'] === 'Despesa'){
                $total += $transacao['valor'];
            }
        }
        return $total;
    }

    function calcularSaldo($transacoes){
        return calcularReceitas($transacoes) - calcularDespesas($transacoes);
    }

    function calcularPorcentagem($valor, $total){
        if($total <= 0){
            return 0;
        }
        return round(($valor / $total) * 100, 2);
    }

