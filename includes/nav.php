<?php
$usuario = $_SESSION['usuario'] ?? null;
?>
<nav class="navbar">
    <div class="nav-brand">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
        </svg>
        <span>MyWallet</span>
    </div>
    <div class="nav-links">
        <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
            Dashboard
        </a>
        <a href="historico.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'historico.php' ? 'active' : '' ?>">
            Histórico
        </a>
    </div>
    <div class="nav-user">
        <span>Olá, <?= $usuario ?></span>
        <a href="logout.php" class="btn-logout">Sair</a>
    </div>
</nav>