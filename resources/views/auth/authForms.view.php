<div class="mp-form-wrap">
	<div class="mp-form__toggler-wrap">
		<span class="mp-form__toggler mp-form__toggler--active" data-mp-auth-form="login">Login</span>
		<span class="mp-form__toggler" data-mp-auth-form="register">Register</span>
	</div>

	<div id="mp-login" class="mp-form__toggle" data-mp-auth-form="login">
		<h3>Login to your existing BillingFox account.</h3>
		<div class="mp-form">
			<form>
				<?php mp_form_fields( 'ajax', 'login', $this ); ?>
				<div class="mp-form__group">
					<input type="email" name="mp_user" placeholder="Email address">
				</div>
				<div class="mp-form__group">
					<input type="password" name="mp_password" placeholder="Password">
				</div>
				<div class="mp-form__group">
					<button type="button" class="mp-front-button" data-mp-btn="login">Login</button>
				</div>
			</form>
		</div>
	</div>

	<div id="mp-register" class="mp-form__toggle mp-form__toggle--hidden" data-mp-auth-form="register">
		<h3>Register for the website to get a BillingFox account.</h3>
		<form>
			<?php mp_form_fields( 'ajax', 'register', $this ); ?>
			<div class="mp-form__group">
				<input type="email" name="mp_user" placeholder="Email address">
			</div>
			<div class="mp-form__group">
				<input type="password" name="mp_password" placeholder="Password">
			</div>
			<div class="mp-form__group">
				<button type="button" class="mp-front-button" data-mp-btn="register">Register</button>
			</div>
		</form>
	</div>

	<span class="mp-popup__close">&times;</span>
</div>