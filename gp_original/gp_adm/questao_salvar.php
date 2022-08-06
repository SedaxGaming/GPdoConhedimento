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
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$texto = $_POST['texto'];
$alternativa1 = $_POST['alternativa1'];
$alternativa2 = $_POST['alternativa2'];
$alternativa3 = $_POST['alternativa3'];
$alternativa4 = $_POST['alternativa4'];
$alternativa5 = $_POST['alternativa5'];
$correta = $_POST['correta'];
$idetapa = $_POST['idetapa'];

$data = date("Y-m-d H:i:s");
$usuario = $_SESSION["login"];
$sql = "select idusuario from usuario where login like '$usuario' and data_exclusao is null";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);
$idusuario = $dados['idusuario'];


if ((!isset($_POST['id'])) || ($_POST['id'] == '')) {
    $id = null;
	
    $sql = "insert into questao (
				titulo,
				descricao,
				texto,
				alternativa1,
				alternativa2,
				alternativa3,
				alternativa4,
				alternativa5,
				correta,
				ordem,
				etapa_idetapa,
				data_alteracao,
				usuario_alteracao) 
			values (
				'$titulo',
				'$descricao',
				'$texto',
				'$alternativa1',
				'$alternativa2',
				'$alternativa3',
				'$alternativa4',
				'$alternativa5',
				$correta,
				(select
						ifnull(max(q.ordem),0) + 1
					from
						questao q
					where
						q.data_exclusao is null
					and
						q.etapa_idetapa = $idetapa),
				$idetapa,
				'$data',
				$idusuario)";

    if (mysqli_query($conexao, $sql)) {
		$response = $sql;//array("success" => true);
	}
    else
		$response = $sql;//array("success" => false);
}
else
{
    $id = $_POST['id'];
    
    $sql = "update questao set 
				titulo = '$titulo',
				descricao = '$descricao', 
				texto = '$texto',
				alternativa1 = '$alternativa1',
				alternativa2 = '$alternativa2',
				alternativa3 = '$alternativa3',
				alternativa4 = '$alternativa4',
				alternativa5 = '$alternativa5',
				correta = $correta,
				data_alteracao = '$data', 
				usuario_alteracao = $idusuario
			where 
				idquestao = $id";

    if (mysqli_query($conexao, $sql)) {
		$response = array("success" => true);
	}
	else
		$response = array("success" => false);
}

echo json_encode($response);

ob_flush();

?>
