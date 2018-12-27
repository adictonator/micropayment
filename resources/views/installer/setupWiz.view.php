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
				<li class="active" data-mp-step="into">Intro</li>
				<li data-mp-step="micropay-billingfox-api-settings">BillingFox API Setup</li>
				<li data-mp-step="micropay-woocommerce-settings">WooCommerce Setup</li>
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

					<div class="mp-setup__content" data-mp-step="micropay-billingfox-api-settings" style="display: none">
						<h1>BillingFox API Settings</h1>

						<input type="text" name="mode" value="test" placeholder="API Endpoint">
						<input type="text" name="apiKey" placeholder="Enter your BillingFox API Key">
					</div>

					<div class="mp-setup__content" data-mp-step="micropay-woocommerce-settings" style="display: none">
						<h1>Welcome</h1>
						<h3>Lorem, ipsum dolor.</h3>

						<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quos illum ea iusto nihil, provident corrupti autem sapiente molestiae tempora adipisci, suscipit cumque facere, ex excepturi sit debitis? Cum, sed autem!</p>
					</div>

					<div class="mp-setup__content" data-mp-step="done" style="display: none">
						<h1>DONE</h1>
						<h3>Lorem, ipsum dolor.</h3>

						<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quos illum ea iusto nihil, provident corrupti autem sapiente molestiae tempora adipisci, suscipit cumque facere, ex excepturi sit debitis? Cum, sed autem!</p>
					</div>

					<div class="mp-setup__action">
						<button button="type" class="mp-setup-button" data-mp-tostep="micropay-billingfox-api-settings">Next</button>
					</div>
				</form>
			</div>
		</div>

		<?php do_action( 'admin_print_scripts' ); ?>
    </body>
</html>