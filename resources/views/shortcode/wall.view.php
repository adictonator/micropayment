<div class="mp-wall">
<?php echo $this->viewMessage; ?>

	<form>
		<?php mp_form_fields( 'ajax', 'unlock', MPEngine\BillingFox\BillingFoxAPI::class ); ?>
		<button type="button" data-mp-btn="unlock">Pay <?php echo $this->shortcodeContents->attrs->price; ?> Credits to Unlock</button>
	</form>
</div>

<div class="mp-auth-popup"></div>