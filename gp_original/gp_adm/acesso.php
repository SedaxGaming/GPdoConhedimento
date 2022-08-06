<?php

session_start();
$login = strtolower($_SESSION["login"]);

require_once ('conexao.php');
require_once ('limpa_injection.php');

$sql = "select grupo from usuario where idusuario = (select idusuario from usuario where login like '$login' and data_exclusao is null)";

$resultado = mysqli_query($conexao, $sql);

$dados = mysqli_fetch_array ( $resultado );
$_acesso = $dados['grupo'];

?>
