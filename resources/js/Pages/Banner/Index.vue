<script setup>
import {computed, reactive, ref, watch} from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import {debounce, throttle} from "lodash";
import Pagination from "@/Components/Pagination.vue";
import Header from "@/Layouts/Header.vue";

const props = defineProps({
    notification: String,
    banners: Object,
    filters: Object,
    errors: Object
})


const form = useForm({
    'banner': '',
    'title': '',
    'link': '',
});
let editForm = reactive(useForm({
        '_method': 'PUT',
        'id': '',
        'banner': '',
        'banner_url': '',
        'title': '',
        'link': '',
    }),
);
let loading = ref(false);
const isOpen = ref(false)
const isOpenUpdateModal = ref(false)
const isDeleteModal = ref(false)
const banner = ref(null)
const bannerPreview = computed(() => {
    if (!banner.value) {
        return;
    }
    form.banner = banner.value
    return URL.createObjectURL(banner.value);
});

const editBannerPreview = computed(() => {
    if (!banner.value) {
        return;
    }
    editForm.banner = banner.value
    return URL.createObjectURL(banner.value);
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

function openUpdateModal(banner) {
    isOpenUpdateModal.value = true;
    editForm.id = banner.id;
    editForm.title = banner.title;
    editForm.banner_url = banner.banner_url;
    editForm.link = banner.link;

}

function openDeleteModal(banner) {
    editForm = banner;
    isDeleteModal.value = true;

}

let submit = () => {
    loading.value = true;
    form.post(route('banners.store'), {
        preserveScroll: true,
        onSuccess: () => {
            isOpen.value = false;
            loading.value = false;
            banner.value = null;
        },
        hasErrors: () => {
            loading.value = false;
        }
    });
}

let updateBanner = () => {
    loading.value = true;
    editForm.post(route('banners.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            isOpenUpdateModal.value = false;
            loading.value = false;
            banner.value = null;

        }
    });
}

let deleteBanner = () => {
    loading.value = true;
    isDeleteModal.value = false;
    router.delete(route('banners.destroy', editForm.id), {
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
        router.get(route('banners.index', {search: value}), {}, {
            preserveState: true,
            replace: true,
            onSuccess: loading.value = false
        });
    }, 300),
);


</script>

<template>
    <Head :title="__('main.banner-list')"/>

    <AuthenticatedLayout>
        <!--        <template #header>-->
        <!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('main.banner-list') }}</h2>-->
        <!--        </template>-->
        <div class="fixed flex items-center justify-center h-screen z-50 w-full bg-slate-100 opacity-25 inset-10"
             v-if="loading">

            <ion-icon name="sync" class="animate-spin text-6xl"></ion-icon>

        </div>

        <div class="py-10 max-w-7xl mx-auto">


            <div id="content" class="">

                <Header title="main.banner-list"></Header>
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
                                   placeholder="Search for Banner">
                        </div>

                        <div>

                            <button
                                type="button"
                                @click="openModal"
                                class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                Add Banner
                            </button>
                        </div>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    ID
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Banner
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                            v-for="(banner,index) in banners.data" :key="index">
                            <td class="w-4 p-2">
                                <div class="flex items-center">
                                    {{ index + 1 }}

                                </div>
                            </td>
                            <th scope="row" class="p-2">
                                <div class="flex items-center">
                                    <img :src="banner.banner_url" alt="" class="h-20 w-60">

                                </div>
                            </th>
                            <th scope="row" class="p-2">
                                <div class="">
                                    {{ banner.title }}

                                </div>
                            </th>
                            <th scope="row" class="p-2">
                                <div class="flex items-center uppercase text-blue-500 font-semibold">
                                    {{ banner.type }}

                                </div>
                            </th>

                            <td class="px-2 py-2 space-x-3">
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                   v-on:click="openUpdateModal(banner)">
                                    <ion-icon name="create"
                                              class="pt-2 " size="large"></ion-icon>
                                </a>
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                   v-on:click="openDeleteModal(banner)">
                                    <ion-icon class="pt-2 text-red-500" name="trash" size="large"></ion-icon>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>

                    <Pagination :meta="banners.meta"></Pagination>
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
                                        class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                                    >

                                        <div class="flex justify-between justify-items-center items-center py-2">
                                            <DialogTitle
                                                as="h3"
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            >
                                                Add New Banner
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
                                        <div class="mt-2 flex space-x-6 justify-between w-full gap-4">
                                            <img :src="bannerPreview? bannerPreview : form.banner_url" alt="No Image"
                                                 class="max-w-8/12 h-20 w-60">
                                            <input type="file" class="form-input px-4 py-3 rounded-md sr-only"
                                                   id="image" placeholder="Enter Your Instructor Name"
                                                   v-on:change="banner = $event.target.files[0]">
                                            <button
                                                class="text-lg font-semibold w-4/12 h-20 text-indigo-600 border-gray-200 border-2 flex items-center justify-center"
                                                v-if="banner" v-on:click="banner = null" type="button">Clear
                                            </button>
                                            <label for="image"
                                                   class="text-lg font-semibold w-4/12 h-20 text-indigo-600 border-gray-200 border-2 flex items-center justify-center"
                                                   v-else>Select New Banner</label>

                                        </div>
                                        <div class="mt-2">
                                            <label for="name" class="text-lg font-semibold">Banner Name</label>
                                            <input type="text" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                                   id="name" v-model="form.title" placeholder="Enter Your Banner Name">
                                        </div>
                                        <div class="mt-2">
                                            <label for="link" class="text-lg font-semibold">Banner Link
                                                (optional)</label>
                                            <input type="url" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                                   id="link" v-model="form.link" placeholder="Enter Your Banner Link">
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
                                        class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                                    >

                                        <div class="flex justify-between justify-items-center items-center py-2">
                                            <DialogTitle
                                                as="h3"
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            >
                                                Update Banner
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

                                        <div class="mt-2 flex space-x-6 justify-between w-full gap-4">
                                            <img :src="editBannerPreview? editBannerPreview : editForm.banner_url"
                                                 alt="No Image" class="max-w-8/12 h-20 w-60">
                                            <input type="file" class="form-input px-4 py-3 rounded-md sr-only"
                                                   id="image" placeholder="Enter Your Instructor Name"
                                                   v-on:change="banner = $event.target.files[0]">
                                            <button
                                                class="text-lg font-semibold w-4/12 h-20 text-indigo-600 border-gray-200 border-2 flex items-center justify-center"
                                                v-if="banner" v-on:click="banner = null" type="button">Clear
                                            </button>
                                            <label for="image"
                                                   class="text-lg font-semibold w-4/12 h-20 text-indigo-600 border-gray-200 border-2 flex items-center justify-center"
                                                   v-else>Select New Banner</label>

                                        </div>
                                        <div class="mt-2">
                                            <label for="title" class="text-lg font-semibold">Banner Name</label>
                                            <input type="text" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                                   id="title" v-model="editForm.title"
                                                   placeholder="Enter Your Banner Name">
                                        </div>
                                        <div class="mt-2">
                                            <label for="link" class="text-lg font-semibold">Banner Link
                                                (optional)</label>
                                            <input type="url" class="form-input px-4 py-3 rounded-md w-full mt-2"
                                                   id="link" v-model="editForm.link"
                                                   placeholder="Enter Your Banner Link">
                                        </div>

                                        <div class="mt-4 float-end space-x-2">
                                            <button
                                                type="button"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                @click="updateBanner"
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
                                                Delete Banner
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
                                            <strong>You Are Sure ? You Want to Delete This Banner
                                                ({{ editForm.name }})</strong>
                                        </div>

                                        <div class="mt-5 text-center space-x-2">
                                            <button
                                                type="button"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-semibold text-gray-50 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                @click="deleteBanner"
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

