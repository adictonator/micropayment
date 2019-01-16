<div class="mp-wall">
<?php echo $this->viewMessage; ?>

	<form>
		<?php mp_form_fields( 'ajax', 'unlock', $this ); ?>
		<input type="hidden" value="<?php echo get_the_ID(); ?>" name="pid">
		<button type="button" data-mp-btn="unlock">Pay <?php echo $this->shortcodeContents->attrs->price; ?> Credits to Unlock</button>
	</form>
</div>

<div class="mp-auth-popup"></div>

<script>
mp.hasWall()
</script>