<script setup>

import {Link} from "@inertiajs/vue3";

defineProps({
    title: String
})
</script>

<template>
    <div class="flex justify-between mx-1 border px-2 py-2  rounded-md dark:bg-slate-400 bg-gray-200 justify-items-center">


        <div>
            <h1 class="font-semibold drop-shadow-2xl text-2xl text-gray-700 ">{{
                    __(title)
                }}</h1>
        </div>

        <div class="font-bold text-sm space-x-2">
            <Link :href="route('admin.dashboard')" class="text-blue-500 dark:text-white font-semibold hover:text-blue-600 hover:font-bold">{{ __('dashboard.dashboard') }}</Link>
            <ion-icon name="arrow-forward"
                      class="mt-1"></ion-icon>
            <span class="text-gray-600 dark:text-white">{{ __(title) }}</span>
        </div>
    </div>
</template>

<style scoped>

</style>
