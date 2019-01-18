/* global jQuery, mp */
jQuery(function($) {
	$('[data-mp-form-btn]').on('click', function(e) {
		const form = $(this).closest('form')
		const formData = new FormData(form[0])

		mp.send( formData ).then(resp => {
			if (resp.success === 'success') {
				alert(resp.msg)
			}
		})
		e.preventDefault()
	})

	if (typeof jQuery.fn.select2 === 'function') {
		/**
		 * Assigns select2 to specified select fields.
		 *
		 */
		$('.mp-has-select2').select2({
			tags: true
		})
	}
})