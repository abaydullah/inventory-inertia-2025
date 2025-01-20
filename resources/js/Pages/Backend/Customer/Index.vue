<script setup>
import {computed, reactive, ref, watch} from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle, Switch,
} from '@headlessui/vue'
import {debounce, throttle} from "lodash";
import Pagination from "@/Components/Pagination.vue";
import Header from "@/Layouts/Header.vue";
import 'vue-select/dist/vue-select.css';
import VSelect from "vue-select";
import InputSwitch from "@/Components/InputSwitch.vue";
const props = defineProps({
    customers: Object,
    filters: Object,
    groups: Object,
    errors: Object,
})
let genders =  [{name: "Male", id: 'male'},{name: "Female", id: 'female'}];
const enabled = ref(false)
const form = useForm({
    'name': '',
    'photo': '',
    'gender': 'male',
    'mobile': '',
    'address': '',
    'due': 0,
    'email': '',
    'group_id': '',
    'status': true,
});
let editForm = useForm({
    '_method' : 'PUT',
    'id': '',
    'name': '',
    'photo': '',
    'gender': '',
    'photo_url': '',
    'mobile': '',
    'address': '',
    'due': '',
    'email': '',
    'group_id': '',
    'status': '',
});
const photo = ref(null);
let loading = ref(false);
const isOpen = ref(false)
const isOpenUpdateModal = ref(false)
const isDeleteModal = ref(false)
watch(enabled,(enabled) => {
    form.status = enabled
})

const imagePreview = computed(()=>{
    if (!photo.value){
        return;
    }
   form.photo =  photo.value
   return URL.createObjectURL(photo.value);
});

const editImagePreview = computed(()=>{
    if (!photo.value){
        return;
    }
    editForm.photo =  photo.value
    return URL.createObjectURL(photo.value);
});
function closeModal() {
    isOpen.value = false
}

function closeUpdateModal() {
    isOpenUpdateModal.value = false
}

function closeDeleteModal() {
    isDeleteModal.value = false
}

function openModal() {
    isOpen.value = true
}

function openUpdateModal(customer) {
    isOpenUpdateModal.value = true;
    editForm.id = customer.id;
    editForm.name = customer.name;
    editForm.photo_url = customer.photo_url;
    editForm.mobile = customer.mobile;
    editForm.gender = customer.gender;
    editForm.address = customer.address;
    editForm.due = customer.due;
    editForm.email = customer.email;
    editForm.group_id = customer.group_id;
    editForm.status = customer.status;


}

function openDeleteModal(customer) {
    editForm.id = customer.id;
    editForm.name = customer.name;
    editForm.mobile = customer.mobile;
    editForm.gender = customer.gender;
    editForm.address = customer.address;
    editForm.due = customer.due;
    editForm.email = customer.email;
    editForm.group_id = customer.group_id;
    editForm.status = customer.status;
    isDeleteModal.value = true;

}

let submit = () => {
    loading.value = true;
    form.post(route('customers.store'), {
        preserveScroll: true,
        onSuccess: () => {
            isOpen.value = false;
            loading.value = false;
            photo.value = null;
            form.reset()
        },
        onError: () => {
            loading.value = false;
        }
    });
}

let updateCustomer = () => {
    loading.value = true;
    editForm.post(route('customers.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            isOpenUpdateModal.value = false;
            loading.value = false;
            photo.value = null;
        },
        onError: () => {
            loading.value = false;
        }
    });
}

let deleteCustomer = () => {
    loading.value = true;
    isDeleteModal.value = false;
    router.delete(route('customers.destroy', editForm.id), {
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

let search = ref(props.filters.search);

watch(search, debounce(function (value) {
        loading.value = true;
        router.get(route('customers.index', {search: value}), {}, {
            preserveState: true,
            replace: true,
            onSuccess: loading.value = false
        });
    }, 300),
);


</script>

<template>
    <Head :title="__('main.customer-list')"/>

    <AuthenticatedLayout>

        <div class="fixed flex items-center justify-center h-screen z-50 w-full bg-slate-100 opacity-25 inset-10"
             v-if="loading">

            <ion-icon name="sync" class="animate-spin text-6xl"></ion-icon>

        </div>

        <div class="py-10 max-w-7xl mx-auto">

            <div id="content" class="">

                <Header title="main.customer-list"></Header>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
                    <div
                        class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">

                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input v-model="search" type="text" id="table-search-users"
                                   class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="Search for Customer">
                        </div>

                        <div>

                            <button
                                type="button"
                                @click="openModal"
                                class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                Add Customer
                            </button>
                        </div>
                    </div>
                    <div v-if="customers">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        ID
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Image
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Group
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Contact
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                                v-for="(customer,index) in customers.data" :key="index">
                                <td class="w-4 p-2">
                                    <div class="flex items-center">
                                        {{ index + 1 }}

                                    </div>
                                </td>
                                <th scope="row" class="p-2">
                                    <div class="flex items-center">
                                        <img :src="customer.photo_url" alt="" width="60">

                                    </div>
                                </th>
                                <th scope="row" class="p-2">
                                    <div class="w-full">

                                      <span class="capitalize"> {{ customer.name }}</span><br/>
                                      <span class="capitalize text-center">{{ customer.gender }}</span>
                                    </div>
                                </th>
                                <td class="px-2 py-2">
                                    <div v-for="group in customer.group_id">
                                      <span class="capitalize">{{ group.name }}</span>

                                    </div>
                                </td>
                                <td class="px-2 py-2">
                                  <span class="capitalize" v-if="customer.email">{{ customer.email }}<br></span>
                                  <span class="capitalize">{{ customer.mobile }}</span>
                                </td>
                                <td class="px-2 py-2">
                                    <span v-if="customer.status" class="bg-green-600 text-white py-1 px-2 rounded-md font-normal">Published</span>
                                    <span v-else class="bg-red-600 text-white py-1 px-2 rounded-md font-normal">Unpublished</span>
                                </td>
                                <td class="px-2 py-2 space-x-3">
                                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                       v-on:click="openUpdateModal(customer)">
                                        <ion-icon name="create"
                                                  class="pt-2 " size="large"></ion-icon>
                                    </a>
                                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                       v-on:click="openDeleteModal(customer)">
                                        <ion-icon class="pt-2 text-red-500" name="trash" size="large"></ion-icon>
                                    </a>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                        <Pagination :meta="customers.meta"></Pagination>
                    </div>

                    <div v-else
                         class="text-center text-4xl font-extrabold bg-gradient-to-t from-green-300 to-green-800 bg-clip-text text-transparent leading-normal">
                        Data Not Found
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
                                        class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                                    >

                                        <div class="flex justify-between justify-items-center items-center py-2">
                                            <DialogTitle
                                                as="h3"
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            >
                                                Add New Customer
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
                                      <div class="mt-2 flex space-x-6 justify-between w-full" >
                                        <img :src="imagePreview? imagePreview : form.photo_url" alt="No Image" width="40" height="40" class="max-w-3/12 h-20 w-20">
                                        <input type="file" class="form-input px-4 py-3 rounded-md sr-only"
                                               id="image" placeholder="Enter Your Customer Name" v-on:change="photo = $event.target.files[0]">
                                        <button class="text-lg font-semibold w-9/12 h-20 text-indigo-600 border-gray-200 border-2 flex items-center justify-center" v-if="photo" v-on:click="photo = null" type="button">Clear</button>
                                        <label for="image" class="text-lg font-semibold w-9/12 h-20 text-indigo-600 border-gray-200 border-2 flex items-center justify-center" v-else>Select New Image</label>

                                      </div>
                                          <div class="grid grid-cols-2">

                                        <div class="mt-2">
                                            <label for="name" class="text-lg font-semibold">Name</label>
                                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                                   id="name" v-model="form.name"
                                                   placeholder="Enter Your Customer Name">
                                            <span class="text-lg text-red-600 font-semibold"
                                                  v-if="form.errors.name">{{ form.errors.name }}</span>
                                        </div>
                                            <div class="mt-2">
                                              <label for="mobile" class="text-lg font-semibold">Due</label>
                                              <input type="number" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                                     id="mobile" v-model="form.due"
                                                     placeholder="Enter Your Customer Due">
                                              <span class="text-lg text-red-600 font-semibold" v-if="form.errors.due">{{
                                                  form.errors.due
                                                }}</span>
                                            </div>

                                      <div class="mt-2">
                                        <label for="email" class="text-lg font-semibold">Email</label>
                                        <input type="text" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                               id="email" v-model="form.email"
                                               placeholder="Enter Your Customer Email ID">
                                        <span class="text-lg text-red-600 font-semibold" v-if="form.errors.email">{{
                                            form.errors.email
                                          }}</span>
                                      </div>
                                        <div class="mt-2">
                                            <label for="mobile" class="text-lg font-semibold">Mobile</label>
                                            <input type="text" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                                   id="mobile" v-model="form.mobile"
                                                   placeholder="Enter Your Customer Mobile">
                                            <span class="text-lg text-red-600 font-semibold" v-if="form.errors.mobile">{{
                                                    form.errors.mobile
                                                }}</span>
                                        </div>
                                            <div class="mt-2">
                                              <label for="name" class="text-lg font-semibold">Gender</label>
                                              <v-select v-model="form.gender" label="name" :reduce="gender => gender.id"
                                                        :options="genders">

                                              </v-select>

                                            </div>
                                      <div class="relative mt-2 w-full">
                                        <label for="mobile" class="text-lg font-semibold">Group</label>
                                        <v-select multiple="multiple"  v-model="form.group_id"  :reduce="group => group" label="name" :options="groups" placeholder="Select Group">

                                          <template #group="{ name}">
                                            {{ name }}
                                          </template>

                                        </v-select>



                                      </div>


                                          </div>
                                        <div class="mt-2">
                                            <label for="mobile" class="text-lg font-semibold">Address</label>
                                            <textarea type="text" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                                      id="mobile" v-model="form.address"
                                                      placeholder="Enter Your Customer About"> </textarea>
                                            <span class="text-lg text-red-600 font-semibold" v-if="form.errors.address">{{
                                                    form.errors.address
                                                }}</span>
                                        </div>

                                        <div class="mt-2 flex space-x-4 justify-items-center items-center">
                                            <label for="name" class="text-lg font-semibold">Publish Status</label>
                                            <input-switch
                                                v-model="form.status">

                                            </input-switch>

                                        </div>
                                        <div class="mt-4 float-end space-x-2">
                                            <button
                                                type="button"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                @click="submit"
                                            >
                                                Submit
                                            </button>
                                            <button type="button"
                                                    class="inline-flex justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                    @click="closeModal">Cancel
                                            </button>
                                        </div>
                                    </DialogPanel>
                                </TransitionChild>
                            </div>
                        </div>
                    </Dialog>
                </TransitionRoot>
                <!--End Add Modal-->
                <!--Start Update Modal-->
                <TransitionRoot appear :show="isOpenUpdateModal" as="template">
                    <Dialog as="div" @close="closeUpdateModal" class="relative z-10">
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
                                        class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                                    >

                                        <div class="flex justify-between justify-items-center items-center py-2">
                                            <DialogTitle
                                                as="h3"
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            >
                                                Update Customer
                                            </DialogTitle>
                                            <div>
                                                <button type="button"
                                                        class="text-5xl leading-6 text-gray-400 font-normal"
                                                        @click="closeUpdateModal" data-bs-dismiss="modal"
                                                        aria-label="Close">&times;
                                                </button>
                                            </div>

                                        </div>
                                        <hr>

                                        <div class="mt-2 flex space-x-6 justify-between w-full" >
                                            <img :src="editImagePreview? editImagePreview : editForm.photo_url" alt="No Image" width="40" height="40" class="max-w-3/12 h-20 w-20">
                                            <input type="file" class="form-input px-4 py-3 rounded-md sr-only"
                                                   id="image" placeholder="Enter Your Customer Name" v-on:change="photo = $event.target.files[0]">
                                            <button class="text-lg font-semibold w-9/12 h-20 text-indigo-600 border-gray-200 border-2 flex items-center justify-center" v-if="photo" v-on:click="photo = null" type="button">Clear</button>
                                            <label for="image" class="text-lg font-semibold w-9/12 h-20 text-indigo-600 border-gray-200 border-2 flex items-center justify-center" v-else>Select New Image</label>

                                        </div>
                                      <div class="grid grid-cols-2">
                                        <div class="mt-2">
                                            <label for="name" class="text-lg font-semibold">Name</label>
                                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                                   id="name" v-model="editForm.name"
                                                   placeholder="Enter Your Customer Name">
                                            <span class="text-lg text-red-600 font-semibold"
                                                  v-if="errors.name">{{ errors.name }}</span>
                                        </div>
                                        <div class="mt-2">
                                          <label for="mobile" class="text-lg font-semibold">Due</label>
                                          <input type="number" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                                 id="mobile" v-model="editForm.due"
                                                 placeholder="Enter Your Customer Due">
                                          <span class="text-lg text-red-600 font-semibold" v-if="editForm.errors.due">{{
                                              editForm.errors.due
                                            }}</span>
                                        </div>

                                      <div class="mt-2">
                                        <label for="email" class="text-lg font-semibold">Email</label>
                                        <input type="text" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                               id="email" v-model="editForm.email"
                                               placeholder="Enter Your Email ID">
                                        <span class="text-lg text-red-600 font-semibold" v-if="editForm.errors.email">{{
                                            editForm.errors.email
                                          }}</span>
                                      </div>
                                        <div class="mt-2">
                                            <label for="mobile" class="text-lg font-semibold">Mobile</label>
                                            <input type="text" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                                   id="mobile" v-model="editForm.mobile"
                                                   placeholder="Enter Your Customer Mobile">
                                            <span class="text-lg text-red-600 font-semibold" v-if="errors.mobile">{{
                                                    errors.mobile
                                                }}</span>
                                        </div>
                                        <div class="mt-2">
                                          <label for="name" class="text-lg font-semibold">Gender</label>
                                          <v-select v-model="editForm.gender" label="name" :reduce="gender => gender.id"
                                                    :options="genders">

                                          </v-select>

                                        </div>
                                        <div class="relative mt-2 w-full">
                                          <label for="group" class="text-lg font-semibold">Group</label>
                                          <v-select multiple="multiple"  v-model="editForm.group_id"  :reduce="group => group" label="name" :options="groups" placeholder="Select Group">

                                            <template #group="{ name}">
                                              {{ name }}
                                            </template>

                                          </v-select>



                                        </div>


                                      </div>
                                        <div class="mt-2">
                                            <label for="mobile" class="text-lg font-semibold">Address</label>
                                            <textarea type="text" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                                      id="mobile" v-model="editForm.address"
                                                      placeholder="Enter Your Customer About"> </textarea>
                                            <span class="text-lg text-red-600 font-semibold" v-if="editForm.errors.address">{{
                                                    editForm.errors.address
                                                }}</span>
                                        </div>

                                        <div class="mt-2 flex space-x-4 justify-items-center items-center">
                                            <label for="name" class="text-lg font-semibold">Publish Status</label>
                                            <input-switch
                                                v-model="editForm.status">

                                            </input-switch>

                                        </div>

                                        <div class="mt-4 float-end space-x-2">
                                            <button
                                                type="button"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                @click="updateCustomer"
                                            >
                                                Update
                                            </button>
                                            <button type="button"
                                                    class="inline-flex justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                    @click="closeUpdateModal">Cancel
                                            </button>
                                        </div>
                                    </DialogPanel>
                                </TransitionChild>
                            </div>
                        </div>
                    </Dialog>
                </TransitionRoot>
                <!--End Update Modal-->
                <!--Start Deelete Modal-->
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
                                        class="w-full max-w-xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                                    >

                                        <div class="flex justify-between justify-items-center items-center py-2">
                                            <DialogTitle
                                                as="h3"
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            >
                                                Delete Customer
                                            </DialogTitle>
                                            <div>
                                                <button type="button"
                                                        class="text-5xl leading-6 text-gray-400 font-normal"
                                                        @click="closeDeleteModal" data-bs-dismiss="modal"
                                                        aria-label="Close">&times;
                                                </button>
                                            </div>

                                        </div>
                                        <hr>

                                        <div class="mt-2">
                                            <strong>You Are Sure ? You Want to Delete This Customer
                                                ({{ editForm.name }})</strong>
                                        </div>

                                        <div class="mt-5 text-center space-x-2">
                                            <button
                                                type="button"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-semibold text-gray-50 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                @click="deleteCustomer"
                                            >
                                                Yes
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
                <!--End Deelete Modal-->

            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
.vs-dropdown-z-index {z-index: 1000}
.vs-dropdown-min-width {
  min-width:  160px;
}
.vs-dropdown-max-height {min-height:  20px;
}
</style>
