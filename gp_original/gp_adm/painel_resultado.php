<?php
	ini_set('display_errors', '0');
	session_start();
		                                                                                                                
	if (!isset ($_SESSION['login']))
		header('Location: index.php?erro=1');

	require_once ('conexao.php');

	foreach ($_POST['participante'] as $participante => $idequipe) {
		$equipes .= "$idequipe, ";
	}
		
	$equipes = substr($equipes, 0, strlen($equipes)-2);

	$divulgacao = date("Y-m-d H:i:s", $now = time() + $_POST['tempo']);

	$sql = "update 
			equipe
		set 
			status = null;
		update 
			equipe 
		set
			status = 1
		where
			idequipe in ($equipes);
		update
			etapa
		set
			divulgacao = '$divulgacao'
		where
			idetapa = " . $_POST['idetapa'];

			
	if (mysqli_multi_query($conexao, $sql)) {
		$response = array("success" => true);
	}
	else
		$response = array("success" => false);

	echo json_encode($response);

	ob_flush();
?>
