<?php
	foreach ($_REQUEST as $index=>$valor){
		$_REQUEST[$index] = limpa_sqlinjection($valor);
	}

	foreach ($_GET as $index=>$valor){
		$_GET[$index] = limpa_sqlinjection($valor);
	}
	
	foreach ($_POST as $index=>$valor){
		$_POST[$index] = limpa_sqlinjection($valor);
	}

	function limpa_sqlinjection($var){
		$var = trim($var);
		$var = stripslashes($var);
		$var = htmlspecialchars($var);
		return $var;
	}
?>

