/* global jQuery, mp, mp_helpers */
jQuery(function($) {
	$(document).on('click', '[data-mp-btn]', function(e) {
		const form = $(this).closest('form')
		const formData = new FormData(form[0])

		mp.send( formData ).then(resp => {
			switch (resp.data.type) {
			case 'unlock':
				if ($('[data-mp-sid="'+ resp.data.sid +'"]').length > 0) {
					$('[data-mp-sid="'+ resp.data.sid +'"]').html(resp.data.content)
				}
				break

			case 'auth':
				$('.mp-auth-popup').html(resp.data.html).css('display', 'flex')
				break

			case 'login':
				shortcode.setBFUserID(resp.data.user.key)
				shortcode.init()
			}
		})
		e.preventDefault()
	})

	$(document).on('click', 'li[data-mp-auth-form]', function() {
		const formID = $(this).attr('data-mp-auth-form')
		$('div[data-mp-auth-form=' + formID + ']').show().siblings().hide()
	})

	shortcode.init()
})

const shortcode = {
	init() {
		const bfUID = this.getBFUserID()

		if ( ! bfUID ) {
			const data = new FormData()
			data.append('mpAction', 'getBFUser')
			data.append('mpController', 'MPEngine:BillingFox:BillingFoxAPI')
			data.append(mp_helpers.nonce_key, mp_helpers.nonce)

			mp.send(data).then(r => {
				if (r.success === true) {
					this.setBFUserID(r.data.user.key)
					this.getUserSpends()
				}
			})
		} else this.getUserSpends()
	},

	getUserSpends() {
		const data = new FormData()
		data.append('mpAction', 'getSpends')
		data.append('mpController', 'MPEngine:BillingFox:BillingFoxAPI')
		data.append('userAccess', true)

		mp.send(data).then(r => {
			if (r.success === true) {
				let shortcodeIDs = []

				r.data.spends.map(id => {
					const shortcodeElm = document.querySelector('div[data-mp-sid="'+ id +'"]')
					if (shortcodeElm) {
						shortcodeElm.innerHTML = r.data.shortcodeContent[ id ]
						shortcodeIDs.push(id)
					}
				})

				this.unlockContent(shortcodeIDs)
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

		mp.send(data)
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
}