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
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$observacao = $_POST['observacao'];
$idprova = $_POST['idprova'];

$data = date("Y-m-d H:i:s");
$usuario = $_SESSION["login"];
$sql = "select idusuario from usuario where login like '$usuario' and data_exclusao is null";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);
$idusuario = $dados['idusuario'];

if ((!isset($_POST['id'])) || ($_POST['id'] == '')) {
    $id = null;
	
    $sql = "insert into etapa (
				nome,
				descricao,
				observacao,
				ordem,
				prova_idprova,
				data_alteracao,
				usuario_alteracao) 
			values (
				'$nome',
				'$descricao',
				'$observacao',
				(select
						ifnull(max(e.ordem),0) + 1
					from
						etapa e
					where
						e.data_exclusao is null
					and
						e.prova_idprova = $idprova),
				$idprova,
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
    
    $sql = "update etapa set 
				nome = '$nome',
				descricao = '$descricao', 
				observacao = '$observacao',
				data_alteracao = '$data', 
				usuario_alteracao = $idusuario
			where 
				idetapa = $id";

    if (mysqli_query($conexao, $sql)) {
		$response = array("success" => true);
	}
	else
		$response = array("success" => false);
}

echo json_encode($response);

ob_flush();

?>
