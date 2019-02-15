/* global jQuery, mpShortcode, mp, mp_helpers, mpStripe */
jQuery( function( $ ) {
	$( document ).on( 'click', '[data-mp-btn]', function( e ) {
		const _this = $( this )
		const form = _this.closest( 'form' )
		const formData = new FormData( form[0] )
		mp.loader( 'show', _this )

		mp.send( formData ).then( resp => {
			switch ( resp.data.type ) {
			case 'check-unlock':

				if ( resp.data.data ) {
					const sid = formData.get( 'sid' )
					let preIDs = []
					resp.data.data.map( s => preIDs.push( s.description ) )

					/** @todo Fix this */
					if ( preIDs.indexOf( sid ) > -1 ) {
						// simply unlock the content
					}  else {
						mpShortcode.makeSpend( sid )
					}
				} else if ( resp.data === null ) {
					const sid = formData.get( 'sid' )
					mpShortcode.makeSpend( sid )
				}
				break

			case 'unlock':
				if ($('[data-mp-sid="'+ resp.data.sid +'"]').length > 0) {
					$('[data-mp-sid="'+ resp.data.sid +'"]').html(resp.data.content)
				}
				break

			/**
			 * Displays authentication barrier to the user.
			 * Lets a user login or register to the site.
			 * Hides the loader.
			 *
			 */
			case 'auth': {
				let elm = $( '.mp-popup' )

				if ( elm.length <= 0 ) {
					elm = $('<div class="mp-popup"></div>"')
					$( 'body' ).append( elm )
				}

				elm.html( resp.data.html )
				elm.addClass( 'mp-popup--active' )
				break
			}

			/**
			 * Processes login of the user.
			 * Gets spends if a valid user.
			 * Unlocks content if the content has already been paid for.
			 *
			 */
			case 'login':
				mpShortcode.setBFUserID( resp.data.user.key )
				mpShortcode.init()
				break

			case 'register':
				if ( resp.data.bfUID ) {
					mpShortcode.setBFUserID( resp.data.bfUID )
					mpShortcode.init()
				}
				mp.loader( 'hide' )
				break

			case 'recharge': {
				let elm = $( '.mp-popup' )

				if ( elm.length <= 0 ) {
					elm = $('<div class="mp-popup"></div>"')
					$( 'body' ).append( elm )
				}

				elm.html( resp.data.html )
				elm.addClass( 'mp-popup--active' )

				/** Load Stripe form. */
				mpStripe.init( 'form[data-mp-stripe-form]' )
				break
			}
			}
		})
		e.preventDefault()
	})

	/**
	 * Toggle auth forms in frontend.
	 *
	 */
	$( document ).on('click', '[data-mp-auth-form]', function() {
		const formID = $( this ).attr( 'data-mp-auth-form' )
		$( this ).addClass( 'mp-form__toggler--active' ).siblings().removeClass( 'mp-form__toggler--active' )
		$( 'div[data-mp-auth-form=' + formID + ']' )
			.removeClass( 'mp-form__toggle--hidden' )
			.siblings( '[data-mp-auth-form]' )
			.addClass( 'mp-form__toggle--hidden' )
	})

	/**
	 * Close popup.
	 *
	 */
	$( document ).on( 'click', '.mp-popup__close', function() {
		$( this ).parents( '.mp-popup' ).removeClass( 'mp-popup--active' )
		mp.loader( 'hide' )
	} )

	$( document ).on( 'click', '[data-mp-process-recharge]', function() {
		mp.loader( 'show', $( this ) )
		const form = $(this).closest( 'form' )
		const formData = new FormData( form[0] )

		mpStripe.process( formData )
	} )

	/**
	 * Calculate actual amount to be paid via Stripe.
	 *
	 */
	$( document ).on( 'blur', '.mp-form #amount', function() {
		const amount = $(this).val()
		const toPay = amount * mp_helpers.mp_bf_price
		const amountElm = $( '<span data-mp-stripe-amount style="margin-left: 10px; font-weight: 600">$'+ toPay +'</span>')
		const btnElm = $( '[data-mp-process-recharge]' )

		if ( btnElm.find( 'span' ).length <= 0 ) {
			btnElm.append( amountElm )
		} else {
			btnElm.find( 'span' ).html( '$' + toPay )
		}
	} )

	/**
	 * Check if shortcode contents need to be unlocked by default.
	 *
	 */
	mpShortcode.init()
})
