<script setup>

import {Link} from "@inertiajs/vue3";

defineProps({
    'meta' : Object
})
</script>

<template>
    <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between py-4 px-2" aria-label="Table navigation">
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing <span class="font-semibold text-gray-900 dark:text-white">{{ meta.from }}-{{ meta.to }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ meta.total }}</span></span>
        <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">

            <li v-for="link in meta.links">
                <Link  :href="link.url??''" v-html="link.label" :class="{'!bg-slate-600 !text-gray-50 !border-2 !text-2xl': link.active,'!bg-gray-200 cursor-not-allowed': !link.url}"   class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" preserve-scroll/>
            </li>

        </ul>
    </nav>
</template>

<style scoped>

</style>
