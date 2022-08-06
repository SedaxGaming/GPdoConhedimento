<script type="text/javascript">
 
function ajax(url, campo, metodo)
{
	if (metodo == 'value') {
		document.getElementById(campo).value = "Carregando...";
	}
	else {
		document.getElementById(campo).innerHTML = "Carregando...";
	}
	
	req = null;
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
		req.onreadystatechange = processReqChange;
		req.open("POST",url,true);
		req.send(null);
	} 
	else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
		if (req) {
			req.onreadystatechange = processReqChange;
			req.open("GET",url,true);
			req.send();
		}
	}
	
	function processReqChange()
	{
		if (req.readyState == 4) {
			if (req.status == 200) {
	 			if (metodo == 'value') {
					document.getElementById(campo).value = req.responseText;
				}
				else {
					document.getElementById(campo).innerHTML = req.responseText;
				}
			}
			else {
				if (metodo == 'value') {
					document.getElementById(campo).value = "";
				}
				else {
					document.getElementById(campo).innerHTML = "";
				}
			}
		}
		else {
			if (metodo == 'value') {
				document.getElementById(campo).value = "Carregando...";
			}
			else {
				document.getElementById(campo).innerHTML = "Carregando...";
			}
		}
		return false;
	} 

}

</script>
