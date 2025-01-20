import {defineStore} from "pinia";

export const usePurchaseStore = defineStore('purchase', {
    state: () => ({
        purchases: [],
        withdraws: []
    }),
    actions: {
        setAllPurchases(products) {
            this.purchases = products
        },
        setAllWithdraws(withdraws) {
            this.withdraws = withdraws
        },
        setPurchase(product) {
            let found = this.purchases.find(p => p?.id === product?.id)

            if (found) {
                found.qty++;
                found.total_buy_price = found.buy_price * found.qty;
                found.total_sell_price = found.sell_price * found.qty;
            } else {
                product.qty = 1;
                product.stocks = [];
                product.total_buy_price = product.buy_price * product.qty;
                product.total_sell_price = product.sell_price * product.qty;
                this.purchases.push(product)
            }
        },
        setPurchases(products) {
            products.map(product => {
                product.qty = 1;
                product.stocks = [];
                product.total_buy_price = product.buy_price * product.qty;
                product.total_sell_price = product.sell_price * product.qty;
                this.purchases.push(product)
            })
        },
        removePurchase(product) {
            this.purchases.splice(this.purchases.indexOf(product), 1)
        },
        removeAllPurchase() {
            this.purchases = []
        },
        setStock(product, stock) {
            let data = this.purchases.find(p => p?.id === product?.id)
            data.stocks.push(stock);
        }
    },
    getters: {
        allPurchases(state) {
            return state.purchases
        },
        allWithdraws(state) {

            return state.withdraws
        }
    }
})
