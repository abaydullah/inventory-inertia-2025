import './bootstrap';
import '../css/app.css';


import {createApp, h} from 'vue';
import {createPinia} from 'pinia'

const pinia = createPinia()
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import __ from "@/lang.js";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
import Toast from "vue-toastification";
// Import the CSS or use your own!
import "vue-toastification/dist/index.css";
import {notifications} from "@/notifications.js";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast)
            .use(notifications)
            .use(pinia)
        app.config.globalProperties.__ = __
        app.mount(el);
    },
    progress: {
        color: '#07be66',
        showSpinner: true,


    },

});
