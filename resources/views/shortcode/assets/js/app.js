/* global jQuery, mp, mp_helpers */
jQuery(function($) {
	$(document).on('click', '[data-mp-btn]', function(e) {
		const form = $(this).closest('form')
		const formData = new FormData(form[0])

		// $( this ).append( '<span class="is-loading"></span>' )

		mp.mpLoader( 'flex' )

		mp.send( formData ).then(resp => {
			switch (resp.data.type) {
			case 'check-unlock':

				if ( resp.data.data ) {
					const sid = formData.get( 'sid' )
					let preIDs = []
					resp.data.data.map( s => preIDs.push( s.description ) )

					if ( preIDs.indexOf( sid ) > -1 ) {
						console.log('naaaa trey')
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
				mp.mpLoader( 'none' )
				break
			}

			/**
			 * Processes login of the user.
			 * Gets spends if a valid user.
			 * Unlocks content if the content has already been paid for.
			 *
			 */
			case 'login':
				shortcode.setBFUserID(resp.data.user.key)
				shortcode.init()
				break

			/**
			 * Marks completion of consecutive API calls.
			 * Hides the loader.
			 *
			 */
			case 'unlocking-done':
				mp.mpLoader( 'none' )
				break
			}
		})

		// $( this ).find( '.is-loading' ).remove()

		e.preventDefault()
	})

	/**
	 * Toggle auth forms in frontend.
	 *
	 */
	$(document).on('click', '[data-mp-auth-form]', function() {
		const formID = $(this).attr('data-mp-auth-form')
		$( this ).addClass( 'mp-form__toggler--active' ).siblings().removeClass( 'mp-form__toggler--active' )
		$('div[data-mp-auth-form=' + formID + ']').removeClass( 'mp-form__toggle--hidden' ).siblings( '[data-mp-auth-form]' ).addClass( 'mp-form__toggle--hidden' )
	})

	$( document ).on( 'click', '.mp-auth-popup__close', function() {
		$( this ).parents( '.mp-auth-popup' ).removeClass( 'mp-auth-popup--active' )
	} )

	shortcode.init()
})

const shortcode = {
	init() {
		const bfUID = this.getBFUserID()
		const _mpIsDone = this._mpIsDone()

		if ( ! bfUID ) {
			const data = new FormData()
			data.append('mpAction', 'getBFUser')
			data.append('mpController', 'MPEngine:BillingFox:BillingFoxAPI')
			data.append(mp_helpers.nonce_key, mp_helpers.nonce)

			mp.send(data).then(r => {
				if (r.success === true) {
					this.setBFUserID(r.data.key)
					this.getUserSpends()
				}
			})
		}
		else if ( ! _mpIsDone ) this.getUserSpends()
	},

	getUserSpends() {
		const data = new FormData()
		data.append('mpAction', 'getSpends')
		data.append('mpController', 'MPEngine:BillingFox:BillingFoxAPI')
		data.append('userAccess', true)

		mp.send(data).then(r => {
			if (r.success === true) {
				let shortcodeIDs = []
				let preIDs = []

				r.data.spends.map(id => {
					preIDs.push( id )

					const shortcodeElm = document.querySelector('div[data-mp-sid="'+ id +'"]')
					if (shortcodeElm) {
						/**
						 * @todo Maybe remove the wall element and only append the content.
						 */
						shortcodeElm.innerHTML = r.data.shortcodeContent[ id ]
						// jQuery( r.data.shortcodeContent[ id ] ).insertAfter( shortcodeElm )
						// shortcodeElm.remove()
						shortcodeIDs.push(id)
					}
				})

				if ( preIDs.indexOf( r.data.sid ) > -1 ) {
					this.unlockContent(shortcodeIDs)
				} else {
					// 	// process the spend
					// 	formData.set( 'mpController', 'MPEngine:BillingFox:BillingFoxAPI' )
					// 	formData.set( 'mpAction', 'processUnlocking' )
					// 	formData.set( 'sid', sid )
					// 	mp.send( formData ).then( r => console.log('sd',  r))
				}
			} else {
				console.error('GETTING SPEND ERROR', r.data)
				this.removeBFUserID()
				//this.init()
			}
		})
	},

	unlockContent(shortcodeIDs) {
		const data = new FormData()
		data.append('mpAction', 'unlockContent')
		data.append('mpController', 'MicroPay:Controllers:Shortcodes:MicroPayShortcodeController')
		data.append('shortcodeIDs', shortcodeIDs)
		data.append('userAccess', true)

		mp.send( data ).then( resp => {
			if ( resp.success === true ) {
				sessionStorage.setItem( '_mpIsDone', true )

				if ( jQuery( '.mp-auth-popup--active' ).length > 0 ) {
					jQuery( '.mp-auth-popup--active' ).find( '.mp-auth-popup__close' ).click()
					mp.mpLoader( 'none' )
				}
			}
		} )
	},

	setBFUserID(id) {
		return localStorage.setItem('bfUID', id)
	},

	getBFUserID() {
		return localStorage.getItem('bfUID')
	},

	removeBFUserID() {
		return localStorage.removeItem('bfUID')
	},

	_mpIsDone() {
		if ( document.cookie.indexOf('wp-settings-time') !== -1 ) {
			return sessionStorage.getItem( '_mpIsDone' )
		} else {
			return sessionStorage.removeItem( '_mpIsDone' )
		}
	},
}