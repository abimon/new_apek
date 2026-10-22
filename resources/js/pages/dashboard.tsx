import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';
import { ArrowUpRight, CheckCircle2, Eye, FolderKanban, Inbox, LayoutDashboard } from 'lucide-react';

type Inquiry = { id: number; name: string; email: string; service: string | null; message: string; read_at: string | null; created_at: string };
type Project = { id: number; title: string; slug: string; is_published: boolean; categories: { name: string }[] };

export default function Dashboard({
    stats,
    trend,
    inquiries,
    projects,
}: {
    stats: Record<string, number>;
    trend: { day: string; views: number; visitors: number }[];
    inquiries: Inquiry[];
    projects: Project[];
}) {
    // check if morning, afternoon or evening
    const hour = new Date().getHours();
    const greeting = hour < 12 ? 'morning' : hour < 18 ? 'afternoon' : 'evening';

    return (
        <AppLayout breadcrumbs={[{ title: 'Dashboard', href: '/dashboard' }]}>
            <Head title="Studio overview" />
            <div className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto max-w-7xl">
                    <div className="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                        <div>
                            <p className="text-xs font-bold tracking-[.2em] text-[#C07A50] uppercase">APEK Tech CMS</p>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight">Good {greeting}, here is the signal.</h1>
                        </div>
                        <div className="flex flex-wrap gap-4">
                            <Link href={route('admin.projects.index')} className="inline-flex items-center gap-2 text-sm font-semibold text-[#C07A50]">
                                Manage portfolio <FolderKanban size={16} />
                            </Link>
                            <Link href={route('home')} className="inline-flex items-center gap-2 text-sm font-semibold text-[#2C3E50] hover:text-[#C07A50]">
                                View live site <ArrowUpRight size={16} />
                            </Link>
                        </div>
                    </div>
                    <div className="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        {[
                            ['Published projects', stats.published, FolderKanban],
                            ['Total projects', stats.projects, LayoutDashboard],
                            ['Unread inquiries', stats.unread, Inbox],
                            ['Views this month', stats.views, Eye],
                        ].map(([label, value, Icon]) => (
                            <div className="border border-[#CBD5E1] bg-white p-5" key={label as string}>
                                <div className="flex justify-between text-slate-500">
                                    <span className="text-sm">{label as string}</span>
                                    {typeof Icon === 'function' && <Icon size={18} />}
                                </div>
                                <p className="mt-4 text-3xl font-semibold">{value as number}</p>
                            </div>
                        ))}
                    </div>
                    <div className="mt-6 grid gap-6 lg:grid-cols-[1.35fr_.65fr]">
                        <section className="border border-[#CBD5E1] bg-white p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <h2 className="font-semibold">Engagement trend</h2>
                                    <p className="mt-1 text-xs text-slate-500">Views and unique visitors - last 7 days</p>
                                </div>
                                <span className="text-xs font-semibold text-[#C07A50]">Privacy focused</span>
                            </div>
                            <div className="mt-8 flex h-48 items-end gap-2">
                                {trend.length ? (
                                    trend.map((item) => (
                                        <div className="flex flex-1 flex-col items-center gap-2" key={item.day}>
                                            <div className="flex h-36 w-full items-end justify-center gap-1">
                                                <div
                                                    className="w-1/2 bg-[#2C3E50]"
                                                    style={{
                                                        height: `${Math.max(8, (item.views / Math.max(...trend.map((entry) => entry.views), 1)) * 100)}%`,
                                                    }}
                                                />
                                                <div
                                                    className="w-1/2 bg-[#D4A373]"
                                                    style={{
                                                        height: `${Math.max(8, (item.visitors / Math.max(...trend.map((entry) => entry.visitors), 1)) * 100)}%`,
                                                    }}
                                                />
                                            </div>
                                            <span className="text-[10px] text-slate-400">{item.day.slice(5)}</span>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-sm text-slate-500">Analytics will appear as visitors browse the site.</p>
                                )}
                            </div>
                        </section>
                        <section className="border border-[#CBD5E1] bg-white p-6">
                            <h2 className="font-semibold">Recent inquiries</h2>
                            <div className="mt-5 space-y-4">
                                {inquiries.length ? (
                                    inquiries.map((inquiry) => (
                                        <div className="border-b border-slate-100 pb-4 last:border-0" key={inquiry.id}>
                                            <div className="flex items-start justify-between gap-3">
                                                <div>
                                                    <p className="text-sm font-semibold">{inquiry.name}</p>
                                                    <p className="mt-1 text-xs text-slate-500">{inquiry.service || 'General inquiry'}</p>
                                                </div>
                                                {inquiry.read_at ? (
                                                    <CheckCircle2 size={16} className="text-emerald-600" />
                                                ) : (
                                                    <button
                                                        aria-label={`Mark inquiry from ${inquiry.name} as read`}
                                                        onClick={() => router.patch(route('admin.inquiries.read', inquiry.id))}
                                                        className="text-xs font-semibold text-[#C07A50]"
                                                    >
                                                        Mark read
                                                    </button>
                                                )}
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-sm text-slate-500">No inquiries yet.</p>
                                )}
                            </div>
                        </section>
                    </div>
                    <section className="mt-6 border border-[#CBD5E1] bg-white p-6">
                                    <div className="flex items-center justify-between gap-4">
                                        <div>
                                            <h2 className="font-semibold">Portfolio manager</h2>
                                            <span className="text-xs text-slate-500">{projects.length} recent records</span>
                                        </div>
                                        <Link href={route('admin.projects.index')} className="text-xs font-semibold text-[#C07A50]">View all</Link>
                        </div>
                        <div className="mt-5 divide-y divide-slate-100">
                            {projects.map((project) => (
                                    <div className="flex items-center justify-between gap-4 py-4" key={project.id}>
                                        <div>
                                            <Link href={route('admin.projects.edit', project.id)} className="font-medium hover:text-[#C07A50]">{project.title}</Link>
                                        <p className="mt-1 text-xs text-slate-500">
                                            {project.categories[0]?.name || 'Uncategorised'} - {project.is_published ? 'Published' : 'Draft'}
                                        </p>
                                    </div>
                                        <Link href={route('admin.projects.edit', project.id)} className="text-xs font-semibold text-[#C07A50]">Edit project</Link>
                                </div>
                            ))}
                        </div>
                    </section>
                </div>
            </div>
        </AppLayout>
    );
}
