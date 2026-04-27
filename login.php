<?php
/**
 * login.php
 * Página de autenticação do sistema MyWallet
 * Utiliza password_hash() e password_verify() para segurança
 */

require_once 'sessao.php';

// Se já está logado, redireciona para o dashboard
if (!empty($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

/**
 * Credenciais fixas (hash gerado com password_hash)
 * Usuário : admin
 * Senha   : admin123
 */
$USUARIOS = [
    'admin' => password_hash('admin123', PASSWORD_BCRYPT),
    'user'  => password_hash('user123',  PASSWORD_BCRYPT),
];

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($login) || empty($senha)) {
        $erro = 'Preencha o usuário e a senha.';
    } elseif (
        isset($USUARIOS[$login]) &&
        password_verify($senha, $USUARIOS[$login])
    ) {
        // Autenticação bem-sucedida
        session_regenerate_id(true); // Previne session fixation
        $_SESSION['usuario'] = $login;
        header('Location: index.php');
        exit;
    } else {
        $erro = 'Usuário ou senha incorretos. Tente novamente.';
    }
}

$pageTitle = 'Login';
require_once 'includes/header.php';
?>

<div class="login-page">
    <div class="login-card">

        <div class="login-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <h1>MyWallet</h1>
            <p>Gestão Financeira Pessoal</p>
        </div>

        <div class="login-card-body">

            <?php if ($erro): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">

                <div class="form-group" style="margin-bottom:1.1rem;">
                    <label class="form-label" for="usuario">UTILIZADOR</label>
                    <div style="position:relative;">
                        <svg style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#94a3b8;"
                             xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input type="text" id="usuario" name="usuario" class="form-input"
                               style="padding-left:2.4rem;"
                               placeholder="admin"
                               value="<?= htmlspecialchars($_POST['usuario'] ?? '', ENT_QUOTES) ?>"
                               autocomplete="username" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label class="form-label" for="senha">PALAVRA-PASSE</label>
                    <div style="position:relative;">
                        <svg style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#94a3b8;"
                             xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" id="senha" name="senha" class="form-input"
                               style="padding-left:2.4rem;"
                               placeholder="••••••••"
                               autocomplete="current-password" required>
                    </div>
                </div>

                <button type="submit" class="btn-login">Entrar no Sistema</button>

            </form>
        </div>

        <div class="login-footer">
            <p>💡 <strong>Dica:</strong> admin / admin123 &nbsp;|&nbsp; user / user123</p>
            <p style="margin-top:.4rem;">PHP Academic Project &copy; <?= date('Y') ?></p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
