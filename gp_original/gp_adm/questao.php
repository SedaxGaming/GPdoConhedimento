<?php
	ini_set('display_errors', '0');
session_start();                         
                                                                                                                    
if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

if (!isset ($_POST["idetapa"]))
    header("Location: prova.php");
    
$idetapa = $_POST['idetapa'];

require_once ('cabecalho.php');

$sql = "select prova_idprova from etapa where idetapa = $idetapa";
$result = mysqli_query($conexao, $sql);
$dados = mysqli_fetch_array($result);
$idprova = $dados['prova_idprova'];

?>
<!-- TinyMCE -->
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
	    language : "pt",
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1:
"bold,italic,underline,strikethrough,sub,sup,subscript,superscript,justifyleft,justifycenter,justifyright,justifyfull,cleanup,image,table,formatselect,fontselect,fontsizeselect,forecolor,backcolor,fullscreen",

		forced_root_block : "",

		// Theme options
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",


		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "none",
		theme_advanced_resizing : true,

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",
	    file_browser_callback : "tinyBrowser",
		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->

<script type="text/javascript" language="javascript">
	$(document).ready(function() {

		$('#questoes').DataTable({
			language: {
			    "url": "js/DataTables/pt-br.json"
			},
			dom: 'lBfrtip',
			buttons: [
	            'csv', 'excel', 'pdf'
	        ],
			lengthMenu: [[-1, 5, 50, 100], ["Todos", 5, 50, 100]],
    		order: [[ 1, "asc" ]],
			"aoColumns": [
				{},
				{},
				{ "bSortable": false },
				{ "bSortable": false }
			]
		});

		$('#salvar').click(function() {
			tinyMCE.triggerSave();
			var dados = $('#cadQuestao').serialize();
			
			if ($("input[name=id]").val() != "") {
				var r=confirm("Alterar o registro selecionado?");

				if (r==false) {
					return false;
				}
			}
			
			if ($("select[name=correta]").val() == "") {
				alert("Selecione a questão correta!");
				$("select[name=correta]").focus();
				return false;
			}


			$.ajax({
				type: 'POST',
				url: 'questao_salvar.php',
				dataType: 'json',
				data: dados,
				success: function(response) {
					location.reload();
				}
			});
			
			return false;
		}); 
		

		$("#questoes").on('click','a[rel=excluir]',function(ev) {
			var apagar = confirm('Confirma exclusão do registro?');
			if (!apagar){
				return false;
			}

			valor = $(this).attr("valor");
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'questao_excluir.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
						if (response.success == true) {
							$.ajax({
								type: 'POST',
								url: 'questao_ordem.php',
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
			
		$("#questoes").on('click','a[rel=sobe]',function(ev) {
			valor = $(this).attr("valor");
			ordem = $(this).attr("ordem");
			idetapa = <?php echo $idetapa; ?>;
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'questao_ordem.php',
					dataType: 'json',
					data: {id: valor, s: 's', ordem: ordem, idetapa: idetapa, l: 2},
					success: function(response) {
						location.reload();
					}
				});
				return false;
			}
			
		});
			
		$("#questoes").on('click','a[rel=desce]',function(ev) {
			valor = $(this).attr("valor");
			ordem = $(this).attr("ordem");
			idetapa = <?php echo $idetapa; ?>;
			
			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'questao_ordem.php',
					dataType: 'json',
					data: {id: valor, s: 'd', ordem: ordem, idetapa: idetapa, l: 2},
					success: function(response) {
						location.reload();
					}
				});
				return false;
			}
			
		});
			
			
/*			
		$("a[rel=popup]").click(function(ev) {
			$.post('questao_editar.php', {idprova: <?php echo $idprova; ?>, idetapa: <?php echo $idetapa; ?>}, function() {location.reload()});
		});
		
		$("#questoes").on('click','a[rel=modal]',function(ev) {
			valor = $(this).attr("valor");

			if (valor != null) {
				$.post('questao_editar.php', {idquestao: valor}, function() {location.reload()});
				
			}

		});
*/
		$("a[rel=popup]").click(function(ev) {
			ev.preventDefault();
            $("input[name=id]").val('');
            $("input[name=titulo]").val('');
            $("input[name=descricao]").val('');
           	$(tinyMCE.get('texto').setContent(''));
            $(tinyMCE.get('alternativa1').setContent(''));
            $(tinyMCE.get('alternativa2').setContent(''));
            $(tinyMCE.get('alternativa3').setContent(''));
            $(tinyMCE.get('alternativa4').setContent(''));
            $(tinyMCE.get('alternativa5').setContent(''));
            $("select[name=correta]").val('');
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
			$("input[name=titulo]").focus();
		
		});
		
		$("a[rel=voltar]").click(function(ev) {
			valor = <?php echo $idprova; ?>;
			
			if (valor != null) {
				$('<form action="etapa.php" method="post"><input type="hidden" name="idprova" value="' + valor + '"></input></form>').appendTo('body').submit().remove();
			}
			
		});
			
		
		$("#questoes").on('click','a[rel=modal]',function(ev) {
			valor = $(this).attr("valor");

			if (valor != null) {
				$.ajax({
					type: 'POST',
					url: 'questao_buscar.php',
					dataType: 'json',
					data: {id: valor},
					success: function(response) {
	                    $("input[name=id]").val(valor);
	                    $("input[name=titulo]").val(response.titulo);
	                   	$("input[name=descricao]").val(response.descricao);
	                   	$(tinyMCE.get('texto').setContent(response.texto));
			            $(tinyMCE.get('alternativa1').setContent(response.alternativa1));
			            $(tinyMCE.get('alternativa2').setContent(response.alternativa2));
			            $(tinyMCE.get('alternativa3').setContent(response.alternativa3));
			            $(tinyMCE.get('alternativa4').setContent(response.alternativa4));
			            $(tinyMCE.get('alternativa5').setContent(response.alternativa5));
	                   	$("select[name=correta]").val(response.correta);
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
			$("textarea[name=titulo]").focus();
		});

		$("#mascara").click(function() {
			$(this).hide();
			$(".window").hide();
		});

		$('.fechar').click(function(ev) {
			ev.preventDefault();
			$("#mascara").hide();
			$(".window").hide();
			$('#cadQuestao input[type=text]').each(function(){
				$(this).val('');
			});
			$('#cadQuestao textarea[]').each(function(){
				$(this).val('');
			});
		});

	});
</script>

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


<div id="retornao"></div>
<div class="window" id="editar" style="overflow-y: scroll; height: 550;">
	<a href="#" class="fechar btn btn-gray">Fechar</a>
	<h4>Quest&atilde;o</h4>
	<form id="cadQuestao" action="" method="POST">
		<input type="hidden" name="id" id="id" />
		<input type="hidden" name="idetapa" id="idetapa" value="<?php echo $idetapa; ?>" />
		<label>T&iacute;tulo:</label>
		<input type="text" size="100" name="titulo" id="titulo">
		<label>Descri&ccedil;&atilde;o:</label>
		<input type="text" size="100" name="descricao" id="descricao">
		<label>Enunciado:</label>
		<textarea rows="10" cols="140" name="texto" id="texto"></textarea>
		<h3>Alternativas</h3>
		<label><b>Alternativa A:</b></label>
		<textarea rows="10" cols="120" name="alternativa1" id="alternativa1"></textarea>
		<label><b>Alternativa B:</b></label>
		<textarea rows="10" cols="120" name="alternativa2" id="alternativa2"></textarea>
		<label><b>Alternativa C:</b></label>
		<textarea rows="10" cols="120" name="alternativa3" id="alternativa3"></textarea>
		<label><b>Alternativa D:</b></label>
		<textarea rows="10" cols="120" name="alternativa4" id="alternativa4"></textarea>
		<label><b>Alternativa E:</b></label>
		<textarea rows="10" cols="120" name="alternativa5" id="alternativa5"></textarea>
		<label><b>Alternativa correta</b></label>
		<select name="correta" id="correta">
			<option value="">-- Selecione --</option>
			<option value="1">A</option>
			<option value="2">B</option>
			<option value="3">C</option>
			<option value="4">D</option>
			<option value="5">E</option>
		</select>
		<br/><br/>
		<input type="button" value="Salvar" id="salvar" />
	</form>
</div>

<?php if ($_acesso == '1') echo "<a href='#editar' rel='popup' class='btn btn-blue'>Incluir Registro</a>"; ?> <a href='#voltar' rel='voltar' class='btn btn-gray'>Voltar</a><br><br>

<table id="questoes" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>T&iacute;tulo</th>
            <th>Ordem</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>T&iacute;tulo</th>
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
		    		idquestao,
		    		titulo, 
					ordem
		    	from 
		    		questao
		    	where 
		    		data_exclusao is null 
		    	and
		    		etapa_idetapa = $idetapa
		    	order by 
		    		ordem";

		    $result = mysqli_query($conexao, $sql)
		    or die("Nao foi possivel conectar no banco de dados!");
			
			$qtdR = mysqli_num_rows($result);
		
		    while ( $linha = mysqli_fetch_array ( $result ) ) {
		    	$x++;
		        $idquestao = $linha['idquestao'];
		        $titulo = $linha['titulo'];
		        $ordem = $linha['ordem'];

		        echo "<tr>
		                <td width='160'>
		                    $titulo
		                </td>
		                <td width='10' align='center'>
		                    $ordem
		                </td>
		                <td align='center' width='80'>";
		                	
		       	if ($_acesso == '1') {
		       		if ($x > 1) echo "<a href='#sobe' rel='sobe' title='Subir' valor='$idquestao' ordem='$ordem'><img src='imagens/up.png' width='36' height='36' border='0'></a>";
		       		if ($x < $qtdR) echo "<a href='#desce' rel='desce' title='Descer' valor='$idquestao' ordem='$ordem'><img src='imagens/down.png' width='36' height='36' border='0'></a>";
		       	}
	
		        echo "</td>
		        	<td align='center' width='140'>";
		                	
		       	if ($_acesso == '1') {
		       		echo "<a href='#editar' rel='modal' title='Editar' valor='$idquestao' class='btn btn-yellow'>Editar</a>
		                <a href='#excluir' rel='excluir' title='Excluir' onClick='return valida_exc()' valor='$idquestao' class='btn btn-red'>Excluir</a>";
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
