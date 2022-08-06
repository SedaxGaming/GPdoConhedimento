<?php
	ini_set('display_errors', '0');
	session_start();
		                                                                                                                
	if (!isset ($_SESSION['login']))
		header('Location: index.php?erro=1');

	require_once ('conexao.php');
	require_once ('acesso.php');

	$idprova = $_POST['idprova'];
	$idetapa = $_POST['idetapa'];

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
?>
	<script src="js/moment.min.js"></script>
	<script src="js/datetime-moment.js"></script>
	<table border="0" width="100%" cellpading="0" cellspacing="0">
		<tr>
			<td class='td-titulo'>
				Equipes
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 10px 10px 20px 10px;">
				<?php
					$sql = "select
								fim
							from
								etapa
							where
								idetapa = $idetapa";
					$resultado = mysqli_query($conexao, $sql);
					$dados = mysqli_fetch_array($resultado);
					$fim = strtotime($dados['fim']);
					
					if ($fim != '' && $fim < time()) {
						if ($_acesso == '100') echo "<a href='#' id='confirma' class='btn btn-red'>Confirmar resultado <br> e definir participantes</a>
							Tempo para divulga&ccedil;&atilde;o do resultado <input type='text' name='tempo' id='tempo' size='2' value='45'> <small>segundos</small>";
					}
					else {
						echo "<script>
								setTimeout(function(){
										$('#resultado').click();
									}, 2000);
							</script>";
					}
				?>	
			</td>
		</tr>
	</table>

	<form id="participantes" action="" method="POST">
		<input type="hidden" name="idprova" id="idprova" value="<?php echo $idprova; ?>">
		<input type="hidden" name="idetapa" id="idetapa" value="<?php echo $idetapa; ?>">
	<table id="relEquipes" width="100%" class="cellspacing" display="0" width="100%">
		<thead>
			<tr>
				<th>Equipe</th>
				<th>Respostas</th>
				<th>Data/Hora</th>
				<th>Pontua&ccedil;&atilde;o</th>
				<th>Participa</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Equipe</th>
				<th>Respostas</th>
				<th>Data/Hora</th>
				<th>Pontua&ccedil;&atilde;o</th>
				<th>Participa</th>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$x = 0;
		
			$sql = "select 
					e.idequipe,
					e.escola,
					e.nome as equipe,
					r.resposta, 
					date_format(r.datahora, '%d/%m/%Y %H:%i:%s') as datahora,
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
		
			while ( $linha = mysqli_fetch_array ( $result ) ) {
				$x++;
				$idequipe = $linha['idequipe'];
				$equipe = $linha['equipe'];
				$resposta = $linha['resposta'];
				$datahora = $linha['datahora'];
				$total = $linha['total'];

				$respostas = explode(",", $resposta);
				$z=0;
				$p=0;
				$acertos="";
				foreach($respostas as $resp) {
					if ($resp == "")
						continue;
					$z++;
					if ($resp == $gabarito[$z]) {
						$acertos = $acertos . "O ";
						$p++;
					}
					else
						$acertos = $acertos . "X ";
				}

				$status = $linha['status'];

				echo "<tr>
					    <td width='160'>
					        $equipe
					    </td>
					    <td align='center'>
					        $acertos
					    </td>
					    <td width='40' align='center'>
					        $datahora
					    </td>
					    <td width='40' align='center'>
					        $total
					    </td>
						<td align='center' width='30'>
							<input type='checkbox' name='participante[]' value='$idequipe' checked>
						</td>
					</tr>";

			}
		?>
		</tbody>
	</table>
	</form>
	<script>
		$("#confirma").click(function (ev) {
			var valor = $("#idetapa").val();
			if (valor != "") {
				var dados = $('#participantes').serialize();
				dados += "&tempo=" + $("#tempo").val();

				if ($('[name="participante[]"]:checked').length == 0) {
					alert("Selecione pelo menos uma equipe!");
					return false;
				}
				
				$.ajax({
					url: 'painel_resultado.php',
					data: dados,
					type: 'post'
				}).done(function(response) {
					$("#equipes").html(response);
				});
			}
		});

		$.fn.dataTable.moment( 'DD/MM/YYYY HH:mm:ss' );
		$('#relEquipes').DataTable({
			language: {
			    "url": "js/DataTables/pt-br.json"
			},
			dom: 'lBfrtip',
			buttons: [
	            'csv', 'excel', 'pdf'
	        ],
			lengthMenu: [[-1, 5, 50, 100], ["Todos", 5, 50, 100]],
			order: [[ 3, 'desc' ], [ 2, 'asc' ]],
			"aoColumns": [
				{},
				{},
				{},
				{},
				{ "bSortable": false }
			]
		});

	</script>

<?php
	ob_flush();
?>

