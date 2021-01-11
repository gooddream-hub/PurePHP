

<? if($_GET['function'] == 'returnURL'): ?>

	<input type='hidden' name='token' id='token' value="<?=$_GET['token']?>" >
	<input type='hidden' name='PayerID' id='PayerID' value="<?=$_GET['PayerID']?>" >

	<script>
	var paypal_token = document.getElementById('token').value;
	var paypal_payer = document.getElementById('PayerID').value;
	window.onunload = function (e) {
		opener.closePaypalLoad();
		opener.getPaypalData(paypal_token, paypal_payer);
	}
	window.close();
	</script>

<?	elseif($_GET['function'] == 'cancelURL'): ?>

	<script>
	window.onunload = function (e) {
		opener.closePaypalLoad();
	}
	window.opener.closePaypalLoad();
	window.close();
	</script>

<? endif; ?>
