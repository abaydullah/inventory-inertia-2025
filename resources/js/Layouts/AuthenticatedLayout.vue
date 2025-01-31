<script setup>
import {ref} from 'vue';
import {Link, router} from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const props = defineProps({
    'active': false
})
const openBar = function openBar() {
    let sidebar = document.querySelector('#sidebar');
    let opensidebar = sidebar.classList.contains('md:w-64');
    opensidebar ? (sidebar.classList.add('md:w-16'), sidebar.classList.remove('md:w-64')) : (sidebar.classList.add('md:w-64') , sidebar.classList.remove('md:w-16'));
    let top_title = document.querySelector('#toptitle').classList.contains('md:block');
    top_title ? (document.querySelector('#toptitle').classList.add('md:hidden'),
        document.querySelector('#toptitle').classList.remove('md:block')) : (document.querySelector('#toptitle').classList.add('md:block'),
        document.querySelector('#toptitle').classList.remove('md:hidden'));
    let sidebars = document.querySelectorAll('sidebar ul li a');

    for (let i = 0; i < sidebars.length; i++) {
        if (opensidebar) {
            sidebars[i].classList.add('md:hidden');
            sidebars[i].classList.remove('md:inline');
        } else {
            sidebars[i].classList.add('md:inline');
            sidebars[i].classList.remove('md:hidden');
        }
    }

    let bar = document.getElementById('menu-bar').classList;

    let bar1 = document.getElementById('menu-bar-1').classList;
    bar1.toggle('rotate-[40deg]');

    bar1.toggle('float-right');
    bar1.toggle('-mt-1');

    let bar3 = document.getElementById('menu-bar-3').classList;
    bar3.toggle('-rotate-[40deg]');

    bar3.toggle('float-right');

    if (bar.contains('space-y-2')) {
        bar.add('space-y-0');
        bar.remove('space-y-2');
        bar1.add('w-3');
        bar1.remove('w-8');
        bar3.add('w-3');
        bar3.remove('w-8');
    } else {
        bar.remove('space-y-0');
        bar.add('space-y-2');
        bar1.remove('w-3');
        bar1.add('w-8');
        bar3.remove('w-3');
        bar3.add('w-8');
    }
}

function dropDown(e) {
    document.querySelector('#submenu' + e).classList.toggle('hidden');
    document.getElementById('arrow-' + e).classList.toggle('-rotate-90');
}
</script>

<template>
    <div>
        <div class="">
            <div class="flex flex-row w-full relative md:static">
                <sidebar class="md:block hidden bg-menu-bar h-screen px-2 z-20 absolute md:static" id="sidebar-area">

                    <div class="flex justify-between my-5">

                        <img src="/img/logo.png" alt="" width="80" height="80"
                             class="rounded-sm">
                        <button class="sm:hidden text-2xl text-white font-semibold" onclick="OpenbarM()">X</button>


                        <h1 class="md:hidden hidden bg-menu-bar transition-all ease-out duration-100 py-2 font-extrabold px-2 "
                            id="toptitle">Inventory Pos</h1>

                    </div>

                    <div class="my-2 text-center shadow-sm rounded-lg">
                        <img src="/img/user.jpg" alt="" class="mx-auto object-cover w-16 h-16 rounded-full" id="user">
                        <h4 class="mt-2 font-extrabold text-base" id="title">{{ $page.props.auth.user.name }}</h4>
                        <h3 class="mt-2 font-semibold text-sm" id="title">{{ $page.props.auth.user.email }}</h3>
                    </div>
                    <ul class="bg-menu-bg flex-col divide-y-2 divide-menu-active top-8 bottom-0
        p-2 text-left shadow md:w-64 w-64 rounded-2xl py-8 mt-5" id="sidebar">
                        <li class="menu-item " :class="{'backend_active': route().current('admin.dashboard')}">
                            <ion-icon name="grid-outline" class="menu-item-icon"></ion-icon>

                            <Link :href="route('admin.dashboard')"> Dashboard</Link>
                        </li>
                        <li class="menu-item" :class="{'backend_active': route().current('customers.index')}">
                            <ion-icon name="grid-outline" class="menu-item-icon"></ion-icon>
                            <Link :href="route('customers.index')"> Customers</Link>
                        </li>
                        <li class="menu-item" :class="{'backend_active': route().current('groups.index')}">
                            <ion-icon name="grid-outline" class="menu-item-icon"></ion-icon>
                            <Link :href="route('groups.index')"> Groups</Link>
                        </li>
                        <li class="menu-item" :class="{'backend_active': route().current('categories.index')}">
                            <ion-icon name="grid-outline" class="menu-item-icon"></ion-icon>
                            <Link :href="route('categories.index')"> Categories</Link>
                        </li>
                        <li class="menu-item" :class="{'backend_active': route().current('suppliers.index')}">
                            <ion-icon name="grid-outline" class="menu-item-icon"></ion-icon>
                            <Link :href="route('suppliers.index')"> Suppliers</Link>
                        </li>
                        <li class="menu-item" :class="{'backend_active': route().current('accounts.index')}">
                            <ion-icon name="grid-outline" class="menu-item-icon"></ion-icon>
                            <Link :href="route('accounts.index')"> Accounts</Link>
                        </li>
                        <li class="menu-item" :class="{'backend_active': route().current('products.index')}">
                            <ion-icon name="grid-outline" class="menu-item-icon"></ion-icon>
                            <Link :href="route('products.index')"> Products</Link>
                        </li>
                        <li class="menu-item"
                            :class="{'backend_active': route().current('invoices.index') || route().current('invoices.create')}"
                        >
                            <ion-icon name="grid-outline"
                                      class="menu-item-icon"></ion-icon>
                            <a href="#" @click="dropDown('invoice')"> Invoices
                                <ion-icon name="chevron-back-outline"
                                          class="float-right" id="arrow-reports"></ion-icon>
                            </a>

                            <ul class="flex-col divide-y-2 w-5/5 pt-2 hidden" id="submenuinvoice">

                                <li class="sub-menu-item">
                                    <ion-icon name="list-outline" class="menu-item-icon"></ion-icon>
                                    <Link :href="route('invoices.create')"> Invoice</Link>
                                </li>
                                <li class="sub-menu-item">
                                    <ion-icon name="list-outline" class="menu-item-icon"></ion-icon>
                                    <Link :href="route('invoices.index')"> Invoice List</Link>
                                </li>

                            </ul>
                        </li>
                        <li class="menu-item"
                            :class="{'backend_active': route().current('purchases.index') || route().current('purchases.create')}"
                        >
                            <ion-icon name="grid-outline"
                                      class="menu-item-icon"></ion-icon>
                            <a href="#" @click="dropDown('purchase')"> Purchases
                                <ion-icon name="chevron-back-outline"
                                          class="float-right" id="arrow-reports"></ion-icon>
                            </a>

                            <ul class="flex-col divide-y-2 w-5/5 pt-2 hidden" id="submenupurchase">

                                <li class="sub-menu-item">
                                    <ion-icon name="list-outline" class="menu-item-icon"></ion-icon>
                                    <Link :href="route('purchases.create')"> Purchase</Link>
                                </li>
                                <li class="sub-menu-item">
                                    <ion-icon name="list-outline" class="menu-item-icon"></ion-icon>
                                    <Link :href="route('purchases.index')"> Purchase List</Link>
                                </li>

                            </ul>
                        </li>


                        <li class="menu-item">
                            <ion-icon name="list-outline" class="menu-item-icon"></ion-icon>
                            <Link :href="route('banners.index')"> Banners</Link>
                        </li>

                        <li class="menu-item">
                            <ion-icon name="grid-outline"
                                      class="menu-item-icon"></ion-icon>
                            <a href="#"> Sms </a></li>
                        <li class="menu-item">
                            <ion-icon name="grid-outline"
                                      class="menu-item-icon"></ion-icon>
                            <a href="#"> Settings </a></li>
                    </ul>
                </sidebar>
                <main class="w-full" id="main">
                    <header class="">
                        <div id="top-menu" class="flex justify-between bg-menu-bar ">
                            <div class="flex">


                                <button class="md:block hidden float-left ml-24 lg:ml-5" @click="openBar">
                                    <div class="space-y-0" id="menu-bar">
                                        <div class="w-3 h-[3px] bg-white rotate-[40deg] float-right -mt-1"
                                             id="menu-bar-1">
                                        </div>
                                        <div class="w-8 h-[3px] bg-white" id="menu-bar-2"></div>
                                        <div class="w-3 h-[3px] bg-white -rotate-[40deg] float-right"
                                             id="menu-bar-3"></div>
                                    </div>
                                    <!-- <ion-icon name="menu-outline" class="text-white text-4xl"></ion-icon> -->
                                </button>
                                <button class="md:hidden block float-left ml-5 lg:ml-5" onclick="OpenbarM()">
                                    <div class="space-y-2" id="m-menu-bar">
                                        <div class="w-8 h-[3px] bg-white" id="m-menu-bar-1"></div>
                                        <div class="w-8 h-[3px] bg-white" id="m-menu-bar-2"></div>
                                        <div class="w-8 h-[3px] bg-white" id="m-menu-bar-3"></div>
                                    </div>
                                </button>
                            </div>

                            <div class="flex">
                                <li
                                    class="md:hidden py-2 px-4 rounded-md list-none  sm:relative md:static text-xl border-2 bg-menu-bg mr-2">
                                    <button class="" onclick="openTopBar()">
                                        <ion-icon name="chevron-back-outline" class="text-lg text-gray-600"
                                                  id="topbaricon"></ion-icon>


                                    </button>

                                </li>
                                <li
                                    class="inline-block md:hidden py-2 px-4 list-none  text-xl border-2 bg-menu-bg mr-5 rounded-md">
                                    <button class="float-left" onclick="ThemeModeM()">
                                        <ion-icon name="moon-outline" id="themem" class="theme"></ion-icon>


                                    </button>

                                </li>
                            </div>
                            <div
                                class="sm:text-slate-300 sm:font-semibold sm:shadow-md sm:text-xl md:flex md:static absolute top-10 right-0 sm:bg-transparent bg-menu-bg md:w-auto w-64 hidden z-20 mr-5 rounded-sm drop-shadow-lg py-3"
                                id="topbar">
                                <ul class="md:flex md:space-x-3 md:last:pr-20 md:space-y-0 space-y-2 md:divide-y-0 divide-y-2 ">
                                    <li class="nav-item duration-500 transition-all ease-in-out"><a href="#"
                                                                                                    onclick="openSubBar('english')">
                                        {{ __('main.' + $page.props.language) }}
                                        <ion-icon name="chevron-back-outline"
                                                  class="float-right md:hidden block" id="arrow-english"></ion-icon>
                                    </a>

                                        <ul class="space-y-2 hidden " id="english">
                                            <li class="nav-item bg-inherit" v-for="lang in $page.props.languages"
                                                :key="lang.value" :value="lang.value">
                                                <button
                                                    @click="router.post(route('language.store',{language : lang.value}))">
                                                    {{ __('main.' + lang.label) }}
                                                </button>
                                            </li>


                                        </ul>
                                    </li>
                                    <li class="nav-item"><a href="#"> Pos </a>
                                    </li>
                                    <li class="nav-item"><a href="#"> Dashboard
                                    </a>
                                    </li>
                                    <li class="nav-item"><a href="#"> Profile </a>
                                    </li>
                                    <li class="nav-item "><a href="#" onclick="openSubBar('settings')"> Settings
                                        <ion-icon name="chevron-back-outline" class="float-right md:hidden block"
                                                  id="arrow-settings"></ion-icon>
                                    </a>

                                        <ul class="space-y-2 hidden" id="settings">
                                            <li class="nav-item bg-inherit">
                                                <Link :href="route('profile.edit')"> Profile Edit
                                                </Link>
                                            </li>
                                            <li class="nav-item">
                                                <Link :href="route('logout')" method="post" as="button">
                                                    logout
                                                </Link>
                                            </li>

                                        </ul>

                                    </li>

                                </ul>
                                <li class="md:inline-block hidden nav-item list-none mr-10 py-2 px-3 mx-3">
                                    <button class="float-left"
                                            onclick="ThemeMode()">
                                        <ion-icon name="moon-outline" id="theme" class="theme"></ion-icon>


                                    </button>

                                </li>
                            </div>


                        </div>
                    </header>
                    <div id="content" class="p-5">
                        <div v-if="$slots.header">
                            <slot name="header"/>
                        </div>

                        <slot/>

                    </div>

                </main>


            </div>
            <!-- Page Content -->

        </div>
    </div>
</template>
