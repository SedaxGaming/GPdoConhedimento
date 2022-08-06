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
			descricao,
			observacao,
			date_format(inicio, '%d/%m/%Y %H:%i') as inicio,
			date_format(fim, '%d/%m/%Y %H:%i') as fim,
			ordem
		from
			etapa
		where 
			idetapa = $id";
			
	$result = mysqli_query($conexao, $sql);
	$dados = mysqli_fetch_array($result);

	$response['nome'] = $dados['nome'];
	$response['descricao'] = $dados['descricao'];
	$response['observacao'] = $dados['observacao'];
	$response['inicio'] = $dados['inicio'];
	$response['fim'] = $dados['fim'];
	$response['ordem'] = $dados['ordem'];

}
else {
	$response = array("success" => false);
}

echo json_encode($response);

ob_flush();

?>
