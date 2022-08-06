<?php
	ini_set('display_errors', '0');
session_start();                                                                                                                                             
if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

require_once ('cabecalho.php');

?>

<script type="text/javascript" language="javascript">
    function valida_exc() {
        var retorno = confirm('Confirma exclusão do registro?');

        return (retorno);
    }
    
	$(document).ready(function() {

		$('#equipes').DataTable({
			language: {
			    "url": "js/DataTables/pt-br.json"
			},
			dom: 'lBfrtip',
			buttons: [
	            'csv', 'excel', 'pdf'
	        ],
			lengthMenu: [[-1, 5, 50, 100], ["Todos", 5, 50, 100]],
    		order: [[ 0, "asc" ]],
			"aoColumns": [
				{},
				{},
				{},
				{},
				{},
				{ "bSortable": false },
				{},
				{ "bSortable": false }
			]
		});
		
		$('#salvar').click(function() {
			var dados = $('#cadEquipe').serialize();
			
			if ($("input[name=id]").val() != "") {
				var r=confirm("Alterar o registro selecionado?");

				if (r==false) {
					return false;
				}
			}

			$.ajax({
				type: 'POST',
				url: 'equipe_salvar.php',
				dataType: 'json',
				data: dados,
				success: function(response) {
					location.reload();
				}
			});
			return false;
		}); 
		
		$("#equipes").on('click','a[rel=excluir]',function(ev) {
			valor = $(this).attr("valor");
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'equipe_excluir.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
						if (response.success == true) {
							location.reload();
						}
					}
				});
				return false;
			}
			
		});
			
		$("a[rel=popup]").click(function(ev) {
			ev.preventDefault();
            $("input[name=id]").val('');
            $("input[name=nome]").val('');
            $("input[name=escola]").val('');
            $("input[name=capitao]").val('');
            $("input[name=responsavel]").val('');
            $("input[name=usuario]").val('');
            $("select[name=unidade]").val('');
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
		
		$("#equipes").on('click','a[rel=modal]',function(ev) {
			valor = $(this).attr("valor");

			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'equipe_buscar.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
	                    $("input[name=id]").val(valor);
	                    $("input[name=nome]").val(response.nome);
	                   	$("input[name=escola]").val(response.escola);
			            $("input[name=capitao]").val(response.capitao);
			            $("input[name=responsavel]").val(response.responsavel);
			            $("input[name=usuario]").val(response.usuario);
			            $("select[name=unidade]").val(response.unidade);
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
			$('#cadEquipe input[type=text]').each(function(){
				$(this).val('');
			});
		});

	});
</script>

<table border="0" width="100%" cellpading="0" cellspacing="0">
    <tr>
		<td colspan="3" class='td-titulo'>Equipes</td>
	</tr>
</table>

<div class="window" id="editar">
	<a href="#" class="fechar btn btn-gray">Fechar</a>
	<h4>Equipe</h4>
	<form id="cadEquipe" action="" method="POST">
		<input type="hidden" name="id" id="id" />
		<label>Nome:</label><input type="text" size="60" maxlength="120" name="nome" id="nome" />
		<label>Escola:</label> <input type="text" size="60" maxlenght="16" name="escola" id="escola" />
		<label>Capit&atilde;o:</label> <input type="text" size="60" maxlenght="60" name="capitao" id="capitao" />
		<label>Respons&aacute;vel:</label> <input type="text" size="60" maxlenght="60" name="responsavel" id="responsavel" />
		<label>Usu&aacute;rio:</label> <input type="text" size="20" maxlenght="16" name="usuario" id="usuario"/>
		<label>Unidade</label>
		<select name="unidade" id="unidade">
			<option value="">-- Selecione uma op&ccedil;&atilde;o--</option>
			<option value="B">Bag&eacute;</option>
			<option value="C">Caxias do Sul</option>
			<option value="G">Get&uacute;lio Vargas</option>
			<option value="P">Passo Fundo</option>
		</select>
		<br/><br/>
		<input type="button" value="Salvar" id="salvar" />
	</form>
</div>

<?php if ($_acesso == '1') echo "<a href='#editar' rel='popup' class='btn btn-blue'>Incluir Registro</a>"; ?><br><br>

<table id="equipes" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Escola</th>
            <th>Capit&atilde;o</th>
            <th>Respons&aacute;vel</th>
            <th>Usu&aacute;rio</th>
            <th>Senha</th>
            <th>Unidade</th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Nome</th>
            <th>Escola</th>
            <th>Capit&atilde;o</th>
            <th>Respons&aacute;vel</th>
            <th>Usu&aacute;rio</th>
            <th>Senha</th>
            <th>Unidade</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
		<?php
		    require_once ('conexao.php');
		
		    $sql = "select 
		    		e.idequipe, 
		    		e.nome, 
		    		e.escola, 
		    		e.capitao, 
		    		e.responsavel, 
		    		u.login as usuario,
		    		e.senha,
		    		e.unidade
		    	from 
		    		equipe e
		    		left join usuario u on u.idusuario = e.usuario_idusuario
		    	where 
		    		e.data_exclusao is null 
		    	order by 
		    		e.nome";
		    $result = mysqli_query($conexao, $sql)
		    or die("Nao foi possivel conectar no banco de dados!");
		
			$unidades['B'] = "Bag&eacute;";
			$unidades['C'] = "Caxias do Sul";
			$unidades['G'] = "Get&uacute;lio Vargas";
			$unidades['P'] = "Passo Fundo";
		
		    while ( $linha = mysqli_fetch_array ( $result ) ) {
		        $idequipe = $linha['idequipe'];
		        $nome = $linha['nome'];
		        $escola = $linha['escola'];
		        $capitao = $linha['capitao'];
		        $responsavel = $linha['responsavel'];
		        $usuario = $linha['usuario'];
		        $senha = $linha['senha'];
		        $unidade = $unidades[$linha['unidade']];
		        

		        echo "<tr>
		                <td>
		                    $nome
		                </td>
		                <td width='140'>
		                    $escola
		                </td>
		                <td width='140'>
		                    $capitao
		                </td>
		                <td width='140'>
		                    $responsavel
		                </td>
		                <td width='20' align='center'>
		                    $usuario
		                </td>
		                <td width='20' align='center'>
		                    $senha
		                </td>
		                <td width='20' align='center'>
		                    $unidade
		                </td>
		                <td align='center' width='140'>";
		                
		       	if ($_acesso == '1') 
		       		echo "<a href='#editar' rel='modal' title='Editar' valor='$idequipe' class='btn btn-yellow'>Editar</a>
		                <a href='#excluir' rel='excluir' title='Excluir' onClick='return valida_exc()' valor='$idequipe' class='btn btn-red'>Excluir</a>";
	
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
