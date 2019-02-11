/* global jQuery, mp, mp_helpers */
window.mpShortcode = {
	init() {
		if ( ! this.getBFUserID() ) {
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
		else if ( ! this._mpIsDone() ) this.getUserSpends()
	},

	getUserSpends() {
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

					this.setShortcodeContent( id, r.data.shortcodeContent )
					shortcodeIDs.push( id )
				})

				if ( typeof r.data.sid !== 'undefined' ) {
					if ( preIDs.indexOf( r.data.sid ) > -1 ) {
						this.unlockContent( shortcodeIDs )
					} else {
						this.makeSpend( r.data.sid )
					}
				} else {
					this.unlockContent( shortcodeIDs )
				}
			} else {
				console.error( 'GETTING SPEND ERROR', r.data )
				this.removeBFUserID()
			}
		})
	},

	setShortcodeContent( id, contents ) {
		const shortcodeElm = document.querySelector( 'div[data-mp-sid="'+ id +'"]' )

		if ( shortcodeElm ) {
			jQuery( '<span>'+ contents[ id ] +'</span>' ).insertBefore( shortcodeElm )
			shortcodeElm.remove()
		}
	},

	makeSpend( shortcodeID ) {
		const data = new FormData()
		data.append( 'mpAction', 'processUnlocking' )
		data.append( 'mpController', 'MPEngine:BillingFox:BillingFoxAPI' )

		if ( typeof shortcodeID !== 'undefined' ) {
			data.append( 'sid', shortcodeID )
		}

		data.append( 'userAccess', true )

		mp.send( data ).then( resp => {
			if ( resp.success === true ) {
				this.unlockContent( shortcodeID )
			}
		} )
	},

	unlockContent( shortcodeIDs ) {
		const data = new FormData()
		data.append( 'mpAction', 'unlockContent' )
		data.append( 'mpController', 'MicroPay:Controllers:Shortcodes:MicroPayShortcodeController' )
		data.append( 'shortcodeIDs', shortcodeIDs )
		data.append( 'userAccess', true )

		mp.send( data ).then( resp => {
			if ( resp.success === true ) {
				sessionStorage.setItem( '_mpIsDone', true )

				this.setShortcodeContent( shortcodeIDs, resp.data.shortcodeContent )
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
	},

	unlockSpendsShortcode() {
		const elm = jQuery( '[data-mp-error="micropay_transactions"]' )

		if ( elm.length > 0 ) {
			// do magic here
		}
	},

	setBFUserID( id ) {
		return localStorage.setItem( 'bfUID', id )
	},

	getBFUserID() {
		return localStorage.getItem( 'bfUID' )
	},

	removeBFUserID() {
		return localStorage.removeItem( 'bfUID' )
	},

	_mpIsDone() {
		if ( document.cookie.indexOf( 'wp-settings-time' ) !== -1 ) {
			return sessionStorage.getItem( '_mpIsDone' )
		} else {
			return sessionStorage.removeItem( '_mpIsDone' )
		}
	},
}