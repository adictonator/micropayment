/* global Stripe */
window.mpStripe = {
	init(elm) {
		if (!this.__checkElm(elm)) {
			console.error(`The element ${elm} doesn't exist on DOM.`)
			return
		}

		this.stripe = Stripe('pk_test_TYooMQauvdEDq54NiTphI7jx')
		this.elements = this.stripe.elements()
		this.__setCard()
	},

	process() {
		this.stripe.createToken(this.card).then(result => {
			if (result.error) this.__handleError(result.error)
			else this.__handleToken(result.token)
		})
	},

	__style() {
		return {
			base: {
				color: '#32325D',
				fontWeight: 500,
				fontFamily: 'Source Code Pro, Consolas, Menlo, monospace',
				fontSize: '16px',
				fontSmoothing: 'antialiased',

				'::placeholder': {
					color: '#CFD7DF',
				},
				':-webkit-autofill': {
					color: '#e39f48',
				},
			},
			invalid: {
				color: '#E25950',
				'::placeholder': {
					color: '#FFCCA5',
				},
			},
		}
	},

	__setCard() {
		const elementClasses = {
			empty: 'empty',
			focus: 'focused',
			invalid: 'invalid',
		}

		this.cardNumber = this.elements.create( 'cardNumber', {
			style: this.__style(),
			classes: elementClasses,
		})
		this.cardNumber.mount( this.cardNumberHolder )

		this.cardExpiry = this.elements.create( 'cardExpiry', {
			style: this.__style(),
			classes: elementClasses,
		})
		this.cardExpiry.mount( this.cardExpiryHolder )

		this.cardCode = this.elements.create( 'cardCvc', {
			style: this.__style(),
			classes: elementClasses,
		})
		this.cardCode.mount( this.cardCodeHolder )

		// this.__registerElements( [
		// 	this.cardNumber,
		// 	this.cardExpiry,
		// 	this.cardCode
		// ] )
	},

	// __registerElements( elements ) {
	// 	console.log('ss', elements)
	// 	function enableInputs() {
	// 		Array.prototype.forEach.call(
	// 			this.form.querySelectorAll(
	// 				'input[type=\'text\'], input[type=\'email\'], input[type=\'tel\']'
	// 			),
	// 			function (input) {
	// 				input.removeAttribute('disabled')
	// 			}
	// 		)
	// 	}
	// },

	__handleToken( token ) {
		console.log('token', token)
		const data = new FormData()
		data.append('mpAction', 'recharge')
		data.append('mpController', 'MPEngine:BillingFox:BillingFoxAPI')
		data.append('tokenID', token.id)
		data.append('userAccess', true)

		// Stripe livemode check?

		mp.send(data).then(resp => console.log('asd', resp))
	},

	__handleError( error ) {
		this.errorHolder.innerHTML = error.message
	},

	__checkElm( elm ) {
		if ( elm !== null && document.querySelector( elm ).length > 0 ) {
			this.form = document.querySelector( elm )
			// this.cardHolder = this.form.querySelector( '[data-mp-stripe-cc]' )
			this.cardNumberHolder = this.form.querySelector( '[data-mp-stripe-cn]' )
			this.cardExpiryHolder = this.form.querySelector( '[data-mp-stripe-ce]' )
			this.cardCodeHolder = this.form.querySelector( '[data-mp-stripe-cc]' )
			this.errorHolder = this.form.querySelector( '[data-mp-stripe-errors]' )
			return true
		} else return false
	},
}


// var elements = stripe.elements({
// 	fonts: [
// 	  {
// 		cssSrc: 'https://fonts.googleapis.com/css?family=Source+Code+Pro',
// 	  },
// 	],
// 	// Stripe's examples are localized to specific languages, but if
// 	// you wish to have Elements automatically detect your user's locale,
// 	// use `locale: 'auto'` instead.
// 	locale: window.__exampleLocale
//   });

//   // Floating labels
//   var inputs = document.querySelectorAll('.cell.example.example2 .input');
//   Array.prototype.forEach.call(inputs, function(input) {
// 	input.addEventListener('focus', function() {
// 	  input.classList.add('focused');
// 	});
// 	input.addEventListener('blur', function() {
// 	  input.classList.remove('focused');
// 	});
// 	input.addEventListener('keyup', function() {
// 	  if (input.value.length === 0) {
// 		input.classList.add('empty');
// 	  } else {
// 		input.classList.remove('empty');
// 	  }
// 	});
//   });