<?php
	ini_set('display_errors', '0');

ob_start();                                                                                                                                                  
session_start();

if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

require_once ('conexao.php');
require_once ('limpa_injection.php');
require_once ('acesso.php');

if ($_acesso != '1000')
    header("Location: index.php");


$data = date("Y-m-d H:i:s");

$idequipe = $_POST['idequipe'];
$idetapa = $_POST['idetapa'];
$qtd = $_POST['qtd'];
$acertos = 0;

$sql = "select
		correta
	from
		questao
	where
		etapa_idetapa = $idetapa
	and
		data_exclusao is null
	order by
		ordem";
$resultado = mysqli_query($conexao, $sql);
while ($dados = mysqli_fetch_array($resultado)) {
	$y++;
	$gabarito[$y] = $dados['correta'];
}

for ($x = 1; $x <= $qtd; $x++){
	$resp = $_POST['q' . $x]==''?0:$_POST['q' . $x];
	if ($x == 1)
		$resposta = $resp;
	else
		$resposta = $resposta . ", " . $resp;
		
	if ($resp == $gabarito[$x])
		$acertos++;	
}


$sql = "select count(*) from respostas where equipe_idequipe = $idequipe and etapa_idetapa = $idetapa";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);

$qtd = $dados['qtd'];

if ($qtd > 0)
    header("Location: gp.php");
    
$sql = "select fim from etapa where idetapa = $idetapa";
$resultado = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($resultado);

if (strtotime($data) > strtotime($dados['fim'])) {
	$acertos = 0;
}

$sql = "insert into respostas (
			equipe_idequipe,
			etapa_idetapa,
			datahora,
			resposta,
			acertos) 
		values (
			$idequipe,
			$idetapa,
			'$data',
			'$resposta',
			$acertos)";

if (mysqli_query($conexao, $sql)) {
	header("Location: gp.php");
}
else 
	echo "<script>window.history.back();</script>";

ob_flush();

?>
