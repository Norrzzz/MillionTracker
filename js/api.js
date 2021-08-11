var api = new Vue({
    data() {
        return {
            url: '/lib/controller.php'
        }
    },
    methods: {
        async newRequest(request, params = null) {
            let data = new FormData()
            data.append('request', request)

            for (var key in params) {
                data.append(key, params[key])
            }

            return await axios.post(this.url, data, {
                headers: {
                    'Content-type': 'application/x-www-form-urlencoded',
                }
            }).then(response => {
                return response.data
            }).catch(e => {
                console.log(e)
            });
        },
        async getHolders(days) {
            return this.newRequest('getHolders', { days }).then(response => { return response })
        },
        async getPrice(days) {
            return this.newRequest('getPrice', { days }).then(response => { return response })
        },
        async getStats() {
            return this.newRequest('getStats').then(response => { return response })
        },
        async getRanks() {
            return this.newRequest('getRanks').then(response => { return response })
        },
        async getTop100() {
            return this.newRequest('getTopHundred').then(response => { return response })
        }
    }
});