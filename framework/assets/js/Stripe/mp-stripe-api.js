/* global Stripe */
window.mpStripe = {
	form: null,
	stripe: null,
	elements: null,
	cardHolder: null,

	style() {
		return {
			base: {
				color: '#32325d',
				lineHeight: '18px',
				fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
				fontSmoothing: 'antialiased',
				fontSize: '16px',
				'::placeholder': {
					color: '#aab7c4'
				}
			},
			invalid: {
				color: '#fa755a',
				iconColor: '#fa755a'
			}
		}
	},

	card() {
		return this.elements.create( 'card', { style: this.style() } )
	},

	handleToken( token ) {
		console.log('asdasd', token)
	},

	process() {
		this.form.addEventListener( 'submit', function( event ) {
			event.preventDefault()

			this.stripe.createToken( this.card() ).then( function(result) {
				if (result.error) {
					console.log('sadd', result.error)
					// var errorElement = document.getElementById('card-errors')
					// errorElement.textContent = result.error.message
				} else {
					this.handleToken( result.token )
				}
			})
		})
	},

	// Is this required?
	// setupStripe() {
	// 	const data = new FormData
	// 	data.append( 'mpAction', 'getStripeCredentials' )
	// 	data.append('mpController', 'MPEngine:BillingFox:BillingFoxAPI')
	// 	mp.send( data ).then( resp => console.log('sdsas', resp))
	// },

	checkElm( elm ) {
		console.log('asdas', elm)
		if ( typeof elm !== null && document.querySelector( elm ).length > 0 ) {
			this.form = document.querySelector( elm )
			this.cardHolder = this.form.querySelector( '[data-mp-stripe-cc]' )
			return true
		}
		else return false
	},

	init( elm ) {
		if ( this.checkElm( elm ) ) {
			// this.setupStripe().process()
			this.stripe = Stripe( 'pk_test_TYooMQauvdEDq54NiTphI7jx' )
			this.elements = this.stripe.elements()
			this.card().mount( this.cardHolder )
		} else {
			console.error( `The element ${elm} doesn't exist on DOM.` )
		}
	}
}