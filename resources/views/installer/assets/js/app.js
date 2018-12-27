jQuery(function($) {
	const mp = new window.mpServer

	$('body').on('click', 'button[data-mp-tostep]', function(e) {
		e.preventDefault()
		const form = $(this).closest('form')
		const formData = new FormData(form[0])
		const step = $(this).attr('data-mp-tostep')
		const nextStep = $('div[data-mp-step='+ step +']').next('div[data-mp-step]').data('mp-step')
		formData.append('key', step)

		$('div[data-mp-step='+ step +']').show().siblings('div[data-mp-step]').hide()
		$('.mp-setup__steps').find('li[data-mp-step='+ step +']').addClass('active').siblings('li').removeClass('active')
		$(this).attr('data-mp-tostep', nextStep)

		mp._setup( formData )
	})
})
