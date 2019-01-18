<div class="mp-wall" data-mp-sid="<?php echo $this->shortcodeContents->attrs->uid; ?>">
<?php echo $this->viewMessage; ?>

	<form>
		<?php mp_form_fields( 'ajax', 'unlock', $this ); ?>
		<input type="hidden" value="<?php echo $this->shortcodeContents->attrs->uid; ?>" name="sid">
		<button type="button" data-mp-btn="unlock">Pay <?php echo $this->shortcodeContents->attrs->price; ?> Credits to Unlock</button>
	</form>
</div>

<div class="mp-auth-popup"></div>