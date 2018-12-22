window.mpServer = class MicroPayServerEnvironment {
	constructor() {
		this.app = 'MicroPayment IO'
		this.endPoints = {
			ajax: wp.ajax.settings.url,
			live: 'live.billingfox.com',
			test: 'test.billingfox.com',
		}
		this.url = null
		this.cacheData = null
	}

	send(type, method) {
		this.url = this.endPoints[ type ]
		this.method = method

		return true
	}

	process() {
		fetch(this.url, {
			method: this.method,
			body: this.cacheData
		}).then(r => console.log('xsd', r))
	}
}