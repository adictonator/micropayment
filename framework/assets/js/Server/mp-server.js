window.mpServer = class MicroPayServerEnvironment {
	constructor() {
		this.app = 'MicroPayment IO'
		this.ajax = mp_helpers.url
		this.api = 'https://test.billingfox.com/api/ping'
		this.url = null
		this.cacheData = null
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
		const resp = await fetch(this.api, {
			method: 'GET',
			headers: new Headers({
				'Authorization': 'Bearer ' + apiKey
			}),
		})

		return await resp.json()
	}
}

const mp = new window.mpServer