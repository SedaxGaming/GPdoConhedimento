<?php
	ini_set('display_errors', '0');

ob_start();                                                                                                                                                  
session_start();

if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

require_once ('conexao.php');
require_once ('limpa_injection.php');
require_once ('acesso.php');

if ($_acesso != '10')
    header("Location: index.php");


$idetapa = $_POST['idetapa'];
$inicio = $_POST['inicio'];
$duracao = $_POST['duracao'];
$minutoi = $_POST['minutoi'];
$minutof = $_POST['minutof'];
$minutor = $_POST['minutor'];
$encerrar = $_POST['encerrar'];
$zerar = $_POST['zerar'];
$ultima = $_POST['ultima'];

if ($zerar == 1) {
	$sql = "update 
				etapa
			set 
				status = null,
				inicio = null,
				fim = null,
				divulgacao = null
			where
				idetapa = $idetapa;
			delete from respostas where etapa_idetapa = $idetapa;";

	if (mysqli_multi_query($conexao, $sql)) {
		$response = array("success" => true);
	}
	else
		$response = array("success" => false);
}
elseif ($ultima == 1) {
	$sql = "update 
				respostas
			set 
				acertos = 0";

	if (mysqli_query($conexao, $sql)) {
		$response = array("success" => true);
	}
	else
		$response = array("success" => false);
}
else {
	$sql = "select inicio, fim, divulgacao from etapa where idetapa = $idetapa";
	$result = mysqli_query($conexao, $sql);
	$dados = mysqli_fetch_array($result);
	$start = $dados['inicio'];
	$end = $dados['fim'];
	$div = $dados['divulgacao'];

	if ($start == '') {
		$now = time();
		$start = date("Y-m-d H:i:s", $now+$inicio);
		$end = date("Y-m-d H:i:s", $now+$inicio+($duracao*60));

		$sql = "update 
					etapa
				set 
					status = null;
				update 
					etapa 
				set 
					status = 1,
					inicio = '$start',
					fim = '$end'
				where 
					idetapa = $idetapa;";

		if (mysqli_multi_query($conexao, $sql)) {
			$response = array("success" => true);
		}
		else
			$response = array("success" => false);
	}
	else {
		if ($inicio != '') {
			$now = time();
			$start = date("Y-m-d H:i:s", $now+$inicio);
			$end = date("Y-m-d H:i:s", $now+$inicio+($duracao*60));

			$sql = "update 
						etapa
					set 
						status = null;
					update 
						etapa 
					set 
						status = 1,
						inicio = '$start',
						fim = '$end'
					where 
						idetapa = $idetapa;";

			if (mysqli_multi_query($conexao, $sql)) {
				$response = array("success" => true);
			}
			else
				$response = array("success" => false);
		}
		
		if ($minutoi == 1) {
			$start = date("Y-m-d H:i:s", strtotime($start) + 60);
			$end = date("Y-m-d H:i:s", strtotime($end) + 60);

			$sql = "update 
						etapa
					set 
						inicio = '$start',
						fim = '$end'
					where 
						idetapa = $idetapa";

			if (mysqli_query($conexao, $sql)) {
				$response = array("success" => true);
			}
			else
				$response = array("success" => false);
		}
		if ($minutof == 1) {
			$end = date("Y-m-d H:i:s", strtotime($end) + 60);

			$sql = "update 
						etapa
					set 
						fim = '$end'
					where 
						idetapa = $idetapa";

			if (mysqli_query($conexao, $sql)) {
				$response = array("success" => true);
			}
			else
				$response = array("success" => false);
		}
		if ($minutor == 1) {
			if ($div != '') {
				$div = date("Y-m-d H:i:s", strtotime($div) + 60);

				$sql = "update 
							etapa
						set 
							divulgacao = '$div'
						where 
							idetapa = $idetapa";

				if (mysqli_query($conexao, $sql)) {
					$response = array("success" => true);
				}
				else
					$response = array("success" => false);
			}
		}
		if ($encerrar == 1) {
			$end = date("Y-m-d H:i:s");

			$sql = "update 
						etapa
					set 
						fim = '$end'
					where 
						idetapa = $idetapa";

			if (mysqli_query($conexao, $sql)) {
				$response = array("success" => true);
			}
			else
				$response = array("success" => false);
		}
	}
}

echo json_encode($response);

ob_flush();

?>
