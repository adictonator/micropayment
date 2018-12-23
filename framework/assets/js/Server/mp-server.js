const wp = window.wp || {}

window.mpServer = class MicroPayServerEnvironment {
	constructor() {
		this.app = 'MicroPayment IO'
		this.endPoints = {
			ajax: wp.ajax.settings.url,
			live: 'https://live.billingfox.com',
			test: 'https://test.billingfox.com',
		}
		this.url = null
		this.cacheData = null
	}

	api(type, method) {
		this.url = this.endPoints[ type ]
		this.method = method

		return this
	}

	send() {
		fetch(this.url + '/api/ping', {
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