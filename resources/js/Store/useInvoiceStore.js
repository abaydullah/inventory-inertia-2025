import {defineStore} from "pinia";

export const useInvoiceStore = defineStore('invoice', {
    state: () => ({
        invoices: [],
        deposits: []
    }),
    actions: {
        setAllInvoices(products) {
            this.invoices = products
        },
        setAllDeposits(deposits) {
            this.deposits = deposits
        },
        setInvoice(product) {
            let found = this.invoices.find(p => p?.id === product?.id)

            if (found) {
                // found.qty++;
                found.total_buy_price = found.buy_price * found.qty;
                found.total_sell_price = found.sell_price * found.qty;
            } else {
                product.stocks.length > 0 ? product.qty = 0 : product.qty = 1;
                product.sells = [];
                product.total_buy_price = product.buy_price * product.qty;
                product.total_sell_price = product.sell_price * product.qty;
                product.total_price = product.sell_price;
                product.discount = 0;
                product.discount_type = 'fixed';
                product.discount_amount = 0;
                this.invoices.push(product)
            }
        },
        setInvoices(products) {
            products.map(product => {
                product.stocks.length > 0 ? product.qty = 0 : product.qty = 1;
                product.sells = [];
                product.total_buy_price = product.buy_price * product.qty;
                product.total_sell_price = product.sell_price * product.qty;
                product.total_price = product.sell_price;
                product.discount = 0;
                product.discount_type = 'fixed';
                product.discount_amount = 0;
                this.invoices.push(product)
            })
        },
        removeInvoice(product) {
            this.invoices.splice(this.invoices.indexOf(product), 1)
        },
        removeAllInvoice() {
            this.invoices = []
        },
        setSell(product, sell) {
            let data = this.invoices.find(p => p?.id === product?.id)
            data.sells.push(sell);
        },
        setStock(product, stock) {
            let data = this.invoices.find(p => p?.id === product?.id)
            data.stocks.push(stock);
        }
    },
    getters: {
        allInvoices(state) {
            return state.invoices
        },
        allDeposits(state) {

            return state.deposits
        }
    }
})
