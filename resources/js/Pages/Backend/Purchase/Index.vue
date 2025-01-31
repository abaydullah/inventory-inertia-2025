<script setup>
import {ref} from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import '@vuepic/vue-datepicker/dist/main.css'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import Header from "@/Layouts/Header.vue";
import 'vue-select/dist/vue-select.css';
import dayjs from 'dayjs'

const dateFormat = (date) => {
    return dayjs(date).format('D MMM YYYY')
}
const props = defineProps({
    purchases: Object,
    filters: Object,
    groups: Object,
    errors: Object,
    categories: Object,
    accounts: Object,
    suppliers: Object,
})
let loading = ref(false);
const isDeleteModal = ref(false)
const deleteId = ref(null)


function closeDeleteModal() {
    isDeleteModal.value = false
    deleteId.value = null;
}

function openDeleteModal(id) {
    isDeleteModal.value = true
    deleteId.value = id;
}

let deletePurchase = () => {
    loading.value = true;
    isDeleteModal.value = false;
    router.delete(route('purchases.destroy', deleteId.value), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {

            loading.value = false;
        },
        onError: () => {
            loading.value = false;
        }
    });

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
                <div class="shadow-md sm:rounded-lg mt-5">

                    <!--                    <div class="grid gap-4 md:grid-cols-5 sm:grid-cols-2">-->
                    <!--                        <div class="mt-2">-->
                    <!--                            <label for="date" class="text-lg font-semibold px-2">Date</label>-->
                    <!--                            <VueDatePicker class="mt-2 p-0" v-model="form.date" :format="format"-->
                    <!--                                           :preview-format="format"-->
                    <!--                                           auto-apply-->
                    <!--                            ></VueDatePicker>-->

                    <!--                            <span class="text-lg text-red-600 font-semibold"-->
                    <!--                                  v-if="form.errors.date">{{ form.errors.date }}</span>-->
                    <!--                        </div>-->
                    <!--                        <div class="mt-2">-->
                    <!--                            <label for="barcode" class="text-lg font-semibold px-2">Barcode</label>-->
                    <!--                            <div class="relative ">-->
                    <!--                                <ion-icon name="barcode-outline" class="absolute z-40 text-6xl"></ion-icon>-->
                    <!--                                <input type="text"-->
                    <!--                                       class="pl-16 absolute top-0 left-0 form-input px-4 py-3 rounded-md w-full my-2"-->
                    <!--                                       id="barcode" v-model="form.barcode"-->
                    <!--                                       placeholder="Enter Your Product Barcode">-->
                    <!--                            </div>-->
                    <!--                            <span class="text-lg text-red-600 font-semibold"-->
                    <!--                                  v-if="form.errors.barcode">{{ form.errors.barcode }}</span>-->
                    <!--                        </div>-->
                    <!--                        <div class="mt-2">-->
                    <!--                            <label for="group_id" class="text-lg font-semibold px-2">Supplier</label>-->
                    <!--                            <v-select class="mt-2 style-chooser w-full" v-model="form.supplier_id"-->
                    <!--                                      :reduce="supplier => supplier" label="company_name"-->
                    <!--                                      :options="suppliers" placeholder="Select Supplier">-->

                    <!--                                <template #option="{ company_name}">-->
                    <!--                                    {{ company_name }}-->
                    <!--                                </template>-->

                    <!--                            </v-select>-->

                    <!--                            <span class="text-lg text-red-600 font-semibold"-->
                    <!--                                  v-if="errors.supplier_id">{{-->
                    <!--                                    errors.supplier_id-->
                    <!--                                }}</span>-->
                    <!--                        </div>-->
                    <!--                        <div class="relative mt-2 w-full">-->
                    <!--                            <label for="group_id" class="text-lg font-semibold px-2">Group</label>-->
                    <!--                            <v-select class="mt-2 style-chooser"-->
                    <!--                                      v-model="form.group_id"-->
                    <!--                                      :reduce="group => group" label="name"-->
                    <!--                                      :options="groups" placeholder="Select Group">-->

                    <!--                                <template #group="{ name}">-->
                    <!--                                    {{ name }}-->
                    <!--                                </template>-->

                    <!--                            </v-select>-->
                    <!--                        </div>-->
                    <!--                        <div class="relative mt-2 w-full">-->
                    <!--                            <label for="group_id" class="text-lg font-semibold px-2">Products</label>-->
                    <!--                            <v-select class="mt-2 style-chooser"-->
                    <!--                                      @update:modelValue="selectedProduct"-->
                    <!--                                      :reduce="product => product" label="name"-->
                    <!--                                      :options="productsAll" placeholder="Select Product">-->

                    <!--                                <template #group="{ name}">-->
                    <!--                                    {{ name }}-->
                    <!--                                </template>-->

                    <!--                            </v-select>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <div class="mt-10 w-full">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="space-x-2 space-y-2 border  divide-x-2 w-full">
                                <th scope="col" class="p-2 w-1/12">ID</th>
                                <th scope="col" class="p-2 w-1/12">Date</th>
                                <th scope="col" class="p-2 w-1/12">Supplier</th>
                                <th scope="col" class="p-2 w-1/12">Subtotal</th>
                                <th scope="col" class="p-2 w-1/12">Discount</th>
                                <th scope="col" class="p-2 w-1/12">Total</th>
                                <th scope="col" class="p-2 w-1/12">Payment</th>
                                <th scope="col" class="p-2 w-1/12">Due</th>
                                <th scope="col" class="p-2 w-1/12">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr class="border divide-x-2 w-full text-sm"
                                v-for="(purchase, key) in purchases">
                                <td class="text-center text-2xl font-semibold">{{ key + 1 }}</td>
                                <td class="text-left px-2 ">{{ dateFormat(purchase.date) }}</td>
                                <td class="text-left px-2 ">{{ purchase.supplier.company_name }}</td>
                                <td class="px-2 text-end">{{ purchase.subtotal }}</td>
                                <td class="px-2 text-end">{{ purchase.discount_amount }}</td>
                                <td class="px-2 text-end">{{ purchase.total }}</td>
                                <td class="px-2 text-end">{{ purchase.payment }}</td>
                                <td class="px-2 text-end">{{ purchase.due }}</td>

                                <td class="text-center">
                                    <Link class="rounded-md" :href="route('purchases.edit',purchase.id)">
                                        <ion-icon name="create-outline" class="text-4xl text-red-600"></ion-icon>
                                    </Link>
                                    <button class=" rounded-md" @click="openDeleteModal(purchase.id)">
                                        <ion-icon name="trash" class="text-4xl text-red-600"></ion-icon>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>


            </div>
            <!--Start Add Modal-->
            <TransitionRoot appear :show="isDeleteModal" as="template">
                <Dialog as="div" @close="closeDeleteModal" class="relative z-10">
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
                            class="flex min-h-full items-center justify-center p-4 text-center"
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
                                    class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                                >

                                    <div class="flex justify-between justify-items-center items-center py-2">
                                        <DialogTitle
                                            as="h3"
                                            class="text-lg font-medium leading-6 text-gray-900"
                                        >
                                            Delete Purchase
                                        </DialogTitle>


                                        <div>
                                            <button type="button"
                                                    class="text-5xl leading-6 text-gray-400 font-normal"
                                                    @click="closeDeleteModal" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                &times;
                                            </button>
                                        </div>

                                    </div>
                                    <hr>

                                    <h1 class="text-center text-red-600 text-2xl py-5">Are You Sure You Want to Delete
                                        This
                                        ID</h1>
                                    <div class="mt-4 float-end space-x-2">
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="deletePurchase"
                                        >
                                            Submit
                                        </button>
                                        <button type="button"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                @click="closeDeleteModal">Cancel
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
}

:root {
    --dp-input-padding: 12px 30px 12px 12px;
}
</style>
