<html>
<head>
    <title>.: GP do Conhecimento - Faculdades IDEAU :.</title>
    <link rel="STYLESHEET" type="text/css" href="css/estilo.css" media="screen">
    <link rel="STYLESHEET" type="text/css" href="css/estilo.css" media="print">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
	<script type="text/javascript" src="js/DataTables/datatables.js"></script>
	<script type="text/javascript" src="js/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="js/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" src="js/DataTables/datatables.min.js"></script>
	<link href="js/datetimepicker-master/build/jquery.datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <script src="js/datetimepicker-master/build/jquery.datetimepicker.full.js" type="text/javascript"></script>
	
	<style>
		label{
			display: block;
   		}
		.window{
			display:none;
			position:absolute;
			left:0;
			top:0;
			background:#FFF;
			z-index:9900;
			padding:10px;
			border-radius:10px;
		}
		#mascara{
			display:none;
			position:absolute;
			left:0;
			top:0;
			z-index:9000;
			background-color:#000;
		}
		.fechar{ text-align:right;}
	</style>
</head>
<body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0" onload="vertical();">
<?php
	require_once('limpa_injection.php');
	require_once('js/menu.js');
//	require_once('js/script.js');
	require_once('acesso.php');
	ini_set('display_errors', '0');
	
?>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tr>
        <td colspan='2' align='center' bgcolor='#FFFFFF' style="background: url('') no-repeat; height: 80px;">
        	<br>
            <h1>GP do Conhecimento - Faculdades IDEAU</h1>
        </td>
    </tr>
    <tr>
        <td width="140" align="left" valign="top" bgcolor="#DDFFDD">
			<ul id="nav" class="menu">
		       	<?php
		       		if ($_acesso == '1') 
		       			echo "<li class='submenu'>
								<a href='equipe.php'>Equipes</a>
							</li>
							<li class='submenu'>
								<a href='usuario.php'>Usu&aacute;rios</a>
							</li>
							<li class='submenu'>
								<a href='prova.php'>Provas</a>
							</li>";
					
					if ($_acesso == '10')
		       			echo "<li class='submenu'>
								<a href='direcao.php'>Dire&ccedil;&atilde;o de Prova</a>
							</li>";

					if ($_acesso == '100')
		       			echo "<li class='submenu'>
								<a href='painel.php'>Administra&ccedil;&atilde;o</a>
							</li>";
				?>
				<li class="submenu">
					<a href="senha.php">Alterar Senha</a>
				</li>
				<li class="submenu">
					<a href="logoff.php">Sair</a>
				</li>
			</ul>
        </td>
        <td valign="top">
