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
$s = $_POST['s'];
$ordem = $_POST['ordem'];
$idprova = $_POST['idprova'];

if ($ordem == "") {
	$sql = "select ordem, prova_idprova from etapa where idetapa = $id";
	$result = mysqli_query($conexao, $sql);
	$dados = mysqli_fetch_array($result);
	$ordem = $dados['ordem'];
	$idprova = $dados['prova_idprova'];
	$s = 'd';
}

$ord = $s == 's'?'desc':'asc';
$sin = $s == 's'?'<=':'>=';
$l = $_POST['l']==""?"":"limit " . $_POST['l'];

$data = date("Y-m-d H:i:s");
$usuario = $_SESSION["login"];
$sql = "select idusuario from usuario where login like '$usuario' and data_exclusao is null";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);
$idusuario = $dados['idusuario'];

$x = 0;
$sql = "select 
		idetapa,
		ordem
	from
		etapa
	where
		prova_idprova = $idprova
	and
		ordem $sin $ordem
	order by
		ordem $ord
	$l";

$result = mysqli_query($conexao, $sql);
$qtd = mysqli_num_rows($result);

while ($dados = mysqli_fetch_array($result)) {
	$x++;
	$idetapa = $dados['idetapa'];
	$ordem = $dados['ordem'];

	if ($s == 's') {
		if ($x == 1) {
			$nordem = $ordem - 1;
		}
		else {
			$nordem = $ordem + 1;
		}
	}
	else {
		if ($x == 1) {
			$nordem = $ordem + 1;
		}
		else {
			$nordem = $ordem - 1;
		}
	}
	
    $sql = "update etapa set 
				ordem = $nordem,
				data_alteracao = '$data', 
				usuario_alteracao = $idusuario
			where 
				idetapa = $idetapa";
	
	if ($qtd > 1) {
		mysqli_query($conexao, $sql);
	}
}

$response = array("success" => true);

echo json_encode($response);

ob_flush();

?>
