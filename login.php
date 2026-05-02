<?php

    session_start();

    $fezLogin = $_SESSION['logado'] ?? null;
    if($fezLogin){
        header("Location: index.php");
    }

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $usuario = $_POST['usuario'] ?? null;
        $senha   = $_POST['senha']   ?? null;

        if(!is_null($usuario) && !is_null($senha)){
            $senhaHash = password_hash("123", PASSWORD_DEFAULT);
            if($usuario == "admin" && password_verify($senha, $senhaHash)){
                $_SESSION['logado']  = true;
                $_SESSION['usuario'] = $usuario;
                header("Location: index.php");
            }else{
                $erro = "Usuário ou senha incorretos.";
            }
        }else{
            $erro = "Preencha todos os campos.";
        }
    }

?>
<?php require_once "includes/header.php"; ?>

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

            <?php if(isset($erro)): ?>
                <div class="alert alert-danger"><?= $erro ?></div>
            <?php endif; ?>

            <form action="" method="post">

                <div class="form-group" style="margin-bottom:1.1rem;">
                    <label class="form-label">UTILIZADOR</label>
                    <div style="position:relative;">
                        <svg style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#94a3b8;"
                             xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input type="text" name="usuario" class="form-input"
                               style="padding-left:2.4rem;" placeholder="admin">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label class="form-label">PALAVRA-PASSE</label>
                    <div style="position:relative;">
                        <svg style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#94a3b8;"
                             xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" name="senha" class="form-input"
                               style="padding-left:2.4rem;" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn-login">Entrar no Sistema</button>

            </form>
        </div>

        <div class="login-footer">
            <p>Usuário: <strong>admin</strong> &nbsp;|&nbsp; Senha: <strong>123</strong></p>
        </div>

    </div>
</div>

<?php require_once "includes/footer.php"; ?>
