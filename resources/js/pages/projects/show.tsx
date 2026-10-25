import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, ArrowUpRight, Check, ExternalLink } from 'lucide-react';

type Project = {
    id: number;
    title: string;
    slug: string;
    project_type: 'product' | 'client';
    client: string | null;
    cover_image: string | null;
    summary: string;
    challenge: string;
    solution: string;
    screenshots: string[] | null;
    features: string[] | null;
    outcomes: string[] | null;
    live_url: string | null;
    accent: string;
    categories: { name: string; slug: string }[];
    tech_stacks: { name: string; slug: string }[];
};

export default function ProjectShow({ project, relatedProjects, canonicalUrl }: { project: Project; relatedProjects: Project[]; canonicalUrl: string }) {
    const description = `${project.title}: ${project.summary} APEK Technologies software engineering case study from Kenya.`;
    const projectSchema = {
        '@context': 'https://schema.org',
        '@type': 'CreativeWork',
        name: project.title,
        description,
        url: canonicalUrl,
        creator: { '@type': 'Organization', name: 'APEK Technologies', url: route('home') },
        keywords: project.tech_stacks.map((stack) => stack.name).join(', '),
        about: project.categories.map((category) => category.name),
    };

    return (
        <>
            <Head title={`${project.title} | APEK Technologies Kenya case study`}>
                <meta name="description" content={description} />
                <meta name="robots" content="index, follow, max-image-preview:large" />
                <meta property="og:type" content="article" />
                <meta property="og:site_name" content="APEK Technologies" />
                <meta property="og:locale" content="en_KE" />
                <meta property="og:title" content={`${project.title} | APEK Technologies Kenya case study`} />
                <meta property="og:description" content={description} />
                <meta property="og:url" content={canonicalUrl} />
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:title" content={`${project.title} | APEK Technologies Kenya case study`} />
                <meta name="twitter:description" content={description} />
                <link rel="canonical" href={canonicalUrl} />
                <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(projectSchema).replace(/</g, '\\u003c') }} />
            </Head>
            <div className="min-h-screen bg-[#F4F7F9] text-[#1E293B]">
                <header className="border-b border-[#CBD5E1] bg-[#F4F7F9]">
                    <div className="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 lg:px-8">
                        <Link href={route('home')} className="flex items-center gap-3 text-lg font-semibold tracking-tight">
                            <span className="flex h-9 w-9 items-center justify-center bg-[#2C3E50] text-sm text-white">AT</span>
                            <span>Apek<span className="text-[#C07A50]">Tech</span></span>
                        </Link>
                        <nav aria-label="Page navigation" className="flex flex-wrap items-center gap-5 text-sm font-semibold text-[#2C3E50]">
                            <Link href={`${route('home')}#work`} className="inline-flex items-center gap-2 hover:text-[#C07A50]">
                                <ArrowLeft size={16} /> All work
                            </Link>
                            <Link href={route('services')} className="hover:text-[#C07A50]">Services</Link>
                            <Link href={route('about')} className="hover:text-[#C07A50]">About</Link>
                            <Link href={route('contact')} className="hover:text-[#C07A50]">Contact</Link>
                        </nav>
                    </div>
                </header>
                <main>
                    <section className="bg-[#2C3E50] text-white">
                        <div className="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:grid-cols-[1fr_.8fr] lg:items-end lg:px-8 lg:py-28">
                            <div>
                                <p className="text-xs font-bold tracking-[.2em] text-[#D4A373] uppercase">{project.categories[0]?.name || 'Selected work'}</p>
                                <h1 className="mt-6 max-w-3xl text-5xl leading-tight font-semibold tracking-tight md:text-7xl">{project.title}</h1>
                                <p className="mt-7 max-w-2xl text-xl leading-8 text-slate-300">{project.summary}</p>
                                <div className="mt-8 flex flex-wrap gap-2">{project.tech_stacks.map((stack) => <span className="border border-slate-500 px-3 py-2 text-xs font-semibold text-slate-200" key={stack.slug}>{stack.name}</span>)}</div>
                            </div>
                            <div className="border-l border-slate-600 pl-7">
                                <p className="text-xs tracking-widest text-slate-400 uppercase">{project.project_type === 'product' ? 'APEK Technologies product' : 'Client'}</p>
                                {project.project_type === 'client' && <p className="mt-2 text-lg font-semibold">{project.client || 'Confidential partner'}</p>}
                                {project.live_url && <a href={project.live_url} target="_blank" rel="noreferrer" className="mt-7 inline-flex items-center gap-2 text-sm font-semibold text-[#D4A373] hover:text-white">{project.project_type === 'product' ? 'Open product' : 'Visit live project'} <ExternalLink size={15} /></a>}
                            </div>
                        </div>
                    </section>
                    <section className="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-24">
                        <div className="grid gap-12 lg:grid-cols-[1.15fr_.85fr]">
                            <div className="relative aspect-[16/10] overflow-hidden border border-[#CBD5E1] p-8" style={{ backgroundColor: `${project.accent}18` }}>
                                {project.cover_image ? (
                                    <img src={`/storage/${project.cover_image}`} alt={`${project.title} cover`} className="absolute inset-0 h-full w-full object-cover" />
                                ) : (
                                    <div className="absolute inset-8 border border-white/80 bg-white/70 p-6 backdrop-blur"><div className="flex items-center justify-between"><span className="text-xs font-bold tracking-widest text-slate-500 uppercase">{project.title}</span><span className="h-3 w-3 rounded-full" style={{ backgroundColor: project.accent }} /></div><div className="mt-8 grid grid-cols-[1fr_1.6fr] gap-5"><div className="space-y-3"><span className="block h-3 bg-slate-200" /><span className="block h-3 w-4/5 bg-slate-200" /><span className="block h-3 w-3/5 bg-slate-200" /></div><div className="h-28 bg-slate-100" /></div><div className="mt-6 h-2 w-2/3" style={{ backgroundColor: project.accent }} /></div>
                                )}
                            </div>
                            <div className="space-y-10"><div><p className="label">The challenge</p><p className="mt-4 text-lg leading-8 text-slate-600">{project.challenge}</p></div><div><p className="label">The solution</p><p className="mt-4 text-lg leading-8 text-slate-600">{project.solution}</p></div></div>
                        </div>
                    </section>
                    <section className="border-y border-[#CBD5E1] bg-[#E8EEF3]"><div className="mx-auto grid max-w-7xl gap-12 px-5 py-16 lg:grid-cols-2 lg:px-8"><div><p className="label">What changed</p><h2 className="section-title">Outcomes that hold up in the real world.</h2><div className="mt-8 grid gap-4 sm:grid-cols-2">{(project.outcomes || []).map((outcome) => <div className="border border-[#CBD5E1] bg-white p-5 text-lg font-semibold" key={outcome}>{outcome}</div>)}</div></div><div><p className="label">Built into the product</p><div className="mt-6 space-y-4">{(project.features || []).map((feature) => <div className="flex items-center gap-3 border-b border-[#CBD5E1] pb-4 text-sm font-medium" key={feature}><Check size={17} className="text-[#C07A50]" />{feature}</div>)}</div></div></div></section>
                    {relatedProjects.length > 0 && <section className="mx-auto max-w-7xl px-5 py-20 lg:px-8"><div className="flex items-end justify-between gap-5"><div><p className="label">Keep exploring</p><h2 className="section-title">More selected work.</h2></div><Link href={`${route('home')}#work`} className="hidden items-center gap-2 text-sm font-semibold text-[#C07A50] sm:flex">View all work <ArrowUpRight size={16} /></Link></div><div className="mt-8 grid gap-5 md:grid-cols-3">{relatedProjects.map((related) => <Link href={route('projects.show', related.slug)} className="group border border-[#CBD5E1] bg-white p-6 hover:border-[#C07A50]" key={related.id}><span className="text-xs font-bold tracking-widest text-slate-400 uppercase">{related.categories[0]?.name}</span><h3 className="mt-12 text-2xl font-semibold tracking-tight group-hover:text-[#C07A50]">{related.title}</h3><p className="mt-3 text-sm leading-6 text-slate-500">{related.summary}</p></Link>)}</div></section>}
                </main>
                <footer className="border-t border-[#CBD5E1] px-5 py-8 text-center text-xs text-slate-500">(c) 2026 APEK Technologies. Built for the long run.</footer>
            </div>
        </>
    );
}
