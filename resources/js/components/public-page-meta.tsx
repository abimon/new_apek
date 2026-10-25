import { Head } from '@inertiajs/react';

type PublicPageMetaProps = {
    title: string;
    description: string;
    canonicalUrl: string;
    type?: 'website' | 'article';
    schema?: Record<string, unknown>;
};

export default function PublicPageMeta({ title, description, canonicalUrl, type = 'website', schema }: PublicPageMetaProps) {
    return (
        <Head title={title}>
            <meta name="description" content={description} />
            <meta name="robots" content="index, follow, max-image-preview:large" />
            <meta property="og:type" content={type} />
            <meta property="og:site_name" content="APEK Technologies" />
            <meta property="og:locale" content="en_KE" />
            <meta property="og:title" content={title} />
            <meta property="og:description" content={description} />
            <meta property="og:url" content={canonicalUrl} />
            <meta name="twitter:card" content="summary" />
            <meta name="twitter:title" content={title} />
            <meta name="twitter:description" content={description} />
            <link rel="canonical" href={canonicalUrl} />
            {schema && <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(schema).replace(/</g, '\\u003c') }} />}
        </Head>
    );
}
