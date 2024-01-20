<?php

$senha = $_GET['senha'];
$senha = password_hash($senha, PASSWORD_DEFAULT);
echo $senha;

?>