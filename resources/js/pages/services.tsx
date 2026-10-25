import PublicPageMeta from '@/components/public-page-meta';
import PublicSiteLayout from '@/components/public-site-layout';
import { Link } from '@inertiajs/react';
import { ArrowUpRight, Braces, Cpu, Layers3, Smartphone, Workflow } from 'lucide-react';

const services = [
    {
        icon: Layers3,
        title: 'Web applications & business systems',
        description: 'Purpose-built web products and internal systems that make important workflows clearer, more reliable, and easier to operate.',
    },
    {
        icon: Braces,
        title: 'APIs & integrations',
        description: 'Well-structured APIs and system integrations that connect products, data, and services without adding unnecessary complexity.',
    },
    {
        icon: Smartphone,
        title: 'Mobile products',
        description: 'Cross-platform mobile experiences designed around real user needs, dependable service connections, and maintainable delivery.',
    },
    {
        icon: Cpu,
        title: 'Software & embedded systems',
        description: 'Software and hardware work that brings applications, devices, and connected workflows together where a product needs both.',
    },
    {
        icon: Workflow,
        title: 'Product strategy & architecture',
        description:
            'Early technical direction, system design, and delivery planning that make constraints and trade-offs visible before implementation.',
    },
];

export default function Services({ canonicalUrl }: { canonicalUrl: string }) {
    const title = 'Software Development Services in Kenya | APEK Technologies';
    const description =
        'Explore APEK Technologies services: web application development, APIs and integrations, mobile products, embedded systems, product strategy, and ongoing engineering.';
    const schema = {
        '@context': 'https://schema.org',
        '@type': 'ItemList',
        name: 'APEK Technologies software development services',
        description,
        url: canonicalUrl,
        itemListElement: services.map((service, index) => ({
            '@type': 'ListItem',
            position: index + 1,
            item: {
                '@type': 'Service',
                name: service.title,
                description: service.description,
                provider: { '@type': 'Organization', name: 'APEK Technologies' },
            },
        })),
    };

    return (
        <>
            <PublicPageMeta title={title} description={description} canonicalUrl={canonicalUrl} schema={schema} />
            <PublicSiteLayout>
                <main>
                    <section className="bg-[#2C3E50] text-white">
                        <div className="mx-auto max-w-7xl px-5 py-20 lg:px-8 lg:py-28">
                            <p className="text-xs font-bold tracking-[.22em] text-[#D4A373] uppercase">What we do</p>
                            <h1 className="mt-6 max-w-4xl text-5xl leading-[1.05] font-semibold tracking-[-.04em] md:text-7xl">
                                Engineering for the whole product journey.
                            </h1>
                            <p className="mt-8 max-w-2xl text-lg leading-8 text-slate-300">
                                From early technical direction to launch and ongoing improvement, we help teams build dependable digital products
                                around their real-world needs.
                            </p>
                        </div>
                    </section>
                    <section className="mx-auto max-w-7xl px-5 py-20 lg:px-8 lg:py-24">
                        <div className="max-w-2xl">
                            <p className="label">Services</p>
                            <h2 className="section-title">The right support for the problem at hand.</h2>
                        </div>
                        <div className="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                            {services.map(({ icon: Icon, title: serviceTitle, description: serviceDescription }) => (
                                <article key={serviceTitle} className="border border-[#CBD5E1] bg-white p-7">
                                    <span className="flex h-11 w-11 items-center justify-center bg-[#E8EEF3] text-[#2C3E50]">
                                        <Icon size={21} aria-hidden="true" />
                                    </span>
                                    <h3 className="mt-6 text-xl font-semibold tracking-tight text-[#2C3E50]">{serviceTitle}</h3>
                                    <p className="mt-4 text-sm leading-7 text-slate-600">{serviceDescription}</p>
                                </article>
                            ))}
                        </div>
                    </section>
                    <section className="border-y border-[#CBD5E1] bg-[#E8EEF3]">
                        <div className="mx-auto grid max-w-7xl gap-10 px-5 py-16 lg:grid-cols-[.8fr_1.2fr] lg:px-8 lg:py-20">
                            <div>
                                <p className="label">A collaborative process</p>
                                <h2 className="section-title">Clarity first. Useful progress throughout.</h2>
                            </div>
                            <div className="grid gap-7 sm:grid-cols-3">
                                {[
                                    ['Discover', 'Align on the challenge, users, constraints, and desired outcomes.'],
                                    ['Deliver', 'Build in clear steps, sharing decisions and progress as the product takes shape.'],
                                    ['Evolve', 'Support the product after launch as feedback and priorities change.'],
                                ].map(([step, copy]) => (
                                    <div key={step} className="border-t border-[#AFC0CE] pt-4">
                                        <h3 className="text-lg font-semibold text-[#2C3E50]">{step}</h3>
                                        <p className="mt-3 text-sm leading-6 text-slate-600">{copy}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </section>
                    <section className="mx-auto flex max-w-7xl flex-col gap-7 px-5 py-16 md:flex-row md:items-center md:justify-between lg:px-8">
                        <div>
                            <p className="label">Have a challenge in mind?</p>
                            <h2 className="mt-3 text-3xl font-semibold tracking-tight text-[#2C3E50] md:text-4xl">
                                Let’s find a practical way forward.
                            </h2>
                        </div>
                        <Link
                            href={route('contact')}
                            className="inline-flex w-fit items-center gap-2 bg-[#2C3E50] px-5 py-3 font-semibold text-white"
                        >
                            Contact APEK <ArrowUpRight size={17} />
                        </Link>
                    </section>
                </main>
            </PublicSiteLayout>
        </>
    );
}
