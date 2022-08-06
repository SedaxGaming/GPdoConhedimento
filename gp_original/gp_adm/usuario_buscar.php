<?php
	ini_set('display_errors', '0');

ob_start();                                                                                                                                                  
session_start();

if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

require_once ('conexao.php');
require_once ('limpa_injection.php');
require_once ('acesso.php');

if ($_acesso != '1')
    header("Location: index.php");

if ((isset($_POST['id'])) && ($_POST['id'] != '')) {
	$id = $_POST['id'];

	$sql = "select 
			nome,
			login,
			grupo
		from
			usuario
		where 
			idusuario = $id";
			
	$result = mysqli_query($conexao, $sql);
	$dados = mysqli_fetch_array($result);

	$response['nome'] = $dados['nome'];
	$response['login'] = $dados['login'];
	$response['grupo'] = $dados['grupo'];

}
else {
	$response = array("success" => false);
}

echo json_encode($response);

ob_flush();

?>
