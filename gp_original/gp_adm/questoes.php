<?php
	ini_set('display_errors', '0');
	session_start();
	if (!isset ($_SESSION["login"]))
		header("Location: index.php?erro=1");

	require_once ('cabecalho.php');
	require_once ('conexao.php');

	$idprova = $_POST['idprova'];
	if ($idprova=="")
		header("location:prova.php");
	
?>
<script>
	$(document).ready(function() {
		$("a[rel=voltar]").click(function(ev) {
			$(location).attr("href", "prova.php");
		});
	});	

</script>
<table border="0" width="100%" cellpading="0" cellspacing="0">
	<tr>
		<td colspan="3" class='td-titulo'>Quest&otilde;es - 
			<?php 
				$sql = "select nome from prova where idprova = $idprova";
				$result = mysqli_query($conexao, $sql);
				$dados = mysqli_fetch_array($result);
				echo $dados['nome'];
			?>
		</td>
	</tr>
</table>
<a href='#voltar' rel='voltar' class='btn btn-gray'>Voltar</a><br><br>
<?php
	$sql = "select
				q.titulo,
				q.descricao,
				q.texto,
				q.alternativa1,
				q.alternativa2,
				q.alternativa3,
				q.alternativa4,
				q.alternativa5,
				e.nome as etapa
			from
				questao q
				left join etapa e on e.idetapa = q.etapa_idetapa
			where
				q.data_exclusao is null
			and 
				e.prova_idprova = $idprova
			order by
				e.nome,
				q.titulo";
				
	$resultado = mysqli_query($conexao, $sql);
	while ($dados = mysqli_fetch_array($resultado)) {
		$x++;
		$etapa = $dados['etapa'];
		if ($tetapa != $etapa)
			echo "<h2>$etapa</h2><br />";
		$tetapa = $etapa;
		$titulo = $dados['titulo'];
		$descricao = $dados['descricao'];
		$texto = substr($dados['texto'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['texto'], 11)):html_entity_decode($dados['texto']);
		$alternativa1 = substr($dados['alternativa1'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['alternativa1'], 11)):html_entity_decode($dados['alternativa1']);
		$alternativa2 = substr($dados['alternativa2'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['alternativa2'], 11)):html_entity_decode($dados['alternativa2']);
		$alternativa3 = substr($dados['alternativa3'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['alternativa3'], 11)):html_entity_decode($dados['alternativa3']);
		$alternativa4 = substr($dados['alternativa4'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['alternativa4'], 11)):html_entity_decode($dados['alternativa4']);
		$alternativa5 = substr($dados['alternativa5'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['alternativa5'], 11)):html_entity_decode($dados['alternativa5']);

		echo "$titulo) $texto<br><br>
			<input type='radio' name='q$x'>" . $alternativa1 . "<br>
			<input type='radio' name='q$x'>" . $alternativa2 . "<br>
			<input type='radio' name='q$x'>" . $alternativa3 . "<br>
			<input type='radio' name='q$x'>" . $alternativa4 . "<br>
			<input type='radio' name='q$x'>" . $alternativa5 . "<br><br><hr><br>";
	}
?>
