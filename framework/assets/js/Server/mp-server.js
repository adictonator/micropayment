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

	mpLoader( display ) {
		let loaderElm = jQuery( '.mp-loader' )

		if ( loaderElm.length <= 0  ) {
			loaderElm = jQuery( '<div class="mp-loader" style="position: fixed; left: 0; top: 0;height:100vh;width:100vw;justify-content: center;align-items:center;z-index: 10002; "></div>' )
			loaderElm.html( '<img src="' + mp_helpers.mp_fw_url + '/assets/images/loader.svg">' )
			jQuery( 'body' ).append( loaderElm )
		}

		loaderElm.css( 'display', display )
	}
}

const mp = new window.mpServer