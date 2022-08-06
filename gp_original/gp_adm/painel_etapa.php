<?php
	ini_set('display_errors', '0');
	session_start();
		                                                                                                                
	if (!isset ($_SESSION['login']))
		header('Location: index.php?erro=1');

	require_once ('conexao.php');

	$idprova = $_POST['idprova'];

?>
	
	<table border='0' width='100%' cellpading='0' cellspacing='0'>
		<tr>
			<td class='td-titulo' colspan='2'>
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
				<a href='#' id='resultado' class='btn btn-blue'>Resultado</a>
			</td>
		</tr>
	</table>
	<script>
			$("#resultado").click(function (ev) {
				var valor = $("#idetapa").val();
				if (valor != "") {
					$.ajax({
						url: 'painel_equipe.php',
						data: { idprova: <?php echo $idprova; ?>, idetapa: valor },
						type: 'post'
					}).done(function(response) {
						$("#equipes").html(response);
					});
				}
			});
	</script>

<?php
	ob_flush();
?>
