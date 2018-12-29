const mp = new window.mpServer || {}

jQuery(function($) {
	$('[data-mp-button]').on('click', function(e) {
		const form = $(this).closest('form')
		const formData = new FormData(form[0])

		mp.send( formData )
		e.preventDefault()
	})
})