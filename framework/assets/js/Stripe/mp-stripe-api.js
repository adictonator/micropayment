/* global Stripe, jQuery, mp */

const mpStripe = function() {
	const _this = this
	const stripe = {}

	_this.__setupStripe = function() {
		const data = new FormData()
		data.append( 'action', 'listenAJAX' )
		data.append( 'mpAction', 'getStripeKeys' )
		data.append( 'mpController', 'MPEngine:BillingFox:BillingFoxAPI' )
		data.append( 'userAccess', true )

		mp.send( data ).then( r => {
			if ( r.success === true && r.data.key !== null ) {
				this.stripe = Stripe( r.data.testMode )

				this.elements = this.stripe.elements( {
					fonts: [
						{
							cssSrc: 'https://fonts.googleapis.com/css?family=Source+Code+Pro',
						},
					],
				} )
				_this.__setCard()
			} else {
				console.error( 'ERROR GETTING CREDENTIALS!' )
			}
		} )
	}

	_this.__style = function() {
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
	}

	_this.__setCard = function() {
		const elementClasses = {
			empty: 'empty',
			focus: 'focused',
			invalid: 'invalid',
		}

		this.cardNumber = this.elements.create( 'cardNumber', {
			style: _this.__style(),
			classes: elementClasses,
		})
		this.cardNumber.mount( this.cardNumberHolder )

		this.cardExpiry = this.elements.create( 'cardExpiry', {
			style: _this.__style(),
			classes: elementClasses,
		})
		this.cardExpiry.mount( this.cardExpiryHolder )

		this.cardCode = this.elements.create( 'cardCvc', {
			style: _this.__style(),
			classes: elementClasses,
		})
		this.cardCode.mount( this.cardCodeHolder )
	}

	_this.__handleToken = function( token, formData ) {
		formData.append( 'tokenID', token.id )
		formData.append( 'userAccess', true )

		mp.send( formData ).then( resp => {
			if ( resp.success === true ) {
				/**
				 * Close the payment popup.
				 *
				 */
				if ( jQuery( '.mp-popup--active' ).length > 0 ) {
					jQuery( '.mp-popup--active' ).find( '.mp-popup__close' ).click()
				} else {
					mp.loader( 'hide' )
				}
			}
		} )
	}

	_this.__handleError = function( error ) {
		this.errorHolder.innerHTML = error.message
	}

	_this.__checkElm = function( elm ) {
		if ( document.querySelector( elm ) !== null && document.querySelector( elm ).length > 0 ) {
			this.form = document.querySelector( elm )
			// this.cardHolder = this.form.querySelector( '[data-mp-stripe-cc]' )
			this.cardNumberHolder = this.form.querySelector( '[data-mp-stripe-cn]' )
			this.cardExpiryHolder = this.form.querySelector( '[data-mp-stripe-ce]' )
			this.cardCodeHolder = this.form.querySelector( '[data-mp-stripe-cc]' )
			this.errorHolder = this.form.querySelector( '[data-mp-stripe-errors]' )
			return true
		} else return false
	}

	stripe.init = function( elm ) {
		if ( ! _this.__checkElm( elm ) ) {
			console.error( `The element ${elm} doesn't exist on DOM.` )
			return
		}

		_this.__setupStripe()
	}

	stripe.process = function( formData ) {
		this.stripe.createToken( this.cardNumber ).then( result => {
			if ( result.error ) _this.__handleError( result.error )
			else _this.__handleToken( result.token, formData )
		})
	}

	return stripe
}()
