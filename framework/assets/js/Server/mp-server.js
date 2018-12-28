const wp = window.wp || {}

window.mpServer = class MicroPayServerEnvironment {
	constructor() {
		this.app = 'MicroPayment IO'
		this.ajax = mp_helpers.url,
		this.api = 'https://test.billingfox.com/api/ping'
		this.url = null
		this.cacheData = null
	}

	_setup( data ) {
		this._setupAPI( data ).then(r => console.log('ee', r))
	}

	async _setupAPI( data ) {
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

	api(type, method) {
		this.url = this.endPoints[ type ]
		this.method = method

		return this
	}

	send() {
		fetch(this.url, {
			headers: new Headers({
				'Authorization': 'Bearer 7ojvgRnvvwbZYSSJy2XKRwJmPiXexXtbkjbAxg3a8zJD',
			}),
			method: this.method,
			body: this.cacheData
		})
			.then(r => r.json())
			.then(x => {
				if ( x.status === 'success' ) {
					alert('-x.message')
				}
			})
			.catch(e => alert('Oops: ' + e))
	}
}