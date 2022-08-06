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

$id = $_POST['id'];

if ($id == '')
    header("Location: index.php");

$data = date("Y-m-d H:i:s");
$usuario = $_SESSION["login"];
$sql = "select idusuario from usuario where login like '$usuario' and data_exclusao is null";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);
$idusuario = $dados['idusuario'];

$sql = "update etapa set 
			status = 0,
			data_alteracao = '$data', 
			usuario_alteracao = $idusuario;
		update etapa set 
			status = 1,
			data_alteracao = '$data', 
			usuario_alteracao = $idusuario
		where 
			idetapa = $id;";
			
if (mysqli_multi_query($conexao, $sql)) {
	$response = array("success" => true);
}
else
	$response = array("success" => false);

echo json_encode($response);

ob_flush();

?>
