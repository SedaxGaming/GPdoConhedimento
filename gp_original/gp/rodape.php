        </td>
    </tr>
</table>

<div id="footer" class="footer">
	<div class="container">
		<div id='horaAtual' class="footer-left"></div>
		<div id='logado' class="footer-center">
			Logado como: <?php echo $_SESSION['login']; ?>
		</div>
		<div id="logos" class="footer-right">
			&nbsp;
			<img src="imagens/logoIdeauPequeno.png" />
			&nbsp;
			<img src="imagens/logoCerebrandoPequeno.png" />
			&nbsp;
		</div>
	</div>
</div>
		<script language="Javascript">
			var clock = document.getElementById('horaAtual');
			setInterval(function () {
				clock.innerHTML = 'Hora atual: ' + ((new Date).toLocaleString().substr(11, 8));  
			}, 1000);
		</script>
</body>
</html>
