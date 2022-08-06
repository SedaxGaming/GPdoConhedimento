<?php
	ini_set('display_errors', '0');
	session_start();

	if (!isset ($_SESSION["login"]))
		header("Location: index.php?erro=1");

?>
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
<body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tr>
        <td align='center' bgcolor='#FFFFFF' style="background: url('') no-repeat; height: 80px;">
        	<br>
            <h1>GP do Conhecimento - Faculdades IDEAU</h1>
        </td>
    </tr>
</table>

<table id="resultado" class="display" cellspacing="10" cellpadding="10" width="100%">
	<thead>
		<tr>
			<th>Escola</th>
			<th>Equipe</th>
			<th>Acertos</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Escola</th>
			<th>Equipe</th>
			<th>Acertos</th>
		</tr>
	</tfoot>
	<tbody>

	<?php
		require_once("conexao.php");
						
		$sql = "select 
				e.idequipe,
				e.escola,
				e.nome as equipe,
				sum(r.acertos) as total 
			from 
				respostas r
				left join equipe e on e.idequipe = r.equipe_idequipe
			group by
				e.idequipe,
				e.escola,
				e.nome
			order by
				total desc";
				
		$result = mysqli_query($conexao, $sql)
		or die("Nao foi possivel conectar no banco de dados!");
		
		while ( $linha = mysqli_fetch_array ( $result ) ) {
			$idequipe = $linha['idequipe'];
			$escola = $linha['escola'];
			$equipe = $linha['equipe'];
			$total = $linha['total'];

			echo "<tr>
				    <td width='360' align='center'>
				        $escola
				    </td>
				    <td width='360' align='center'>
				        $equipe
				    </td>
				    <td width='40' align='center'>
				        $total
				    </td>
				</tr>";

		}
		?>
		</tbody>
	</table>
	<div style="margin-top: 20px; text-align: center;"><a href='placar.php' rel='retorno' class='btn btn-blue'>Retornar</a></div>
