import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';

type Project = { id: number; title: string; summary: string; portfolio_review_status: string; portfolio_reviewed_at: string | null };
export default function PortfolioProjects({ projects }: { projects: Project[] }) {
    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Portfolio profile', href: '/settings/portfolio' },
                { title: 'Your projects', href: '/settings/portfolio/projects' },
            ]}
        >
            <Head title="Your portfolio projects" />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto max-w-5xl">
                    <div className="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <Link href={route('portfolio.profile.edit')} className="text-xs font-semibold text-slate-500 hover:text-[#C07A50]">
                                ← Portfolio profile
                            </Link>
                            <h1 className="mt-4 text-3xl font-semibold">Your project submissions</h1>
                            <p className="mt-2 text-sm text-slate-500">
                                Projects are visible publicly only after superadmin approval and when your portfolio is public.
                            </p>
                        </div>
                        <Link href={route('portfolio.projects.create')} className="bg-[#2C3E50] px-4 py-3 text-sm font-semibold text-white">
                            Add a project
                        </Link>
                    </div>
                    <div className="mt-7 divide-y divide-slate-100 border border-[#CBD5E1] bg-white">
                        {projects.map((project) => (
                            <article key={project.id} className="flex flex-wrap items-center justify-between gap-4 p-5">
                                <div className="min-w-0 flex-1">
                                    <h2 className="font-semibold">{project.title}</h2>
                                    <p className="mt-1 text-sm text-slate-500">{project.summary}</p>
                                </div>
                                <span
                                    className={`px-2.5 py-1 text-xs font-semibold ${project.portfolio_review_status === 'approved' ? 'bg-emerald-50 text-emerald-700' : project.portfolio_review_status === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700'}`}
                                >
                                    {project.portfolio_review_status.replace('_', ' ')}
                                </span>
                                <Link href={route('portfolio.projects.edit', project.id)} className="text-sm font-semibold text-[#9A5636]">
                                    Edit
                                </Link>
                                <button
                                    onClick={() =>
                                        window.confirm('Remove this project from your portfolio?') &&
                                        router.delete(route('portfolio.projects.destroy', project.id))
                                    }
                                    className="text-sm font-semibold text-red-700"
                                >
                                    Delete
                                </button>
                            </article>
                        ))}
                        {projects.length === 0 && (
                            <p className="p-8 text-center text-sm text-slate-500">
                                No projects submitted yet. Add your first project to request review.
                            </p>
                        )}
                    </div>
                </div>
            </main>
        </AppLayout>
    );
}
