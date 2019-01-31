window.mpServer = class MicroPayServerEnvironment {
	constructor() {
		this.app = 'MicroPayment IO'
		this.ajax = mp_helpers.url
		this.api = 'https://test.billingfox.com/api/'
	}

	_setup( data ) {
		this.send( data ).then(r => console.log('ee', r))
	}

	async send( data ) {
		const resp = await fetch(this.ajax, {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		});
		return await resp.json();
	}

	async _validateAPI( apiKey ) {
		const resp = await fetch(this.api + 'ping', {
			method: 'GET',
			headers: new Headers({
				'Authorization': 'Bearer ' + apiKey
			}),
		})

		return await resp.json()
	}

	loader( display, elm = null ) {
		if ( display === 'show' ) {
			elm.append( '<span class="is-loading"></span>' )
			elm.prop( 'disabled', true )
		} else {
			jQuery( '.is-loading' ).parent().prop( 'disabled', false )
			jQuery( '.is-loading' ).remove()
		}
	}
}

const mp = new window.mpServer