<?php
ini_set('display_errors', '0');
ob_start();
session_start();

if (!isset ($_SESSION["login"]))
    header("Location: index.php?erro=1");

ob_flush();

switch ($_GET['erro']) {
	case 1:
		echo '<script language="Javascript">
				alert("Senha ou usuario invalidos!");
			</script>';
		break;
	case 2:
		echo '<script language="Javascript">
				alert("Senha alterada com sucesso!");
			</script>';
		break;
	case 3:
		echo '<script language="Javascript">
				alert("Senha nao pode ser alterada!");
			</script>';
		break;
}

require_once ('cabecalho.php');
?>

<script>
    function validadados()
    {
    
        if (formulario.novasenha.value == '')
        {
            alert("Informe a nova senha!");
            return (false);
        }
				
        if (formulario.novasenha.value != formulario.confirmasenha.value)
        {
            alert("Confirme a senha!");
            return (false);
        }
				
	var retorno = confirm('Confirma alteracao da senha?');
	return (retorno);
    }

</script>

<form action="senha_alterar.php" method="post" name="formulario" onSubmit="return validadados()">
<table border="0" width="100%" cellpading="0" cellspacing="0">
    <tr>
        <td colspan='2' align='center' class='td-titulo'>Alterar Senha</th>
    <tr><td></td></tr>
    <tr>
		<td>
		    <input type="hidden" name="login" value="<?php echo $_SESSION['login']; ?>">
		</td>
    </tr>
	<tr>
		<td width='150'>
		    Senha atual
		</td>
		<td>
		    <input type="password" name="senhaatual" maxlength="10">
		</td>
    </tr>
    <tr>
		<td width='150'>
		    Nova senha </label>
	</td>
		<td>
		    <input type="password" name="novasenha" maxlength="10">
		</td>
    </tr>
    <tr>
		<td width='150'>
		    Confirmar nova senha
		</td>
		<td>
		    <input type="password" name="confirmasenha" maxlength="10">
		</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
		<td colspan="2">
		    <input type="submit" value="Salvar">
		    <input type="reset" value="Cancelar" onClick="location.href='main.php'">
		</td>
    </tr>
</table>
</form>

<?php
	require_once ('rodape.php');
?>
