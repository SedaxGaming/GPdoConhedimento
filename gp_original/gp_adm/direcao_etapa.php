<?php
	ini_set('display_errors', '0');
	session_start();
		                                                                                                                
	if (!isset ($_SESSION['login']))
		header('Location: index.php?erro=1');

	require_once ('conexao.php');

	$idprova = $_POST['idprova'];

?>
	<form id="confEtapa" method="POST" action="">
	<table border='0' width='100%' cellpading='0' cellspacing='0'>
		<tr>
			<td class='td-titulo' colspan="2">
				Etapas
			</td>
		</tr>
	</table>
	<table border='0' cellpading='0' cellspacing='0'>
		<tr>
			<td>
				<select name="idetapa" id="idetapa">
					<option value=''>--Selecione uma op&ccedil;&atilde;o--</option>

	<?php
		$sql = "select idetapa, nome from etapa where data_exclusao is null and prova_idprova = $idprova order by nome";
		$resultado = mysqli_query($conexao, $sql);
		while ($dados = mysqli_fetch_array($resultado)) {
			$idetapa = $dados['idetapa'];
			$nome = $dados['nome'];
			echo "<option value='$idetapa'>$nome</option>";
		}
	?>

				</select>
			</td>
			<td rowspan='3' align='center' style="padding-left: 20px;">
				<a href='#' id='iniciar' class='btn btn-green'>Iniciar</a>
				<a href='#' id='minutoI' class='btn btn-blue'>+1 minuto (In&iacute;cio)</a>
				<a href='#' id='minutoF' class='btn btn-blue'>+1 minuto (Final)</a>
				<a href='#' id='minutoR' class='btn btn-blue'>+1 minuto (Resultado)</a>
				<!-- <a href='#' id='encerrar' class='btn btn-red'>Encerrar</a> -->
				<a href='#' id='ultima' class='btn btn-red'>&Uacute;ltima etapa</a>
				<a href='#' id='zerar' class='btn btn-black'>Zerar etapa atual</a>
			</td>
		</tr>
		<tr>
			<td>
				Tempo para in&iacute;cio <input type='text' name='inicio' id='inicio' size='10' value='60'> <small>segundos</small>
			</td>
		</tr>
		<tr>
			<td>
				Tempo de dura&ccedil;&atildeo <input type='text' name='duracao' id='duracao' size='10' value='10'> <small>minutos</small>
			</td>
		<tr>
	</table>
	</form>
	<script>
		$(document).ready(function() {
			$("#iniciar").click(function (ev) {
				var valor = $("#idetapa").val();
				if (valor != "") {
					var dados = $('#confEtapa').serialize();

					$.ajax({
						url: 'etapa_iniciar.php',
						data: dados,
						type: 'post',
						success: function(response) {
							var retorno = $.parseJSON(response); 
							if (retorno.success) {
								alert("Etapa iniciada!");
							}
						}
					});
				}
			});
			$("#minutoI").click(function (ev) {
				var valor = $("#idetapa").val();
				if (valor != "") {
					$.ajax({
						url: 'etapa_iniciar.php',
						data: {idetapa: valor, minutoi: 1},
						type: 'post',
						success: function(response) {
							var retorno = $.parseJSON(response); 
							if (retorno.success) {
								alert("Um minuto adicionado antes de iniciar etapa!");
							}
						}
					});
				}
			});
			$("#minutoF").click(function (ev) {
				var valor = $("#idetapa").val();
				if (valor != "") {
					$.ajax({
						url: 'etapa_iniciar.php',
						data: {idetapa: valor, minutof: 1},
						type: 'post',
						success: function(response) {
							var retorno = $.parseJSON(response); 
							if (retorno.success) {
								alert("Um minuto adicionado para fim da etapa!");
							}
						}
					});
				}
			});
			$("#minutoR").click(function (ev) {
				var valor = $("#idetapa").val();
				if (valor != "") {
					$.ajax({
						url: 'etapa_iniciar.php',
						data: {idetapa: valor, minutor: 1},
						type: 'post',
						success: function(response) {
							var retorno = $.parseJSON(response); 
							if (retorno.success) {
								alert("Um minuto adicionado antes da divulgação do resultado!");
							}
						}
					});
				}
			});
			$("#encerrar").click(function (ev) {
				var valor = $("#idetapa").val();
				if (valor != "") {
					var r=confirm("Tem certeza que deseja encerrar a etapa atual?");

					if (r==false) {
						return false;
					}
				
					$.ajax({
						url: 'etapa_iniciar.php',
						data: {idetapa: valor, encerrar: 1},
						type: 'post',
						success: function(response) {
							var retorno = $.parseJSON(response); 
							if (retorno.success) {
								alert("Etapa encerrada!");
							}
						}
					});
				}
			});
			$("#ultima").click(function (ev) {
				var valor = $("#idetapa").val();
				if (valor != "") {
					var r=confirm("Esse procedimento apaga a pontuação de todas as equipes e deve ser realizado somente após o início (e antes do final) da última etapa do GP. É importante que já estejam habilitadas as equipes classificadas para a última etapa.\nDeseja continuar?");

					if (r==false) {
						return false;
					}
				
					$.ajax({
						url: 'etapa_iniciar.php',
						data: {idetapa: valor, ultima: 1},
						type: 'post',
						success: function(response) {
							var retorno = $.parseJSON(response); 
							if (retorno.success) {
								alert("Sistema preparado para última etapa!");
							}
						}
					});
				}
			});
			$("#zerar").click(function (ev) {
				var valor = $("#idetapa").val();
				if (valor != "") {
					var r=confirm("Esse procedimento reinicia a etapa atual, removendo eventuais respostas enviadas pelas equipes, os horários de início, fim e divulgação de resultado.\nTem certeza que deseja continuar?");

					if (r==false) {
						return false;
					}
				
					$.ajax({
						url: 'etapa_iniciar.php',
						data: {idetapa: valor, zerar: 1},
						type: 'post',
						success: function(response) {
							var retorno = $.parseJSON(response); 
							if (retorno.success) {
								alert("Etapa atual zerada!");
							}
						}
					});
				}
			});
		});
	</script>
	<div id="retorno">
	</div>

<?php
	ob_flush();
?>
