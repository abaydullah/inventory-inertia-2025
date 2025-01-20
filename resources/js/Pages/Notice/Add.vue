<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head,Link} from '@inertiajs/vue3';
</script>

<template>
    <Head :title="__('main.subject-list')"/>

    <AuthenticatedLayout>
        <!--        <template #header>-->
        <!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('main.subject-list') }}</h2>-->
        <!--        </template>-->

        <div class="py-5">
            <div id="content" class="">

                <div
                    class="flex justify-between mx-1 border px-2 py-2  rounded-md dark:bg-slate-400 bg-gray-200 justify-items-center">
                    <div>
                        <h1 class="font-extrabold drop-shadow-2xl text-xl text-green-500 ">{{
                                __('main.subject-list')
                            }}</h1>
                    </div>

                    <div class="font-bold text-sm space-x-2">
                        <Link :href="route('dashboard')" class="text-blue-500 dark:text-white font-semibold hover:text-blue-600 hover:font-bold">{{ __('dashboard.dashboard') }}</Link>
                        <ion-icon name="arrow-forward"
                                  class="mt-1"></ion-icon>
                        <span class="text-gray-600 dark:text-white">{{ __('main.subject-list') }}</span>
                    </div>
                </div>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
