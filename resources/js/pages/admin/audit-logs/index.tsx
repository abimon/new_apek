import AppLayout from '@/layouts/app-layout';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, ChevronLeft, ChevronRight, ShieldAlert } from 'lucide-react';

type AuditLog = {
    id: number;
    event: string;
    subject_type: string | null;
    subject_id: number | null;
    route: string | null;
    method: string;
    status: 'attempted' | 'succeeded' | 'failed';
    http_status: number | null;
    ip_address: string | null;
    occurred_at: string;
    actor: { id: number; name: string } | null;
};

type Pagination = {
    data: AuditLog[];
    links: { url: string | null; label: string; active: boolean }[];
    current_page: number;
    last_page: number;
    total: number;
};

export default function AuditLogIndex({ logs }: { logs: Pagination }) {
    return (
        <AppLayout breadcrumbs={[{ title: 'Dashboard', href: '/dashboard' }, { title: 'Audit logs', href: '/admin/audit-logs' }]}>
            <Head title="Audit logs" />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto max-w-7xl">
                    <Link href={route('dashboard')} className="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#C07A50]">
                        <ArrowLeft size={14} /> Dashboard
                    </Link>
                    <div className="mt-5 flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <p className="flex items-center gap-2 text-xs font-bold tracking-[.2em] text-[#C07A50] uppercase">
                                <ShieldAlert size={15} /> Restricted · Superadmin
                            </p>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight">Audit logs</h1>
                            <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                                Sensitive sign-in and admin change attempts. Passwords and request contents are never recorded.
                            </p>
                        </div>
                        <p className="text-sm text-slate-500">{logs.total} recorded activities</p>
                    </div>

                    <div className="mt-8 overflow-x-auto border border-[#CBD5E1] bg-white">
                        <table className="w-full min-w-[850px] text-left text-sm">
                            <thead className="border-b border-slate-200 bg-slate-50 text-[11px] font-bold tracking-widest text-slate-500 uppercase">
                                <tr>
                                    <th className="px-4 py-3">Activity</th>
                                    <th className="px-4 py-3">Actor</th>
                                    <th className="px-4 py-3">Target</th>
                                    <th className="px-4 py-3">Status</th>
                                    <th className="px-4 py-3">IP address</th>
                                    <th className="px-4 py-3">Time</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {logs.data.map((log) => (
                                    <tr key={log.id} className="align-top">
                                        <td className="px-4 py-4">
                                            <p className="font-semibold">{log.event.replaceAll('_', ' ')}</p>
                                            <p className="mt-1 text-xs text-slate-400">
                                                {log.method} {log.route || '—'} {log.http_status ? `· HTTP ${log.http_status}` : ''}
                                            </p>
                                        </td>
                                        <td className="px-4 py-4">{log.actor?.name || 'Unauthenticated'}</td>
                                        <td className="px-4 py-4 text-slate-500">
                                            {log.subject_type ? `${log.subject_type.split('\\').pop()}${log.subject_id ? ` #${log.subject_id}` : ''}` : '—'}
                                        </td>
                                        <td className="px-4 py-4">
                                            <span
                                                className={`inline-block px-2 py-1 text-xs font-semibold ${
                                                    log.status === 'succeeded'
                                                        ? 'bg-emerald-50 text-emerald-700'
                                                        : log.status === 'failed'
                                                          ? 'bg-red-50 text-red-700'
                                                          : 'bg-amber-50 text-amber-700'
                                                }`}
                                            >
                                                {log.status}
                                            </span>
                                        </td>
                                        <td className="px-4 py-4 font-mono text-xs text-slate-500">{log.ip_address || '—'}</td>
                                        <td className="px-4 py-4 text-xs whitespace-nowrap text-slate-500">{new Date(log.occurred_at).toLocaleString()}</td>
                                    </tr>
                                ))}
                                {logs.data.length === 0 && (
                                    <tr>
                                        <td colSpan={6} className="px-4 py-12 text-center text-slate-500">
                                            No audited activities yet.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {logs.last_page > 1 && (
                        <nav aria-label="Audit log pages" className="mt-5 flex flex-wrap items-center justify-between gap-3">
                            <p className="text-xs text-slate-500">
                                Page {logs.current_page} of {logs.last_page}
                            </p>
                            <div className="flex gap-2">
                                {logs.links
                                    .filter((link) => link.label.includes('Previous') || link.label.includes('Next'))
                                    .map((link) => (
                                        <Link
                                            key={link.label}
                                            href={link.url || '#'}
                                            aria-disabled={!link.url}
                                            className={`inline-flex items-center gap-2 border border-[#CBD5E1] bg-white px-3 py-2 text-xs font-semibold ${
                                                link.url ? 'hover:border-[#2C3E50]' : 'pointer-events-none opacity-40'
                                            }`}
                                        >
                                            {link.label.includes('Previous') && <ChevronLeft size={14} />}
                                            {link.label.includes('Previous') ? 'Previous' : 'Next'}
                                            {link.label.includes('Next') && <ChevronRight size={14} />}
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
