<div class="mp-form-wrap mp-recharge-form">
	<h3>Recharge your Account!</h3>
	<div class="mp-form">
		<form data-mp-stripe-form>
			<?php mp_form_fields( 'ajax', 'processRecharge', $this ); ?>
			<div class="mp-form__group">
				<input id="amount" name="rechargeAmount" type="text" class="input empty" onfocus="this.classList.add('focused')" onblur="this.classList.remove('focused')" onkeyup="this.value.length > 0 ? this.classList.remove('empty') : this.classList.add('empty')">
				<label for="amount">Amount to Credit</label>
				<div class="baseline"></div>
			</div>
			<div class="mp-form__group">
				<div id="mp-stripe-card-number" data-mp-stripe-cn class="input empty"></div>
				<label for="mp-stripe-card-number">Card number</label>
				<div class="baseline"></div>
			</div>
			<div class="mp-form__group mp-form__group--has-half">
				<div class="mp-from__group--half">
					<div id="mp-stripe-card-expiry" data-mp-stripe-ce class="input empty"></div>
					<label for="mp-stripe-card-expiry">Expiration</label>
					<div class="baseline"></div>
				</div>
				<div class="mp-from__group--half">
					<div id="mp-stripe-card-code" data-mp-stripe-cc class="input empty"></div>
					<label for="mp-stripe-card-code">CVC</label>
					<div class="baseline"></div>
				</div>
			</div>
			<div class="mp-form__group">
				<button type="button" data-mp-process-recharge class="mp-front-button mp-front-button--primary">Process Payment</button>
			</div>
		</form>
	</div>

	<span class="mp-popup__close">&times;</span>
</div>