import AppLayout from '@/layouts/app-layout';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

type Experience = {
    [key: string]: string;
    title: string;
    organization: string;
    location: string;
    start_date: string;
    end_date: string;
    description: string;
};
type Education = {
    [key: string]: string;
    qualification: string;
    institution: string;
    location: string;
    start_date: string;
    end_date: string;
    description: string;
};
type Profile = {
    name: string;
    portfolio_slug: string;
    portfolio_is_public: boolean;
    professional_title: string | null;
    portfolio_summary: string | null;
    portfolio_email: string | null;
    portfolio_phone: string | null;
    portfolio_location: string | null;
    portfolio_website: string | null;
    linkedin_url: string | null;
    github_url: string | null;
    professional_photo_url: string | null;
    portfolio_skills: string[] | null;
    portfolio_experience: Experience[] | null;
    portfolio_education: Education[] | null;
    portfolio_certifications: string[] | null;
    portfolio_languages: string[] | null;
    public_url: string;
};
type PortfolioProject = { id: number; title: string; summary: string; portfolio_review_status: string; portfolio_reviewed_at: string | null };
type Data = {
    professional_title: string;
    portfolio_summary: string;
    portfolio_email: string;
    portfolio_phone: string;
    portfolio_location: string;
    portfolio_website: string;
    linkedin_url: string;
    github_url: string;
    portfolio_skills: string[];
    portfolio_experience: Experience[];
    portfolio_education: Education[];
    portfolio_certifications: string[];
    portfolio_languages: string[];
    professional_photo: File | null;
    portfolio_is_public: boolean;
};
const blankExperience: Experience = { title: '', organization: '', location: '', start_date: '', end_date: '', description: '' };
const blankEducation: Education = { qualification: '', institution: '', location: '', start_date: '', end_date: '', description: '' };

function TextField({
    label,
    value,
    onChange,
    error,
    type = 'text',
}: {
    label: string;
    value: string;
    onChange: (value: string) => void;
    error?: string;
    type?: string;
}) {
    return (
        <label className="grid gap-1.5 text-sm font-medium">
            {label}
            <input
                type={type}
                value={value}
                onChange={(event) => onChange(event.target.value)}
                className="border border-slate-300 bg-white px-3 py-2.5 font-normal focus:border-[#C07A50] focus:outline-none"
            />
            {error && <span className="text-xs text-red-600">{error}</span>}
        </label>
    );
}

function EntryEditor<T extends Record<string, string>>({
    title,
    entries,
    fields,
    onChange,
    onAdd,
    onRemove,
}: {
    title: string;
    entries: T[];
    fields: { key: keyof T; label: string; multiline?: boolean }[];
    onChange: (index: number, key: keyof T, value: string) => void;
    onAdd: () => void;
    onRemove: (index: number) => void;
}) {
    const inputClass = 'border border-slate-300 bg-white px-3 py-2.5 focus:border-[#C07A50] focus:outline-none';
    return (
        <section className="border border-[#CBD5E1] bg-white p-5 md:p-7">
            <div className="flex items-center justify-between">
                <div>
                    <h2 className="text-lg font-semibold">{title}</h2>
                    <p className="mt-1 text-sm text-slate-500">Add dated, concise entries to your CV.</p>
                </div>
                <button type="button" onClick={onAdd} className="border border-slate-300 px-3 py-2 text-sm font-semibold">
                    Add entry
                </button>
            </div>
            <div className="mt-5 space-y-5">
                {entries.map((entry, index) => (
                    <div key={index} className="grid gap-4 border-t border-slate-100 pt-5 md:grid-cols-2">
                        {fields.map(({ key, label, multiline }) => (
                            <label key={String(key)} className={`grid gap-1.5 text-sm font-medium ${multiline ? 'md:col-span-2' : ''}`}>
                                {label}
                                {multiline ? (
                                    <textarea
                                        rows={3}
                                        value={entry[key]}
                                        onChange={(e) => onChange(index, key, e.target.value)}
                                        className={inputClass}
                                    />
                                ) : (
                                    <input value={entry[key]} onChange={(e) => onChange(index, key, e.target.value)} className={inputClass} />
                                )}
                            </label>
                        ))}
                        <div className="md:col-span-2">
                            <button type="button" onClick={() => onRemove(index)} className="text-xs font-semibold text-red-700">
                                Remove this entry
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </section>
    );
}

export default function PortfolioProfile({ profile, projects }: { profile: Profile; projects: PortfolioProject[] }) {
    const form = useForm<Data>({
        professional_title: profile.professional_title || '',
        portfolio_summary: profile.portfolio_summary || '',
        portfolio_email: profile.portfolio_email || '',
        portfolio_phone: profile.portfolio_phone || '',
        portfolio_location: profile.portfolio_location || '',
        portfolio_website: profile.portfolio_website || '',
        linkedin_url: profile.linkedin_url || '',
        github_url: profile.github_url || '',
        portfolio_skills: profile.portfolio_skills || [],
        portfolio_experience: profile.portfolio_experience || [],
        portfolio_education: profile.portfolio_education || [],
        portfolio_certifications: profile.portfolio_certifications || [],
        portfolio_languages: profile.portfolio_languages || [],
        professional_photo: null,
        portfolio_is_public: profile.portfolio_is_public,
    });
    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.transform((data) => ({ ...data, _method: 'put' }));
        form.post(route('portfolio.profile.update'), { forceFormData: true });
    };
    const inputClass = 'border border-slate-300 bg-white px-3 py-2.5 focus:border-[#C07A50] focus:outline-none';
    const replaceLine = (key: 'portfolio_skills' | 'portfolio_certifications' | 'portfolio_languages', value: string) => {
        form.setData(
            key,
            value
                .split('\n')
                .map((item) => item.trim())
                .filter(Boolean),
        );
    };
    const updateExperience = (index: number, key: keyof Experience, value: string) => {
        const rows = [...form.data.portfolio_experience];
        rows[index] = { ...rows[index], [key]: value };
        form.setData('portfolio_experience', rows);
    };
    const updateEducation = (index: number, key: keyof Education, value: string) => {
        const rows = [...form.data.portfolio_education];
        rows[index] = { ...rows[index], [key]: value };
        form.setData('portfolio_education', rows);
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Portfolio profile', href: '/settings/portfolio' }]}>
            <Head title="Build your portfolio and CV" />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto max-w-5xl">
                    <div className="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <p className="text-xs font-bold tracking-widest text-[#C07A50] uppercase">Your professional profile</p>
                            <h1 className="mt-2 text-3xl font-semibold">Portfolio & CV</h1>
                            <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                                Complete your profile, then explicitly publish it. Only your approved projects are shown to public visitors.
                            </p>
                        </div>
                        <div className="flex gap-2">
                            <a
                                target="_blank"
                                rel="noreferrer"
                                href={profile.public_url}
                                className="border border-[#CBD5E1] bg-white px-4 py-2.5 text-sm font-semibold"
                            >
                                View public link ↗
                            </a>
                            <Link href={route('portfolio.projects.index')} className="bg-[#2C3E50] px-4 py-2.5 text-sm font-semibold text-white">
                                Manage projects
                            </Link>
                        </div>
                    </div>
                    <div
                        className={`mt-5 border px-4 py-3 text-sm ${profile.portfolio_is_public ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-amber-200 bg-amber-50 text-amber-900'}`}
                    >
                        {profile.portfolio_is_public ? 'Your public portfolio is live.' : 'Your portfolio is private until you publish it below.'}{' '}
                        Shareable link:{' '}
                        <a href={profile.public_url} className="font-semibold underline">
                            {profile.public_url}
                        </a>
                    </div>
                    <form onSubmit={submit} className="mt-6 space-y-6">
                        <section className="grid gap-5 border border-[#CBD5E1] bg-white p-5 md:grid-cols-2 md:p-7">
                            <div className="md:col-span-2">
                                <h2 className="text-lg font-semibold">Professional identity</h2>
                                <p className="mt-1 text-sm text-slate-500">Your account name is shown as your name.</p>
                            </div>
                            <div className="md:col-span-2">
                                <TextField
                                    label="Professional title"
                                    value={form.data.professional_title}
                                    onChange={(v) => form.setData('professional_title', v)}
                                    error={form.errors.professional_title}
                                />
                            </div>
                            <label className="grid gap-1.5 text-sm font-medium md:col-span-2">
                                Professional photo (JPG, PNG, or WebP; max 5 MB)
                                <input
                                    className={inputClass}
                                    type="file"
                                    accept="image/jpeg,image/png,image/webp"
                                    onChange={(e) => form.setData('professional_photo', e.target.files?.[0] || null)}
                                />
                                {form.errors.professional_photo && <span className="text-xs text-red-600">{form.errors.professional_photo}</span>}
                                {profile.professional_photo_url && (
                                    <img
                                        src={profile.professional_photo_url}
                                        alt="Current professional portrait"
                                        className="mt-2 h-24 w-24 rounded-full object-cover"
                                    />
                                )}
                            </label>
                            <label className="grid gap-1.5 text-sm font-medium md:col-span-2">
                                Professional summary
                                <textarea
                                    required
                                    maxLength={5000}
                                    rows={5}
                                    value={form.data.portfolio_summary}
                                    onChange={(e) => form.setData('portfolio_summary', e.target.value)}
                                    className={inputClass}
                                />
                                {form.errors.portfolio_summary && <span className="text-xs text-red-600">{form.errors.portfolio_summary}</span>}
                            </label>
                            <TextField
                                label="Public email"
                                type="email"
                                value={form.data.portfolio_email}
                                onChange={(v) => form.setData('portfolio_email', v)}
                                error={form.errors.portfolio_email}
                            />
                            <TextField
                                label="Public phone"
                                value={form.data.portfolio_phone}
                                onChange={(v) => form.setData('portfolio_phone', v)}
                                error={form.errors.portfolio_phone}
                            />
                            <TextField
                                label="Location"
                                value={form.data.portfolio_location}
                                onChange={(v) => form.setData('portfolio_location', v)}
                                error={form.errors.portfolio_location}
                            />
                            <TextField
                                label="Website"
                                value={form.data.portfolio_website}
                                onChange={(v) => form.setData('portfolio_website', v)}
                                error={form.errors.portfolio_website}
                            />
                            <TextField
                                label="LinkedIn URL"
                                value={form.data.linkedin_url}
                                onChange={(v) => form.setData('linkedin_url', v)}
                                error={form.errors.linkedin_url}
                            />
                            <TextField
                                label="GitHub URL"
                                value={form.data.github_url}
                                onChange={(v) => form.setData('github_url', v)}
                                error={form.errors.github_url}
                            />
                        </section>
                        <section className="grid gap-5 border border-[#CBD5E1] bg-white p-5 md:grid-cols-2 md:p-7">
                            <div className="md:col-span-2">
                                <h2 className="text-lg font-semibold">Skills & credentials</h2>
                                <p className="mt-1 text-sm text-slate-500">Enter one item per line.</p>
                            </div>
                            {(
                                [
                                    ['portfolio_skills', 'Skills'],
                                    ['portfolio_certifications', 'Certifications'],
                                    ['portfolio_languages', 'Languages'],
                                ] as const
                            ).map(([key, label]) => (
                                <label key={key} className="grid gap-1.5 text-sm font-medium">
                                    {label}
                                    <textarea
                                        rows={5}
                                        value={form.data[key].join('\n')}
                                        onChange={(e) => replaceLine(key, e.target.value)}
                                        className={inputClass}
                                    />
                                    {form.errors[key] && <span className="text-xs text-red-600">{form.errors[key]}</span>}
                                </label>
                            ))}
                        </section>
                        <EntryEditor
                            title="Experience"
                            entries={form.data.portfolio_experience}
                            fields={[
                                { key: 'title', label: 'Role / title' },
                                { key: 'organization', label: 'Organization' },
                                { key: 'location', label: 'Location' },
                                { key: 'start_date', label: 'Start date' },
                                { key: 'end_date', label: 'End date' },
                                { key: 'description', label: 'Responsibilities and achievements', multiline: true },
                            ]}
                            onChange={updateExperience}
                            onAdd={() => form.setData('portfolio_experience', [...form.data.portfolio_experience, { ...blankExperience }])}
                            onRemove={(index) =>
                                form.setData(
                                    'portfolio_experience',
                                    form.data.portfolio_experience.filter((_, i) => i !== index),
                                )
                            }
                        />
                        <EntryEditor
                            title="Education"
                            entries={form.data.portfolio_education}
                            fields={[
                                { key: 'qualification', label: 'Qualification' },
                                { key: 'institution', label: 'Institution' },
                                { key: 'location', label: 'Location' },
                                { key: 'start_date', label: 'Start date' },
                                { key: 'end_date', label: 'End date' },
                                { key: 'description', label: 'Details', multiline: true },
                            ]}
                            onChange={updateEducation}
                            onAdd={() => form.setData('portfolio_education', [...form.data.portfolio_education, { ...blankEducation }])}
                            onRemove={(index) =>
                                form.setData(
                                    'portfolio_education',
                                    form.data.portfolio_education.filter((_, i) => i !== index),
                                )
                            }
                        />
                        <section className="flex flex-wrap items-center justify-between gap-5 border border-[#CBD5E1] bg-white p-5 md:p-7">
                            <div>
                                <h2 className="font-semibold">Make your portfolio public</h2>
                                <p className="mt-1 max-w-xl text-sm text-slate-500">
                                    Anyone with your unique link can see the profile details entered above, professional photo, and
                                    superadmin-approved projects. This does not publish your projects on the company site.
                                </p>
                            </div>
                            <label className="flex items-center gap-3 text-sm font-semibold">
                                <input
                                    type="checkbox"
                                    checked={form.data.portfolio_is_public}
                                    onChange={(e) => form.setData('portfolio_is_public', e.target.checked)}
                                    className="h-4 w-4 accent-[#2C3E50]"
                                />{' '}
                                Public
                            </label>
                        </section>
                        {form.hasErrors && (
                            <p role="alert" className="text-sm text-red-700">
                                Some details need attention. Review the highlighted fields.
                            </p>
                        )}
                        <button disabled={form.processing} className="bg-[#2C3E50] px-6 py-3 text-sm font-semibold text-white disabled:opacity-50">
                            {form.processing ? 'Saving…' : 'Save portfolio profile'}
                        </button>
                    </form>
                    <section className="mt-10">
                        <h2 className="text-xl font-semibold">Your project submissions</h2>
                        <div className="mt-4 divide-y divide-slate-100 border border-[#CBD5E1] bg-white">
                            {projects.map((project) => (
                                <article key={project.id} className="flex flex-wrap items-center justify-between gap-3 p-4">
                                    <div>
                                        <h3 className="font-semibold">{project.title}</h3>
                                        <p className="mt-1 text-sm text-slate-500">{project.summary}</p>
                                    </div>
                                    <span
                                        className={`px-2.5 py-1 text-xs font-semibold ${project.portfolio_review_status === 'approved' ? 'bg-emerald-50 text-emerald-700' : project.portfolio_review_status === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700'}`}
                                    >
                                        {project.portfolio_review_status.replace('_', ' ')}
                                    </span>
                                </article>
                            ))}
                            {projects.length === 0 && <p className="p-5 text-sm text-slate-500">You have not added any projects yet.</p>}
                        </div>
                    </section>
                </div>
            </main>
        </AppLayout>
    );
}
