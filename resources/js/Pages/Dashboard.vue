<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';

const props = defineProps({
    users: Array
})
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('dashboard.dashboard') }}</h2>
        </template>

        <div class="py-12">
            <div id="content" class="p-5">
                {{ users.length }}
                <div
                    class="flex justify-between  my-10 mx-3 border-2 px-5 py-3 ring-2 ring-offset-2 rounded-md dark:bg-slate-400 ">
                    <div>
                        <h1 class="font-extrabold drop-shadow-2xl text-3xl text-green-500 ">{{
                                __('dashboard.dashboard')
                            }}</h1>
                    </div>
                    <div class="font-bold -z-10">
                        <a href="#" class="text-blue-500 dark:text-white">Home</a>
                        <ion-icon name="arrow-forward"
                                  class="pt-2"></ion-icon>
                        <span class="text-gray-600 dark:text-white">Dashboard</span>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 grid-cols-1  lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                    <div
                        class="flex bg-[#40189D] w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  hover:scale-105 duration-200">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300 hover:bg-[#401880]"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-green-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-green-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2 ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white">
                            <span class="mb-3 capitalize">DIAL App Balance</span>
                            <span class=" py-1 px-2 rounded-lg inline-block font-extrabold w-fit text-3xl">0.00
                                TK.</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-blue-400 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-gray-700 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-lime-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-purple-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-orange-600 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize font-semibold">account Status</span>
                            <span
                                class="bg-slate-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>


                    <div
                        class="flex bg-red-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-green-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-amber-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2 ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white">
                            <span class="mb-3 capitalize">DIAL App Balance</span>
                            <span class=" py-1 px-2 rounded-lg inline-block font-extrabold w-fit text-3xl">0.00
                                TK.</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-emerald-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-green-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-yellow-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-green-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-teal-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-green-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>


                    <div
                        class="flex bg-sky-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-green-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-blue-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2 ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white">
                            <span class="mb-3 capitalize">DIAL App Balance</span>
                            <span class=" py-1 px-2 rounded-lg inline-block font-extrabold w-fit text-3xl">0.00
                                TK.</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-indigo-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-green-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-violet-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2  ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-green-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>

                    <div
                        class="flex bg-purple-500 w-full h-36 items-center py-4 px-5 justify-between  rounded-md rounded-ss-3xl rounded-ee-3xl ring-2 border-2 ring-offset-gray-300 ring-offset-2 ">
                        <ion-icon name="people-outline"
                                  class="text-white text-5xl border border-1 p-2 rounded-md hover:scale-110 duration-300"></ion-icon>
                        <div class="flex flex-col text-white text-right">
                            <span class="mb-3 capitalize">account Status</span>
                            <span
                                class="bg-green-500 py-1 px-2 rounded-lg inline-block font-extrabold w-fit">Active</span>
                        </div>
                    </div>
                </div>

                <!--Start Graph -->
                <div class="grid md:grid-cols-2 grid-cols-1 gap-4 ">
                    <div>
                        <div
                            class="flex justify-between  my-10 mx-3 border-2 px-5 py-3 ring-2 ring-offset-2 rounded-md dark:bg-slate-400 ">
                            <div>
                                <h1 class="font-extrabold drop-shadow-2xl text-3xl text-green-500 ">Product Sell This
                                    Year</h1>
                            </div>
                            <div class="font-bold -z-10">
                                <a href="#" class="text-blue-500 dark:text-white">Home</a>
                                <ion-icon name="arrow-forward"
                                          class="pt-2"></ion-icon>
                                <span class="text-gray-600 dark:text-white">Dashboard</span>
                            </div>
                        </div>

                        <div class="flex justify-between max-w-[760px]">
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full h-[30%] bottom-0">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">30%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">jan</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full h-[50%] bottom-0">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">50%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">feb</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full h-[50%] bottom-0">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">50%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">mar</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full h-[70%] bottom-0">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">70%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">apr</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full h-[90%] bottom-0">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">90%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">may</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full h-[60%] bottom-0">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">60%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">jun</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full h-[78%] bottom-0">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">78%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">jul</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full h-[20%] bottom-0">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">20%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">aug</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full h-[40%] bottom-0">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">40%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">sep</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full h-[99%] bottom-0">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">99%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">oct</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full bottom-0" style="height: 50%;">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">50%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">nov</span>
                            </div>
                            <div class="">
                                <div class="relative bg-slate-400 h-48 w-10 flex flex-row shadow-sm">

                                <span class="absolute bg-green-400 w-full bottom-0" style="height: 100%;">
                                    <span class="absolute text-sm text-center w-full font-semibold -top-5">100%</span>

                                </span>

                                </div>
                                <span class="uppercase w-10 font-semibold text-gray-600 text-center">dec</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div
                            class="flex justify-between  my-10 mx-3 border-2 px-5 py-3 ring-2 ring-offset-2 rounded-md dark:bg-slate-400 ">
                            <div>
                                <h1 class="font-extrabold drop-shadow-2xl text-3xl text-green-500 ">Product Sell This
                                    Week</h1>
                            </div>
                            <div class="font-bold -z-10">
                                <a href="#" class="text-blue-500 dark:text-white">Home</a>
                                <ion-icon name="arrow-forward"
                                          class="pt-2"></ion-icon>
                                <span class="text-gray-600 dark:text-white">Dashboard</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Graph -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
