<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo MP_PLUGIN_LONG_NAME . ' &mdash; Installer Wizard' ;?></title>
		<?php do_action( 'admin_print_styles' ); ?>
		<?php do_action( 'admin_head' ); ?>
    </head>

    <body>
		<div class="mp-setup-wrap">
			<ul class="mp-setup__steps">
				<li class="step" data-mp-step="intro">Intro</li>
				<li data-mp-step="api">BillingFox API Setup</li>
				<li data-mp-step="woo">WooCommerce Setup</li>
				<li data-mp-step="done">All Done!</li>
			</ul>

			<div class="mp-setup">
				<form>
					<?php mp_form_fields( 'ajax', 'setup', $this ); ?>
					<div class="mp-setup__content" data-mp-step="intro">
						<h1>Welcome</h1>
						<h3>And Thanks For installing <?php echo MP_PLUGIN_LONG_NAME; ?></h3>

						<p><?php echo MP_PLUGIN_LONG_NAME; ?> is an account credits service built to make micro-transactions on the WordPress platform simple, and seamless. Protect articles, audio & video players, and use virtual credits in your WooCommerce store if you like.</p>
						<p>What does a BillingFox cost? <strong>1 Credit = $<?php echo MP_BF_PRICE; ?></strong></p>
						<p>So let's get started!</p>
					</div>

					<div class="mp-setup__content" data-mp-step="api" style="display: none">
						<h1>BillingFox API Settings</h1>

						<div class="mp-setup-group">
							<input type="text" class="mp-setup-input" name="api[key]" placeholder="Enter your BillingFox API Key">
							<button type="button" class="mp-setup-button mp-setup-button--secondary" data-mp-validate-api>Validate</button>
						</div>

						<div class="mp-setup-group mp-setup-group--hidden">
							<div class="mp-checkbox-wrap">
								<input type="checkbox" name="api[testMode]" value="yes" checked>
								<div class="mp-checkbox-toggler">
									<label>API Test Mode</label>
								</div>
							</div>
							<div class="mp-checkbox-wrap">
								<input type="checkbox" name="api[debug]" value="yes">
								<div class="mp-checkbox-toggler">
									<label>Enable Debugging</label>
								</div>
							</div>
						</div>
					</div>

					<div class="mp-setup__content" data-mp-step="woo" style="display: none">
						<h1>WooCommerce Settings</h1>

						<p>WooCommerce setup</p>
					</div>

					<div class="mp-setup__content" data-mp-step="done" style="display: none">
						<h1>DONE</h1>
						<h3>Ready to create your first metered post!</h3>

						<p>Let's get started!</p>
					</div>

					<div class="mp-setup__action">
						<input type="hidden" name="redirect" value="<?php echo admin_url( '?page=' . MP_PLUGIN_SLUG ); ?>">
						<button button="type" class="mp-setup-button" data-mp-setup-btn>Next</button>
					</div>
				</form>
			</div>

			<a href="<?php echo admin_url( '?page=' . MP_PLUGIN_SLUG ); ?>" class="mp-setup__skip">Not right now</a>
		</div>

		<?php do_action( 'admin_print_scripts' ); ?>
    </body>
</html>