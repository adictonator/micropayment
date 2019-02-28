<div data-mp-error="<?php echo $this->errorType; ?>">
	<form>
		<?php mp_form_fields( 'ajax', 'processAuth', $this ); ?>
		<button class="mp-front-button" data-mp-btn><?php echo $this->viewMessage; ?></button>
	</form>
</div>