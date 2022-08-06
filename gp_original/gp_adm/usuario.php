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

		$('#usuarios').DataTable({
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
			var dados = $('#cadUsuario').serialize();
			
			if ($("input[name=id]").val() != "") {
				var r=confirm("Alterar o registro selecionado?");

				if (r==false) {
					return false;
				}
			}

			$.ajax({
				type: 'POST',
				url: 'usuario_salvar.php',
				dataType: 'json',
				data: dados,
				success: function(response) {
					location.reload();
				}
			});
			return false;
		}); 
		
		$("#usuarios").on('click','a[rel=excluir]',function(ev) {
			valor = $(this).attr("valor");
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'usuario_excluir.php',
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
            $("input[name=login]").val('');
            $("input[name=password]").val('');
            $("select[name=grupo]").val('');
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
		
		$("#usuarios").on('click','a[rel=modal]',function(ev) {
			valor = $(this).attr("valor");

			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'usuario_buscar.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
	                    $("input[name=id]").val(valor);
	                    $("input[name=nome]").val(response.nome);
	                   	$("input[name=login]").val(response.login);
			            $("input[name=password]").val('');
	                    $("select[name=grupo]").val(response.grupo);
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
			$('#cadUsuario input[type=text]').each(function(){
				$(this).val('');
			});
		});

	});
</script>

<table border="0" width="100%" cellpading="0" cellspacing="0">
    <tr>
		<td colspan="3" class='td-titulo'>Usu&aacute;rios</td>
	</tr>
</table>

<div class="window" id="editar">
	<a href="#" class="fechar btn btn-gray">Fechar</a>
	<h4>Usu&aacute;rio</h4>
	<form id="cadUsuario" action="" method="POST">
		<input type="hidden" name="id" id="id" />
		<label>Nome:</label><input type="text" size="60" maxlength="120" name="nome" id="nome" />
		<label>Login:</label> <input type="text" size="16" maxlenght="16" name="login" id="login" />
		<label>Password:</label> <input type="password" size="20" maxlenght="20" name="password" id="password" />
		<label>Grupo:</label>
			<select name="grupo">
				<option value="">-- Selecione uma op&ccedil;&atilde;o --</option>
				<option value="1">Administrador</option>
				<option value="10">Diretor de prova</option>
				<option value="100">Juiz de prova</option>
				<option value="101">Juiz (consulta)</option>
				<option value="1000">Equipe</option>
				<option value="0">Placar</option>
			</select>
		<br/><br/>
		<input type="button" value="Salvar" id="salvar" />
	</form>
</div>

<?php if ($_acesso == '1') echo "<a href='#editar' rel='popup' class='btn btn-blue'>Incluir Registro</a>"; ?><br><br>

<table id="usuarios" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Login</th>
            <th>Grupo</th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Nome</th>
            <th>Login</th>
            <th>Grupo</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
		<?php
		    require_once ('conexao.php');
		
		    $sql = "select idusuario, login, nome, grupo from usuario where data_exclusao is null order by nome";
		    $result = mysqli_query($conexao, $sql)
		    or die("Nao foi possivel conectar no banco de dados!");
		
		    while ( $linha = mysqli_fetch_array ( $result ) ) {
		        $idusuario = $linha['idusuario'];
		        $login = $linha['login'];
		        $nome = $linha['nome'];
		        $grupo = $linha['grupo'];
		        switch ($linha['grupo']) {
		        	case '1': 
		        		$grupo="Administrador";
		        		break;
		        	case '10': 
		        		$grupo="Diretor de prova";
		        		break;
		        	case '100': 
		        		$grupo="Juiz de prova";
		        		break;
		        	case '101': 
		        		$grupo="Juiz (consulta)";
		        		break;
		        	case '1000': 
		        		$grupo="Equipe";
		        		break;
		        	default: 
		        		$grupo="Placar";
		        		break;
		        }
		
		        echo "<tr>
		                <td>
		                    $nome
		                </td>
		                <td width='60'>
		                    $login
		                </td>
		                <td width='120' align='center'>
		                    $grupo
		                </td>
		                <td align='center' width='140'>";
		                
		       	if ($_acesso == '1') 
		       		echo "<a href='#editar' rel='modal' title='Editar' valor='$idusuario' class='btn btn-yellow'>Editar</a>
		                <a href='#excluir' rel='excluir' title='Excluir' onClick='return valida_exc()' valor='$idusuario' class='btn btn-red'>Excluir</a>";
	
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
