jQuery(function($) {
	$('[data-mp-form-btn]').on('click', function(e) {
		const form = $(this).closest('form')
		const formData = new FormData(form[0])

		mp.send( formData ).then(resp => {
			if (resp.type === 'success') {
				alert(resp.msg)
			}
		})
		e.preventDefault()
	})
})