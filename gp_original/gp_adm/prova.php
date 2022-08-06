<?php
	ini_set('display_errors', '0');
session_start();                                                                                                                                             
if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

require_once ('cabecalho.php');

?>

<script type="text/javascript" language="javascript">
	$(document).ready(function() {

		$('#provas').DataTable({
			language: {
			    "url": "js/DataTables/pt-br.json"
			},
			dom: 'lBfrtip',
			buttons: [
	            'csv', 'excel', 'pdf'
	        ],
			lengthMenu: [[-1, 5, 50, 100], ["Todos", 5, 50, 100]],
    		order: [[ 0, "desc" ]],
			"aoColumns": [
				{},
				{},
				{},
				{ "bSortable": false }
			]
		});

		$('#salvar').click(function() {
			var dados = $('#cadProva').serialize();
			
			if ($("input[name=id]").val() != "") {
				var r=confirm("Alterar o registro selecionado?");

				if (r==false) {
					return false;
				}
			}

			$.ajax({
				type: 'POST',
				url: 'prova_salvar.php',
				dataType: 'json',
				data: dados,
				success: function(response) {
					location.reload();
				}
			});
			return false;
		}); 
		
		$("#provas").on('click','a[rel=excluir]',function(ev) {
			var apagar = confirm('Confirma exclusão do registro?');
			if (!apagar){
				return false;
			}

			valor = $(this).attr("valor");
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'prova_excluir.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
						location.reload();
					}
				});
				return false;
			}
			
		});
			
		$("#provas").on('click','a[rel=etapas]',function(ev) {
			valor = $(this).attr("valor");
			
			if (valor != null) {
				$('<form action="etapa.php" method="post"><input type="hidden" name="idprova" value="' + valor + '"></input></form>').appendTo('body').submit().remove();
			}
			
		});
			
		$("#provas").on('click','a[rel=visualizar]',function(ev) {
			valor = $(this).attr("valor");
			
			if (valor != null) {
				$('<form action="questoes.php" method="post"><input type="hidden" name="idprova" value="' + valor + '"></input></form>').appendTo('body').submit().remove();
			}
			
		});
			
		$("#provas").on('click','a[rel=ativar]',function(ev) {
			valor = $(this).attr("valor");
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'prova_ativar.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
						location.reload();
					}
				});
				return false;
			}
			
		});
			
			
		$("a[rel=popup]").click(function(ev) {
			ev.preventDefault();
            $("input[name=id]").val('');
            $("select[name=unidade]").val('');
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
			//var top = ($(window).height() / 2) - ($(id).height() / 2);
			var top = 10;
			$(id).css({'top': top, 'left': left});
			$(id).show();
			$("input[name=nome]").focus();
		
		});
		
		$("#provas").on('click','a[rel=modal]',function(ev) {
			valor = $(this).attr("valor");

			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'prova_buscar.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
	                    $("input[name=id]").val(valor);
	                    $("select[name=unidade]").val(response.unidade);
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
			//var top = ($(window).height() / 2) - ($(id).height() / 2);
			var top = 10;
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
			$('#cadProva input[type=text]').each(function(){
				$(this).val('');
			});
		});

	});
</script>

<table border="0" width="100%" cellpading="0" cellspacing="0">
    <tr>
		<td colspan="3" class='td-titulo'>Provas</td>
	</tr>
</table>

<div class="window" id="editar">
	<a href="#" class="fechar btn btn-gray">Fechar</a>
	<h4>Prova</h4>
	<form id="cadProva" action="" method="POST">
		<input type="hidden" name="id" id="id" />
		<label>Unidade</label>
		<select name="unidade" id="unidade">
			<option value="">-- Selecione uma op&ccedil;&atilde;o--</option>
			<option value="B">Bag&eacute;</option>
			<option value="C">Caxias do Sul</option>
			<option value="G">Get&uacute;lio Vargas</option>
			<option value="P">Passo Fundo</option>
		</select>
		<label>Nome:</label> <input type="text" size="60" maxlength="120" name="nome" id="nome" />
		<label>Descricao:</label> <input type="text" size="60" maxlength="250" name="descricao" id="descricao" />
		<label>Observa&ccedil;&atilde;o:</label>
		<textarea rows="4" cols="60" name="observacao" id="observacao"></textarea>
		<br/><br/>
		<input type="button" value="Salvar" id="salvar" />
	</form>
</div>

<?php if ($_acesso == '1') echo "<a href='#editar' rel='popup' class='btn btn-blue'>Incluir Registro</a>"; ?><br><br>

<table id="provas" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Unidade</th>
            <th>Nome</th>
            <th>Descri&ccedil;&atilde;o</th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Unidade</th>
            <th>Nome</th>
            <th>Descri&ccedil;&atilde;o</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
		<?php
		    require_once ('conexao.php');
		
		    $sql = "select 
		    		idprova,
		    		unidade,
		    		nome, 
		    		descricao, 
		    		status
		    	from 
		    		prova
		    	where 
		    		data_exclusao is null 
		    	order by 
		    		nome";
		    $result = mysqli_query($conexao, $sql)
		    or die("Nao foi possivel conectar no banco de dados!");
		
			$unidades['B'] = "Bag&eacute;";
			$unidades['C'] = "Caxias do Sul";
			$unidades['G'] = "Get&uacute;lio Vargas";
			$unidades['P'] = "Passo Fundo";

		    while ( $linha = mysqli_fetch_array ( $result ) ) {
		        $idprova = $linha['idprova'];
		        $nome = $linha['nome'];
		        $descricao = $linha['descricao'];
		        $status = $linha['status'];
		        $unidade = $unidades[$linha['unidade']];

		        echo "<tr>
		                <td width='120' align='center'>
		                    $unidade
		                </td>
		                <td width='160'>
		                    $nome
		                </td>
		                <td>
		                    $descricao
		                </td>
		                <td align='center' width='380'>
		                	<a href='#etapas' rel='etapas' title='Etapas' valor='$idprova' class='btn btn-green'>Etapas</a>
		                	<a href='#visualizar' rel='visualizar' title='Visualizar quest&otilde;es' valor='$idprova' class='btn btn-blue'>Quest&otilde;es</a>";
		                
		       	if ($_acesso == '1') {
		       		echo " <a href='#editar' rel='modal' title='Editar' valor='$idprova' class='btn btn-yellow'>Editar</a>
		       			<a href='#excluir' rel='excluir' title='Excluir' valor='$idprova' class='btn btn-red'>Excluir</a>";
				}
	       		elseif ($_acesso == '10') {
	       			if ($status != 1)
	       				echo " <a href='#ativar' rel='ativar' title='Definir ativa' valor='$idprova' class='btn btn-blue'>Ativar</a>";
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
