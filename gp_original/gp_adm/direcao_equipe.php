<?php
	ini_set('display_errors', '0');
	session_start();
		                                                                                                                
	if (!isset ($_SESSION['login']))
		header('Location: index.php?erro=1');

	require_once ('conexao.php');

	$idprova = $_POST['idprova'];

?>

	<table border="0" width="100%" cellpading="0" cellspacing="0">
		<tr>
			<td class='td-titulo'>
				Equipes
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 10px 10px 20px 10px;">
				<a href='#' id='confirma' class='btn btn-red'>Definir participantes</a> <a href='#' id='limpar' class='btn btn-blue' marcado='true'>Desmarcar</a>
			</td>
		</tr>
	</table>

	<form id="participantes" action="" method="POST">
		<input type="hidden" name="idprova" id="idprova" value="<?php echo $idprova; ?>">
	<table id="relEquipes" width="100%" class="cellspacing" display="0" width="100%">
		<thead>
			<tr>
				<th>Escola</th>
				<th>Equipe</th>
				<th>Participa</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Escola</th>
				<th>Equipe</th>
				<th>Participa</th>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$sql = "select 
					idequipe,
					escola,
					nome,
					status
				from 
					equipe
				where 
					data_exclusao is null
				and
					unidade = (select
							p.unidade
						from
							prova p
						where
							p.idprova = $idprova)";
			$result = mysqli_query($conexao, $sql)
			or die("Nao foi possivel conectar no banco de dados!");
			
			$qtdR = mysqli_num_rows($result);
		
			while ( $linha = mysqli_fetch_array ( $result ) ) {
				$idequipe = $linha['idequipe'];
				$escola = $linha['escola'];
				$nome = $linha['nome'];
				$status = $linha['status']==''?'':'checked';

				echo "<tr>
					    <td>
					        $escola
					    </td>
					    <td>
					        $nome
					    </td>
						<td align='center' width='30'>
							<input type='checkbox' name='participante[]' value='$idequipe' $status>
						</td>
					</tr>";

			}
		?>
		</tbody>
	</table>
	</form>
	<script>
		$("#limpar").click(function (ev) {
			if ($("#limpar").attr('marcado')=='true'){
				$("input[type=checkbox]").prop('checked', false);
				$("#limpar").attr('marcado', false);
				$("#limpar").html('Marcar');
			}
			else {
				$("input[type=checkbox]").prop('checked', true);
				$("#limpar").attr('marcado', 'true');
				$("#limpar").html('Desmarcar');
			}
		});
		$("#confirma").click(function (ev) {
			var valor = $("#idprova").val();
			if (valor != "") {
				var dados = $('#participantes').serialize();

				if ($('[name="participante[]"]:checked').length == 0) {
					alert("Selecione pelo menos uma equipe!");
					return false;
				}
				
				$.ajax({
					url: 'direcao_participantes.php',
					data: dados,
					type: 'post',
					success: function(response) {
						alert(response);
					}
				});
			}
		});

		$('#relEquipes').DataTable({
			language: {
			    "url": "js/DataTables/pt-br.json"
			},
			dom: 'lBfrtip',
			buttons: [
	            'csv', 'excel', 'pdf'
	        ],
			lengthMenu: [[-1, 5, 50, 100], ["Todos", 5, 50, 100]],
			order: [[ 0, 'asc' ], [ 1, 'asc' ]],
			"aoColumns": [
				{},
				{},
				{ "bSortable": false }
			]
		});

	</script>

<?php
	ob_flush();
?>
