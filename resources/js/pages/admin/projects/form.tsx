import AppLayout from '@/layouts/app-layout';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { ArrowLeft, Save } from 'lucide-react';
import { FormEvent, useEffect, useState } from 'react';

type Option = { id: number; name: string };
const commonFeatures = [
    'User authentication',
    'Role-based access control',
    'Admin dashboard',
    'Search and filtering',
    'Payments and subscriptions',
    'Email notifications',
    'File uploads',
    'Analytics and reporting',
    'Third-party integrations',
    'Responsive design',
    'Audit logs',
    'API access',
];

type Project = {
    id: number;
    title: string;
    slug: string;
    cover_image: string | null;
    project_type: 'product' | 'client';
    client: string | null;
    summary: string;
    challenge: string;
    solution: string;
    live_url: string;
    accent: string;
    features: string[] | null;
    owner_involvement: boolean;
    is_featured: boolean;
    is_published: boolean;
    categories: Option[];
    tech_stacks: Option[];
} | null;

type FormData = {
    title: string;
    project_type: 'product' | 'client';
    client: string;
    summary: string;
    challenge: string;
    solution: string;
    live_url: string;
    accent: string;
    features: string[];
    category_ids: number[];
    tech_stack_ids: number[];
    owner_involvement: boolean;
    is_featured: boolean;
    is_published: boolean;
    cover: File | null;
};

export default function ProjectForm({ project, categories, techStacks }: { project: Project; categories: Option[]; techStacks: Option[] }) {
    const form = useForm<FormData>({
        title: project?.title || '',
        project_type: project?.project_type || 'client',
        client: project?.client || '',
        summary: project?.summary || '',
        challenge: project?.challenge || '',
        solution: project?.solution || '',
        live_url: project?.live_url || '',
        accent: project?.accent || '#D4A373',
        features: project?.features || [],
        category_ids: project?.categories?.map((item) => item.id) || [],
        tech_stack_ids: project?.tech_stacks?.map((item) => item.id) || [],
        owner_involvement: project?.owner_involvement ?? true,
        is_featured: project?.is_featured ?? false,
        is_published: project?.is_published ?? true,
        cover: null,
    });
    const [coverPreview, setCoverPreview] = useState<string | null>(null);
    const [submitError, setSubmitError] = useState<string | null>(null);
    const [customFeatureDraft, setCustomFeatureDraft] = useState('');
    const customFeatures = form.data.features.filter((feature) => !commonFeatures.includes(feature));

    useEffect(
        () =>
            router.on('exception', () => {
                setSubmitError('The project could not be saved due to a server or connection error. Please try again.');
            }),
        [],
    );

    useEffect(() => {
        if (!form.data.cover) {
            setCoverPreview(null);
            return;
        }

        const previewUrl = URL.createObjectURL(form.data.cover);
        setCoverPreview(previewUrl);

        return () => URL.revokeObjectURL(previewUrl);
    }, [form.data.cover]);

    const submit = (event: FormEvent) => {
        event.preventDefault();
        setSubmitError(null);
        const url = project ? route('admin.projects.update', project.id) : route('admin.projects.store');
        form.transform((data) => (project ? { ...data, _method: 'put' } : data));
        form.post(url, {
            forceFormData: true,
            onSuccess: () => setSubmitError(null),
            onError: (errors) => {
                setSubmitError(Object.values(errors).find((message) => Boolean(message)) || 'Please review the highlighted fields and try again.');
            },
        });
    };
    const selectCover = (file: File | undefined, input: HTMLInputElement) => {
        form.clearErrors('cover');

        if (!file) {
            form.setData('cover', null);
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
        if (!allowedTypes.includes(file.type)) {
            input.value = '';
            form.setData('cover', null);
            form.setError('cover', 'Choose a JPG, PNG, WebP, or AVIF image.');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            input.value = '';
            form.setData('cover', null);
            form.setError('cover', 'The cover image must be 5 MB or smaller.');
            return;
        }

        form.setData('cover', file);
    };
    const toggleFeature = (feature: string, checked: boolean) => {
        form.clearErrors('features');
        form.setData('features', checked ? [...form.data.features, feature] : form.data.features.filter((item) => item !== feature));
    };
    const addCustomFeature = () => {
        const feature = customFeatureDraft.trim();

        if (!feature) {
            return;
        }
        if (feature.length > 160) {
            form.setError('features', 'Each feature must be 160 characters or fewer.');
            return;
        }
        if (form.data.features.includes(feature)) {
            form.setError('features', 'That feature has already been added.');
            return;
        }
        if (form.data.features.length >= 20) {
            form.setError('features', 'A project can have up to 20 features.');
            return;
        }

        form.clearErrors('features');
        form.setData('features', [...form.data.features, feature]);
        setCustomFeatureDraft('');
    };
    const input = (key: keyof FormData, label: string, multiline = false) => (
        <label className="text-sm font-medium">
            {label}
            {multiline ? (
                <textarea
                    rows={4}
                    value={form.data[key] as string}
                    onChange={(event) => form.setData(key, event.target.value as never)}
                    className="mt-2 w-full border border-[#CBD5E1] px-3 py-3 outline-none focus:border-[#2C3E50]"
                />
            ) : (
                <input
                    value={form.data[key] as string}
                    onChange={(event) => form.setData(key, event.target.value as never)}
                    className="mt-2 w-full border border-[#CBD5E1] px-3 py-3 outline-none focus:border-[#2C3E50]"
                />
            )}
            {form.errors[key] && <span className="mt-1 block text-xs text-red-600">{form.errors[key]}</span>}
        </label>
    );
    const toggle = (key: 'owner_involvement' | 'is_featured' | 'is_published', label: string) => (
        <label className="flex items-center gap-2 text-sm">
            <input type="checkbox" checked={form.data[key]} onChange={(event) => form.setData(key, event.target.checked)} /> {label}
        </label>
    );
    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Dashboard', href: '/dashboard' },
                { title: 'Projects', href: '/admin/projects' },
            ]}
        >
            <Head title={project ? 'Edit project' : 'New project'} />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <form onSubmit={submit} className="mx-auto max-w-5xl">
                    <Link href={route('admin.projects.index')} className="inline-flex items-center gap-2 text-xs font-semibold text-slate-500">
                        <ArrowLeft size={14} /> All projects
                    </Link>
                    <div className="mt-5 flex items-end justify-between gap-4">
                        <div>
                            <p className="text-xs font-bold tracking-[.2em] text-[#C07A50] uppercase">Portfolio manager</p>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight">{project ? 'Edit project' : 'New project'}</h1>
                        </div>
                        <button
                            disabled={form.processing}
                            className="inline-flex items-center gap-2 bg-[#2C3E50] px-4 py-3 text-sm font-semibold text-white disabled:opacity-60"
                        >
                            <Save size={16} /> {form.processing ? 'Saving...' : 'Save project'}
                        </button>
                    </div>
                    {submitError && (
                        <p role="alert" className="mt-4 border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {submitError}
                        </p>
                    )}
                    {Object.keys(form.errors).length > 0 && (
                        <div role="alert" className="mt-4 border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <p className="font-semibold">The project could not be saved. Please check the following:</p>
                            <ul className="mt-2 list-disc space-y-1 pl-5">
                                {Object.entries(form.errors).map(([field, message]) => (
                                    <li key={field}>{message}</li>
                                ))}
                            </ul>
                        </div>
                    )}
                    {form.progress && (
                        <p aria-live="polite" className="mt-3 text-sm text-slate-500">
                            Uploading cover image: {form.progress.percentage}%
                        </p>
                    )}
                    <div className="mt-8 grid gap-6 lg:grid-cols-[1.4fr_.6fr]">
                        <section className="space-y-5 border border-[#CBD5E1] bg-white p-6">
                            {input('title', 'Title')}
                            <label className="block text-sm font-medium">
                                Project type
                                <select
                                    value={form.data.project_type}
                                    onChange={(event) => form.setData('project_type', event.target.value as FormData['project_type'])}
                                    className="mt-2 w-full border border-[#CBD5E1] bg-white px-3 py-3 outline-none focus:border-[#2C3E50]"
                                >
                                    <option value="product">ApekTech product (SaaS)</option>
                                    <option value="client">Client project</option>
                                </select>
                                {form.errors.project_type && <span className="mt-1 block text-xs text-red-600">{form.errors.project_type}</span>}
                            </label>
                            {form.data.project_type === 'client' && (
                                <label className="text-sm font-medium">
                                    Client / partner (optional)
                                    <input
                                        value={form.data.client}
                                        onChange={(event) => form.setData('client', event.target.value)}
                                        className="mt-2 w-full border border-[#CBD5E1] px-3 py-3 outline-none focus:border-[#2C3E50]"
                                    />
                                    <span className="mt-1 block text-xs text-slate-500">Leave blank for confidential client work.</span>
                                    {form.errors.client && <span className="mt-1 block text-xs text-red-600">{form.errors.client}</span>}
                                </label>
                            )}
                            {input('summary', 'Short summary')}
                            {input('challenge', 'Challenge', true)}
                            {input('solution', 'Solution', true)}
                            <fieldset className="space-y-4 border-t border-slate-100 pt-5">
                                <legend className="text-sm font-semibold">Project features</legend>
                                <p className="text-xs leading-5 text-slate-500">Select common features, then add any project-specific features.</p>
                                <div className="grid gap-3 sm:grid-cols-2">
                                    {commonFeatures.map((feature) => (
                                        <label key={feature} className="flex items-start gap-2 text-sm">
                                            <input
                                                type="checkbox"
                                                checked={form.data.features.includes(feature)}
                                                onChange={(event) => toggleFeature(feature, event.target.checked)}
                                                className="mt-0.5"
                                            />
                                            {feature}
                                        </label>
                                    ))}
                                </div>
                                {customFeatures.length > 0 && (
                                    <div className="space-y-2">
                                        <p className="text-xs font-semibold text-slate-600">Additional features</p>
                                        {customFeatures.map((feature) => (
                                            <div key={feature} className="flex items-center justify-between gap-3 border border-[#CBD5E1] px-3 py-2 text-sm">
                                                <span>{feature}</span>
                                                <button
                                                    type="button"
                                                    onClick={() => toggleFeature(feature, false)}
                                                    className="text-xs font-semibold text-red-600 hover:text-red-800"
                                                    aria-label={`Remove ${feature}`}
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                )}
                                <div className="flex flex-col gap-2 sm:flex-row">
                                    <input
                                        value={customFeatureDraft}
                                        onChange={(event) => setCustomFeatureDraft(event.target.value)}
                                        onKeyDown={(event) => {
                                            if (event.key === 'Enter') {
                                                event.preventDefault();
                                                addCustomFeature();
                                            }
                                        }}
                                        maxLength={160}
                                        placeholder="Add a custom feature"
                                        aria-label="Custom project feature"
                                        className="w-full border border-[#CBD5E1] px-3 py-2 text-sm outline-none focus:border-[#2C3E50]"
                                    />
                                    <button
                                        type="button"
                                        onClick={addCustomFeature}
                                        disabled={form.data.features.length >= 20}
                                        className="shrink-0 bg-[#2C3E50] px-4 py-2 text-sm font-semibold text-white disabled:opacity-50"
                                    >
                                        Add feature
                                    </button>
                                </div>
                                <p className="text-xs text-slate-500">{form.data.features.length}/20 features selected</p>
                                {form.errors.features && <p role="alert" className="text-xs text-red-600">{form.errors.features}</p>}
                            </fieldset>
                            <label className="block text-sm font-medium">
                                Cover image
                                {coverPreview || project?.cover_image ? (
                                    <img
                                        src={coverPreview || `/storage/${project?.cover_image}`}
                                        alt="Project cover preview"
                                        className="mt-2 aspect-video w-full max-w-lg object-cover"
                                    />
                                ) : (
                                    <div className="mt-2 flex aspect-video max-w-lg items-center justify-center border border-dashed border-[#CBD5E1] bg-slate-50 text-sm text-slate-400">
                                        No cover image selected
                                    </div>
                                )}
                                <input
                                    type="file"
                                    accept=".jpg,.jpeg,.png,.webp,.avif"
                                    onChange={(event) => selectCover(event.target.files?.[0], event.currentTarget)}
                                    aria-invalid={Boolean(form.errors.cover)}
                                    aria-describedby={form.errors.cover ? 'cover-error' : undefined}
                                    disabled={form.processing}
                                    className="mt-3 block w-full text-sm file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:font-semibold file:text-slate-700"
                                />
                                <span className="mt-1 block text-xs text-slate-500">Optional. Choose an image up to 5 MB.</span>
                                {form.errors.cover && <span id="cover-error" role="alert" className="mt-1 block text-xs text-red-600">{form.errors.cover}</span>}
                            </label>
                        </section>
                        <aside className="space-y-6 border border-[#CBD5E1] bg-white p-6">
                            <label className="text-sm font-medium">
                                Accent color
                                <input
                                    type="color"
                                    value={form.data.accent}
                                    onChange={(event) => form.setData('accent', event.target.value)}
                                    className="mt-2 block h-10 w-full"
                                />
                            </label>
                            {input('live_url', 'Live URL')}
                            {
                                <fieldset>
                                    <legend className="text-sm font-medium">Categories</legend>
                                    {categories.map((item) => (
                                        <label className="mt-3 flex items-center gap-2 text-sm" key={item.id}>
                                            <input
                                                type="checkbox"
                                                checked={form.data.category_ids.includes(item.id)}
                                                onChange={(event) =>
                                                    form.setData(
                                                        'category_ids',
                                                        event.target.checked
                                                            ? [...form.data.category_ids, item.id]
                                                            : form.data.category_ids.filter((id) => id !== item.id),
                                                    )
                                                }
                                            />
                                            {item.name}
                                        </label>
                                    ))}
                                </fieldset>
                            }
                            {
                                <fieldset>
                                    <legend className="text-sm font-medium">Tech stack</legend>
                                    {techStacks.map((item) => (
                                        <label className="mt-3 flex items-center gap-2 text-sm" key={item.id}>
                                            <input
                                                type="checkbox"
                                                checked={form.data.tech_stack_ids.includes(item.id)}
                                                onChange={(event) =>
                                                    form.setData(
                                                        'tech_stack_ids',
                                                        event.target.checked
                                                            ? [...form.data.tech_stack_ids, item.id]
                                                            : form.data.tech_stack_ids.filter((id) => id !== item.id),
                                                    )
                                                }
                                            />
                                            {item.name}
                                        </label>
                                    ))}
                                </fieldset>
                            }
                            <div className="space-y-3 border-t border-slate-100 pt-5">
                                {toggle('is_published', 'Published')}
                                {toggle('is_featured', 'Featured work')}
                                {toggle('owner_involvement', 'Owner led')}
                            </div>
                        </aside>
                    </div>
                </form>
            </main>
        </AppLayout>
    );
}
