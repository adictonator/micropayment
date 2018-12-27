<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo MP_PLUGIN_LONG_NAME . ' &mdash; Installer Wizard' ;?></title>
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
		<?php do_action( 'admin_print_styles' ); ?>
		<?php do_action( 'admin_head' ); ?>
    </head>

    <body>
		<div class="mp-setup-wrap">
			<div class="mp-setup">
				<ul class="mp-setup__steps">
					<li class="active" data-mp-step="into">Intro</li>
					<li data-mp-step="api">BillingFox API Setup</li>
					<li data-mp-step="woo">WooCommerce Setup</li>
					<li data-mp-step="done">All Done!</li>
				</ul>

				<div class="mp-setup__content" data-mp-step="intro">
					<h1>Welcome</h1>
					<h3>Lorem, ipsum dolor.</h3>

					<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quos illum ea iusto nihil, provident corrupti autem sapiente molestiae tempora adipisci, suscipit cumque facere, ex excepturi sit debitis? Cum, sed autem!</p>
				</div>

				<div class="mp-setup__content" data-mp-step="api" style="display: none">
					<h1>Welcome</h1>
					<h3>Lorem, ipsum dolor.</h3>

					<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quos illum ea iusto nihil, provident corrupti autem sapiente molestiae tempora adipisci, suscipit cumque facere, ex excepturi sit debitis? Cum, sed autem!</p>
				</div>

				<div class="mp-setup__content" data-mp-step="woo" style="display: none">
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
					<button class="mp-setup-button" data-mp-tostep="api">Next</button>
				</div>
			</div>
		</div>

		<?php do_action( 'admin_print_scripts' ); ?>
    </body>
</html>