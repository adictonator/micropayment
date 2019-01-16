/* global jQuery, mp */
jQuery(function($) {
	// $('[data-mp-btn="unlock"]').on('click', function(e) {
	// 	const form = $(this).closest('form')
	// 	const formData = new FormData(form[0])

	// 	mp.send( formData ).then(resp => {
	// 		$('.mp-auth-popup').html(resp.html).css('display', 'flex')
	// 	})
	// 	e.preventDefault()
	// })

	$(document).on('click', '[data-mp-btn]', function(e) {
		const type = $(this).attr('data-mp-btn')
		const form = $(this).closest('form')
		const formData = new FormData(form[0])

		mp.send( formData ).then(resp => {
			switch (type) {
			case 'unlock':
				$('.mp-auth-popup').html(resp.html).css('display', 'flex')
				break

			case 'login':
				alert('ascads')

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
	init(user) {
		this.getAPICred(user)
		return this
	},

	getAPICred(user) {
		const formData = new FormData()
		formData.append('mpAction', 'getAPICred')
		formData.append(mp_helpers.nonce_key, mp_helpers.nonce)
		formData.append('mpController', 'MPEngine:BillingFox:BillingFoxAPI')

		mp.send(formData).then(resp => {
			if ( resp.key ) {
				this.checkContentStatus(resp, user)
			}
		})
	},

	checkContentStatus: function(cred, user) {
		console.log('sada', cred.key)
		// console.log('sada', user.message.user.key)

		fetch(cred.url + '/spend?user=' + user.message.user.key, {
			method: 'GET',
			headers: new Headers({
				'Authorization': 'Bearer ' + cred.key,
				'Content-type': 'application/json',
			}),
		}).then(x => x.json())
			.then(v => console.log('asa', v))
	}
}