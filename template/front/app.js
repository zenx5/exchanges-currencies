
const App = Vue.createApp({
    data(){
        return {
            loading: false,
            from:0,
            currencies:[],
            message:'hola mundo'
        }
    },
    async beforeMount(){
        console.log('before mounted')
    },
    computed:{
        fromCurrencies(){
            return []
        }
    }

})
