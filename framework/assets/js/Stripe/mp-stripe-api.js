/* global Stripe, jQuery, mp */

const mpStripe = function() {
	const _this = this
	const stripe = {}
	let _cardCode
	let _stripeObj
	let _cardNumber
	let _cardExpiry
	let _stripeElmObj
	let _cardNumberHolder
	let _cardExpiryHolder
	let _cardCodeHolder
	let _errorHolder

	_this.__setupStripe = function() {
		const data = new FormData()
		data.append( 'action', 'listenAJAX' )
		data.append( 'mpAction', 'getStripeKeys' )
		data.append( 'mpController', 'MPEngine:BillingFox:BillingFoxAPI' )
		data.append( 'userAccess', true )

		mp.send( data ).then( r => {
			if ( r.success === true && r.data.keys !== null ) {
				console.log('ss', r)
				_stripeObj = Stripe( r.data.keys.publisher )

				_stripeElmObj = _stripeObj.elements( {
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

		_cardNumber = _stripeElmObj.create( 'cardNumber', {
			style: _this.__style(),
			classes: elementClasses,
		})
		_cardNumber.mount( _cardNumberHolder )

		_cardExpiry = _stripeElmObj.create( 'cardExpiry', {
			style: _this.__style(),
			classes: elementClasses,
		})
		_cardExpiry.mount( _cardExpiryHolder )

		_cardCode = _stripeElmObj.create( 'cardCvc', {
			style: _this.__style(),
			classes: elementClasses,
		})
		_cardCode.mount( _cardCodeHolder )
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
			_cardNumberHolder = this.form.querySelector( '[data-mp-stripe-cn]' )
			_cardExpiryHolder = this.form.querySelector( '[data-mp-stripe-ce]' )
			_cardCodeHolder = this.form.querySelector( '[data-mp-stripe-cc]' )
			_errorHolder = this.form.querySelector( '[data-mp-stripe-errors]' )
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
		_stripeObj.createToken( _cardNumber ).then( result => {
			if ( result.error ) _this.__handleError( result.error )
			else _this.__handleToken( result.token, formData )
		})
	}

	return stripe
}()
