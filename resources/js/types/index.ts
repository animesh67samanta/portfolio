export * from './auth';
export * from './seo';

import type { Auth } from './auth';
import type { GlobalSeo } from './seo';

export interface PageProps {
    auth: Auth;
    flash?: {
        success?: string | null;
        error?: string | null;
    };
    app?: {
        name?: string | null;
        url?: string | null;
    };
    seo?: GlobalSeo;
    sidebarCounts?: {
        unreadContacts: number;
        draftBlogs: number;
        draftBanners: number;
        draftProjects: number;
        inactiveSkills: number;
        inactiveTestimonials: number;
    };
    socialLinks?: {
        linkedin: string;
        github: string;
        facebook: string;
    };
    [key: string]: unknown;
}
