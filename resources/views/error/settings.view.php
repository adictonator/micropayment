<div class="wrap mp-wrap">
	<h1>Oops!</h1>
	<div>
		<h3>We have encountered a problem with <?php echo MP_PLUGIN_LONG_NAME; ?> data</h3>
		<p>Most likely the data in the database has been compromised and needs resetting.</p>
		<a href="<?php echo admin_url( '?page=' . MP_PLUGIN_SLUG . '-setup' ); ?>" class="button-primary">Process setup again!</a>
	</div>
</div>