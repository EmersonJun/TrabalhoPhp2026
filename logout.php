<?php
/**
 * logout.php
 * Encerra a sessão do usuário e redireciona para o login
 */

require_once 'sessao.php';

encerrarSessao();

header('Location: login.php');
exit;
