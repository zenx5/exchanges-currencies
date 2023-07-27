
const App = Vue.createApp({
    data(){
        return {
            loading: false,
            countdown: 180,
            state: 1,
            from: 2,
            to:0,
            mount:0,
            currencies: window.exchanges.currencies,
            changes: window.exchanges.changes,
            paymentDetails:''
        }
    },
    async beforeMount(){
        console.log('before mounted')
    },
    computed:{
        calculatedChange(){
            const change = this.changes.find( change => change.currency_from==this.from && change.currency_to==this.to )
            return parseFloat( change.rate ) * this.mount;
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
            this.paymentDetails = "lorem ipsun ...."
        },
        async reserve(){
            this.state++;
            console.log('reservar monto')
            setInterval( ()=>{
                this.countdown--;
            },1000)
        },
        async send(){
            document.location.reload()
        }
    }

})
