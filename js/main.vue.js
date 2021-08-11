const app_Main = new Vue({
    el: '#app',
    mixins: [
        Utils
    ],
    data: {
        priceChart: null,
        holderChart: null,
        dataForXDays: 7,
        holders: [],
        monthlyHolders: [],
        prices: [],
        ranks: null,
        search: "",
        searchError: null,
        stats: null,
        top100: [],
        top1000: [],
        searchResult: []
    },
    methods: {
        initPriceChart(update) {
            let self = this

            let minPrice = Math.min.apply(null, this.holders.map(function(a){return a.price;}))
            if (minPrice > 1) {
                minPrice = Math.floor(minPrice)
            }

            const data = {
                datasets: [{
                    label: 'Price',
                    data: self.prices,
                    backgroundColor: '#cf022bBA',
                    borderColor: '#cf022bCA',
                    borderWidth: 1,
                    parsing: {
                        xAxisKey: 'time',
                        yAxisKey: 'price'
                    },
                    pointStyle: 'circle',
                    pointRadius: 0.2,
                    hoverRadius: 3
                }]
            }


            const config = {
                type: 'line',
                data: data,
                options: {
                    animation: false,
                    interaction: {
                        intersect: false,
                        mode: 'nearest',
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (tooltipItem) => {
                                    switch (tooltipItem.dataset.label) {
                                        case "Price":
                                            return "Price: $" + tooltipItem.formattedValue
                                    }
                                }
                            },
                            position: 'nearest'
                        },
                        zoom: {
                            pan: {
                                enabled: true,
                                mode: 'x'
                            },
                            zoom: {
                                wheel: {
                                    enabled: true,
                                },
                                pinch: {
                                    enabled: true
                                },
                                mode: 'x'
                            },
                            limits: {
                                x: {
                                    min: 'original',
                                    max: 'original'
                                },
                                y: {
                                    min: 'original',
                                    max: 'original'
                                }
                            }
                        }
                      },
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                minUnit: 'day',
                                tooltipFormat: 'll HH:mm',
                                displayFormats: {
                                    'day': 'MMM DD',
                                }
                            },
                            title: {
                                display: true,
                                text: "Time (UTC)"
                            }
                        },
                        y: {
                            ticks: {
                                callback: function(value, index, values) {
                                    return '$' + value;
                                }
                            },
                            min: minPrice
                        }
                    }
                }
            }

            if (update === true) {
                self.priceChart.data = data
                switch (this.dataForXDays) {
                    case 1:
                        self.priceChart.options.scales.x.time.minUnit = 'hour'
                        break;

                    default:
                        self.priceChart.options.scales.x.time.minUnit = 'day'
                        break;
                }
                self.priceChart.options.scales.y.min = minPrice
                
                self.priceChart.update()
                self.priceChart.resetZoom()
            } else {
                self.priceChart = new Chart(
                    document.getElementById('pricegraph'),
                    config
                )
            }
        },
        initHolderChart(update) {
            let self = this

            // let minHolders = Math.min.apply(null, this.holders.map(function(a){return a.total;}))
            // if ((minHolders * 1000) > 50) {
            //     minHolders = Math.floor(minHolders * 10) / 10
            // }
            let minHolders = 0

            const data = {
                datasets: [
                    {
                        label: 'Total',
                        data: self.holders,
                        backgroundColor: '#28a745',
                        borderColor: '#28a745',
                        borderWidth: 1,
                        parsing: {
                            xAxisKey: 'time',
                            yAxisKey: 'total'
                        },
                        pointStyle: 'circle',
                        pointRadius: 0.2,
                        hoverRadius: 3,
                        fill: {
                            target: 1,
                            above: 'rgba(40, 167, 69, 0.5)',
                            below: 'rgba(40, 167, 69, 0.5)'
                        }
                    },
                    {
                    label: 'Eth',
                    data: self.holders,
                    backgroundColor: '#457b9d',
                    borderColor: '#457b9d',
                    borderWidth: 1,
                    parsing: {
                        xAxisKey: 'time',
                        yAxisKey: 'eth'
                    },
                    pointStyle: 'circle',
                    pointRadius: 0.2,
                    hoverRadius: 3,
                    fill: {
                        target: 2,
                        above: 'rgba(69, 123, 157, 0.5)',
                        below: 'rgba(69, 123, 157, 0.5)'
                    }
                },
                    {
                        label: 'Bsc',
                        data: self.holders,
                        backgroundColor: '#ffc107',
                        borderColor: '#ffc107',
                        borderWidth: 1,
                        parsing: {
                            xAxisKey: 'time',
                            yAxisKey: 'bsc'
                        },
                        pointStyle: 'circle',
                        pointRadius: 0.2,
                        hoverRadius: 3,
                        fill: {
                            target: 'start',
                            above: 'rgba(255, 193, 7, 0.5)',
                            below: 'rgba(255, 193, 7, 0.5)'
                        }
                }]
            }


            const config = {
                type: 'line',
                data: data,
                options: {
                    animation: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (tooltipItem) => {
                                    return tooltipItem.dataset.label + ": " + tooltipItem.formattedValue + "K"
                                }
                            },
                            position: 'nearest'
                        },
                        zoom: {
                            pan: {
                                enabled: true,
                                mode: 'x'
                            },
                            zoom: {
                                wheel: {
                                    enabled: true,
                                },
                                pinch: {
                                    enabled: true
                                },
                                mode: 'x'
                            },
                            limits: {
                                x: {
                                    min: 'original',
                                    max: 'original'
                                },
                                y: {
                                    min: 'original',
                                    max: 'original'
                                }
                            }
                        }
                      },
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                minUnit: 'day',
                                tooltipFormat: 'll HH:mm',
                                displayFormats: {
                                    'day': 'MMM DD',
                                }
                            },
                            title: {
                                display: true,
                                text: "Time (UTC)"
                            }
                        },
                        y: {
                            ticks: {
                                callback: function(value, index, values) {
                                    return value + 'K';
                                }
                            },
                            min: minHolders
                        }
                    }
                }
            }

            if (update === true) {
                self.holderChart.data = data
                switch (this.dataForXDays) {
                    case 1:
                        self.holderChart.options.scales.x.time.minUnit = 'hour'
                        break;

                    default:
                        self.holderChart.options.scales.x.time.minUnit = 'day'
                        break;
                }

                self.holderChart.options.scales.y.min = minHolders
                
                self.holderChart.update()
                self.holderChart.resetZoom()
            } else {
                self.holderChart = new Chart(
                    document.getElementById('holdergraph'),
                    config
                )
            }
        },
        async getTop1000() {
            let self = this

            try {
                await api.getTop100().then((response) => {
                    self.top100 = response.slice(0, 100)
                    self.top1000 = response
                    if (self.search === "") {
                        self.searchResult = self.top100
                    }
                })
            } catch (e) {
                console.log(e)
            }
        },
        async getHolders(days) {
            let self = this

            try {
                await api.getHolders(days).then((response) => {
                    response.forEach(element => {
                        element.time = new Date(element.time)
                        element.total = element.total / 1000
                        element.eth = element.eth / 1000
                        element.bsc = element.bsc / 1000
                    })
                    self.holders = response
                })
            } catch (e) {
                console.log(e)
            }
        },
        async getMonthlyHolders() {
            let self = this

            try {
                await api.getHolders(30).then((response) => {
                    response.forEach(element => {
                        element.time = new Date(element.time)
                        element.total = element.total / 1000
                        element.eth = element.eth / 1000
                        element.bsc = element.bsc / 1000
                    })
                    self.monthlyHolders = response
                })
            } catch (e) {
                console.log(e)
            }
        },
        async getPrice(days) {
            let self = this

            try {
                await api.getPrice(days).then((response) => {
                    response.forEach(element => {
                        element.time = new Date(element.time)
                    })
                    self.prices = response
                })
            } catch (e) {
                console.log(e)
            }
        },
        async getStats() {
            let self = this

            try {
                await api.getStats().then((response) => {
                    self.stats = response[0]
                    self.stats.volume1d = Math.round(Number(self.stats.volume1d))
                    self.stats.volume7d = Math.round(Number(self.stats.volume7d))
                    self.stats.volume30d = Math.round(Number(self.stats.volume30d))
                })
                document.title = "$" + self.stats.price + " | Million Tracker"
            } catch (e) {
                console.log(e)
            }
        },
        async getRanks() {
            let self = this

            try {
                await api.getRanks().then((response) => {
                    self.ranks = response[0]
                })
            } catch (e) {
                console.log(e)
            }
        },
        async init() {
            await this.getMonthlyHolders()
            await this.getHolders(this.dataForXDays)
            await this.getPrice(this.dataForXDays)
            await this.getStats()
            await this.getRanks()
            if (this.priceChart == null) {
                this.initPriceChart()
            } else {
                this.initPriceChart(true)
            }

            
            if (this.holderChart == null) {
                this.initHolderChart()
            } else {
                this.initHolderChart(true)
            }

            await this.getTop1000()
        },
        performSearch () {
            if (this.notNullEmptyOrUndefined(this.search) && this.top1000.length > 0) {
                let result = this.top1000.filter(e => e.address.toLowerCase().startsWith(this.search.toLowerCase().trim()))
                if (this.notNullEmptyOrUndefined(result)) {
                    this.searchError = null
                    this.searchResult = result
                } else {
                    this.searchResult = []
                    this.searchError = "Found no addresses starting with '" + this.search + "' among top 1000 addresses."
                }
            } else if (this.search === "") {
                this.searchResult = this.top100
            }
        }
    },
    computed: {
        price1h: function () {
            if (this.prices.length > 0) {
                let oneHourAgo = this.addDate(this.getUTCDate(new Date()), -1, 'hour')
                let pastPrice = this.prices.find(day => day.time > oneHourAgo)
                let currentPrice = this.prices[this.prices.length - 1]
                if (!this.notNullEmptyOrUndefined(pastPrice) || !this.notNullEmptyOrUndefined(currentPrice)) {
                    return 0
                }
                return this.showTwoDecimals((((currentPrice.price / pastPrice.price) * 100) - 100))
            }
        },
        mcap: function () {
            if (this.stats != null) {
                var mcap = this.stats.marketcap / 1000000
                return this.showTwoDecimals(mcap)
            }
        },
        holders1h: function () {
            if (this.holders.length > 0 && this.monthlyHolders.length > 0) {
                let oneHourAgo = this.addDate(this.getUTCDate(new Date()), -1, 'hour')
                let pastHolders = this.monthlyHolders.find(day => day.time > oneHourAgo).total
                let currentHolders = this.monthlyHolders[this.monthlyHolders.length - 1].total
                return this.showTwoDecimals((((currentHolders / pastHolders) * 100) - 100))
            }
        },
        holders1d: function () {
            if (this.holders.length > 0 && this.monthlyHolders.length > 0) {
                let yesterday = this.addDate(this.getUTCDate(new Date()), -1, 'day')
                let pastHolders = this.monthlyHolders.find(day => day.time > yesterday).total
                let currentHolders = this.monthlyHolders[this.monthlyHolders.length - 1].total
                return this.showTwoDecimals((((currentHolders / pastHolders) * 100) - 100))
            }
        },
        holders7d: function () {
            if (this.holders.length > 0 && this.monthlyHolders.length > 0) {
                let weekago = this.addDate(this.getUTCDate(new Date()), -7, 'day')
                let pastHolders = this.monthlyHolders.find(day => day.time > weekago).total
                let currentHolders = this.monthlyHolders[this.monthlyHolders.length - 1].total
                return this.showTwoDecimals((((currentHolders / pastHolders) * 100) - 100))
            }
        },
        holders30d: function () {
            if (this.holders.length > 0 && this.monthlyHolders.length > 0) {
                let monthago = this.addDate(this.getUTCDate(new Date()), -30, 'day')
                let pastHolders = this.monthlyHolders.find(day => day.time > monthago).total
                let currentHolders = this.monthlyHolders[this.monthlyHolders.length - 1].total
                return this.showTwoDecimals((((currentHolders / pastHolders) * 100) - 100))
            }
        },
        volume1d: function () {
            if (this.stats != null) {
                var vol = this.stats.volume1d / 1000000
                return this.showTwoDecimals(vol)
            }
        },
        volume7d: function () {
            if (this.stats != null) {
                var vol = this.stats.volume7d / 1000000
                return this.showTwoDecimals(vol)
            }
        },
        volume30d: function () {
            if (this.stats != null) {
                var vol = this.stats.volume30d / 1000000
                return this.showTwoDecimals(vol)
            }
        },
        price1d: function () {
            if (this.stats != null) {
                return this.showTwoDecimals((this.stats.price1dpct * 100))
            }
        },
        price7d: function () {
            if (this.stats != null) {
                return this.showTwoDecimals((this.stats.price7dpct * 100))
            }
        },
        price30d: function () {
            if (this.stats != null) {
                return this.showTwoDecimals((this.stats.price30dpct * 100))
            }
        },
        top10pct: function() {
            if (this.top100.length > 0) {
                let pct = 0
                this.top100.filter(e => e.rank <= 10).forEach(o => { pct += parseFloat(o.percentage) })
                return pct.toFixed(2)
            }
        },
        top100pct: function() {
            if (this.top100.length > 0) {
                let pct = 0
                this.top100.forEach(o => { pct += parseFloat(o.percentage) })
                return pct.toFixed(2)
            }
        },
        top100Unipct: function() {
            if (this.top100.length > 0) {
                let pct = 0
                this.top100.filter(e => e.address.indexOf("Uniswap") >= 0).forEach(o => { pct += parseFloat(o.percentage) })
                return pct.toFixed(2)
            }
        },
        top100Bscpct: function() {
            if (this.top100.length > 0) {
                let pct = 0
                this.top100.filter(e => e.address.indexOf("AnySwap: BSC Bridge") >= 0).forEach(o => { pct += parseFloat(o.percentage) })
                return pct.toFixed(2)
            }
        },
        top100OrdinaryWalletsPct: function() {
            if (this.top100.length > 0) {
                let pct = 0
                this.top100.filter(e => e.address.indexOf("AnySwap: BSC Bridge") < 0 && e.address.indexOf("Uniswap") < 0).forEach(o => { pct += parseFloat(o.percentage) })
                return pct.toFixed(2)
            }
        }
    },
    created() {
        
    },
    mounted() {
        this.init()
        setInterval(this.getStats, 60000)
    }
});