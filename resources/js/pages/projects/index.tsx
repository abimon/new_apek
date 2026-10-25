import PublicSiteLayout from '@/components/public-site-layout';
import { Head, Link, router } from '@inertiajs/react';
import { ArrowUpRight } from 'lucide-react';

type Project = {
    id: number;
    title: string;
    slug: string;
    project_type: 'product' | 'client';
    client: string | null;
    cover_image: string | null;
    summary: string;
    accent: string;
    is_featured: boolean;
    categories: { name: string; slug: string }[];
    tech_stacks: { name: string }[];
};
type Category = { id: number; name: string; slug: string };
type PageLink = { label: string; url: string | null; active: boolean };

export default function ProjectsIndex({
    projects,
    categories,
    activeCategory,
    canonicalUrl,
}: {
    projects: { data: Project[]; links: PageLink[] };
    categories: Category[];
    activeCategory: string;
    canonicalUrl: string;
}) {
    const setCategory = (category: string) =>
        router.get(route('projects.index'), category ? { category } : {}, { preserveState: true, preserveScroll: true });

    return (
        <PublicSiteLayout>
            <Head title="Projects | APEK Technologies">
                <meta name="description" content="Selected software products and client projects built by APEK Technologies." />
                <link rel="canonical" href={canonicalUrl} />
            </Head>
            <main>
                <section className="bg-[#2C3E50] px-5 py-20 text-white lg:px-8 lg:py-28">
                    <div className="mx-auto max-w-7xl">
                        <p className="text-xs font-bold tracking-[.22em] text-[#D4A373] uppercase">Selected work</p>
                        <h1 className="mt-5 max-w-3xl text-5xl font-semibold tracking-tight md:text-7xl">Projects built to make work better.</h1>
                        <p className="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
                            A selection of products and partnerships shaped around real-world challenges.
                        </p>
                    </div>
                </section>
                <section className="mx-auto max-w-7xl px-5 py-14 lg:px-8">
                    <div className="mb-8 flex flex-wrap gap-2" aria-label="Filter projects by category">
                        <button
                            onClick={() => setCategory('')}
                            className={`border px-4 py-2 text-sm ${!activeCategory ? 'border-[#2C3E50] bg-[#2C3E50] text-white' : 'border-slate-300 bg-white'}`}
                        >
                            All work
                        </button>
                        {categories.map((category) => (
                            <button
                                key={category.id}
                                onClick={() => setCategory(category.slug)}
                                className={`border px-4 py-2 text-sm ${activeCategory === category.slug ? 'border-[#2C3E50] bg-[#2C3E50] text-white' : 'border-slate-300 bg-white'}`}
                            >
                                {category.name}
                            </button>
                        ))}
                    </div>
                    {projects.data.length ? (
                        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {projects.data.map((project) => (
                                <article key={project.id} className="group overflow-hidden border border-slate-200 bg-white">
                                    <Link href={route('projects.show', project.slug)} className="block">
                                        <div className="aspect-[16/10] bg-slate-100">
                                            {project.cover_image ? (
                                                <img
                                                    src={`/storage/${project.cover_image}`}
                                                    alt=""
                                                    className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]"
                                                />
                                            ) : (
                                                <div className="h-full" style={{ backgroundColor: project.accent }} />
                                            )}
                                        </div>
                                        <div className="p-6">
                                            <div className="flex items-center justify-between gap-3">
                                                <p className="text-xs font-bold tracking-widest text-[#C07A50] uppercase">
                                                    {project.project_type === 'product' ? 'APEKTECH PRODUCT' : project.client || 'CLIENT PROJECT'}
                                                </p>
                                                {project.is_featured && <span className="text-xs text-slate-500">Featured</span>}
                                            </div>
                                            <h2 className="mt-3 text-2xl font-semibold">{project.title}</h2>
                                            <p className="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">{project.summary}</p>
                                            <div className="mt-5 flex flex-wrap gap-2">
                                                {project.categories.map((category) => (
                                                    <span key={category.slug} className="bg-slate-100 px-2 py-1 text-xs text-slate-600">
                                                        {category.name}
                                                    </span>
                                                ))}
                                                {project.tech_stacks.slice(0, 3).map((stack) => (
                                                    <span key={stack.name} className="border border-slate-200 px-2 py-1 text-xs text-slate-500">
                                                        {stack.name}
                                                    </span>
                                                ))}
                                            </div>
                                            <span className="mt-6 inline-flex items-center gap-2 text-sm font-semibold">
                                                View case study <ArrowUpRight size={15} />
                                            </span>
                                        </div>
                                    </Link>
                                </article>
                            ))}
                        </div>
                    ) : (
                        <p className="border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600">
                            No projects match this category yet.
                        </p>
                    )}
                    {projects.links.length > 3 && (
                        <nav aria-label="Project pages" className="mt-10 flex flex-wrap justify-center gap-2">
                            {projects.links.map((link, index) => (
                                <Link
                                    key={`${link.label}-${index}`}
                                    href={link.url || '#'}
                                    preserveScroll
                                    className={`border px-3 py-2 text-sm ${link.active ? 'border-[#2C3E50] bg-[#2C3E50] text-white' : 'border-slate-300 bg-white'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </nav>
                    )}
                </section>
            </main>
        </PublicSiteLayout>
    );
}
