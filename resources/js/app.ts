import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue');
const pagesLowercase = import.meta.glob<DefineComponent>('./pages/**/*.vue');

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const page =
            pages[`./Pages/${name}.vue`] ??
            pagesLowercase[`./pages/${name}.vue`];

        if (!page) {
            throw new Error(`Inertia page not found: ${name}`);
        }

        return page();
    },
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        if (typeof window !== 'undefined') {
            vueApp.mount(el);
        }

        return vueApp;
    },
    progress: {
        color: '#4B5563',
    },
});
