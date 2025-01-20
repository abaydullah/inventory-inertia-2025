<script setup>
import {computed, onUnmounted, ref, watch} from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import {usePurchaseStore} from "@/Store/usePurchaseStore.js";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import Header from "@/Layouts/Header.vue";
import 'vue-select/dist/vue-select.css';
import VSelect from "vue-select";
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from "@headlessui/vue";

onUnmounted(() => {
    usePurchaseStore().removeAllPurchase()
})

const date = ref(new Date());

const format = (date) => {
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
}
const props = defineProps({
    products: Object,
    filters: Object,
    groups: Object,
    errors: Object,
    categories: Object,
    accounts: Object,
    suppliers: Object,
    colors: Object,
    sizes: Object,
    units: Object,
})
let discount_types = [{name: "Percentage", id: 'percentage'}, {name: "Fixed", id: 'fixed'}];
const form = useForm({
    'subtotal': '',
    'discount': '0',
    'discount_type': 'fixed',
    'discount_amount': '',
    'total': '',
    'payment': '0',
    'due': '',
    'payer': '',
    'note': '',
    'receive': '',
    'return': '',
    'group_id': '',
    'supplier_id': '',
    'account_id': '',
    'date': date,
    'items': computed(() => usePurchaseStore().allPurchases)
});

let loading = ref(false);
const isOpen = ref(false)
const productStocks = ref([])


const openModal = (product) => {
    isOpen.value = true
    productStocks.value = product
}
const closeModal = () => {
    isOpen.value = false
    let qty = 0;
    let buy_price = 0;
    let sell_price = 0;
    if (productStocks.value.stocks.length > 0) {
        productStocks.value.stocks.forEach(stock => {
            qty += stock.qty;
            buy_price += stock.buy_price * stock.qty
            sell_price += stock.sell_price * stock.qty
        })
    }
    if (qty > 0) {
        productStocks.value.qty = qty;
        productStocks.value.total_buy_price = buy_price;
        productStocks.value.total_sell_price = sell_price;
    }
    productStocks.value = []


}
const decrement = (id) => {
    let p = usePurchaseStore().allPurchases.find(purchase => purchase.id === id)
    p.qty--;
    p.total_buy_price = p.buy_price * p.qty;
    p.total_sell_price = p.sell_price * p.qty;
}
const increment = (id) => {
    let p = usePurchaseStore().allPurchases.find(purchase => purchase.id === id)
    p.qty++;
    p.total_buy_price = p.buy_price * p.qty;
    p.total_sell_price = p.sell_price * p.qty;
}
const decrementStock = (id, stock) => {
    let p = usePurchaseStore().allPurchases.find(purchase => purchase.id === id)

    let s = p.stocks.find(st => st.id === stock)
    s.qty--;
}

const incrementStock = (id, stock) => {
    let p = usePurchaseStore().allPurchases.find(purchase => purchase.id === id)

    let s = p.stocks.find(st => st.id === stock)
    s.qty++;
}
const removeStock = (id, stock) => {
    let p = usePurchaseStore().allPurchases.find(purchase => purchase.id === id)
    p.stocks.splice(p.stocks.indexOf(stock), 1)
}
const stock = (stock) => {
    let id = productStocks.value.stocks.length + 1
    let stockData = {
        'id': id,
        'product_id': productStocks.value?.id,
        'buy_price': productStocks.value.buy_price,
        'sell_price': productStocks.value.sell_price,
        'color_id': '',
        'size_id': '',
        'unit_id': '',
        'unit_size': '',
        'qty': 1,
    }
    usePurchaseStore().setStock(productStocks.value, stockData)
}
watch(() => form.group_id, group_id => {
    productAll();

})
watch(() => [form.subtotal, form.receive, form.payment, form.discount_type, form.discount, form.discount_amount], (type, discount = 0, amount = 0) => {
    if (form.discount) {

        if (form.discount_type === 'fixed') {
            form.discount_amount = parseFloat(form.discount);
        } else {
            form.discount_amount = parseInt(form.subtotal / 100 * form.discount)
        }
    }
    if (form.receive >= form.payment) {
        form.return = form.receive - form.payment

    }
    form.total = form.subtotal - form.discount_amount
    form.due = (form.subtotal - form.discount_amount) - form.payment
})

const productsAll = ref(props.products);
const productAll = () => {
    productsAll.value = props.products.filter((product) => {
        if (form.group_id) {
            if (form.group_id === product.group_id) {

                return !usePurchaseStore().allPurchases.find(purchase => purchase.id === product.id)
            }
        } else {
            return !usePurchaseStore().allPurchases.find(purchase => purchase.id === product.id)

        }
    })
}
const totalQty = ref(0);
const totalSell = ref(0);
const totalPrice = () => {
    let totalPrice = 0;
    let totalSellPrice = 0;
    let qty = 0;
    usePurchaseStore().allPurchases.forEach(purchase => {
        totalPrice += parseFloat(purchase.total_buy_price)
        totalSellPrice += parseFloat(purchase.total_sell_price)
        qty += parseInt(purchase.qty)
    })
    totalQty.value = qty
    totalSell.value = totalSellPrice
    form.subtotal = totalPrice
    return totalPrice;
}

const removePurchase = (product) => {
    usePurchaseStore().removePurchase(product)
    productAll()
}
let submit = () => {
    // loading.value = true;
    form.post(route('purchases.store'), {
        preserveScroll: true,
        onSuccess: () => {
            //  loading.value = false;
            usePurchaseStore().removeAllPurchase()
            router.get(route('purchases.index'))
        },
        onError: () => {
            loading.value = false;
        }
    });
}
const selectedProduct = (product) => {
    usePurchaseStore().setPurchase(product)
    productAll()

}
const selectedUnit = (stock) => {
    setTimeout(() => {
        let unit = props.units.find(u => u.id === stock.unit_id)
        stock.unit_size = unit.size
        stock.unit_id = unit.id
    }, 200)
}
const groupAllProduct = () => {
    usePurchaseStore().setPurchases(productsAll.value)
    productAll()
}

</script>

<template>
    <Head :title="__('main.purchase-list')"/>

    <AuthenticatedLayout>

        <div class="fixed flex items-center justify-center h-screen z-50 w-full bg-slate-100 opacity-25 inset-10"
             v-if="loading">

            <ion-icon name="sync" class="animate-spin text-6xl"></ion-icon>

        </div>

        <div class="py-10 max-w-8xl mx-auto">
            <div id="content" class="">
                <Header title="main.purchase-list"></Header>
                <div class="py-10">
                    <button class="py-2 px-3 bg-green-500 rounded-md font-semibold text-white float-right "
                            @click="submit">
                        Save
                    </button>
                </div>
                <div class="shadow-md sm:rounded-lg mt-5">
                    <div class="grid gap-4 md:grid-cols-5 sm:grid-cols-2">
                        <div class="mt-2">
                            <label for="date" class="text-lg font-semibold px-2">Date</label>
                            <VueDatePicker class="mt-2 p-0" v-model="form.date" :format="format"
                                           :preview-format="format"
                                           auto-apply
                            ></VueDatePicker>

                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="form.errors.date">{{ form.errors.date }}</span>
                        </div>

                        <div class="mt-2">
                            <label for="group_id" class="text-lg font-semibold px-2">Supplier</label>
                            <v-select class="mt-2 style-chooser w-full" v-model="form.supplier_id"
                                      :reduce="supplier => supplier.id" label="company_name"
                                      :options="suppliers" placeholder="Select Supplier" :required="!form.supplier_id">

                                <template #option="{ company_name}">
                                    {{ company_name }}
                                </template>

                            </v-select>

                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.supplier_id">{{
                                    errors.supplier_id
                                }}</span>
                        </div>
                        <div class="relative mt-2 w-full">
                            <label for="group_id" class="text-lg font-semibold px-2">Group</label>
                            <v-select class="mt-2 style-chooser"
                                      v-model="form.group_id"
                                      :reduce="group => group.id" label="name"
                                      :options="groups" placeholder="Select Group">

                                <template #group="{ name}">
                                    {{ name }}
                                </template>

                            </v-select>
                        </div>
                        <div class="mt-2">
                            <label for="barcode" class="text-lg font-semibold px-2">Barcode</label>
                            <div class="relative ">
                                <ion-icon name="barcode-outline" class="absolute z-10 text-6xl"></ion-icon>
                                <input type="text"
                                       class="pl-16 absolute top-0 left-0 form-input px-4 py-3 rounded-md w-full my-2"
                                       id="barcode" v-model="form.barcode"
                                       placeholder="Enter Your Product Barcode">
                            </div>
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="form.errors.barcode">{{ form.errors.barcode }}</span>
                        </div>
                        <div class="relative mt-2 w-full">
                            <label for="group_id" class="text-lg font-semibold px-2 ">Products</label>
                            <button v-if="form.group_id && productsAll.length > 0" @click="groupAllProduct"
                                    class="bg-blue-500 text-gray-50 py-1 px-2 rounded-sm font-semibold">Group All
                                Product Select
                            </button>
                            <v-select class="mt-2 style-chooser"
                                      @update:modelValue="selectedProduct"
                                      :reduce="product => product" label="name"
                                      :options="productsAll" placeholder="Select Product">

                                <template #group="{ name}">

                                    {{ name }}
                                </template>

                            </v-select>

                        </div>
                    </div>
                    <div class="mt-10 w-full">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="space-x-2 space-y-2 border  divide-x-2">
                                <th scope="col" class="p-2" style="width: 5%;">ID</th>
                                <th scope="col" class="p-2" style="width: 20%;">Product Name</th>
                                <th scope="col" class="p-2" style="width: 20%;">QTY</th>
                                <th scope="col" class="p-2" style="width: 15%;">Buy Price</th>
                                <th scope="col" class="p-2" style="width: 10%;">Total Buy Price</th>
                                <th scope="col" class="p-2" style="width: 15%;">Sell Price</th>
                                <th scope="col" class="p-2" style="width: 10%;">Total Sell Price</th>
                                <th scope="col" class="p-2" style="width: 5%;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border divide-x-2 text-sm"
                                v-for="(product, key) in usePurchaseStore().allPurchases">
                                <td style="width: 5%;" class="text-center text-2xl font-semibold">{{ key + 1 }}</td>
                                <td style="width: 20%;" class="text-left px-2 ">{{ product.name }}</td>

                                <td style="width: 20%;" class="">

                                    <div class="flex bg-slate-50 border-0 text-end justify-around w-full">
                                        <button v-if="product.stocks.length <= 0 " type="button"
                                                @click="decrement(product.id)"
                                                class="m-0 w-3/12 bg-red-500 py-0 px-2 text-white font-extrabold text-xl rounded-sm">
                                            -
                                        </button>

                                        <button v-else type="button" disabled
                                                class="m-0 w-3/12 bg-red-500 py-0 px-2 text-white font-extrabold text-xl rounded-sm">
                                            -
                                        </button>
                                        <input type="number"
                                               class="text-center m-0 w-6/12 rounded-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                               v-model="product.qty">
                                        <button v-if="product.stocks.length <= 0 " type="button"
                                                @click="increment(product.id)"
                                                class="w-3/12 bg-green-500 m-0 py-0 px-2 text-white font-extrabold text-xl rounded-sm">
                                            +
                                        </button>
                                        <button v-else type="button" disabled
                                                class="w-3/12 bg-green-500 m-0 py-0 px-2 text-white font-extrabold text-xl rounded-sm">
                                            +
                                        </button>
                                        <button type="button" @click="openModal(product)"
                                                class="ml-2 w-3/12 bg-blue-500 m-0 py-0 px-2 text-white font-extrabold text-xl rounded-sm">
                                            <ion-icon class="text-2xl" name="list-outline"></ion-icon>
                                        </button>
                                    </div>

                                </td>
                                <td style="width: 15%;" class="">
                                    <input type="number" class="w-full bg-slate-50 text-end"
                                           v-model="product.buy_price"></td>
                                <td style="width: 10%;" class="px-2 text-end">{{ product.total_buy_price }}</td>
                                <td style="width: 15%;" class=""><input type="number"
                                                                        class="w-full bg-slate-50 text-end"
                                                                        v-model="product.sell_price"></td>
                                <td style="width: 10%;" class="px-2 text-end">{{ product.total_sell_price }}
                                </td>
                                <td style="width: 5%;" class="text-center">
                                    <button class=" rounded-md" @click="removePurchase(product)">
                                        <ion-icon name="trash" class="text-2xl text-red-600"></ion-icon>
                                    </button>
                                </td>
                            </tr>
                            </tbody>

                        </table>
                        <table
                            class="text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 w-full table-auto">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 w-full">
                            <tr class="space-x-2 space-y-2 border  divide-x-2">
                                <th scope="col" class="p-2" style="width: 5%;"></th>
                                <th scope="col" class="p-2" style="width: 15%;"></th>
                                <th scope="col" class="p-2" style="width: 5%;">Total</th>
                                <th scope="col" class="p-2 text-center" style="width: 20%;">{{ totalQty }}</th>
                                <th scope="col" class="p-2" style="width: 15%;"></th>
                                <th scope="col" class="p-2 text-right" style="width: 10%;">{{ totalPrice() }}</th>
                                <th scope="col" class="p-2" style="width: 15%;"></th>
                                <th scope="col" class="p-2 text-right" style="width: 10%;">{{ totalSell }}</th>
                                <th scope="col" class="p-2" style="width: 5%;"></th>
                            </tr>
                            </thead>

                        </table>
                        <table
                            class="text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 w-full table-auto">
                            <thead>
                            <tr class="space-x-2 space-y-2 border  divide-x-2">
                                <th scope="col" colspan="5"></th>
                                <th scope="col" class="p-2">Discount Type</th>
                                <th scope="col" class="p-2">Discount</th>
                                <th scope="col" class="p-2 text-right" style="width: 10%;">Subtotal</th>
                                <th scope="col" class="p-2 text-right" style="width: 5%;">{{ totalPrice() }}</th>
                            </tr>


                            <tr class="space-x-2 space-y-2 border  divide-x-2">
                                <th scope="col" colspan="5"></th>
                                <th scope="col" class=" text-right" style="width: 20%;">

                                    <v-select class="" v-model="form.discount_type"
                                              :reduce="discount => discount.id" label="name"
                                              :options="discount_types" placeholder="Select Discount">

                                    </v-select>

                                </th>
                                <th scope="col" class="" style="width: 10%;">
                                    <input type="number" class="m-0 py-1 px-2 bg-slate-50 rounded-md"
                                           v-model="form.discount">
                                </th>
                                <th scope="col" class="p-2 text-right" style="width: 10%;">Discount</th>
                                <th scope="col" class="p-2 text-right" style="width: 5%;">{{
                                        form.discount_amount
                                    }}
                                </th>
                            </tr>
                            <tr class="space-x-2 space-y-2 border  divide-x-2">
                                <th scope="col" colspan="3" class="p-2">Note</th>

                                <th scope="col" class="p-2">Receive</th>
                                <th scope="col" class="p-2">Return</th>
                                <th scope="col" class="p-2">Select Account</th>
                                <th scope="col" class="p-2">Payment</th>
                                <th scope="col" class="p-2 text-right" style="width: 10%;">Total</th>
                                <th scope="col" class="p-2 text-right" style="width: 5%;">{{ form.total }}</th>
                            </tr>
                            <tr class="space-x-2 space-y-2 border  divide-x-2">
                                <th scope="col" colspan="3"><input id="note" type="text"
                                                                   class="w-full m-0 py-1 px-2 bg-slate-50 rounded-md"
                                                                   v-model="form.note"
                                                                   placeholder="Enter Payment Note"></th>
                                <th scope="col" class=" text-center" style="width: 10%;"><input
                                    id="receive"
                                    type="number"
                                    class="w-full m-0 py-1 px-2 bg-slate-50 rounded-md"
                                    v-model="form.receive">
                                </th>
                                <th scope="col" class="" style="width: 10%;"><input id="return"
                                                                                    type="number"
                                                                                    class="w-full m-0 py-1 px-2 bg-slate-50 rounded-md"
                                                                                    v-model="form.return"></th>
                                <th scope="col" class=" text-right" style="width: 20%;">
                                    <v-select class="" v-model="form.account_id"
                                              :reduce="account => account.id" label="name"
                                              :options="accounts" placeholder="Select Account">

                                    </v-select>
                                </th>
                                <th scope="col" class="" style="width: 10%;"><input
                                    type="number"
                                    class="w-full m-0 py-1 px-2 bg-slate-50 rounded-md"
                                    v-model="form.payment"></th>
                                <th scope="col" class="p-2 text-right" style="width: 10%;">Payment</th>
                                <th scope="col" class="p-2 text-right" style="width: 5%;">{{ form.payment }}</th>
                            </tr>
                            <tr class="space-x-2 space-y-2 border  divide-x-2">
                                <th scope="col" colspan="7"></th>

                                <th scope="col" class="p-2 text-right" style="width: 10%;">Due</th>
                                <th scope="col" class="p-2 text-right" style="width: 5%;">{{ form.due }}</th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>


            </div>

            <!--Start Add Modal-->
            <TransitionRoot appear :show="isOpen" as="template">
                <Dialog as="div" @close="closeModal" class="relative z-10">
                    <TransitionChild
                        as="template"
                        enter="duration-500 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-500 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/25"/>
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div
                            class="flex min-h-full items-center justify-center p-4 text-center "
                        >
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel
                                    class="w-full max-w-7xl transform  rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                                >

                                    <div class="flex justify-between justify-items-center items-center py-2">
                                        <DialogTitle
                                            as="h3"
                                            class="text-lg font-medium leading-6 text-gray-900"
                                        >
                                            Manage Stock

                                        </DialogTitle>
                                        <div>

                                            <button type="button"
                                                    class="text-5xl leading-6 text-gray-400 font-normal"
                                                    @click="closeModal" data-bs-dismiss="modal" aria-label="Close">
                                                &times;
                                            </button>
                                        </div>

                                    </div>
                                    <hr>

                                    <div class="">
                                        <table
                                            class=" text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 w-full table-auto">

                                            <thead
                                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr class="space-x-2 space-y-2 border  divide-x-2 w-full">
                                                <th scope="col" class="p-2" style="width: 5%;">ID</th>
                                                <th scope="col" class="p-2" style="width: 15%;">QTY</th>
                                                <th scope="col" class="p-2" style="width: 10%;">Buy Price</th>
                                                <th scope="col" class="p-2" style="width: 10%;">Sell Price</th>
                                                <th scope="col" class="p-2" style="width: 15%;">Color</th>
                                                <th scope="col" class="p-2" style="width: 15%;">Size</th>
                                                <th scope="col" class="p-2" style="width: 15%;">Unit</th>
                                                <th scope="col" class="p-2" style="width: 10%;">Unit Size</th>
                                                <th scope="col" class="p-2" style="width: 5%;">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="border divide-x-2"
                                                v-for="(stock, key) in productStocks.stocks">

                                                <td scope="col" class="p-2" style="width: 5%;"> {{ key + 1 }}</td>
                                                <td scope="col" class="p-2" style="width: 15%;">
                                                    <div
                                                        class="flex bg-slate-50 w-full border-0 text-end justify-around ">
                                                        <button type="button"
                                                                @click="decrementStock(productStocks.id,stock.id)"
                                                                class="m-0 w-3/12 bg-red-500 py-0 px-2 text-white font-extrabold text-xl rounded-sm">
                                                            -
                                                        </button>
                                                        <input type="number"
                                                               class="text-center m-0 w-6/12 rounded-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                               v-model="stock.qty">
                                                        <button type="button"
                                                                @click="incrementStock(productStocks.id,stock.id)"
                                                                class="w-3/12 bg-green-500 m-0 py-0 px-2 text-white font-extrabold text-xl rounded-sm">
                                                            +
                                                        </button>
                                                    </div>
                                                </td>
                                                <td scope="col" class="p-2" style="width: 8%;">
                                                    <input type="number" class=" bg-slate-50 w-full text-end"
                                                           v-model="stock.buy_price"></td>
                                                <td scope="col" class="p-2" style="width: 8%;"><input type="number"
                                                                                                      class=" bg-slate-50 w-full text-end"
                                                                                                      v-model="stock.sell_price">
                                                </td>
                                                <td scope="col" class="p-2" style="width: 15%;">
                                                    <v-select class="style-chooser1"
                                                              v-model="stock.color_id"
                                                              :reduce="color => color.id" label="name"
                                                              :options="colors" placeholder="Select Color">

                                                    </v-select>
                                                </td>
                                                <td scope="col" class="p-2" style="width: 15%;">
                                                    <v-select class="style-chooser1" v-model="stock.size_id"
                                                              :reduce="size => size.id" label="name"
                                                              :options="sizes" placeholder="Select Size">

                                                    </v-select>
                                                </td>
                                                <td scope="col" class="p-2" style="width: 15%;">
                                                    <v-select class="style-chooser1"
                                                              @update:modelValue="selectedUnit(stock)"
                                                              v-model="stock.unit_id"
                                                              :reduce="unit => unit.id" label="name"
                                                              :options="units" placeholder="Select Unit">

                                                    </v-select>
                                                </td>
                                                <td scope="col" class="p-2" style="width: 10%;"><input type="number"
                                                                                                       class=" bg-slate-50 w-full text-end"
                                                                                                       v-model="stock.unit_size">
                                                </td>
                                                <td scope="col" class="p-2" style="width: 10%;">
                                                    <button class=" rounded-md"
                                                            @click="removeStock(productStocks.id,stock)">
                                                        <ion-icon name="trash" class="text-2xl text-red-600"></ion-icon>
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot>


                                            </tfoot>

                                        </table>
                                    </div>
                                    <div class="mt-4 flex justify-between space-x-2">
                                        <button type="button"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-teal-400 px-4 py-2 text-sm font-semibold text-gray-100 hover:bg-teal-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                @click="stock">Add More
                                        </button>


                                        <button type="button"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                @click="closeModal">Close
                                        </button>

                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
            <!--End Add Modal-->
        </div>
    </AuthenticatedLayout>
</template>
<style>

.style-chooser .vs__search::placeholder,
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {

    padding: 10px 8px;
}

.style-chooser1 .vs__search::placeholder,
.style-chooser1 .vs__dropdown-toggle,
.style-chooser1 .vs__dropdown-menu {

    padding: 7px 8px;
    z-index: 10;
}

:root {
    --dp-input-padding: 12px 30px 12px 12px;
    --vs-dropdown-bg: #fff;
    --vs-dropdown-color: inherit;
    --vs-dropdown-z-index: 1000;
    --vs-dropdown-min-width: 160px;
    --vs-dropdown-max-height: 1250px;
}
</style>
