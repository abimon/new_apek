import PublicPageMeta from '@/components/public-page-meta';
import PublicSiteLayout from '@/components/public-site-layout';
import { Link } from '@inertiajs/react';
import { ArrowUpRight, Check } from 'lucide-react';

export default function About({ canonicalUrl }: { canonicalUrl: string }) {
    const title = 'About APEK Technologies | Software Studio in Kenya';
    const description =
        'Meet APEK Technologies, a Kenya-focused software and hardware studio partnering with teams across East Africa to build dependable digital products.';
    const schema = {
        '@context': 'https://schema.org',
        '@type': 'AboutPage',
        name: title,
        description,
        url: canonicalUrl,
        mainEntity: {
            '@type': 'Organization',
            name: 'APEK Technologies',
            url: route('home'),
            areaServed: [
                { '@type': 'Country', name: 'Kenya' },
                { '@type': 'Place', name: 'East Africa' },
            ],
        },
    };

    return (
        <>
            <PublicPageMeta title={title} description={description} canonicalUrl={canonicalUrl} schema={schema} />
            <PublicSiteLayout>
                <main>
                    <section className="bg-[#2C3E50] text-white">
                        <div className="mx-auto max-w-7xl px-5 py-20 lg:px-8 lg:py-28">
                            <p className="text-xs font-bold tracking-[.22em] text-[#D4A373] uppercase">About APEK Technologies</p>
                            <h1 className="mt-6 max-w-4xl text-5xl leading-[1.05] font-semibold tracking-[-.04em] md:text-7xl">
                                Thoughtful engineering for work that matters.
                            </h1>
                            <p className="mt-8 max-w-2xl text-lg leading-8 text-slate-300">
                                We partner with ambitious teams in Kenya and across East Africa to turn complex challenges into dependable software,
                                hardware, and digital products.
                            </p>
                        </div>
                    </section>
                    <section className="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:grid-cols-[.8fr_1.2fr] lg:px-8 lg:py-24">
                        <div>
                            <p className="label">Who we are</p>
                            <h2 className="section-title">A close, senior-led engineering partner.</h2>
                        </div>
                        <div className="space-y-6 text-lg leading-8 text-slate-600">
                            <p>
                                APEK Technologies is a Kenya-focused software and hardware studio. We help organizations design, build, and improve
                                digital products around the real needs of the people who use and operate them.
                            </p>
                            <p>
                                Led by founder Edimon, our work brings full-stack engineering together with practical product thinking—from early
                                architecture and APIs to cross-platform delivery and ongoing evolution.
                            </p>
                            <p>We value clear decisions, useful releases, and systems that remain understandable as a team and its needs grow.</p>
                        </div>
                    </section>
                    <section className="border-y border-[#CBD5E1] bg-[#E8EEF3]">
                        <div className="mx-auto max-w-7xl px-5 py-20 lg:px-8">
                            <p className="label">How we work</p>
                            <h2 className="section-title">Make the important decisions visible.</h2>
                            <div className="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                                {[
                                    ['01', 'Understand the problem', 'Start with the people, constraints, and opportunity—not a predetermined tool.'],
                                    ['02', 'Design a clear path', 'Make architecture and trade-offs understandable before they become expensive.'],
                                    ['03', 'Build what is useful', 'Deliver a reliable first release and learn from how it works in practice.'],
                                    ['04', 'Keep improving', 'Stay close as the product, team, and operating needs evolve.'],
                                ].map(([number, heading, copy]) => (
                                    <article key={number} className="border-t border-[#AFC0CE] pt-5">
                                        <span className="text-xs font-bold text-[#C07A50]">{number}</span>
                                        <h3 className="mt-4 text-xl font-semibold text-[#2C3E50]">{heading}</h3>
                                        <p className="mt-3 text-sm leading-6 text-slate-600">{copy}</p>
                                    </article>
                                ))}
                            </div>
                        </div>
                    </section>
                    <section className="mx-auto grid max-w-7xl gap-10 px-5 py-20 lg:grid-cols-2 lg:px-8 lg:py-24">
                        <div>
                            <p className="label">A practical toolkit</p>
                            <h2 className="section-title">Choose technology for the job.</h2>
                        </div>
                        <div>
                            <p className="text-lg leading-8 text-slate-600">
                                Our experience spans web and mobile products, cloud services, APIs, databases, and embedded systems. We use tools such
                                as Laravel, React, TypeScript, Flutter, Firebase, MySQL, PostgreSQL, AWS, Embedded C, and MicroPython where they fit
                                the product and its constraints.
                            </p>
                            <div className="mt-7 flex flex-wrap gap-3">
                                {[
                                    'Laravel',
                                    'React',
                                    'TypeScript',
                                    'Flutter',
                                    'Firebase',
                                    'MySQL',
                                    'PostgreSQL',
                                    'AWS',
                                    'Embedded C',
                                    'MicroPython',
                                ].map((technology) => (
                                    <span
                                        key={technology}
                                        className="flex items-center gap-2 border border-[#CBD5E1] bg-white px-3 py-2 text-xs font-semibold"
                                    >
                                        <Check size={13} className="text-[#C07A50]" /> {technology}
                                    </span>
                                ))}
                            </div>
                        </div>
                    </section>
                    <section className="bg-[#2C3E50] text-white">
                        <div className="mx-auto flex max-w-7xl flex-col gap-7 px-5 py-16 md:flex-row md:items-center md:justify-between lg:px-8">
                            <div>
                                <p className="label text-[#D4A373]">Work with us</p>
                                <h2 className="mt-3 text-3xl font-semibold tracking-tight md:text-4xl">Let’s make the next step clear.</h2>
                            </div>
                            <Link
                                href={route('contact')}
                                className="inline-flex w-fit items-center gap-2 bg-[#D4A373] px-5 py-3 font-semibold text-[#2C3E50]"
                            >
                                Tell us what you’re building <ArrowUpRight size={17} />
                            </Link>
                        </div>
                    </section>
                </main>
            </PublicSiteLayout>
        </>
    );
}
