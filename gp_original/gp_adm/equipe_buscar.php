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
			e.nome,
			e.escola,
			e.capitao,
			e.responsavel,
			u.login as usuario,
			e.unidade
		from
			equipe e
			left join usuario u on u.idusuario = e.usuario_idusuario
		where 
			e.idequipe = $id";
			
	$result = mysqli_query($conexao, $sql);
	$dados = mysqli_fetch_array($result);

	$response['nome'] = $dados['nome'];
	$response['escola'] = $dados['escola'];
	$response['capitao'] = $dados['capitao'];
	$response['responsavel'] = $dados['responsavel'];
	$response['usuario'] = $dados['usuario'];
	$response['unidade'] = $dados['unidade'];

}
else {
	$response = array("success" => false);
}

echo json_encode($response);

ob_flush();

?>
