window.mpServer = class MicroPayServerEnvironment {
	constructor() {
		this.app = 'MicroPayment IO'
		this.ajax = mp_helpers.url
		this.api = 'https://test.billingfox.com/api/'
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
		const resp = await fetch(this.api + 'ping', {
			method: 'GET',
			headers: new Headers({
				'Authorization': 'Bearer ' + apiKey
			}),
		})

		return await resp.json()
	}

	async hasWall() {
		const data = new FormData()
		data.append('action', 'listenAJAX')
		data.append('mpAction', 'isAuthUser')
		data.append('mpController', 'MPEngine:BillingFox:BillingFoxAPI')
		data.append(mp_helpers.nonce_key, mp_helpers.nonce)
		data.append('fromFront', 'true')
		const resp = await fetch(this.ajax, {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		})

		return await resp.json().then(r => {
			if (r.type === 'success') {
				shortcode.init(r)
			}
		})
	}
}

const mp = new window.mpServer