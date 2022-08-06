<?php
	session_start();

	if (!isset ($_SESSION["login"]))
		header("Location: index.php?erro=1");

?>
<html>
<head>
    <title>.: GP do Conhecimento - Faculdades IDEAU :.</title>
    <link rel="STYLESHEET" type="text/css" href="css/estilo.css" media="screen">
    <link rel="STYLESHEET" type="text/css" href="css/estilo.css" media="print">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
	<script type="text/javascript" src="js/DataTables/datatables.js"></script>
	<script type="text/javascript" src="js/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="js/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" src="js/DataTables/datatables.min.js"></script>
	<link href="js/datetimepicker-master/build/jquery.datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <script src="js/datetimepicker-master/build/jquery.datetimepicker.full.js" type="text/javascript"></script>
	<script src="js/moment.min.js"></script>
	<script src="js/datetime-moment.js"></script>
	
	<style>
		label{
			display: block;
   		}
   		body{
		  background-image: url("imagens/fundo.jpg");
		  background-repeat: no-repeat;
		  background-position: center;
		  background-size: contain;
   		}
		.window{
			display:none;
			position:absolute;
			left:0;
			top:0;
			background:#FFF;
			z-index:9900;
			padding:10px;
			border-radius:10px;
		}
		#mascara{
			display:none;
			position:absolute;
			left:0;
			top:0;
			z-index:9000;
			background-color:#000;
		}
		.fechar{ text-align:right;}
	</style>
</head>
<body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<?php
	ini_set('display_errors', '0');
?>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tr>
        <td align='center' bgcolor='#FFFFFF' style="background: url('') no-repeat; height: 80px;">
        	<br>
            <h1>GP do Conhecimento - Faculdades IDEAU</h1>
        </td>
    </tr>
</table>
<?php
	require_once("conexao.php");

	$agora = date("Y-m-d H:i:s");

	$sql = "select prova_idprova, idetapa, inicio, fim, divulgacao from etapa where status = 1";
	$result = mysqli_query($conexao, $sql);
	$dados = mysqli_fetch_array($result);
	$idprova = $dados['prova_idprova'];
	$idetapa = $dados['idetapa'];
	$divulgacao = $dados['divulgacao'];
	$agora = time();

	$inicio = $dados['inicio']==""?0:$dados['inicio'];
	$fim = $dados['fim'];
	
	if ($idprova == "") {
		echo "<div style='width: 100%; height: 70px; position: absolute; top: 25%; margin-top: -35px; text-align: center; vertical-align:middle;'><h2 style=\"font-size: 80px;\">Aguardando o in&iacute;cio da pr&oacute;xima etapa!</h2>
			<script type='text/javascript'>    
				setTimeout(function(){
					location.reload();	
				}, 2000);
			</script>";		
	}
	else {
		if (strtotime($inicio) > time()) {
			echo "<div id='tempo' style='width: 100%; height: 70px; position: absolute; top: 25%; margin-top: -35px; text-align: center; vertical-align:middle;'>
				<script>
					var x = setInterval(function() {
					  var agora = new Date().getTime() / 1000;
					  var fim = " . strtotime($inicio) . " - agora;

					  var min = Math.floor(fim / 60);
					  var seg = Math.floor(fim % 60);

					  if (min < 0 || seg < 0) {
						  location.reload();
					  }
					  else {
						  document.getElementById('tempo').innerHTML = '<h2 style=\"font-size: 80px;\">In&iacute;cio da pr&oacute;xima etapa em ' + ('00' + min).slice(-2)	 + ':' + ('00' + seg).slice(-2) + '</h2>';
					  }

					  if (fim < 1) {
						clearInterval(x);
						location.reload();
					  }
					}, 1000);
				</script>";
		}
		else {
			if (strtotime($fim) < $agora) {
				$sql = "select divulgacao from etapa where idetapa = $idetapa";
				$resultado = mysqli_query($conexao, $sql);
				$dados = mysqli_fetch_array($resultado);
				$divulgacao = $dados['divulgacao'];
				
				if ($divulgacao == "" || $divulgacao == "NULL") {
					echo "<div style='width: 100%; height: 70px; position: absolute; top: 25%; margin-top: -35px; text-align: center; vertical-align:middle;'><h2 style=\"font-size: 80px;\">Etapa atual encerrada!<br>Aguardando resultado...</h2>";
					echo "<script type='text/javascript'>    
							setTimeout(function(){
								location.reload();
							}, 2000);
						</script>";
				}
				else {
					if (strtotime($divulgacao) > $agora) {
						echo "<div id='tempo' style='width: 100%; height: 70px; position: absolute; top: 25%; margin-top: -35px; text-align: center; vertical-align:middle;'>
							<script>
								var x = setInterval(function() {
								  var agora = new Date().getTime() / 1000;
								  var fim = " . strtotime($divulgacao) . " - agora;

								  var min = Math.floor(fim / 60);
								  var seg = Math.floor(fim % 60);

								  if (min < 0 || seg < 0) {
									  location.reload();
								  }
								  else {
									  document.getElementById('tempo').innerHTML = '<h2 style=\"font-size: 80px;\">Divulga&ccedil;&atilde;o do resultado em ' + ('00' + min).slice(-2)	 + ':' + ('00' + seg).slice(-2) + '</h2>';
								  }

								  if (fim < 1) {
									clearInterval(x);
									location.reload();
								  }
								}, 1000);
							</script>";
					}
					else {
						?>
						<table id="resultado" class="display" cellspacing="10" cellpadding="10" width="100%" style='font-size: 24px;'>
							<thead>
								<tr style="background-color: #FFF">
									<th>Coloca&ccedil;&atilde;o</th>
									<th>Escola</th>
									<th>Equipe</th>
									<th>Pontua&ccedil;&atilde;o</th>
									<th>Data/Hora</th>
								</tr>
							</thead>
							<tfoot>
								<tr style="background-color: #FFF">
									<th>Coloca&ccedil;&atilde;o</th>
									<th>Escola</th>
									<th>Equipe</th>
									<th>Pontua&ccedil;&atilde;o</th>
									<th>Data/Hora</th>
								</tr>
							</tfoot>
							<tbody>
						<?php
						
						$sql = "select 
								e.idequipe,
								e.escola,
								e.nome as equipe,
								r.resposta, 
								date_format(r.datahora, '%H:%i:%s') as datahora,
								sum(t.acertos) as total
							from 
								equipe e
								left join respostas r on r.equipe_idequipe = e.idequipe and r.etapa_idetapa = $idetapa
								left join respostas t on t.equipe_idequipe = e.idequipe
							where 
								e.status = 1
							group by
								e.idequipe
							order by
								total desc,
								datahora";
								
						$result = mysqli_query($conexao, $sql)
						or die("Nao foi possivel conectar no banco de dados!");
						
						$qtdR = mysqli_num_rows($result);
						
						$pos = 0;
						$fundo = "#FFF";

						while ( $linha = mysqli_fetch_array ( $result ) ) {
							$pos++;
							$idequipe = $linha['idequipe'];
							$escola = $linha['escola'];
							$equipe = $linha['equipe'];
							$acertos = $linha['acertos'];
							$datahora = $linha['datahora']==""?"-":$linha['datahora'];

							$sql_temp = "select sum(acertos) as total from respostas where equipe_idequipe = $idequipe";
							$resultado_temp = mysqli_query($conexao, $sql_temp);
							$dados_temp = mysqli_fetch_array($resultado_temp);
							$total = $dados_temp['total']==""?0:$linha['total'];

							if ($pos < 6)
								$cor = "#FF0000";
							else
								$cor = "#000000";
								
							if ($fundo == "#CCC")
								$fundo = "#FFF";
							else
								$fundo = "#CCC";
							
							echo "<tr style='background-color: $fundo'>
									<td width='360' align='center' style='font-size: 24px; color: $cor;'>
									    $pos&ordm; lugar
									</td>
									<td width='360' align='center' style='font-size: 24px; color: $cor;'>
									    $escola
									</td>
									<td width='360' align='center' style='font-size: 24px; color: $cor;'>
									    $equipe
									</td>
									<td width='20' align='center' style='font-size: 24px; color: $cor;'>
									    $total
									</td>
									<td width='40' align='center' style='font-size: 24px; color: $cor;'>
									    $datahora
									</td>
								</tr>";

						}
						?>
						</tbody>
					</table>
					<!-- <div style="margin-top: 20px; text-align: center;"><a href='acumulado.php' rel='acumulado' class='btn btn-blue'>Mostrar acumulado</a></div> -->
					<?php
					}
				}
			}
			else {
				echo "<div id='tempo' style='width: 100%; height: 70px; position: absolute; top: 30%; margin-top: -35px; text-align: center; vertical-align:middle;'>
					<script>
						var x = setInterval(function() {
						  var agora = new Date().getTime() / 1000;
						  var fim = " . strtotime($fim) . " - agora;

						  var min = Math.floor(fim / 60);
						  var seg = Math.floor(fim % 60);
						  
						  if (min < 0 || seg < 0) {
							  location.reload();
						  }
						  else {
							  document.getElementById('tempo').innerHTML = '<h2 style=\"font-size: 120px;\">' + ('00' + min).slice(-2)	 + ':' + ('00' + seg).slice(-2) + '</h2>';
						  }

						  if (fim < 11) {
							$('#tempo').css('text-decoration', 'blink');
						  }

						  if (fim < 1) {
							clearInterval(x);
							location.reload();
						  }
						  
						  if (seg % 10 == 0) {
							  location.reload();
						  }
						  
						}, 1000);
					</script>";
			}
		}
	}
?>
