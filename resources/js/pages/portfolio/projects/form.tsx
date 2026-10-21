import AppLayout from '@/layouts/app-layout';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEvent, useEffect, useState } from 'react';

type Project = {
    id: number;
    title: string;
    project_type: 'product' | 'client';
    client: string | null;
    summary: string;
    challenge: string;
    solution: string;
    live_url: string | null;
    features: string[] | null;
    cover_image: string | null;
} | null;
type Data = {
    title: string;
    project_type: 'product' | 'client';
    client: string;
    summary: string;
    challenge: string;
    solution: string;
    live_url: string;
    features: string[];
    cover: File | null;
};
export default function PortfolioProjectForm({ project }: { project: Project }) {
    const form = useForm<Data>({
        title: project?.title || '',
        project_type: project?.project_type || 'client',
        client: project?.client || '',
        summary: project?.summary || '',
        challenge: project?.challenge || '',
        solution: project?.solution || '',
        live_url: project?.live_url || '',
        features: project?.features || [],
        cover: null,
    });
    const [preview, setPreview] = useState<string | null>(null);
    const [submitError, setSubmitError] = useState<string | null>(null);
    useEffect(() => {
        if (!form.data.cover) {
            setPreview(null);
            return;
        }
        const url = URL.createObjectURL(form.data.cover);
        setPreview(url);
        return () => URL.revokeObjectURL(url);
    }, [form.data.cover]);
    const submit = (event: FormEvent) => {
        event.preventDefault();
        setSubmitError(null);
        const url = project ? route('portfolio.projects.update', project.id) : route('portfolio.projects.store');
        form.transform((data) => (project ? { ...data, _method: 'put' } : data));
        form.post(url, {
            forceFormData: true,
            onError: (errors) => setSubmitError(Object.values(errors).find(Boolean) || 'Please correct the highlighted fields and try again.'),
        });
    };
    const input = 'border border-slate-300 bg-white px-3 py-2.5 focus:border-[#C07A50] focus:outline-none';
    const field = (key: Exclude<keyof Data, 'features' | 'cover'>, label: string, multiline = false, required = false) => (
        <label className="grid gap-1.5 text-sm font-medium">
            {label}
            {multiline ? (
                <textarea
                    required={required}
                    rows={key === 'summary' ? 3 : 5}
                    maxLength={key === 'summary' ? 240 : 5000}
                    value={form.data[key]}
                    onChange={(e) => form.setData(key, e.target.value)}
                    className={input}
                />
            ) : (
                <input
                    required={required}
                    type={key === 'live_url' ? 'url' : 'text'}
                    value={form.data[key]}
                    onChange={(e) => form.setData(key, e.target.value)}
                    className={input}
                />
            )}
            {form.errors[key] && <span className="text-xs text-red-600">{form.errors[key]}</span>}
        </label>
    );

    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Your projects', href: '/settings/portfolio/projects' },
                { title: project ? 'Edit submission' : 'Add project', href: '#' },
            ]}
        >
            <Head title={project ? 'Edit project submission' : 'Submit a project'} />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto max-w-3xl">
                    <Link href={route('portfolio.projects.index')} className="text-xs font-semibold text-slate-500">
                        ← Your projects
                    </Link>
                    <h1 className="mt-4 text-3xl font-semibold">{project ? 'Edit project' : 'Submit a project'}</h1>
                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Submitted work is private while pending. Any edit to an approved project sends it back for review.
                    </p>
                    <form onSubmit={submit} className="mt-6 space-y-5 border border-[#CBD5E1] bg-white p-5 md:p-7">
                        {field('title', 'Project name', false, true)}
                        <label className="grid gap-1.5 text-sm font-medium">
                            Project type
                            <select
                                value={form.data.project_type}
                                onChange={(e) => form.setData('project_type', e.target.value as Data['project_type'])}
                                className={input}
                            >
                                <option value="client">Client project</option>
                                <option value="product">SaaS / product</option>
                            </select>
                        </label>
                        {field('client', 'Client or product name (optional)')}
                        {field('summary', 'Short summary', true, true)}
                        {field('challenge', 'Challenge', true, true)}
                        {field('solution', 'Solution', true, true)}
                        {field('live_url', 'Public project URL (optional)')}
                        <label className="grid gap-1.5 text-sm font-medium">
                            Project features (one per line)
                            <textarea
                                rows={5}
                                value={form.data.features.join('\n')}
                                onChange={(e) =>
                                    form.setData(
                                        'features',
                                        e.target.value
                                            .split('\n')
                                            .map((v) => v.trim())
                                            .filter(Boolean),
                                    )
                                }
                                className={input}
                                placeholder={'Responsive design\nUser authentication\nPayment integration'}
                            />
                            {form.errors.features && <span className="text-xs text-red-600">{form.errors.features}</span>}
                        </label>
                        <label className="grid gap-1.5 text-sm font-medium">
                            Cover image (JPG, PNG, or WebP; max 5 MB)
                            <input
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                className={input}
                                onChange={(e) => form.setData('cover', e.target.files?.[0] || null)}
                            />
                            {form.errors.cover && <span className="text-xs text-red-600">{form.errors.cover}</span>}
                        </label>
                        {(preview || (project?.cover_image && `/storage/${project.cover_image}`)) && (
                            <img
                                src={preview || `/storage/${project?.cover_image}`}
                                alt="Project cover preview"
                                className="max-h-64 w-full object-cover"
                            />
                        )}
                        {submitError && (
                            <p role="alert" className="border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                                {submitError}
                            </p>
                        )}
                        <button disabled={form.processing} className="bg-[#2C3E50] px-6 py-3 text-sm font-semibold text-white disabled:opacity-50">
                            {form.processing ? 'Submitting…' : project ? 'Save and resubmit' : 'Submit for review'}
                        </button>
                    </form>
                </div>
            </main>
        </AppLayout>
    );
}
