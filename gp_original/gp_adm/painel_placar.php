<?php
	ini_set('display_errors', '0');
	session_start();
		                                                                                                                
	if (!isset ($_SESSION['login']))
		header('Location: index.php?erro=1');

	require_once ('conexao.php');

	$idetapa = $_POST['idetapa'];

	$sql = "select
			correta
		from
			questao
		where
			etapa_idetapa = $idetapa
		order by
			ordem";
	$resultado = mysqli_query($conexao, $sql);
	while ($dados = mysqli_fetch_array($resultado)) {
		$y++;
		$gabarito[$y] = $dados['correta'];
	}
?>

	<table border="0" width="100%" cellpading="0" cellspacing="0">
		<tr>
			<td class='td-titulo'>
				Equipes
			</td>
		</tr>
	</table>

	<table id="respostas" width="100%" class="cellspacing" display="0" width="100%">
		<thead>
			<tr>
				<th>Equipe</th>
				<th>Respostas</th>
				<th>Data/Hora</th>
				<th>Acertos</th>
				<th>Participa</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Equipe</th>
				<th>Respostas</th>
				<th>Data/Hora</th>
				<th>Acertos</th>
				<th>Participa</th>
			</tr>
		</tfoot>
		<tbody>
		<form id="participantes" action="" method="POST">
		<?php
			$x = 0;
		
			$sql = "select 
					e.nome as equipe,
					r.resposta, 
					r.datahora
				from 
					respostas r
					left join equipe e on e.idequipe = r.equipe_idequipe
				where 
					r.etapa_idetapa = $idetapa";
			$result = mysqli_query($conexao, $sql)
			or die("Nao foi possivel conectar no banco de dados!");
			
			$qtdR = mysqli_num_rows($result);
		
			while ( $linha = mysqli_fetch_array ( $result ) ) {
				$x++;
				$equipe = $linha['equipe'];
				$resposta = $linha['resposta'];
				$datahora = $linha['datahora'];

				$respostas = explode(",", $resposta);
				$z=0;
				$p=0;
				$acertos="";
				foreach($respostas as $resp) {
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
					        $p
					    </td>
						<td align='center' width='30'>
							<input type='checkbox' name='participante[]' value='$idequipe'>
						</td>
					</tr>";

			}
		?>
		</form>
		</tbody>
	</table>
	<script>
		$('#respostas').DataTable({
			language: {
			    "url": "js/DataTables/pt-br.json"
			},
			dom: 'lBfrtip',
			buttons: [
	            'csv', 'excel', 'pdf'
	        ],
			order: [[ 3, 'asc' ], [ 4, 'desc' ]],
			"aoColumns": [
				{ "bSortable": false },
				{ "bSortable": false },
				{ "bSortable": false },
				{ "bSortable": false }
			]
		});

	</script>

<?php
	ob_flush();
?>
