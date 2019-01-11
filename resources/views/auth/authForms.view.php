<div class="mp-form-wrap">
	<ul>
		<li>Login</li>
		<li>Register</li>
	</ul>

	<div id="mp-login">
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
					<button type="button" class="mp-form__button mp-form__button--primary" data-mp-btn="login">Login</button>
				</div>
			</form>
		</div>
	</div>
	<div id="mp-register">
		<?php /** @todo create a partial include file helper function.
		 * and include respective forms */ ?>
	</div>
</div>