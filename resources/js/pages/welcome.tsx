import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { ArrowUpRight, Check, ChevronRight, Mail, Menu, X } from 'lucide-react';
import { FormEvent, useState } from 'react';

type Project = {
    id: number;
    title: string;
    slug: string;
    project_type: 'product' | 'client';
    client: string | null;
    cover_image: string | null;
    summary: string;
    accent: string;
    outcomes: string[];
    categories: { name: string; slug: string }[];
    tech_stacks: { name: string }[];
};
type Category = { id: number; name: string; slug: string };

const nav = [
    ['Work', '#work'],
    ['Services', '/services'],
    ['About', '/about'],
    ['Contact', '/contact'],
];

export default function Welcome({ projects, categories, activeCategory, canonicalUrl }: { projects: Project[]; categories: Category[]; activeCategory: string; canonicalUrl: string }) {
    const { flash } = usePage<{ flash?: { success?: string } }>().props;
    const [menuOpen, setMenuOpen] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({ name: '', email: '', company: '', service: '', message: '' });
    const submit = (event: FormEvent) => {
        event.preventDefault();
        post(route('contact.store'), { onSuccess: () => reset() });
    };
    const field = (key: keyof typeof data, label: string, type = 'text') => (
        <label className="text-sm font-medium">
            {label}
            <input
                type={type}
                value={data[key]}
                onChange={(event) => setData(key, event.target.value)}
                className="mt-2 w-full border border-slate-600 bg-slate-700/50 px-3 py-3 text-white outline-none focus:border-[#D4A373]"
            />
            {errors[key] && <span className="mt-1 block text-xs text-[#F4B5A9]">{errors[key]}</span>}
        </label>
    );
    const description = 'APEK Technologies is a Kenya-focused software engineering studio building reliable web applications, APIs, mobile products, and business systems for ambitious teams.';
    const organizationSchema = {
        '@context': 'https://schema.org',
        '@type': 'ProfessionalService',
        name: 'APEK Technologies',
        url: canonicalUrl,
        description,
        areaServed: [{ '@type': 'Country', name: 'Kenya' }, { '@type': 'Place', name: 'East Africa' }],
        serviceType: ['Software engineering', 'Web application development', 'API development', 'Mobile app development', 'Business systems'],
        knowsAbout: ['Laravel', 'React', 'TypeScript', 'PostgreSQL','MySQL', 'Cloud architecture'],
    };

    return (
        <>
            <Head title="APEK Technologies | Software Engineering Studio in Kenya">
                <meta name="description" content={description} />
                <meta name="robots" content="index, follow, max-image-preview:large" />
                <meta
                    name="keywords"
                    content="software development Kenya, web application development Nairobi, Laravel developers Kenya, React development Kenya, API development East Africa"
                />
                <meta property="og:type" content="website" />
                <meta property="og:site_name" content="APEK Technologies" />
                <meta property="og:locale" content="en_KE" />
                <meta property="og:title" content="APEK Technologies | Software Engineering Studio in Kenya" />
                <meta property="og:description" content={description} />
                <meta property="og:url" content={canonicalUrl} />
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:title" content="APEK Technologies | Software Engineering Studio in Kenya" />
                <meta name="twitter:description" content={description} />
                <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
                <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
                <link rel="shortcut icon" href="/favicon.ico" />
                <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
                <link rel="manifest" href="/site.webmanifest" />
                <link rel="canonical" href={canonicalUrl} />
                <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(organizationSchema) }} />
            </Head>
            <div className="min-h-screen bg-[#F4F7F9] text-[#1E293B]">
                <header className="sticky top-0 z-40 border-b border-[#CBD5E1]/70 bg-[#F4F7F9]/95 backdrop-blur">
                    <div className="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 lg:px-8">
                        <Link href={route('home')} className="flex items-center gap-3 text-lg font-semibold tracking-tight">
                            <span className="flex h-9 w-9 items-center justify-center bg-[#2C3E50] text-sm text-white">AT</span>
                            <span>
                                Apek<span className="text-[#C07A50]">Tech</span>
                            </span>
                        </Link>
                        <nav className="hidden items-center gap-8 text-sm font-medium md:flex">
                            {nav.map(([label, href]) => (
                                <Link key={href} href={href} className="transition-colors hover:text-[#C07A50]">
                                    {label}
                                </Link>
                            ))}
                        </nav>
                        <a
                            href={route('contact')}
                            className="hidden items-center gap-2 bg-[#2C3E50] px-4 py-2.5 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5 sm:flex"
                        >
                            Start a conversation <ArrowUpRight size={16} />
                        </a>
                        <button aria-label="Open navigation" onClick={() => setMenuOpen(!menuOpen)} className="p-2 sm:hidden">
                            {menuOpen ? <X /> : <Menu />}
                        </button>
                    </div>
                    {menuOpen && (
                        <nav className="border-t border-[#CBD5E1] bg-[#F4F7F9] px-5 py-5 sm:hidden">
                            {nav.map(([label, href]) => (
                                <Link onClick={() => setMenuOpen(false)} key={href} href={href} className="block py-2 font-medium">
                                    {label}
                                </Link>
                            ))}
                        </nav>
                    )}
                </header>
                <main>
                    <section className="border-b border-[#CBD5E1] bg-[#2C3E50] text-white">
                        <div className="mx-auto grid max-w-7xl gap-14 px-5 py-24 lg:grid-cols-[1.15fr_.85fr] lg:items-end lg:px-8 lg:py-32">
                            <div>
                                <p className="mb-6 flex items-center gap-3 text-xs font-bold tracking-[.22em] text-[#D4A373] uppercase">
                                    <span className="h-px w-8 bg-[#D4A373]" /> Independent software studio
                                </p>
                                <h1 className="max-w-4xl text-5xl leading-[1.03] font-semibold tracking-[-.04em] md:text-7xl">
                                    Software that makes <span className="text-[#D4A373]">complex work</span> feel simple.
                                </h1>
                                <p className="mt-8 max-w-xl text-lg leading-8 text-slate-300">
                                    APEK Technologies partners with ambitious teams in Kenya and across East Africa to design, build, and evolve digital
                                    products that earn their place in the everyday.
                                </p>
                                <div className="mt-10 flex flex-wrap gap-4">
                                    <a
                                        href="#contact"
                                        className="inline-flex items-center gap-2 bg-[#D4A373] px-5 py-3 font-semibold text-[#2C3E50] hover:bg-[#e2b98d]"
                                    >
                                        Tell us what you are building <ArrowUpRight size={17} />
                                    </a>
                                    <a
                                        href="#work"
                                        className="inline-flex items-center gap-2 border border-slate-500 px-5 py-3 font-semibold hover:border-white"
                                    >
                                        Explore selected work <ChevronRight size={17} />
                                    </a>
                                </div>
                            </div>
                            <div className="border-l border-slate-600 pl-7 lg:mb-2">
                                <p className="text-sm leading-7 text-slate-300">
                                    From first architecture decision to the hundredth release, we bring senior engineering thinking to the details
                                    that matter.
                                </p>
                                <div className="mt-10 grid grid-cols-2 gap-7 border-t border-slate-600 pt-6">
                                    <div>
                                        <p className="text-3xl font-semibold text-white">12+</p>
                                        <p className="mt-1 text-xs tracking-widest text-slate-400 uppercase">Years building</p>
                                    </div>
                                    <div>
                                        <p className="text-3xl font-semibold text-white">48</p>
                                        <p className="mt-1 text-xs tracking-widest text-slate-400 uppercase">Products shipped</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section id="work" className="mx-auto max-w-7xl px-5 py-24 lg:px-8">
                        <div className="flex flex-col justify-between gap-6 md:flex-row md:items-end">
                            <div>
                                <p className="label">Selected work</p>
                                <h2 className="section-title">Useful by design.</h2>
                            </div>
                            <p className="max-w-sm text-sm leading-6 text-slate-500">
                                A few products where careful systems thinking met real-world momentum.
                            </p>
                        </div>
                        <div className="mt-10 flex flex-wrap gap-2">
                            {[{ name: 'All work', slug: '' }, ...categories].map((category) => (
                                <Link
                                    preserveScroll
                                    href={category.slug ? `${route('home')}?category=${category.slug}` : route('home')}
                                    key={category.slug}
                                    className={`border px-3 py-2 text-xs font-semibold transition-colors ${activeCategory === category.slug ? 'border-[#2C3E50] bg-[#2C3E50] text-white' : 'border-[#CBD5E1] bg-white hover:border-[#2C3E50]'}`}
                                >
                                    {category.name}
                                </Link>
                            ))}
                        </div>
                        <div className="mt-8 grid gap-5 md:grid-cols-3">
                            {projects.map((project, index) => (
                                <article key={project.id} className={`group border border-[#CBD5E1] bg-white ${index === 0 ? 'md:col-span-2' : ''}`}>
                                    <div className="relative aspect-[16/9] overflow-hidden p-6" style={{ backgroundColor: `${project.accent}18` }}>
                                        {project.cover_image && (
                                            <img
                                                src={`/storage/${project.cover_image}`}
                                                alt=""
                                                className="absolute inset-0 h-full w-full object-cover"
                                            />
                                        )}
                                        <div className="absolute inset-0 bg-slate-900/10" />
                                        <div className="absolute top-6 right-6 border border-white/70 bg-white/70 px-3 py-1 text-[10px] font-bold tracking-widest text-[#2C3E50] uppercase">
                                            {project.categories[0]?.name}
                                        </div>
                                        <div className="flex h-full items-end">
                                            <div className="relative z-10 w-full border border-white/70 bg-white/80 p-4 backdrop-blur">
                                                <div className="flex items-center justify-between">
                                                    <span className="text-xs font-bold tracking-widest text-slate-500 uppercase">
                                                        {project.project_type === 'product' ? 'APEK Technologies product' : project.client}
                                                    </span>
                                                    <ArrowUpRight size={18} style={{ color: project.accent }} />
                                                </div>
                                                <div className="mt-3 h-2 w-2/3" style={{ backgroundColor: project.accent }} />
                                                <div className="mt-3 grid grid-cols-3 gap-2">
                                                    <span className="h-1 bg-slate-300" />
                                                    <span className="h-1 bg-slate-200" />
                                                    <span className="h-1 bg-slate-200" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="p-6">
                                        <Link
                                            href={route('projects.show', project.slug)}
                                            className="group/title inline-flex items-center gap-2 text-2xl font-semibold tracking-tight hover:text-[#C07A50]"
                                        >
                                            {project.title}{' '}
                                            <ArrowUpRight
                                                size={20}
                                                className="transition-transform group-hover/title:translate-x-0.5 group-hover/title:-translate-y-0.5"
                                            />
                                        </Link>
                                        <p className="mt-2 text-sm leading-6 text-slate-500">{project.summary}</p>
                                        <div className="mt-5 flex flex-wrap gap-2">
                                            {project.tech_stacks.slice(0, 3).map((stack) => (
                                                <span key={stack.name} className="text-xs font-medium text-slate-500">
                                                    {stack.name}
                                                </span>
                                            ))}
                                        </div>
                                    </div>
                                </article>
                            ))}
                        </div>
                    </section>
                    <section id="approach" className="border-y border-[#CBD5E1] bg-[#E8EEF3]">
                        <div className="mx-auto grid max-w-7xl gap-14 px-5 py-24 lg:grid-cols-[.8fr_1.2fr] lg:px-8">
                            <div>
                                <p className="label">Our approach</p>
                                <h2 className="section-title">Clarity before velocity.</h2>
                                <p className="mt-6 max-w-sm text-sm leading-7 text-slate-600">
                                    We make the important decisions visible early, so the team can move quickly without creating future drag.
                                </p>
                            </div>
                            <div className="grid gap-8 sm:grid-cols-2">
                                {[
                                    ['01', 'Find the shape', 'We map the real problem, constraints, and opportunity before choosing a technology.'],
                                    ['02', 'Build the spine', 'We create the reliable architecture and the smallest useful release together.'],
                                    ['03', 'Make it legible', 'Good products are understandable to the people who operate and grow them.'],
                                    ['04', 'Stay in the room', 'We help teams evolve the system long after the first launch.'],
                                ].map(([number, title, body]) => (
                                    <div key={number} className="border-t border-[#AFC0CE] pt-4">
                                        <span className="text-xs font-bold text-[#C07A50]">{number}</span>
                                        <h3 className="mt-5 text-xl font-semibold">{title}</h3>
                                        <p className="mt-3 text-sm leading-6 text-slate-600">{body}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </section>
                    <section id="about" className="mx-auto grid max-w-7xl gap-14 px-5 py-24 lg:grid-cols-[1fr_1fr] lg:px-8">
                        <div>
                            <p className="label">About APEK Tech</p>
                            <h2 className="section-title">Senior thinking, close collaboration.</h2>
                        </div>
                        <div className="text-lg leading-8 text-slate-600">
                            <p>
                                We are a Kenya-focused, senior-led software and hardware studio for companies whose work is too important for a throwaway solution.
                            </p>
                            <p className="mt-6">
                                Our founder, Edimon, brings a full-stack engineering lens to architecture, APIs, scaling, and cross-platform product
                                delivery. The result is software/hardware that feels considered now and remains adaptable later.
                            </p>
                            <div className="mt-8 flex flex-wrap gap-3">
                                {['Laravel','MySQL','Flutter','Firebase','React', 'TypeScript', 'PostgreSQL', 'AWS', 'Product strategy','Embedded C','Micropython'].map((item) => (
                                    <span className="border border-[#CBD5E1] bg-white px-3 py-2 text-xs font-semibold" key={item}>
                                        {item}
                                    </span>
                                ))}
                            </div>
                        </div>
                    </section>
                    <section id="contact" className="border-t border-[#CBD5E1] bg-[#2C3E50] text-white">
                        <div className="mx-auto grid max-w-7xl gap-14 px-5 py-24 lg:grid-cols-[.8fr_1.2fr] lg:px-8">
                            <div>
                                <p className="label text-[#D4A373]">Start a project</p>
                                <h2 className="mt-4 text-4xl font-semibold tracking-tight">Bring the hard problem.</h2>
                                <p className="mt-5 max-w-sm leading-7 text-slate-300">
                                    Tell us a little about what you are trying to make better. We work with teams in Nairobi, across Kenya, and
                                    throughout East Africa. We will reply within one business day.
                                </p>
                                <Link href={route('contact')} className="mt-10 inline-flex items-center gap-3 text-sm text-slate-300 hover:text-white">
                                    <Mail size={17} className="text-[#D4A373]" /> Contact APEK Technologies
                                </Link>
                            </div>
                            <form onSubmit={submit} className="grid gap-5 sm:grid-cols-2">
                                {field('name', 'Your name')}
                                {field('email', 'Work email', 'email')}
                                {field('company', 'Company (optional)')}
                                {field('service', 'What can we help with?')}
                                <label className="text-sm font-medium sm:col-span-2">
                                    A little context
                                    <textarea
                                        required
                                        minLength={20}
                                        value={data.message}
                                        onChange={(event) => setData('message', event.target.value)}
                                        rows={5}
                                        className="mt-2 w-full resize-none border border-slate-600 bg-slate-700/50 px-3 py-3 text-white outline-none focus:border-[#D4A373]"
                                    />
                                    {errors.message && <span className="mt-1 block text-xs text-[#F4B5A9]">{errors.message}</span>}
                                </label>
                                <button
                                    disabled={processing}
                                    className="inline-flex w-fit items-center gap-2 bg-[#D4A373] px-5 py-3 font-semibold text-[#2C3E50] disabled:opacity-60"
                                >
                                    {processing ? 'Sending...' : 'Send inquiry'} <ArrowUpRight size={17} />
                                </button>
                                {flash?.success && (
                                    <p className="flex items-center gap-2 text-sm text-[#B9E0C0]">
                                        <Check size={16} /> {flash.success}
                                    </p>
                                )}
                            </form>
                        </div>
                    </section>
                </main>
                <footer className="mx-auto flex max-w-7xl flex-col gap-4 px-5 py-8 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
                    <span>&copy; 2022 - {new Date().getFullYear()} APEK Tech. Built for the long run.</span>
                    <nav aria-label="Footer navigation" className="flex flex-wrap gap-x-5 gap-y-2">
                        <Link href={route('about')} className="hover:text-[#C07A50]">About</Link>
                        <Link href={route('services')} className="hover:text-[#C07A50]">Services</Link>
                        <Link href={route('contact')} className="hover:text-[#C07A50]">Contact</Link>
                    </nav>
                </footer>
            </div>
        </>
    );
}
