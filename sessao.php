<?php
/**
 * sessao.php
 * Gerenciamento central de sessão e dados do sistema
 */

session_start();

// Inicializa o array de transações na sessão se ainda não existir
if (!isset($_SESSION['transacoes'])) {
    $_SESSION['transacoes'] = [];
}

// Inicializa o usuário da sessão se ainda não existir
if (!isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = null;
}

/**
 * Verifica se o usuário está autenticado.
 * Se não estiver, redireciona para a página de login.
 */
function verificarAutenticacao(): void {
    if (empty($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Adiciona uma nova transação ao array da sessão
 */
function adicionarTransacao(string $descricao, float $valor, string $tipo): void {
    require_once __DIR__ . '/funcoes.php';
    $_SESSION['transacoes'][] = [
        'id'        => gerarId(),
        'descricao' => sanitizarTexto($descricao),
        'valor'     => $valor,
        'tipo'      => $tipo,
        'data'      => date('d/m/Y H:i'),
    ];
}

/**
 * Remove uma transação pelo ID
 */
function removerTransacao(string $id): void {
    $_SESSION['transacoes'] = array_filter(
        $_SESSION['transacoes'],
        fn($t) => $t['id'] !== $id
    );
    // Re-indexa o array
    $_SESSION['transacoes'] = array_values($_SESSION['transacoes']);
}

/**
 * Limpa todo o histórico de transações da sessão
 */
function limparHistorico(): void {
    $_SESSION['transacoes'] = [];
}

/**
 * Encerra a sessão do usuário completamente
 */
function encerrarSessao(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']
        );
    }
    session_destroy();
}
