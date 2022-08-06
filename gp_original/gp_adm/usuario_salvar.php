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
$login = $_POST['login'];
$password = $_POST['password'];
$grupo = $_POST['grupo'];

$sql = "select count(login) as qtd from usuario where data_exclusao is null and login like '$login'";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);

$qtd = $dados['qtd'];

//if ($qtd == 0)
//    header("Location: usuario.php?erro=5");

$data = date("Y-m-d H:i:s");
$usuario = $_SESSION["login"];
$sql = "select idusuario from usuario where login like '$usuario' and data_exclusao is null";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);
$idusuario = $dados['idusuario'];

if ((!isset($_POST['id'])) || ($_POST['id'] == '')) {
    $id = null;

	$password ==""?md5("ideau"):md5($password);
		
    $sql = "insert into usuario (
				nome,
				login,
				password,
				grupo,
				data_alteracao,
				usuario_alteracao) 
			values (
				'$nome',
				'$login',
				'$password',
				'$grupo',
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
    if ($password == "") {
    	$senha = "";
    }
    else {
    	$senha = "password = '" . md5($password) . "', ";
    }
    	
    
    $sql = "update usuario set 
				nome = '$nome',
				login = '$login', 
				$senha
				grupo = '$grupo',
				data_alteracao = '$data', 
				usuario_alteracao = $idusuario
			where 
				idusuario = $id";

    if (mysqli_query($conexao, $sql)) {
    	if ($senha != "") {
			$sql = "update equipe set 
						senha = '". $_POST['password'] . "'
					where 
						usuario_idusuario = $id";

			mysqli_query($conexao, $sql);
    	}
		$response = array("success" => true);
	}
	else
		$response = array("success" => false);
}

echo json_encode($response);

ob_flush();

?>
