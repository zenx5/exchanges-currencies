
const App = Vue.createApp({
    data(){
        return {
            loading: false,
            countdown: 180,
            state: 1,
            operation: 0,
            reference: '',
            from: 2,
            to:0,
            mount:0,
            currencies: window.exchanges.currencies,
            changes: window.exchanges.changes,
            paymentDetails:'',
            interval: 0
        }
    },
    async beforeMount(){
        console.log('before mounted')
    },
    computed:{
        calculatedChange(){
            const change = this.changes.find( change => change.currency_from==this.from && change.currency_to==this.to )
            return parseFloat( change.type ? change.rate : 1/change.rate ) * this.mount;
        },
        maxAvalaible(){
            return this.currencies.find( currency => currency.id==this.to )?.founds || 0
        },
        symbol(){
            return {
                from: this.currencies.find( currency => currency.id==this.from )?.symbol || '-',
                to: this.currencies.find( currency => currency.id==this.to )?.symbol || '-'
            }
        },
        toCurrencies(){
            const ids = this.changes.filter( change => change.currency_from==this.from ).map( change => change.currency_to )
            this.to = ids.at(0) || 0;
            return ids.map( id => this.currencies.find( currency => currency.id==id ) )
        }
    },
    methods:{
        async loadPaymentMethod(){
            this.paymentDetails = this.currencies.find( currency => currency.id == this.to )?.details
        },
        async reserve(){
            this.state++;
            this.loading = true
            const id = this.changes.find( change => change.currency_from==this.from && change.currency_to==this.to )?.id
            if( id ) {
                const response = await fetch(document.location.origin + '/wp-admin/admin-ajax.php',{
                    method:'post',
                    headers:{
                        'Content-Type':'application/x-www-form-urlencoded'
                    },
                    body:`action=create_operation&exchange_id=${id}&mount=${this.mount}&to_pay=${this.calculatedChange}`,
                })
                const result = await response.json()
                console.log( result )
                this.operation = result.id
                this.interval = setInterval( () => {
                    this.countdown--
                    if( this.countdown==0 ) {
                        clearInterval( this.interval )
                        this.reference = "canceled"
                        this.send()
                    }
                },1000)
            }
            this.loading = false
        },
        async send(){
            this.loading = true
            const response = await fetch(document.location.origin + '/wp-admin/admin-ajax.php',{
                method:'post',
                headers:{
                    'Content-Type':'application/x-www-form-urlencoded'
                },
                body:`action=update_operation&id=${this.operation}&reference=${this.reference}`,
            })
            const result = await response.json()
            console.log( result )
            document.location.reload()
            this.loading = false
        }
    }

})
