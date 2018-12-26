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
        <!-- Top content -->
        <!-- <div class="top-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 form-box">
                    	<form role="form" action="" method="post" class="f1">

                    		<h3>Welcome</h3>
							<p>Micropayment.io is an account credits service built to make microtransactions on the Wordpress platform simple, and seamless. Protect articles, audio & video players, and use virtual credits in your WooCommerce store if you like.</p>
							<p>What does a BillingFox cost? 1 Credit = $0.01</p>
							<p>So let’s get started!</p>

                    		<div class="f1-steps">
                    			<div class="f1-progress">
                    			    <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3" style="width: 16.66%;"></div>
                    			</div>
                    			<div class="f1-step active">
                    				<div class="f1-step-icon"><i class="fa fa-user"></i></div>
                    				<p>BillingFox Setup</p>
                    			</div>
                    			<div class="f1-step">
                    				<div class="f1-step-icon"><i class="fa fa-key"></i></div>
                    				<p>WooCommerce Setup</p>
                    			</div>
                    		    <div class="f1-step">
                    				<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
                    				<p>All Done!</p>
                    			</div>
                    		</div>

                    		<fieldset>
                                <div class="f1-buttons">
                                    <button type="button" class="btn btn-next">Next</button>
                                </div>
                            </fieldset>

                            <fieldset>
                                <h4>Set up your account:</h4>
                                <div class="form-group">
                                    <label class="sr-only" for="f1-email">Email</label>
                                    <input type="text" name="f1-email" placeholder="Email..." class="f1-email form-control" id="f1-email">
                                </div>
                                <div class="f1-buttons">
                                    <button type="button" class="btn btn-previous">Previous</button>
                                    <button type="button" class="btn btn-next">Next</button>
                                </div>
                            </fieldset>

                            <fieldset>
                                <h4>Social media profiles:</h4>
                            </fieldset>
                    	</form>
                    </div>
                </div>
            </div>
		</div> -->

		<?php do_action( 'admin_print_scripts' ); ?>
    </body>
</html>