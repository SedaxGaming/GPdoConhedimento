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
$nome = addslashes($_POST['nome']);
$escola = addslashes($_POST['escola']);
$capitao = addslashes($_POST['capitao']);
$responsavel = addslashes($_POST['responsavel']);
$unidade = $_POST['unidade'];
$login = $_POST['usuario'];

$data = date("Y-m-d H:i:s");
$usuario = $_SESSION["login"];
$sql = "select idusuario from usuario where login like '$usuario' and data_exclusao is null";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);
$idusuario = $dados['idusuario'];

if ((!isset($_POST['id'])) || ($_POST['id'] == '')) {
    $id = null;

	$sql = "select count(nome) as qtd from usuario where data_exclusao is null and login like '$login'";
	$result = mysqli_query($conexao, $sql);
	$dados = mysqli_fetch_array($result);

	$qtd = $dados['qtd'];

	if ($qtd > 0) {
		$response = array("success" => false);
		//header("Location: equipe.php?erro=5");
	}
	else {
		$password = substr(str_shuffle("abcdefghijklmnopqrstuvyxwz0123456789"),0,5);
	
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
					md5('$password'),
					1000,
					'$data',
					$idusuario)";

		mysqli_query($conexao, $sql);

		$sql = "insert into equipe (
					nome,
					escola,
					capitao,
					responsavel,
					usuario_idusuario,
					unidade,
					senha,
					data_alteracao,
					usuario_alteracao) 
				values (
					'$nome',
					'$escola',
					'$capitao',
					'$responsavel',
					(select 
							u.idusuario
						from
							usuario u
						where
							u.login like '$login'
						and
							u.data_exclusao is null),
					'$unidade',
					'$password',
					'$data',
					$idusuario)";

		if (mysqli_query($conexao, $sql)) {
			$response = array("success" => true);
		}
		else
			$response = array("success" => false);
	}
}
else
{
    $id = $_POST['id'];
    
	$sql = "select count(nome) as qtd from usuario where data_exclusao is null and login like '$login' and idusuario <> (select usuario_idusuario from equipe where idequipe = $id)";
	$result = mysqli_query($conexao, $sql);
	$dados = mysqli_fetch_array($result);

	$qtd = $dados['qtd'];

	if ($qtd > 0) {
		$response = array("success" => false);
		//header("Location: equipe.php?erro=5");
	}
	else {
		$sql = "update equipe set 
					nome = '$nome',
					escola = '$escola', 
					capitao = '$capitao',
					responsavel = '$responsavel',
					unidade = '$unidade',
					data_alteracao = '$data', 
					usuario_alteracao = $idusuario
				where 
					idequipe = $id;
				update usuario set
					login = '$login',
					data_alteracao = '$data', 
					usuario_alteracao = $idusuario
				where
					idusuario = (select
									usuario_idusuario
								from
									equipe
								where
									idequipe = $id);";

		if (mysqli_multi_query($conexao, $sql)) {
			$response = array("success" => true);
		}
		else
			$response = array("success" => false);
	}
}

echo json_encode($response);

ob_flush();

?>
