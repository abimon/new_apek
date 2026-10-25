import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';
import { Check, Trash2, X } from 'lucide-react';

type Comment = {
    id: number;
    name: string;
    email: string;
    body: string;
    approved_at: string | null;
    created_at: string;
    post: { id: number; title: string; slug: string };
};
type PageLink = { label: string; url: string | null; active: boolean };

export default function BlogComments({ comments }: { comments: { data: Comment[]; links: PageLink[] } }) {
    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Dashboard', href: '/dashboard' },
                { title: 'Comments', href: '/admin/blog-comments' },
            ]}
        >
            <Head title="Blog comments" />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto max-w-5xl">
                    <div className="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <h1 className="text-3xl font-semibold">Comment moderation</h1>
                            <p className="mt-2 text-sm text-slate-500">Approve comments before they appear on public articles.</p>
                        </div>
                        <Link href={route('admin.blog.index')} className="border border-slate-300 bg-white px-4 py-3 text-sm font-semibold">
                            Back to posts
                        </Link>
                    </div>
                    <div className="mt-8 space-y-4">
                        {comments.data.map((comment) => (
                            <article key={comment.id} className="border border-slate-200 bg-white p-5 md:p-7">
                                <div className="flex flex-wrap items-start justify-between gap-4">
                                    <div>
                                        <Link href={route('blog.show', comment.post.slug)} className="font-semibold text-[#9A5937]">
                                            {comment.post.title}
                                        </Link>
                                        <p className="mt-1 text-sm text-slate-500">
                                            {comment.name} · {comment.email} · {new Date(comment.created_at).toLocaleString()}
                                        </p>
                                    </div>
                                    <span
                                        className={`px-2 py-1 text-xs font-semibold ${comment.approved_at ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800'}`}
                                    >
                                        {comment.approved_at ? 'Approved' : 'Awaiting review'}
                                    </span>
                                </div>
                                <p className="mt-4 leading-7 whitespace-pre-wrap text-slate-700">{comment.body}</p>
                                <div className="mt-5 flex gap-3">
                                    <button
                                        onClick={() => router.patch(route('admin.blog-comments.approval', comment.id))}
                                        className="inline-flex items-center gap-2 border border-slate-300 px-3 py-2 text-sm font-semibold"
                                    >
                                        {comment.approved_at ? <X size={15} /> : <Check size={15} />}
                                        {comment.approved_at ? 'Hide' : 'Approve'}
                                    </button>
                                    <button
                                        onClick={() =>
                                            window.confirm('Delete this comment?') && router.delete(route('admin.blog-comments.destroy', comment.id))
                                        }
                                        aria-label="Delete comment"
                                        className="inline-flex items-center gap-2 border border-red-200 px-3 py-2 text-sm font-semibold text-red-700"
                                    >
                                        <Trash2 size={15} />
                                        Delete
                                    </button>
                                </div>
                            </article>
                        ))}
                        {!comments.data.length && (
                            <p className="border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600">No comments yet.</p>
                        )}
                    </div>
                    {comments.links.length > 3 && (
                        <nav aria-label="Comment pages" className="mt-6 flex justify-center gap-2">
                            {comments.links.map((link, index) => (
                                <Link
                                    key={`${link.label}-${index}`}
                                    href={link.url || '#'}
                                    className={`border px-3 py-2 text-sm ${link.active ? 'bg-[#2C3E50] text-white' : 'bg-white'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`}
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
