/**
 * SEO Meta Tag Types
 *
 * Modern SEO types for Inertia.js + Vue 3 dynamic meta system.
 */

export interface SeoMeta {
    /** Page title (without site suffix; composable appends it) */
    title?: string;

    /** Meta description (150–160 chars recommended) */
    description?: string;

    /** Comma-separated keywords */
    keywords?: string;

    /** Canonical URL (absolute) */
    canonical?: string;

    /** Absolute URL to OG/Twitter image (1200×630 recommended) */
    image?: string;

    /** Open Graph type: 'website' | 'article' | 'profile' etc. */
    type?: 'website' | 'article' | 'profile' | 'book';

    /** Article publish date (ISO 8601). Only used when type === 'article' */
    publishedAt?: string;

    /** Article modification date (ISO 8601). Only used when type === 'article' */
    modifiedAt?: string;

    /** Article author name. Only used when type === 'article' */
    author?: string;

    /** Twitter card type override (defaults to global config) */
    twitterCard?: 'summary' | 'summary_large_image' | 'app' | 'player';

    /** Robots meta directive, e.g. 'noindex, nofollow' */
    robots?: string;
}

export interface GlobalSeo {
    siteName: string;
    defaultTitle: string;
    defaultDescription: string;
    defaultKeywords: string;
    defaultImage: string;
    locale: string;
    url: string;
    twitter: {
        card: string;
        site: string | null;
        creator: string | null;
    };
    facebook: {
        appId: string | null;
    };
    robots: string;
}

export interface PageSeoProps {
    seo?: {
        meta?: SeoMeta;
    };
}

