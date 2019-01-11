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
})