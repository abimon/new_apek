import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';
import { FormEvent } from 'react';

type User = {
    id: number;
    name: string;
    email: string;
    role: string;
    is_active: boolean;
    portfolio_enabled: boolean;
    portfolio_slug: string | null;
    created_at: string;
};
type UserPage = {
    data: User[];
    links: { url: string | null; label: string; active: boolean }[];
    current_page: number;
    last_page: number;
    total: number;
};

export default function UserManagement({ users }: { users: UserPage }) {
    const setAccess = (event: FormEvent, user: User) => {
        event.preventDefault();
        const enabling = !user.is_active;
        if (!enabling && !window.confirm(`Revoke system access for ${user.name}? They will be signed out on their next request.`)) return;
        router.patch(route('admin.users.access', user.id), { is_active: enabling }, { preserveScroll: true });
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'User management', href: '/admin/users' }]}>
            <Head title="User management" />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto max-w-6xl">
                    <p className="text-xs font-bold tracking-widest text-[#C07A50] uppercase">Superadmin only</p>
                    <div className="mt-2 flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <h1 className="text-3xl font-semibold">User management</h1>
                            <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                                Review system accounts and grant or revoke access. Disabling a user blocks future sign-in, ends their authenticated
                                session on its next request, and hides their public portfolio.
                            </p>
                        </div>
                        <p className="text-sm text-slate-500">{users.total} accounts</p>
                    </div>
                    <div className="mt-7 overflow-x-auto border border-[#CBD5E1] bg-white">
                        <table className="w-full min-w-[760px] text-left text-sm">
                            <thead className="border-b border-slate-200 bg-slate-50 text-[11px] font-bold tracking-widest text-slate-500 uppercase">
                                <tr>
                                    <th className="px-4 py-3">User</th>
                                    <th className="px-4 py-3">Role</th>
                                    <th className="px-4 py-3">Portfolio</th>
                                    <th className="px-4 py-3">System access</th>
                                    <th className="px-4 py-3">Created</th>
                                    <th className="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {users.data.map((user) => (
                                    <tr key={user.id}>
                                        <td className="px-4 py-4">
                                            <p className="font-semibold">{user.name}</p>
                                            <p className="mt-1 text-xs text-slate-500">{user.email}</p>
                                        </td>
                                        <td className="px-4 py-4 text-slate-600 capitalize">{user.role}</td>
                                        <td className="px-4 py-4">
                                            {user.portfolio_enabled ? (
                                                <a
                                                    href={route('portfolio.public', user.portfolio_slug || '')}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="text-xs font-semibold text-[#9A5636] hover:underline"
                                                >
                                                    Enabled ↗
                                                </a>
                                            ) : (
                                                <span className="text-xs text-slate-400">Not enabled</span>
                                            )}
                                        </td>
                                        <td className="px-4 py-4">
                                            <span
                                                className={`inline-flex px-2 py-1 text-xs font-semibold ${user.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'}`}
                                            >
                                                {user.is_active ? 'Permitted' : 'Revoked'}
                                            </span>
                                        </td>
                                        <td className="px-4 py-4 text-xs text-slate-500">{new Date(user.created_at).toLocaleDateString()}</td>
                                        <td className="px-4 py-4 text-right">
                                            <button
                                                onClick={(event) => setAccess(event, user)}
                                                className={`px-3 py-2 text-xs font-semibold ${user.is_active ? 'border border-red-200 text-red-700 hover:bg-red-50' : 'bg-[#2C3E50] text-white hover:bg-[#1E293B]'}`}
                                            >
                                                {user.is_active ? 'Revoke access' : 'Permit access'}
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                                {users.data.length === 0 && (
                                    <tr>
                                        <td colSpan={6} className="px-4 py-12 text-center text-slate-500">
                                            No user accounts found.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                    {users.last_page > 1 && (
                        <nav aria-label="User pages" className="mt-5 flex flex-wrap items-center justify-between gap-3">
                            <p className="text-xs text-slate-500">
                                Page {users.current_page} of {users.last_page}
                            </p>
                            <div className="flex gap-2">
                                {users.links.map((link) => (
                                    <Link
                                        key={link.label}
                                        href={link.url || '#'}
                                        aria-disabled={!link.url}
                                        className={`border px-3 py-2 text-xs ${link.active ? 'border-[#2C3E50] bg-[#2C3E50] text-white' : 'border-[#CBD5E1] bg-white text-slate-600'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`}
                                    >
                                        {link.label.replace('&laquo;', '‹').replace('&raquo;', '›')}
                                    </Link>
                                ))}
                            </div>
                        </nav>
                    )}
                </div>
            </main>
        </AppLayout>
    );
}
