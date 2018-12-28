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
						<h3>Lorem, ipsum dolor.</h3>

						<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quos illum ea iusto nihil, provident corrupti autem sapiente molestiae tempora adipisci, suscipit cumque facere, ex excepturi sit debitis? Cum, sed autem!</p>
					</div>

					<div class="mp-setup__content" data-mp-step="api" style="display: none">
						<h1>BillingFox API Settings</h1>

						<div class="mp-setup-group">
							<input type="text" class="mp-setup-input" name="apiKey" placeholder="Enter your BillingFox API Key">
							<button type="button" class="mp-setup-button mp-setup-button--secondary" data-mp-validate-api>Validate</button>
						</div>

						<div class="mp-setup-group mp-setup-group--hidden">
							<div class="mp-checkbox-wrap">
								<input type="checkbox" name="api[mode]" value="yes" checked>
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

						<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quos illum ea iusto nihil, provident corrupti autem sapiente molestiae tempora adipisci, suscipit cumque facere, ex excepturi sit debitis? Cum, sed autem!</p>
					</div>

					<div class="mp-setup__content" data-mp-step="done" style="display: none">
						<h1>DONE</h1>
						<h3>Lorem, ipsum dolor.</h3>

						<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quos illum ea iusto nihil, provident corrupti autem sapiente molestiae tempora adipisci, suscipit cumque facere, ex excepturi sit debitis? Cum, sed autem!</p>
					</div>

					<div class="mp-setup__action">
						<input type="hidden" name="redirect" value="<?php echo admin_url( '?page=' . MP_PLUGIN_SLUG ); ?>">
						<button button="type" class="mp-setup-button" data-mp-setup-btn>Next</button>
					</div>
				</form>
			</div>
		</div>

		<?php do_action( 'admin_print_scripts' ); ?>
    </body>
</html>