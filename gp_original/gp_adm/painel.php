<?php
	ini_set('display_errors', '0');
session_start();                         
                                                                                                                    
if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

require_once ('cabecalho.php');
?>

<script>
	$(document).ready(function() {
		$("#ativar").click(function(ev){
		    var valor = $("#idprova").val();
		    if (valor != "") {
				$.ajax({
				    url: 'painel_etapa.php',
				    data: { idprova: valor },
				    type: 'post'
				}).done(function(response) {
				    $("#etapas").html(response);
				    $("#equipes").html('');
				});
			}
		});
	});

</script>

<table border="0" width="100%" cellpading="0" cellspacing="0">
	<tr>
		<td class='td-titulo'>
			Painel de administra&ccedil;&atilde;o
		</td>
	</tr>
</table>

<div id="provas">
	<table border="0" width="100%" cellpading="0" cellspacing="0">
		<tr>
			<td class='td-titulo'>
				Provas
			</td>
		</tr>
		<tr>
			<td>
				<select name="idprova" id="idprova">
					<option value="">--Selecione uma op&ccedil;&atilde;o--</option>
					<?php
						$sql = "select idprova, nome from prova where data_exclusao is null order by nome";
						$resultado = mysqli_query($conexao, $sql);
						while ($dados = mysqli_fetch_array($resultado)) {
							$idprova = $dados['idprova'];
							$nome = $dados['nome'];
							echo "<option value='$idprova'>$nome</option>";
						}
					?>					
				</select>
				&nbsp;<a href='#' id='ativar' class='btn btn-blue'>Selecionar</a>
			</td>
		</tr>
	</table>
</div>
<div id="etapas">
</div>
<div id="equipes">
</div>

<?php
    require_once ('rodape.php');
?>
