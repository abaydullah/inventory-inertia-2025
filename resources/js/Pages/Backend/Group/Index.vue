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
  groups: Object,
  filters: Object,
  errors: Object,
})
const enabled = ref(false)
const form = useForm({
  'name': '',
  'icon': '',
  'status': true,
});
let editForm = useForm({
  '_method': 'PUT',
  'id': '',
  'name': '',
  'icon': '',
  'status': '',
});
let loading = ref(false);
const isOpen = ref(false)
const isOpenUpdateModal = ref(false)
const isDeleteModal = ref(false)
watch(enabled, (enabled) => {
  form.status = enabled
})


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

function openUpdateModal(group) {
  isOpenUpdateModal.value = true;
  editForm.id = group.id;
  editForm.name = group.name;
  editForm.icon = group.icon;
  editForm.status = group.status;


}

function openDeleteModal(group) {
  editForm.id = group.id;
  editForm.name = group.name;
  editForm.icon = group.icon;
  editForm.status = group.status;
  isDeleteModal.value = true;

}

let submit = () => {
  loading.value = true;
  form.post(route('groups.store'), {
    preserveScroll: true,
    onSuccess: () => {
      isOpen.value = false;
      loading.value = false;
      form.reset()
    },
    onError: () => {
      loading.value = false;
    }
  });
}

let updateGroup = () => {
  loading.value = true;
  editForm.post(route('groups.update', editForm.id), {
    preserveScroll: true,
    onSuccess: () => {
      isOpenUpdateModal.value = false;
      loading.value = false;
    },
    onError: () => {
      loading.value = false;
    }
  });
}

let deleteGroup = () => {
  loading.value = true;
  isDeleteModal.value = false;
  router.delete(route('groups.destroy', editForm.id), {
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
      router.get(route('groups.index', {search: value}), {}, {
        preserveState: true,
        replace: true,
        onSuccess: loading.value = false
      });
    }, 300),
);


</script>

<template>
  <Head :title="__('main.group-list')"/>

  <AuthenticatedLayout>

    <div class="fixed flex items-center justify-center h-screen z-50 w-full bg-slate-100 opacity-25 inset-10"
         v-if="loading">

      <ion-icon name="sync" class="animate-spin text-6xl"></ion-icon>

    </div>

    <div class="py-10 max-w-7xl mx-auto">

      <div id="content" class="">

        <Header title="main.group-list"></Header>
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
                     placeholder="Search for Group">
            </div>

            <div>

              <button
                  type="button"
                  @click="openModal"
                  class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
              >
                Add Group
              </button>
            </div>
          </div>
          <div v-if="groups">
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
                  Name
                </th>
                <th scope="col" class="px-6 py-3">
                  Icon
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
                  v-for="(group,index) in groups.data" :key="index">
                <td class="w-4 p-2">
                  <div class="flex items-center">
                    {{ index + 1 }}

                  </div>
                </td>

                <th scope="row" class="p-2">
                  <div class="flex items-center">
                    {{ group.name }}

                  </div>
                </th>

                <td class="px-2 py-2" >
                  <div class="flex items-center">
                  <ion-icon v-if="group.icon" :name="group.icon" class="size-6"></ion-icon>
                  </div>
                </td>
                <td class="px-2 py-2">
                  <span v-if="group.status"
                        class="bg-green-600 text-white py-1 px-2 rounded-md font-normal">Published</span>
                  <span v-else class="bg-red-600 text-white py-1 px-2 rounded-md font-normal">Unpublished</span>
                </td>
                <td class="px-2 py-2 space-x-3">
                  <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                     v-on:click="openUpdateModal(group)">
                    <ion-icon name="create"
                              class="pt-2 " size="large"></ion-icon>
                  </a>
                  <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                     v-on:click="openDeleteModal(group)">
                    <ion-icon class="pt-2 text-red-500" name="trash" size="large"></ion-icon>
                  </a>
                </td>
              </tr>

              </tbody>
            </table>

            <Pagination :meta="groups.meta"></Pagination>
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
                      class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                  >

                    <div class="flex justify-between justify-items-center items-center py-2">
                      <DialogTitle
                          as="h3"
                          class="text-lg font-medium leading-6 text-gray-900"
                      >
                        Add New Group
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

                    <div class="mt-2">
                      <label for="name" class="text-lg font-semibold">Name</label>
                      <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                             id="name" v-model="form.name"
                             placeholder="Enter Your Group Name">
                      <span class="text-lg text-red-600 font-semibold"
                            v-if="form.errors.name">{{ form.errors.name }}</span>
                    </div>

                    <div class="mt-2">
                      <label for="icon" class="text-lg font-semibold">Icon</label>
                      <input type="text" class="form-input px-4 py-3 rounded-md w-full mt-2"
                             id="icon" v-model="form.icon"
                             placeholder="Enter Your Icon">
                      <span class="text-lg text-red-600 font-semibold" v-if="form.errors.icon">{{
                          form.errors.icon
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
                      class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                  >

                    <div class="flex justify-between justify-items-center items-center py-2">
                      <DialogTitle
                          as="h3"
                          class="text-lg font-medium leading-6 text-gray-900"
                      >
                        Update Group
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


                    <div class="mt-2">
                      <label for="name" class="text-lg font-semibold">Name</label>
                      <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                             id="name" v-model="editForm.name"
                             placeholder="Enter Your Group Name">
                      <span class="text-lg text-red-600 font-semibold"
                            v-if="errors.name">{{ errors.name }}</span>
                    </div>


                    <div class="mt-2">
                      <label for="icon" class="text-lg font-semibold">Icon</label>
                      <input type="text" class="form-input px-4 py-3 rounded-md w-full mt-2"
                             id="icon" v-model="editForm.icon"
                             placeholder="Enter Your Icon">
                      <span class="text-lg text-red-600 font-semibold" v-if="editForm.errors.icon">{{
                          editForm.errors.icon
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
                          @click="updateGroup"
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
                        Delete Group
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
                      <strong>You Are Sure ? You Want to Delete This Group
                        ({{ editForm.name }})</strong>
                    </div>

                    <div class="mt-5 text-center space-x-2">
                      <button
                          type="button"
                          class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-semibold text-gray-50 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                          @click="deleteGroup"
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

