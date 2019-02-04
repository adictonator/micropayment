<span>Email: <?php echo $user['email']; ?></span>
<span>Credits: <?php echo $user['balances']['available']; ?></span>
<form>
	<?php mp_form_fields( 'ajax', 'recharge', $this ); ?>
	<button type="button" data-mp-btn="recharge" class="mp-front-button">Recharge</button>
</form>