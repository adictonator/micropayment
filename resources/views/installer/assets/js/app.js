jQuery(function($) {
	$('body').on('click', 'button[data-mp-setup-btn]', function(e) {
		e.preventDefault()
		let step = $('div[data-mp-step]:visible').next('div[data-mp-step]').data('mp-step')
		const form = $(this).closest('form')
		const formData = new FormData(form[0])

		formData.append('key', step)
		$('div[data-mp-step='+ step +']').show().siblings('div[data-mp-step]').hide()
		$('.mp-setup__steps').find('li[data-mp-step='+ step +']').addClass('step')
		if (step === 'done') {
			$(this).text('Let\'s Go!')
			$(this).click(function() {
				window.location.href = $('input[name=redirect]').val()
			})

			mp._setup( formData )
		}
	})

	$('li[data-mp-step]').on('click', function() {
		const step = $(this).data('mp-step')
		$(this).addClass('step').nextAll().removeClass('step')
		$(this).prevAll().addClass('step')
		$('div[data-mp-step='+ step +']').show().siblings('div[data-mp-step]').hide()
	})

	$('[data-mp-validate-api]').on('click', function() {
		const key = $('input[name=apiKey]').val()

		mp._validateAPI( key ).then(resp => {
			if (resp.status === 'error') {
				alert(resp.data)
				return false
			}

			$('.mp-setup-group--hidden').removeClass('mp-setup-group--hidden')
		})
	})
})
