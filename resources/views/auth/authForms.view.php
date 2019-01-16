<div class="mp-form-wrap">
	<ul>
		<li data-mp-auth-form="login">Login</li>
		<li data-mp-auth-form="register">Register</li>
	</ul>

	<div id="mp-login" data-mp-auth-form="login">
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
	<div id="mp-register" style="display: none" data-mp-auth-form="register">
		<?php /** @todo create a partial include file helper function.
		 * and include respective forms */ ?>

		<form>
			<?php mp_form_fields( 'ajax', 'register', $this ); ?>
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