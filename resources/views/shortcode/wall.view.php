<div class="mp-wall" data-mp-sid="<?php echo $this->shortcodeContents->attrs->uid; ?>">
	<form>
		<?php mp_form_fields( 'ajax', 'unlock', $this ); ?>
		<input type="hidden" value="<?php echo $this->shortcodeContents->attrs->uid; ?>" name="sid">
		<button type="button" class="mp-front-button" data-mp-btn="unlock">Pay <?php echo $this->shortcodeContents->attrs->price; ?> Credits to Unlock</button>
	</form>
</div>
