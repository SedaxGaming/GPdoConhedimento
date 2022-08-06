<?php
ini_set('display_errors', '0');

ob_start();                                                                                                                                                  
session_start();                                                                                                                                             
if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

if ((!isset($_POST['id'])) || ($_POST['id'] == '')) 
    header("Location: prova.php?erro=1");

require_once ('conexao.php');
require_once ('limpa_injection.php');
require_once ('acesso.php');

if ($_acesso != '1')
    header("Location: index.php");

$id = $_POST['id'];
$hoje = date("Y-m-d H:i:s");
$usuario = $_SESSION["login"];

$sql = "select idusuario from usuario where login like '$usuario' and data_exclusao is null";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);
$idusuario = $dados['idusuario'];

$sql = "update etapa set 
			data_exclusao = '$hoje', 
			usuario_exclusao = $idusuario
		where 
			idetapa = $id;
		update questao set
			data_exclusao = '$hoje', 
			usuario_exclusao = $idusuario
		where 
			etapa_idetapa = $id";
			
if (mysqli_multi_query($conexao, $sql)) {
	$response = array("success" => true);
}
else {
	$response = array("success" => false);
}

echo json_encode($response);

ob_flush();

?>
