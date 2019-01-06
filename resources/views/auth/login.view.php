<form>
	<h2>Login Form</h2>
	<?php mp_form_fields( 'ajax', 'unlock', MPEngine\BillingFox\BillingFoxAPI::class ); ?>
	<button type="button" data-mp-btn="unlock">Login</button>
</form>