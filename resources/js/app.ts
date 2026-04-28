import '../css/app.css';
import { createInertiaApp, router } from '@inertiajs/vue3';
import type { DefineComponent} from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Ziggy } from './ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'HUB Portfolio';

const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue');
const pagesLowercase = import.meta.glob<DefineComponent>('./pages/**/*.vue');

createInertiaApp({
    title: (title) => {
        if (!title || title.includes(appName)) {
            return title || appName;
        }

        return `${title} - ${appName}`;
    },

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

            router.on('navigate', (event) => {
                (window as any).dataLayer = (window as any).dataLayer || [];
                (window as any).dataLayer.push({
                    event: 'pageview',
                    page: event.detail.page.url
                });
            });
        }

        return vueApp;
    },
    progress: {
        color: '#4B5563',
    },
});
