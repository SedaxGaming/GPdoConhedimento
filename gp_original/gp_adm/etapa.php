<?php
	ini_set('display_errors', '0');
session_start();                         
                                                                                                                    
if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

if (!isset ($_POST["idprova"]))
    header("Location: prova.php");
    
$idprova = $_POST['idprova'];

require_once ('cabecalho.php');

?>

<script type="text/javascript" language="javascript">
	jQuery.fn.dataTableExt.oSort['custom_euro_date-asc'] = function(x, y) {
		var xVal = getCustomEuroDateValue(x);
		var yVal = getCustomEuroDateValue(y);
	 
		if (xVal < yVal) {
		    return -1;
		} else if (xVal > yVal) {
		    return 1;
		} else {
		    return 0;
		}
	}
	 
	jQuery.fn.dataTableExt.oSort['custom_euro_date-desc'] = function(x, y) {
		var xVal = getCustomEuroDateValue(x);
		var yVal = getCustomEuroDateValue(y);
	 
		if (xVal < yVal) {
		    return 1;
		} else if (xVal > yVal) {
		    return -1;
		} else {
		    return 0;
		}
	}
	 
	function getCustomEuroDateValue(strDate) {
		var frDatea = $.trim(strDate).split(' ');
		var frTimea = frDatea[1].split(':');
		var frDatea2 = frDatea[0].split('/');
		 
		var x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + frTimea[2]);
		x = x * 1;
	 
		return x;
	}

	$(document).ready(function() {

		$('#etapas').DataTable({
			language: {
			    "url": "js/DataTables/pt-br.json"
			},
			dom: 'lBfrtip',
			buttons: [
	            'csv', 'excel', 'pdf'
	        ],
			lengthMenu: [[-1, 5, 50, 100], ["Todos", 5, 50, 100]],
    		order: [[ 2, "asc" ]],
			"aoColumns": [
				{},
				{},
				{ "sType": "custom_euro_date" },
				{ "sType": "custom_euro_date" },
				{ "sType": "custom_euro_date" },
				{},
				{ "bSortable": false },
				{ "bSortable": false }
			]
		});

		$('#salvar').click(function() {
			var dados = $('#cadEtapa').serialize();
			
			if ($("input[name=id]").val() != "") {
				var r=confirm("Alterar o registro selecionado?");

				if (r==false) {
					return false;
				}
			}

			$.ajax({
				type: 'POST',
				url: 'etapa_salvar.php',
				dataType: 'json',
				data: dados,
				success: function(response) {
					location.reload();
				}
			});
			return false;
		}); 
		
		$("#etapas").on('click','a[rel=excluir]',function(ev) {
			var apagar = confirm('Confirma exclusão do registro?');
			if (!apagar){
				return false;
			}

			valor = $(this).attr("valor");
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'etapa_excluir.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
						if (response.success == true) {
							$.ajax({
								type: 'POST',
								url: 'etapa_ordem.php',
								dataType: 'json',
								data: {id: valor, s: 'd'},
								success: function(response) {
									location.reload();
								}
							});
						}
					}
				});
				return false;
			}
			
		});
			
		$("#etapas").on('click','a[rel=ativar]',function(ev) {
			valor = $(this).attr("valor");
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'etapa_ativar.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
						location.reload();
					}
				});
				return false;
			}
			
		});
			
			
		$("#etapas").on('click','a[rel=sobe]',function(ev) {
			valor = $(this).attr("valor");
			ordem = $(this).attr("ordem");
			idprova = <?php echo $idprova; ?>;
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'etapa_ordem.php',
					dataType: 'json',
					data: {id: valor, s: 's', ordem: ordem, idprova: idprova, l: 2},
					success: function(response) {
						location.reload();
					}
				});
				return false;
			}
			
		});
			
		$("#etapas").on('click','a[rel=desce]',function(ev) {
			valor = $(this).attr("valor");
			ordem = $(this).attr("ordem");
			idprova = <?php echo $idprova; ?>;
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'etapa_ordem.php',
					dataType: 'json',
					data: {id: valor, s: 'd', ordem: ordem, idprova: idprova, l: 2},
					success: function(response) {
						location.reload();
					}
				});
				return false;
			}
			
		});
			
			
			
		$("#etapas").on('click','a[rel=questoes]',function(ev) {
			valor = $(this).attr("valor");
			
			if (valor != null) {
				$('<form action="questao.php" method="post"><input type="hidden" name="idetapa" value="' + valor + '"></input></form>').appendTo('body').submit().remove();
			}
			
		});
			
			
		$("a[rel=popup]").click(function(ev) {
			ev.preventDefault();
            $("input[name=id]").val('');
            $("input[name=nome]").val('');
            $("input[name=descricao]").val('');
            $("input[name=observacao]").val('');
			var id = $(this).attr("href");
			var alturaTela = $(document).height();
			var larguraTela = $(window).width();
			$('#mascara').css({'width': larguraTela, 'height': alturaTela});
			$('#mascara').fadeIn(500);
			$('#mascara').fadeTo("slow", 0.4);
			var left = ($(window).width() / 2) - ($(id).width() / 2);
			var top = ($(window).height() / 2) - ($(id).height() / 2);
			$(id).css({'top': top, 'left': left});
			$(id).show();
			$("input[name=nome]").focus();
		
		});

		$("a[rel=voltar]").click(function(ev) {
			$(location).attr("href", "prova.php");
		});
			
		
		$("#etapas").on('click','a[rel=modal]',function(ev) {
			valor = $(this).attr("valor");

			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'etapa_buscar.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
	                    $("input[name=id]").val(valor);
	                    $("input[name=nome]").val(response.nome);
	                   	$("input[name=descricao]").val(response.descricao);
			            $("textarea[name=observacao]").val(response.observacao);
					}
				});
				
			}

			ev.preventDefault();
			var id = $(this).attr("href");
			var alturaTela = $(document).height();
			var larguraTela = $(window).width();
			$('#mascara').css({'width': larguraTela, 'height': alturaTela});
			$('#mascara').fadeIn(500);
			$('#mascara').fadeTo("slow", 0.4);
			var left = ($(window).width() / 2) - ($(id).width() / 2);
			var top = ($(window).height() / 2) - ($(id).height() / 2);
			$(id).css({'top': top, 'left': left});
			$(id).show();
			$("input[name=nome]").focus();
		});

		$("#mascara").click(function() {
			$(this).hide();
			$(".window").hide();
		});

		$('.fechar').click(function(ev) {
			ev.preventDefault();
			$("#mascara").hide();
			$(".window").hide();
			$('#cadEtapa input[type=text]').each(function(){
				$(this).val('');
			});
		});

	});
</script>

<table border="0" width="100%" cellpading="0" cellspacing="0">
    <tr>
		<td colspan="3" class='td-titulo'>Etapas - 
			<?php 
				$sql = "select nome from prova where idprova = $idprova";
				$result = mysqli_query($conexao, $sql);
				$dados = mysqli_fetch_array($result);
				echo $dados['nome'];
			?>
		</td>
	</tr>
</table>

<div class="window" id="editar">
	<a href="#" class="fechar btn btn-gray">Fechar</a>
	<h4>Etapa</h4>
	<form id="cadEtapa" action="" method="POST">
		<input type="hidden" name="id" id="id" />
		<input type="hidden" name="idprova" id="idprova" value="<?php echo $idprova; ?>" />
		<label>Nome:</label> <input type="text" size="60" maxlength="120" name="nome" id="nome" />
		<label>Descricao:</label> <input type="text" size="60" maxlength="250" name="descricao" id="descricao" />
		<label>Observa&ccedil;&atilde;o:</label>
		<textarea rows="4" cols="60" name="observacao" id="observacao"></textarea>
		<br/><br/>
		<input type="button" value="Salvar" id="salvar" />
	</form>
</div>

<?php if ($_acesso == '1') echo "<a href='#editar' rel='popup' class='btn btn-blue'>Incluir Registro</a>"; ?> <a href='#voltar' rel='voltar' class='btn btn-gray'>Voltar</a><br><br>

<table id="etapas" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Descri&ccedil;&atilde;o</th>
            <th>In&iacute;cio</th>
            <th>Fim</th>
            <th>Divulga&ccedil;&atilde;o</th>
            <th>Ordem</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Nome</th>
            <th>Descri&ccedil;&atilde;o</th>
            <th>In&iacute;cio</th>
            <th>Fim</th>
            <th>Divulga&ccedil;&atilde;o</th>
            <th>Ordem</th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
		<?php
		    require_once ('conexao.php');
		    
		    $x = 0;
		
		    $sql = "select 
		    		idetapa,
		    		nome, 
		    		descricao, 
					ordem,
					status,
					date_format(inicio, '%d/%m/%Y %H:%i:%s') as inicio,
					date_format(fim, '%d/%m/%Y %H:%i:%s') as fim,
					date_format(divulgacao, '%d/%m/%Y %H:%i:%s') as divulgacao
		    	from 
		    		etapa
		    	where 
		    		data_exclusao is null 
		    	and
		    		prova_idprova = $idprova
		    	order by 
		    		ordem";
		    $result = mysqli_query($conexao, $sql)
		    or die("Nao foi possivel conectar no banco de dados!");
			
			$qtdR = mysqli_num_rows($result);
		
		    while ( $linha = mysqli_fetch_array ( $result ) ) {
		    	$x++;
		        $idetapa = $linha['idetapa'];
		        $nome = $linha['nome'];
		        $descricao = $linha['descricao'];
		        $inicio = $linha['inicio'];
		        $fim = $linha['fim'];
		        $divulgacao = $linha['divulgacao'];
		        $ordem = $linha['ordem'];
		        $status = $linha['status'];

		        echo "<tr>
		                <td width='160'>
		                    $nome
		                </td>
		                <td>
		                    $descricao
		                </td>
		                <td width='120' align='center'>
		                    $inicio
		                </td>
		                <td width='120' align='center'>
		                    $fim
		                </td>
		                <td width='120' align='center'>
		                    $divulgacao
		                </td>
		                <td width='10' align='center'>
		                    $ordem
		                </td>
		                <td align='center' width='80'>";
		                	
		       	if ($_acesso == '1') {
		       		if ($x > 1) echo "<a href='#sobe' rel='sobe' title='Subir' valor='$idetapa' ordem='$ordem'><img src='imagens/up.png' width='36' height='36' border='0'></a>";
		       		if ($x < $qtdR) echo "<a href='#desce' rel='desce' title='Descer' valor='$idetapa' ordem='$ordem'><img src='imagens/down.png' width='36' height='36' border='0'></a>";
		       	}
	
		        echo "</td>
		        	<td align='center' width='300'>
		        		<a href='#questoes' rel='questoes' title='Quest&otilde;es' valor='$idetapa' class='btn btn-green'>Quest&otilde;es</a>";
		                	
		       	if ($_acesso == '1') {
		       		echo " <a href='#editar' rel='modal' title='Editar' valor='$idetapa' class='btn btn-yellow'>Editar</a>
		       			<a href='#excluir' rel='excluir' title='Excluir' valor='$idetapa' class='btn btn-red'>Excluir</a>";
				}
		       	elseif ($_acesso == '10') {
		       		if ($status != 1) 
	       				echo " <a href='#ativar' rel='ativar' title='Definir ativa' valor='$idetapa' class='btn btn-blue'>Ativar</a>";
	       		}
		       		
	
		        echo "</td>
		            </tr>";

			}
		?>
    </tbody>
</table>
<div id="mascara"></div>
<?php
    require_once ('rodape.php');
?>
