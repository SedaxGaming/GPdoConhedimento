<?php
require_once ('conexao.php');    
require_once ('limpa_injection.php');
$login = strtolower($_POST['login']);
$senha = md5($_POST['senha']);

$sql = "select idusuario, grupo from usuario where login like '$login' and password like '$senha' and data_exclusao is null";

$result = mysqli_query($conexao, $sql);

if ( $dados = mysqli_fetch_array ( $result ) ) {
    if ($dados['idusuario'] != '')
        $ok = 1;
}

ob_start();

if ($ok == '1') {
    session_start();
    
    $grupo = $dados['grupo'];
    
    if ($grupo = 1000) {
		$_SESSION['login'] = $login;
		header("Location: gp.php");
	}
	else {
		header("Location: index.php?erro=1");
	}
}
else {
   	header("Location: index.php?erro=1");
}

ob_end_flush();

?>
