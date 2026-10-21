import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { FormEvent } from 'react';

type Project = {
    id: number;
    title: string;
    project_type: 'product' | 'client';
    summary: string;
    challenge: string;
    solution: string;
    client: string | null;
    cover_image_url: string | null;
    live_url: string | null;
    features: string[] | null;
    owner: { id: number; name: string; email: string } | null;
    categories: string[];
    tech_stacks: string[];
    created_at: string;
};
type Page<T> = { data: T[]; links: { url: string | null; label: string; active: boolean }[] };

export default function PortfolioProjectReviews({ projects }: { projects: Page<Project> }) {
    const review = (event: FormEvent, id: number, action: 'approve' | 'reject') => {
        event.preventDefault();
        router.patch(route(`admin.portfolio-projects.${action}`, id));
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Project approvals', href: '/admin/portfolio-projects' }]}>
            <Head title="Portfolio project review" />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto max-w-5xl">
                    <p className="text-xs font-bold tracking-widest text-[#C07A50] uppercase">Superadmin review</p>
                    <h1 className="mt-2 text-3xl font-semibold">Project submissions</h1>
                    <p className="mt-2 text-sm text-slate-500">
                        Approval only makes a project eligible for its owner’s public portfolio. It does not publish it on the company projects page.
                    </p>
                    <div className="mt-7 space-y-5">
                        {projects.data.map((project) => (
                            <article key={project.id} className="border border-[#CBD5E1] bg-white p-5 md:p-7">
                                <div className="flex flex-wrap items-start justify-between gap-4">
                                    <div>
                                        <p className="text-xs font-semibold text-[#9A5636]">
                                            {project.owner?.name || 'Unknown owner'} · {project.owner?.email}
                                        </p>
                                        <div className="mt-2 flex flex-wrap items-center gap-2">
                                            <h2 className="text-xl font-semibold">{project.title}</h2>
                                            <span className="bg-slate-100 px-2 py-1 text-xs text-slate-600">
                                                {project.project_type === 'product' ? 'SaaS / product' : 'Client project'}
                                            </span>
                                        </div>
                                        <p className="mt-1 text-sm text-slate-500">{project.summary}</p>
                                    </div>
                                    <time className="text-xs text-slate-400">{new Date(project.created_at).toLocaleDateString()}</time>
                                </div>
                                <div className="mt-5 grid gap-4 text-sm md:grid-cols-2">
                                    <div>
                                        <h3 className="font-semibold">Challenge</h3>
                                        <p className="mt-1 whitespace-pre-line text-slate-600">{project.challenge}</p>
                                    </div>
                                    {project.cover_image_url && (
                                        <img
                                            src={project.cover_image_url}
                                            alt={`${project.title} project cover`}
                                            className="mt-5 max-h-64 w-full object-cover"
                                        />
                                    )}
                                    <div>
                                        <h3 className="font-semibold">Solution</h3>
                                        <p className="mt-1 whitespace-pre-line text-slate-600">{project.solution}</p>
                                    </div>
                                </div>
                                <div className="mt-4 flex flex-wrap gap-2 text-xs text-slate-500">
                                    {project.client && <span className="border border-slate-200 px-2 py-1">Client: {project.client}</span>}
                                    {project.tech_stacks.map((stack) => (
                                        <span key={stack} className="border border-slate-200 px-2 py-1">
                                            {stack}
                                        </span>
                                    ))}
                                    {project.features?.map((feature) => (
                                        <span key={feature} className="border border-slate-200 px-2 py-1">
                                            {feature}
                                        </span>
                                    ))}
                                    {project.live_url && (
                                        <a href={project.live_url} target="_blank" rel="noreferrer" className="px-2 py-1 text-[#9A5636] underline">
                                            Live link ↗
                                        </a>
                                    )}
                                </div>
                                <div className="mt-6 flex justify-end gap-3">
                                    <button
                                        onClick={(event) => review(event, project.id, 'reject')}
                                        className="border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50"
                                    >
                                        Reject
                                    </button>
                                    <button
                                        onClick={(event) => review(event, project.id, 'approve')}
                                        className="bg-[#2C3E50] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1E293B]"
                                    >
                                        Approve for portfolio
                                    </button>
                                </div>
                            </article>
                        ))}
                        {projects.data.length === 0 && (
                            <p className="border border-[#CBD5E1] bg-white p-8 text-center text-sm text-slate-500">
                                No projects are waiting for review.
                            </p>
                        )}
                    </div>
                    {projects.links.length > 3 && (
                        <nav className="mt-6 flex flex-wrap gap-2">
                            {projects.links.map((link) => (
                                <a
                                    key={link.label}
                                    href={link.url || '#'}
                                    className={`border px-3 py-2 text-sm ${link.active ? 'border-[#2C3E50] bg-[#2C3E50] text-white' : 'border-slate-300 bg-white text-slate-600'}`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </nav>
                    )}
                </div>
            </main>
        </AppLayout>
    );
}
