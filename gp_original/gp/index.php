<?php

if (isset($_GET['erro'])) {
	if ($_GET['erro'] == 1) {
		echo '<script language="Javascript">
				alert("Usuário ou senha inválida!");
			</script>';
	}
}

?>

<html>
<head>
    <title>.: GP do Conhecimento - Faculdades IDEAU :.</title>
    <link rel="STYLESHEET" type="text/css" href="css/estilo.css" media="screen">
    <link rel="STYLESHEET" type="text/css" href="css/estilo.css" media="print">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0" onload="vertical();">

<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tr>
        <td colspan='2' align='center' bgcolor='#BBDDBB'>
        	<br>
            <h1>GP do Conhecimento</h1>
        </td>
    </tr>
</table>

<form action="login.php" method="post" name="formulario">
    <table border="0" align="center">
		<tr>
			<td align="right">
                usu&aacute;rio
            </td>
            <td>
                <input type="text" name="login">
            </td>
        </tr>
        <tr>
            <td align="right">
                senha
            </td>
            <td>
                <input type="password" name="senha">
            </td>
        </tr>
        <tr>
        	<td></td>
            <td align="left">
                <input type="submit" value="ok">
            </td>
        </tr>
    </table>
</form>

<div id="footer" class="footer">
	<div class="container">
		<div id='horaAtual' class="footer-left"></div>
		<div id="logos" class="footer-right">
			&nbsp;
			<img src="imagens/logoIdeauPequeno.png" />
			&nbsp;
			<img src="imagens/logoCerebrandoPequeno.png" />
			&nbsp;
		</div>
	</div>
</div>
</body>
</html>
