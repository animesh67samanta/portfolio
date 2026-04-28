import { usePage } from '@inertiajs/vue3';
import { computed, type ComputedRef } from 'vue';
import type { SeoMeta, GlobalSeo } from '@/types/seo';
import type { PageProps } from '@/types';

/**
 * useSeoMeta Composable
 *
 * Merges global SEO defaults (shared from Laravel via Inertia)
 * with page-level overrides and returns a reactive final meta object.
 *
 * Usage in a Vue page:
 *   const meta = useSeoMeta({
 *       title: 'My Page',
 *       description: 'A great page.',
 *       image: '/uploads/projects/hero.webp',
 *   });
 *
 * Then pass `meta` to <SeoHead :meta="meta" />
 */

function resolveImageUrl(image: string | undefined, baseUrl: string): string {
    if (!image) {
        return `${baseUrl}/logo.png`;
    }

    if (image.startsWith('http')) {
        return image;
    }

    const normalized = image.replace(/^\/+(?=\/)/, '');

    return `${baseUrl}/${normalized}`;
}

interface SeoPageProps extends PageProps {
    seo?: GlobalSeo & { meta?: SeoMeta };
}

export function useSeoMeta(pageOverrides: SeoMeta = {}): ComputedRef<Required<SeoMeta>> {
    const page = usePage<SeoPageProps>();

    return computed(() => {
        const global = page.props.seo as GlobalSeo | undefined;
        const baseUrl = page.props.app?.url ?? global?.url ?? (typeof window !== 'undefined' ? window.location.origin : '');

        // Merge priority: pageOverrides > inertia meta prop > global defaults
        const inertiaMeta = page.props.seo?.meta ?? {};

        const title = pageOverrides.title ?? inertiaMeta.title ?? global?.defaultTitle ?? 'HUB Portfolio';
        const description = pageOverrides.description ?? inertiaMeta.description ?? global?.defaultDescription ?? '';
        const keywords = pageOverrides.keywords ?? inertiaMeta.keywords ?? global?.defaultKeywords ?? '';
        const canonical = pageOverrides.canonical ?? inertiaMeta.canonical ?? (typeof window !== 'undefined' ? window.location.href : baseUrl);
        const image = resolveImageUrl(pageOverrides.image ?? inertiaMeta.image ?? global?.defaultImage, baseUrl);
        const type = pageOverrides.type ?? inertiaMeta.type ?? 'website';
        const publishedAt = pageOverrides.publishedAt ?? inertiaMeta.publishedAt ?? '';
        const modifiedAt = pageOverrides.modifiedAt ?? inertiaMeta.modifiedAt ?? '';
        const author = pageOverrides.author ?? inertiaMeta.author ?? '';
        const twitterCard = pageOverrides.twitterCard ?? inertiaMeta.twitterCard ?? (global?.twitter.card as SeoMeta['twitterCard']) ?? 'summary_large_image';
        const robots = pageOverrides.robots ?? inertiaMeta.robots ?? global?.robots ?? 'index, follow';

        return {
            title,
            description,
            keywords,
            canonical,
            image,
            type,
            publishedAt,
            modifiedAt,
            author,
            twitterCard,
            robots,
        };
    });
}

