import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Ziggy } from './ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Hub Portfolio';

const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue');
const pagesLowercase = import.meta.glob<DefineComponent>('./pages/**/*.vue');

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // title: (title) => `${title}  ${appName}`,

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
        if (typeof window !== 'undefined') {
            Ziggy.url = window.location.origin;
        }

        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy as any);

        if (typeof window !== 'undefined') {
            vueApp.mount(el);
        }

        return vueApp;
    },
    progress: {
        color: '#4B5563',
    },
});
