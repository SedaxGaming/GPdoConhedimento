<?php


ob_start();

session_start();

if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

require_once ('conexao.php');
require_once ('limpa_injection.php');

if (!isset($_POST['senhaatual']))
    header("Location: senha.php?erro=1");


$login = $_POST['login'];
$senha = md5($_POST['senhaatual']);
$novasenha = md5($_POST['novasenha']);

$sql = "select login from usuario where login = '$login' and senha = '$senha'";

$result = mysqli_query($conect, $sql);

$linha = mysqli_fetch_array($result);

if ($linha['login'] == $login){

    $sql = "update usuario set senha = '$novasenha' where login = '$login'";


    if (mysqli_query($conect, $sql))
		header("Location: senha.php?erro=2");
    else
		header("Location: senha.php?erro=3");
}
else
    header("Location: senha.php?erro=1");

ob_flush();

?>
