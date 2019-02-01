/* global jQuery, mp */
jQuery(function($) {
	$(document).on('click', '[data-mp-btn]', function(e) {
		const _this = $( this )
		const form = _this.closest('form')
		const formData = new FormData( form[0] )
		mp.loader( 'show', _this )

		mp.send( formData ).then(resp => {
			switch (resp.data.type) {
			case 'check-unlock':

				if ( resp.data.data ) {
					const sid = formData.get( 'sid' )
					let preIDs = []
					resp.data.data.map( s => preIDs.push( s.description ) )

					/** @todo Fix this */
					if ( preIDs.indexOf( sid ) > -1 ) {
						// simply unlock the content
					}  else {
						// process the spend
						formData.set( 'mpController', 'MPEngine:BillingFox:BillingFoxAPI' )
						formData.set( 'mpAction', 'processUnlocking' )
						formData.set( 'sid', sid )
						mp.send( formData ).then( r => console.log('sd',  r))
					}
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
				let elm = $( '.mp-auth-popup' )

				if ( elm.length <= 0 ) {
					elm = $('<div class="mp-auth-popup"></div>"')
					$( 'body' ).append( elm )
				}

				elm.html( resp.data.html )
				elm.addClass( 'mp-auth-popup--active' )
				break
			}

			/**
			 * Processes login of the user.
			 * Gets spends if a valid user.
			 * Unlocks content if the content has already been paid for.
			 *
			 */
			case 'login':
				window.mpStripe.setBFUserID(resp.data.user.key)
				window.mpStripe.init()
				break

			case 'recharge': {
				let elm = $( '.mp-auth-popup' )

				if ( elm.length <= 0 ) {
					elm = $('<div class="mp-auth-popup"></div>"')
					$( 'body' ).append( elm )
				}

				elm.html( resp.data.html )
				elm.addClass( 'mp-auth-popup--active' )

				/** Load Stripe form. */
				window.mpStripe.init( 'form[data-mp-stripe-form]' )
				break
			}

			/**
			 * Marks completion of consecutive API calls.
			 * Hides the loader.
			 *
			 */
			case 'unlocking-done':
				mp.loader( 'hide' )
				break
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
	 * Close auth popup.
	 *
	 */
	$( document ).on( 'click', '.mp-auth-popup__close', function() {
		$( this ).parents( '.mp-auth-popup' ).removeClass( 'mp-auth-popup--active' )
		mp.loader( 'hide' )
	} )

	/**
	 * Check if shortcode contents need to be unlocked by default.
	 *
	 */
	window.mpShortcode.init()
})
