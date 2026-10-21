import AppLayout from '@/layouts/app-layout';
import { Head, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

type PortfolioUser = {
    id: number;
    name: string;
    email: string;
    portfolio_slug: string;
    portfolio_is_public: boolean;
    portfolio_projects_count: number;
};

export default function PortfolioUsers({ users }: { users: PortfolioUser[] }) {
    const form = useForm({ name: '', email: '' });
    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('admin.portfolio-users.store'), { onSuccess: () => form.reset() });
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Portfolio users', href: '/admin/portfolio-users' }]}>
            <Head title="Portfolio users" />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto grid max-w-6xl gap-8 lg:grid-cols-[minmax(0,1fr)_1.3fr]">
                    <section className="h-fit border border-[#CBD5E1] bg-white p-6">
                        <p className="text-xs font-bold tracking-widest text-[#C07A50] uppercase">Superadmin only</p>
                        <h1 className="mt-2 text-2xl font-semibold">Provision a portfolio account</h1>
                        <p className="mt-2 text-sm leading-6 text-slate-500">
                            We’ll email the account email and a temporary password. The user must change it at first sign-in.
                        </p>
                        <form onSubmit={submit} className="mt-6 space-y-4">
                            {(['name', 'email'] as const).map((field) => (
                                <label key={field} className="grid gap-1.5 text-sm font-medium">
                                    {field[0].toUpperCase() + field.slice(1)}
                                    <input
                                        className="border border-slate-300 px-3 py-2.5 font-normal"
                                        type={field === 'email' ? 'email' : 'text'}
                                        value={form.data[field]}
                                        autoComplete={field.includes('password') ? 'new-password' : undefined}
                                        onChange={(event) => form.setData(field, event.target.value)}
                                        required
                                    />
                                    {form.errors[field] && <span className="text-xs text-red-600">{form.errors[field]}</span>}
                                </label>
                            ))}
                            {form.errors.email && (
                                <p role="alert" className="text-sm text-red-700">
                                    {form.errors.email}
                                </p>
                            )}
                            <button
                                disabled={form.processing}
                                className="w-full bg-[#2C3E50] px-4 py-3 text-sm font-semibold text-white disabled:opacity-50"
                            >
                                {form.processing ? 'Creating account and sending email…' : 'Create account and email credentials'}
                            </button>
                        </form>
                    </section>
                    <section>
                        <h2 className="text-xl font-semibold">Provisioned accounts</h2>
                        <div className="mt-4 divide-y divide-slate-100 border border-[#CBD5E1] bg-white">
                            {users.map((user) => (
                                <article key={user.id} className="flex flex-wrap items-center justify-between gap-4 p-5">
                                    <div>
                                        <h3 className="font-semibold">{user.name}</h3>
                                        <p className="mt-1 text-sm text-slate-500">
                                            {user.email} · {user.portfolio_projects_count} projects
                                        </p>
                                    </div>
                                    <div className="text-right text-xs">
                                        <a
                                            className="font-semibold text-[#9A5636] hover:underline"
                                            href={route('portfolio.public', user.portfolio_slug)}
                                            target="_blank"
                                            rel="noreferrer"
                                        >
                                            Public portfolio ↗
                                        </a>
                                        <p className={user.portfolio_is_public ? 'mt-1 text-emerald-700' : 'mt-1 text-slate-400'}>
                                            {user.portfolio_is_public ? 'Public' : 'Private'}
                                        </p>
                                    </div>
                                </article>
                            ))}
                            {users.length === 0 && <p className="p-6 text-sm text-slate-500">No portfolio accounts have been provisioned.</p>}
                        </div>
                    </section>
                </div>
            </main>
        </AppLayout>
    );
}
