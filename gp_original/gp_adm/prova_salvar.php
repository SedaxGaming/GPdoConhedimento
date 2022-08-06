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
$unidade = $_POST['unidade'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$observacao = $_POST['observacao'];

$data = date("Y-m-d H:i:s");
$usuario = $_SESSION["login"];
$sql = "select idusuario from usuario where login like '$usuario' and data_exclusao is null";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);
$idusuario = $dados['idusuario'];

if ((!isset($_POST['id'])) || ($_POST['id'] == '')) {
    $id = null;
	
    $sql = "insert into prova (
				unidade,
				nome,
				descricao,
				observacao,
				data_alteracao,
				usuario_alteracao) 
			values (
				'$unidade',
				'$nome',
				'$descricao',
				'$observacao',
				'$data',
				$idusuario)";

    if (mysqli_query($conexao, $sql)) {
		$response = array("success" => true);
	}
    else
		$response = array("success" => false);
}
else
{
    $id = $_POST['id'];
    
    $sql = "update prova set 
				unidade = '$unidade',
				nome = '$nome',
				descricao = '$descricao', 
				observacao = '$observacao',
				data_alteracao = '$data', 
				usuario_alteracao = $idusuario
			where 
				idprova = $id";

    if (mysqli_query($conexao, $sql)) {
		$response = array("success" => true);
	}
	else
		$response = array("success" => false);
}

echo json_encode($response);

ob_flush();

?>
