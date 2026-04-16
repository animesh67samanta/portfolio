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
    [key: string]: unknown;
}
