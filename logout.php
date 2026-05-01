<?php

    session_start();

    unset($_SESSION['logado']);
    unset($_SESSION['usuario']);
    unset($_SESSION['transacoes']);

    session_destroy();

    header("Location: login.php");

