/* global Stripe */
window.mpStripe = {
	init( elm ) {
		if ( ! this.__checkElm( elm ) ) {
			console.error( `The element ${elm} doesn't exist on DOM.` )
			return
		}

		this.stripe = Stripe( 'pk_test_TYooMQauvdEDq54NiTphI7jx' )
		this.elements = this.stripe.elements( {
			fonts: [
				{
					cssSrc: 'https://fonts.googleapis.com/css?family=Source+Code+Pro',
				},
			],
		} )
		this.__setCard()
	},

	process( formData ) {
		this.stripe.createToken( this.cardNumber ).then( result => {
			if ( result.error ) this.__handleError( result.error )
			else this.__handleToken( result.token, formData )
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
	},

	__handleToken( token, formData ) {
		formData.append( 'tokenID', token.id )
		formData.append( 'userAccess', true )

		mp.send( formData ).then( resp => console.log('resp', resp))
	},

	__handleError( error ) {
		this.errorHolder.innerHTML = error.message
	},

	__checkElm( elm ) {
		if ( document.querySelector( elm ) !== null && document.querySelector( elm ).length > 0 ) {
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