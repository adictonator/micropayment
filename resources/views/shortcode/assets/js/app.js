jQuery(function($) {
	$('[data-mp-btn="unlock"]').on('click', function(e) {
		const form = $(this).closest('form')
		const formData = new FormData(form[0])

		mp.send( formData ).then(resp => {
			$('.mp-auth-popup').html(resp.html).css('display', 'flex')
		})
		e.preventDefault()
	})
})