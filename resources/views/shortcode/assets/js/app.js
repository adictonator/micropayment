/* global jQuery, mp, mp_helpers */
jQuery(function($) {
	$(document).on('click', '[data-mp-btn]', function(e) {
		const type = $(this).attr('data-mp-btn')
		const form = $(this).closest('form')
		const formData = new FormData(form[0])

		mp.send( formData ).then(resp => {
			switch (resp.type) {
			case 'unlock':
				if ($('[data-mp-sid="'+ resp.message.sid +'"]').length > 0) {
					$('[data-mp-sid="'+ resp.message.sid +'"]').html(resp.message.content)
				}
				break

			case 'auth':
				$('.mp-auth-popup').html(resp.message).css('display', 'flex')
				break

			case 'login':
				// something

			}
		})
		e.preventDefault()
	})

	$(document).on('click', 'li[data-mp-auth-form]', function() {
		const formID = $(this).attr('data-mp-auth-form')
		$('div[data-mp-auth-form=' + formID + ']').show().siblings().hide()
	})
})

const shortcode = {
	init() {
		const bfUID = this.getBFUserID()

		if ( ! bfUID ) {
			const data = new FormData()
			data.append('mpAction', 'isAuthUser')
			data.append('mpController', 'MPEngine:BillingFox:BillingFoxAPI')
			data.append(mp_helpers.nonce_key, mp_helpers.nonce)
			data.append('fromFront', 'true')

			mp.send(data).then(r => {
				if (r.type === 'success') {
					localStorage.setItem('bfUID', r.message.user.key)

					this.getUserSpends()
				}
			})
		} else this.getUserSpends()
	},

	getBFUserID() {
		return localStorage.getItem('bfUID')
	},

	getUserSpends() {
		const data = new FormData()
		data.append('mpAction', 'getSpends')
		data.append('mpController', 'MPEngine:BillingFox:BillingFoxAPI')
		data.append(mp_helpers.nonce_key, mp_helpers.nonce)
		data.append('fromFront', 'true')

		mp.send(data).then(r => {
			if (r.type === 'success') {
				r.message.spends.map(id => {
					const shortcodeElm = document.querySelector('div[data-mp-sid="'+ id +'"]')

					if (shortcodeElm) {
						shortcodeElm.innerHTML = r.message.shortcodeContent[ id ]
						this.unlockContent(id)
					}
				})
			} else {
				console.error('GETTING SPEND ERROR', 'Cannot get user\'s spends')
			}
		})
	},

	unlockContent(sID) {
		const data = new FormData()
		data.append('mpAction', 'unlockContent')
		data.append('mpController', 'MicroPay:Controllers:Shortcodes:MicroPayShortcodeController')
		data.append('sID', sID)
		data.append(mp_helpers.nonce_key, mp_helpers.nonce)

		mp.send(data)
	},
}