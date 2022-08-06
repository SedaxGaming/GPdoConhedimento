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
			titulo,
			texto,
			descricao,
			alternativa1,
			alternativa2,
			alternativa3,
			alternativa4,
			alternativa5,
			correta
		from
			questao
		where 
			idquestao = $id";
			
	$result = mysqli_query($conexao, $sql);
	$dados = mysqli_fetch_array($result);

	$response['titulo'] = $dados['titulo'];
	$response['descricao'] = $dados['descricao'];
	$response['texto'] = html_entity_decode($dados['texto']);
	$response['alternativa1'] = html_entity_decode($dados['alternativa1']);
	$response['alternativa2'] = html_entity_decode($dados['alternativa2']);
	$response['alternativa3'] = html_entity_decode($dados['alternativa3']);
	$response['alternativa4'] = html_entity_decode($dados['alternativa4']);
	$response['alternativa5'] = html_entity_decode($dados['alternativa5']);
	$response['correta'] = $dados['correta'];

}
else {
	$response = array("success" => false);
}

echo json_encode($response);

ob_flush();

?>
