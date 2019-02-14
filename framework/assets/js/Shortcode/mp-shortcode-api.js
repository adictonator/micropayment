/* global jQuery, mp, mp_helpers */
const mpShortcode = function() {
	const _this = this
	const shortcode = {}

	_this.__setShortcodeContent = function( id, contents ) {
		const shortcodeElm = document.querySelector( 'div[data-mp-sid="'+ id +'"]' )

		if ( shortcodeElm ) {
			jQuery( '<span>'+ contents[ id ] +'</span>' ).insertBefore( shortcodeElm )
			shortcodeElm.remove()
		}
	}

	_this.__unlockContent = function( shortcodeIDs ) {
		const data = new FormData()
		data.append( 'mpAction', 'unlockContent' )
		data.append( 'mpController', 'MicroPay:Controllers:Shortcodes:MicroPayShortcodeController' )
		data.append( 'shortcodeIDs', shortcodeIDs )
		data.append( 'userAccess', true )

		mp.send( data ).then( resp => {
			if ( resp.success === true ) {
				sessionStorage.setItem( '_mpIsDone', true )

				_this.__setShortcodeContent( shortcodeIDs, resp.data.shortcodeContent )

				/**
				 * Unlock other shortcodes content.
				 *
				 */
				_this.__unlockSpendsShortcode()
				_this.__unlockProfileShortcode()

				/**
				 * Close the auth popup.
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

	_this.__unlockProfileShortcode = function() {
		const elm = jQuery( '[data-mp-error="micropay_profile"]' )

		if ( elm.length > 0 ) {
			const data = new FormData()
			data.append( 'mpAction', 'getSpendsContent' )
			data.append( 'mpController', 'MicroPay:Controllers:Shortcodes:ProfileShortcodeController' )
			data.append( 'userAccess', true )

			mp.send( data, 'text' ).then( resp => elm.innerHTML = resp )
		}
	}

	_this.__unlockSpendsShortcode = function() {
		const elm = jQuery( '[data-mp-error="micropay_transactions"]' )

		if ( elm.length > 0 ) {
			const data = new FormData()
			data.append( 'mpAction', 'getSpendsContent' )
			data.append( 'mpController', 'MicroPay:Controllers:Shortcodes:TransactionsShortcodeController' )
			data.append( 'userAccess', true )

			mp.send( data, 'text' ).then( resp => elm.innerHTML = resp )
		}
	}

	_this.__removeBFUserID = function() {
		return localStorage.removeItem( 'bfUID' )
	}

	_this.__getUserSpends = function() {
		const data = new FormData()
		data.append( 'mpAction', 'getSpends' )
		data.append( 'mpController', 'MPEngine:BillingFox:BillingFoxAPI' )
		data.append( 'userAccess', true )

		mp.send( data ).then( r => {
			if ( r.success === true ) {
				let shortcodeIDs = []
				let preIDs = []

				r.data.spends.map( id => {
					preIDs.push( id )

					_this.__setShortcodeContent( id, r.data.shortcodeContent )
					shortcodeIDs.push( id )
				})

				if ( typeof r.data.sid !== 'undefined' ) {
					if ( preIDs.indexOf( r.data.sid ) > -1 ) {
						_this.__unlockContent( shortcodeIDs )
					} else {
						_this.__makeSpend( r.data.sid )
					}
				} else {
					_this.__unlockContent( shortcodeIDs )
				}
			} else {
				console.error( 'GETTING SPEND ERROR', r.data )
				_this.__removeBFUserID()
			}
		})
	}

	_this.__getBFUserID = function() {
		return localStorage.getItem( 'bfUID' )
	}

	_this.__mpIsDone = function() {
		if ( document.cookie.indexOf( 'wp-settings-time' ) !== -1 ) {
			return sessionStorage.getItem( '_mpIsDone' )
		} else {
			return sessionStorage.removeItem( '_mpIsDone' )
		}
	}

	shortcode.init = function() {
		if ( ! _this.__getBFUserID() ) {
			const data = new FormData()
			data.append( 'mpAction', 'getBFUser' )
			data.append( 'mpController', 'MPEngine:BillingFox:BillingFoxAPI' )
			data.append( mp_helpers.nonce_key, mp_helpers.nonce )

			mp.send( data ).then( r => {
				if ( r.success === true ) {
					this.setBFUserID( r.data.key )
					_this.__getUserSpends()
				}
			})
		}
		else if ( ! _this.__mpIsDone() ) _this.__getUserSpends()
	}

	shortcode.setBFUserID = function( id ) {
		return localStorage.setItem( 'bfUID', id )
	}

	shortcode.makeSpend = function( shortcodeID ) {
		const data = new FormData()
		data.append( 'mpAction', 'processUnlocking' )
		data.append( 'mpController', 'MPEngine:BillingFox:BillingFoxAPI' )

		if ( typeof shortcodeID !== 'undefined' ) {
			data.append( 'sid', shortcodeID )
		}

		data.append( 'userAccess', true )

		mp.send( data ).then( resp => {
			if ( resp.success === true ) {
				_this.__unlockContent( shortcodeID )
			}
		} )
	}

	return shortcode
}()