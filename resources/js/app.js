import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import Notifications from 'notiwind';
import print from 'vue3-print-nb';
import dayjs from 'dayjs';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({
            render: () => h(App, props),
          })
            .use(plugin)
            .use(VueSweetalert2)
            .use(Notifications)
            .use(dayjs)
            .use(print)
            .use(ZiggyVue)
            
            app.config.errorHandler = function (err, vm, info) {
                console.error('Vue Error:', err, info);
            };
            app.component('VueSelect', VueSelect);
            app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
