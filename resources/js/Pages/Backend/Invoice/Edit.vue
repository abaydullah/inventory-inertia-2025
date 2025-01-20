<script setup>
import {computed, onMounted, ref, watch} from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import Header from "@/Layouts/Header.vue";
import 'vue-select/dist/vue-select.css';
import VSelect from "vue-select";

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
    purchase: Object
})
onMounted(() => updatePayment())
onMounted(() => productAll())

let discount_types = [{name: "Percentage", id: 'percentage'}, {name: "Fixed", id: 'fixed'}];
const form = useForm({
    '_method': 'PUT',
    'id': props.purchase.id,
    'subtotal': props.purchase.subtotal,
    'discount': props.purchase.discount,
    'discount_type': props.purchase.discount_type,
    'discount_amount': props.purchase.discount_amount,
    'total': props.purchase.total,
    'payment': props.purchase.payment,
    'due': props.purchase.due,
    'payer': props.purchase.payer,
    'note': props.purchase.note,
    'receive': props.purchase.receive,
    'return': props.purchase.return,
    'group_id': props.purchase.group_id,
    'supplier_id': props.purchase.supplier_id,
    'account_id': props.purchase.account_id,
    'date': props.purchase.date,
    'items': computed(() => usePurchaseUpdate().allPurchases),
    'withdraws': computed(() => usePurchaseUpdate().allWithdraws)
});
let editForm = useForm({
    '_method': 'PUT',
    'id': '',
    'subtotal': '',
    'discount': '',
    'discount_type': '',
    'discount_amount': '',
    'total': '',
    'payment': '',
    'due': '',
    'group_id': '',
    'supplier_id': '',
    'account_id': '',
    'date': date,
});
const image = ref(null);
let loading = ref(false);
const isOpen = ref(false)
const isOpenUpdateModal = ref(false)

const decrement = (id) => {
    let p = usePurchaseUpdate().allPurchases.find(purchase => purchase.id === id)
    p.qty--;
    p.total_buy_price = p.buy_price * p.qty;
    p.total_sell_price = p.sell_price * p.qty;
}
const increment = (id) => {
    let p = usePurchaseUpdate().allPurchases.find(purchase => purchase.id === id)
    p.qty++;
    p.total_buy_price = p.buy_price * p.qty;
    p.total_sell_price = p.sell_price * p.qty;
}
const updatePayment = () => {
    form.payment = paymentTotal();
}
watch(() => [usePurchaseUpdate().allWithdraws, form.subtotal, form.receive, form.payment, form.discount_type, form.discount, form.discount_amount], (type, discount = 0, amount = 0) => {


    if (form.discount) {

        if (form.discount_type === 'fixed') {
            form.discount_amount = parseFloat(form.discount);
        } else {
            form.discount_amount = parseInt(form.subtotal / 100 * form.discount)
        }
    }

    form.payment = paymentTotal();
    if (form.receive >= form.payment) {
        form.return = form.receive - form.payment

    }
    form.total = form.subtotal - form.discount_amount
    form.due = (form.subtotal - form.discount_amount) - form.payment

})
const paymentTotal = () => {
    let payment = 0;
    usePurchaseUpdate().allWithdraws.forEach(withdraw => {
        payment += withdraw.amount;
        if (withdraw.receive > 0) {
            withdraw.return = withdraw.receive - withdraw.amount

        }
    })

    return payment;
}


const productsAll = ref(props.products);
const productAll = () => {
    productsAll.value = props.products.filter((product) => {
        return !usePurchaseUpdate().allPurchases.find(purchase => purchase.id === product.id)
    })
}
const totalQty = ref(0);
const totalPrice = () => {
    let totalPrice = 0;
    let qty = 0;
    usePurchaseUpdate().allPurchases.forEach(purchase => {
        totalPrice += parseFloat(purchase.buy_price * purchase.qty)
        qty += parseInt(purchase.qty)
    })
    totalQty.value = qty
    form.subtotal = totalPrice
    return totalPrice;
}
const imagePreview = computed(() => {
    if (!image.value) {
        return;
    }
    form.image = image.value
    return URL.createObjectURL(image.value);
});


const removePurchase = (product) => {
    usePurchaseUpdate().removePurchase(product)
    productAll()
}

let submit = () => {
    form.post(route('purchases.update', form.id), {
        preserveScroll: true,
        onSuccess: () => {

        },
        onError: () => {
            loading.value = false;
        }
    });
}


const selectedProduct = (product) => {
    usePurchaseUpdate().setPurchase(product)
    productAll()

}


</script>

<template>
    <Head :title="__('main.purchase-edit')"/>

    <AuthenticatedLayout>

        <div class="fixed flex items-center justify-center h-screen z-50 w-full bg-slate-100 opacity-25 inset-10"
             v-if="loading">

            <ion-icon name="sync" class="animate-spin text-6xl"></ion-icon>

        </div>

        <div class="py-10 max-w-8xl mx-auto">

            <div id="content" class="">
                <Header title="main.purchase-edit"></Header>
                <div class="py-10">
                    <button class="py-2 px-3 bg-green-500 rounded-md font-semibold text-white float-right "
                            @click="submit">
                        Update
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
                            <label for="barcode" class="text-lg font-semibold px-2">Barcode</label>
                            <div class="relative ">
                                <ion-icon name="barcode-outline" class="absolute z-40 text-6xl"></ion-icon>
                                <input type="text"
                                       class="pl-16 absolute top-0 left-0 form-input px-4 py-3 rounded-md w-full my-2"
                                       id="barcode" v-model="form.barcode"
                                       placeholder="Enter Your Product Barcode">
                            </div>
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="form.errors.barcode">{{ form.errors.barcode }}</span>
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
                        <div class="relative mt-2 w-full">
                            <label for="group_id" class="text-lg font-semibold px-2">Products</label>
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
                            <tr class="space-x-2 space-y-2 border  divide-x-2 w-full">
                                <th scope="col" class="p-2 w-1/12">ID</th>
                                <th scope="col" class="p-2 w-4/12">Product Name</th>
                                <th scope="col" class="p-2 w-2/12">QTY</th>
                                <th scope="col" class="p-2 w-1/12">Buy Price</th>
                                <th scope="col" class="p-2 w-1/12">Total Buy Price</th>
                                <th scope="col" class="p-2 w-1/12">Sell Price</th>
                                <th scope="col" class="p-2 w-1/12">Total Sell Price</th>
                                <th scope="col" class="p-2 w-1/12">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border divide-x-2 w-full text-sm"
                                v-for="(product, key) in usePurchaseUpdate().allPurchases">
                                <td class="text-center text-2xl font-semibold">{{ key + 1 }}</td>
                                <td class="text-left px-2 ">{{ product.name }}</td>
                                <td class="w-2/12 px-1">

                                    <div class="flex bg-slate-50 w-full border-0 text-end justify-around ">
                                        <button type="button" @click="decrement(product.id)"
                                                class="m-0 w-3/12 bg-red-500 py-0 px-2 text-white font-extrabold text-xl rounded-sm">
                                            -
                                        </button>
                                        <input type="number"
                                               class="text-center m-0 w-6/12 rounded-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                               v-model="product.qty">
                                        <button type="button" @click="increment(product.id)"
                                                class="w-3/12 bg-green-500 m-0 py-0 px-2 text-white font-extrabold text-xl rounded-sm">
                                            +
                                        </button>
                                    </div>

                                </td>
                                <td class="">

                                    <input type="number" class=" bg-slate-50 w-full text-end"
                                           v-model="product.buy_price"></td>
                                <td class="px-2 text-end">{{ product.buy_price * product.qty }}</td>
                                <td class=""><input type="number" class=" bg-slate-50 w-full text-end"
                                                    v-model="product.buy_price"></td>
                                <td class="px-2 text-end"><input type="number" class=" bg-slate-50 w-full text-end"
                                                                 v-model="product.total_buy_price">
                                </td>
                                <td class="text-center">
                                    <button class=" rounded-md" @click="removePurchase(product)">
                                        <ion-icon name="trash" class="text-2xl text-red-600"></ion-icon>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr class="border space-x-2 space-y-2 divide-x-2">
                                <th class="p-2"></th>
                                <th class="p-2 text-right">Total</th>
                                <th class="p-2 text-center">{{ totalQty }}</th>
                                <th class="p-2"></th>
                                <th class="p-2"></th>
                                <th class="p-2"></th>

                                <th class="p-2 text-end">{{ totalPrice() }}</th>
                                <th class="p-2"></th>
                            </tr>
                            <tr class="space-x-2 space-y-2 divide-x-2">
                                <th class="p-2" colspan="6"></th>
                                <th class="p-2">Subtotal</th>
                                <th class="p-2">{{ totalPrice() }}</th>

                            </tr>
                            <tr class="border space-x-2 space-y-2 divide-x-2">
                                <th class="p-2"></th>
                                <th class="p-2">
                                </th>
                                <th class="p-2" colspan="2"></th>
                                <th colspan="2" class="float-right">
                                    <v-select class="style-chooser1 w-64" v-model="form.discount_type"
                                              :reduce="discount => discount.id" label="name"
                                              :options="discount_types" placeholder="Select Discount">

                                    </v-select>
                                </th>
                                <th class="p-2"><input type="number" class=" bg-slate-50 w-full rounded-md"
                                                       v-model="form.discount"></th>
                                <th class="p-2">Discount</th>
                                <th class="p-2">{{ form.discount_amount }}</th>
                            </tr>
                            <tr class="border space-x-2 space-y-2 divide-x-2">
                                <th class="p-2"></th>
                                <th class="p-2">
                                </th>
                                <th class="p-2" colspan="2"></th>
                                <th colspan="2" class="float-right"></th>
                                <th class="p-2"></th>
                                <th class="p-2">Total</th>
                                <th class="p-2">{{ form.total }}</th>
                            </tr>
                            <tr class="border space-x-2 space-y-2 divide-x-2"
                                v-for="withdraw in usePurchaseUpdate().allWithdraws">

                                <th class="p-2" colspan="1"></th>
                                <th class="p-2">
                                    <div>
                                        <label for="note">Note</label>
                                        <input id="note" type="text" class="m-0 py-1 px-2 bg-slate-50 w-full rounded-md"
                                               v-model="withdraw.note">
                                    </div>
                                </th>
                                <th class="p-2">
                                    <div>
                                        <label for="receive">Receive</label>
                                        <input @change="updatePayment" id="receive" type="number"
                                               class="m-0 py-1 px-2 bg-slate-50 w-full rounded-md"
                                               v-model="withdraw.receive">
                                    </div>
                                </th>

                                <th class="p-2">
                                    <div>
                                        <label for="return">Return</label>
                                        <input @change="updatePayment" id="return" type="number"
                                               class="m-0 py-1 px-2 bg-slate-50 w-full rounded-md"
                                               v-model="withdraw.return">
                                    </div>
                                </th>
                                <th class="px-2">
                                    <label for="receive">Account</label>
                                    <v-select class="w-64" v-model="withdraw.account_id"
                                              :reduce="account => account.id" label="name"
                                              :options="accounts" placeholder="Select Account">

                                    </v-select>
                                </th>
                                <th class="px-2">
                                    <label for="receive">Payment</label>
                                    <input @change="updatePayment" type="number"
                                           class="m-0 py-1 px-2 bg-slate-50 w-full rounded-md"
                                           v-model="withdraw.amount"></th>
                                <th class="p-2">Payment</th>
                                <th class="p-2">{{ withdraw.amount }}</th>
                            </tr>
                            <tr class="border space-x-2 space-y-2 divide-x-2">
                                <th class="p-2" colspan="6"></th>
                                <th class="p-2">Due</th>
                                <th class="p-2">{{ form.due }}</th>
                            </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>


            </div>
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
}

:root {
    --dp-input-padding: 12px 30px 12px 12px;
}
</style>
