<?php
	require_once("cabecalho.php");
	session_start();

	if (!isset ($_SESSION["login"]))
		header("Location: index.php?erro=1");


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
		echo "<div style='width: 100%; height: 70px; position: absolute; top: 50%; margin-top: -35px; text-align: center; vertical-align:middle;'><h2>Aguarde o in&iacute;cio da pr&oacute;xima etapa!</h2>
			<script type='text/javascript'>    
				setTimeout(function(){
					location.reload();	
				}, 2000);
			</script>";		
	}
	else {
		if (strtotime($inicio) > time()) {
			echo "<div id='tempo' style='width: 100%; height: 40px; position: absolute; top: 50%; margin-top: -35px; text-align: center; vertical-align:middle;'><h2>Aguardando o in&iacute;cio da pr&oacute;xima etapa</h2>
				<script type='text/javascript'>
					setTimeout(function(){
						location.reload();	
					}, 1000);
				</script>";
		}
		else {
			$sql = "select idequipe, escola, status from equipe where usuario_idusuario = (select idusuario from usuario where login like '" . $_SESSION['login'] . "' and data_exclusao is null)";
			$result = mysqli_query($conexao, $sql);
			$dados = mysqli_fetch_array($result);
			$idequipe = $dados['idequipe'];
			$escola = $dados['escola'];
			$status = $dados['status'];

			if ($status != 1) {
				echo "<div style='width: 100%; height: 70px; position: absolute; top: 50%; margin-top: -35px; text-align: center; vertical-align:middle;'><h2>Equipe n&atilde;o habilitada para a etapa atual!<br>Agradecemos a participa&ccedil;&atilde;o</h2>
				<script type='text/javascript'>
					setTimeout(function(){
						location.reload();	
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
						echo "<div style='width: 100%; height: 70px; position: absolute; top: 50%; margin-top: -35px; text-align: center; vertical-align:middle;'><h2>Etapa atual encerrada!<br>Aguardando resultado...</h2>";
						echo "<script type='text/javascript'>    
								setTimeout(function(){
									location.reload();
								}, 2000);
							</script>";
					}
					else {
						if (strtotime($divulgacao) > $agora) {
							echo "<div id='tempo' style='width: 100%; height: 40px; position: absolute; top: 50%; margin-top: -35px; text-align: center; vertical-align:middle;'><h2>Aguardando divulga&ccedil;&atilde;o do resultado</h2>
								<script type='text/javascript'>
									setTimeout(function(){
										location.reload();	
									}, 1000);
								</script>";
						}
						else {
?>
						<table id="resultado" class="display" cellspacing="10" cellpadding="10" width="100%">
							<thead>
								<tr>
									<th>Coloca&ccedil;&atilde;o</th>
									<th>Escola</th>
									<th>Equipe</th>
									<th>Pontua&ccedil;&atilde;o</th>
									<th>Data/Hora</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Coloca&ccedil;&atilde;o</th>
									<th>Escola</th>
									<th>Equipe</th>
									<th>Pontua&ccedil;&atilde;o</th>
									<th>Data/Hora</th>
								</tr>
							</tfoot>
							<tbody>
						<?php
						
/*						$sql = "select 
								e.idequipe,
								e.escola,
								e.nome as equipe,
								r.acertos, 
								date_format(r.datahora, '%d/%m/%Y %H:%i:%s') as datahora
							from 
								respostas r
								left join equipe e on e.idequipe = r.equipe_idequipe
							where 
								r.etapa_idetapa = $idetapa
							order by
								r.acertos desc,
								r.datahora";
*/
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
								e.data_exclusao is null
							group by
								e.idequipe
							order by
								total desc,
								datahora";
							
						$result = mysqli_query($conexao, $sql)
						or die("Nao foi possivel conectar no banco de dados!");
						
						$qtdR = mysqli_num_rows($result);
					
						$pos = 0;
						while ( $linha = mysqli_fetch_array ( $result ) ) {
							$pos++;
							$idequipe = $linha['idequipe'];
							$escola = $linha['escola'];
							$equipe = $linha['equipe'];
							$total = $linha['total']==""?0:$linha['total'];
							$datahora = $linha['datahora']==""?"-":$linha['datahora'];

/*							$sql_temp = "select sum(acertos) as total from respostas where equipe_idequipe = $idequipe";
							$resultado_temp = mysqli_query($conexao, $sql_temp);
							$dados_temp = mysqli_fetch_array($resultado_temp);
							$total = $dados_temp['total'];
*/
							echo "<tr>
								    <td width='100' align='center'>
								        $pos&ordm; lugar
								    </td>
								    <td width='360' align='center'>
								        $escola
								    </td>
								    <td width='360' align='center'>
								        $equipe
								    </td>
								    <td width='20' align='center'>
								        $total
								    </td>
								    <td width='40' align='center'>
								        $datahora
								    </td>
								</tr>";

						}
						?>
						</tbody>
					</table>
					<!--<div style="margin-top: 20px; text-align: center;"><a href='acumulado.php' rel='acumulado' class='btn btn-blue'>Mostrar acumulado</a></div> -->
					<script type='text/javascript'>    
						setTimeout(function(){
							location.reload();
						}, 5000);
					</script>";
					<?php						}
					}
				}
				else {
			
		?>
					<table border="0" width="100%" cellpading="0" cellspacing="0">
						<tr>
							<td colspan="3" class='td-titulo'>Quest&otilde;es - 
								<?php 
									$sql = "select nome from etapa where idetapa = $idetapa";
									$result = mysqli_query($conexao, $sql);
									$dados = mysqli_fetch_array($result);
									echo $dados['nome'] . " - ";

									$sql = "select nome from prova where idprova = $idprova";
									$result = mysqli_query($conexao, $sql);
									$dados = mysqli_fetch_array($result);
									echo $dados['nome'];
								?>
							</td>
						</tr>
					</table>


		<?php
					$sql = "select count(*) from respostas where equipe_idequipe = $idequipe and etapa_idetapa = $idetapa";
					$resultado = mysqli_query($conexao, $sql);
					$dados = mysqli_fetch_array($resultado);
					$qtd = $dados['count(*)'];
					
					if ($qtd == 0) {
						echo "<form id='gp' method='POST' action='resposta.php'>
								<input type='hidden' name='idequipe' value='$idequipe'>
								<input type='hidden' name='idetapa' value='$idetapa'>";

						$sql = "select
									idquestao,
									titulo,
									descricao,
									texto,
									alternativa1,
									alternativa2,
									alternativa3,
									alternativa4,
									alternativa5
								from
									questao
								where
									data_exclusao is null
								and
									etapa_idetapa = $idetapa";


						$result = mysqli_query($conexao, $sql);
						while ($dados = mysqli_fetch_array($result)) {
							$x++;
							$idquestao = $dados['idquestao'];
							$titulo = $dados['titulo'];
							$descricao = $dados['descricao'];
							$texto = substr($dados['texto'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['texto'], 11)):html_entity_decode($dados['texto']);
							$alternativa1 = substr($dados['alternativa1'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['alternativa1'], 11)):html_entity_decode($dados['alternativa1']);
							$alternativa2 = substr($dados['alternativa2'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['alternativa2'], 11)):html_entity_decode($dados['alternativa2']);
							$alternativa3 = substr($dados['alternativa3'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['alternativa3'], 11)):html_entity_decode($dados['alternativa3']);
							$alternativa4 = substr($dados['alternativa4'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['alternativa4'], 11)):html_entity_decode($dados['alternativa4']);
							$alternativa5 = substr($dados['alternativa5'], 0, 11)=="&lt;div&gt;"?html_entity_decode(substr($dados['alternativa5'], 11)):html_entity_decode($dados['alternativa5']);

							echo "$x) $texto<br><br>
								<input type='radio' name='q$x' value='1' required>" . $alternativa1 . "<br>
								<input type='radio' name='q$x' value='2' required>" . $alternativa2 . "<br>
								<input type='radio' name='q$x' value='3' required>" . $alternativa3 . "<br>
								<input type='radio' name='q$x' value='4' required>" . $alternativa4 . "<br>
								<input type='radio' name='q$x' value='5' required>" . $alternativa5 . "<br><hr>";
						}
						
						echo "<input type='hidden' name='qtd' value='$x'>
							<input type='submit' value='Enviar' class='btn btn-green'><br /><br /><br />";

/*
						$tempo = (strtotime($fim) - time()) * 1000;

						echo "<script type='text/javascript'>    
								setTimeout(function(){
									location.reload();
								}, $tempo);
							</script>";
*/
					}	
					else {
						echo "<div style='width: 100%; height: 70px; position: absolute; top: 50%; margin-top: -35px; text-align: center; vertical-align:middle;'><h2>Equipe respondeu &agrave; etapa atual</h2>";
						echo "<script type='text/javascript'>    
								setTimeout(function(){
									location.reload();
								}, 2000);
							</script>";
					}
				}
			}
		}
	}
	require_once("rodape.php");
?>
