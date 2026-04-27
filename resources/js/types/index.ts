export * from './auth';

import type { Auth } from './auth';

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
    sidebarCounts?: {
        unreadContacts: number;
        draftBlogs: number;
        draftBanners: number;
        draftProjects: number;
        inactiveSkills: number;
        inactiveTestimonials: number;
    };
    [key: string]: unknown;
}
