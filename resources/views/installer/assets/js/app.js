jQuery(function($) {
	$('body').on('click', 'button[data-mp-tostep]', function() {
		const step = $(this).attr('data-mp-tostep')
		const nextStep = $('div[data-mp-step='+ step +']').next('div[data-mp-step]').data('mp-step')
		$('div[data-mp-step='+ step +']').show().siblings('div[data-mp-step]').hide()
		$('.mp-setup__steps').find('li[data-mp-step='+ step +']').addClass('active').siblings('li').removeClass('active')
		$(this).attr('data-mp-tostep', nextStep)
	})
})