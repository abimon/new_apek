import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';
import { MessageCircle, Plus, Trash2 } from 'lucide-react';

type Post = {
    id: number;
    title: string;
    slug: string;
    status: 'draft' | 'scheduled' | 'published';
    published_at: string | null;
    categories: { id: number; name: string }[];
    pending_comments_count: number;
};
type PageLink = { label: string; url: string | null; active: boolean };

function displayStatus(post: Post): 'draft' | 'scheduled' | 'published' {
    if (post.status === 'scheduled' && post.published_at && new Date(post.published_at) <= new Date()) {
        return 'published';
    }

    return post.status;
}

export default function BlogPostIndex({ posts }: { posts: { data: Post[]; links: PageLink[] } }) {
    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Dashboard', href: '/dashboard' },
                { title: 'Blog', href: '/admin/blog' },
            ]}
        >
            <Head title="Blog posts" />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto max-w-6xl">
                    <div className="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <h1 className="text-3xl font-semibold tracking-tight">Blog posts</h1>
                            <p className="mt-2 text-sm text-slate-500">Create, schedule, and maintain journal articles.</p>
                        </div>
                        <div className="flex gap-3">
                            <Link
                                href={route('admin.blog-comments.index')}
                                className="inline-flex items-center gap-2 border border-slate-300 bg-white px-4 py-3 text-sm font-semibold"
                            >
                                <MessageCircle size={16} /> Comments
                            </Link>
                            <Link
                                href={route('admin.blog.create')}
                                className="inline-flex items-center gap-2 bg-[#2C3E50] px-4 py-3 text-sm font-semibold text-white"
                            >
                                <Plus size={16} /> New post
                            </Link>
                        </div>
                    </div>
                    <div className="mt-8 border border-[#CBD5E1] bg-white">
                        <div className="hidden grid-cols-[1fr_170px_180px_100px] gap-4 border-b border-slate-100 px-5 py-3 text-[11px] font-bold tracking-widest text-slate-400 uppercase md:grid">
                            <span>Post</span>
                            <span>Categories</span>
                            <span>Status / publish time</span>
                            <span />
                        </div>
                        {posts.data.map((post) => (
                            <div
                                key={post.id}
                                className="grid gap-3 border-b border-slate-100 px-5 py-5 last:border-0 md:grid-cols-[1fr_170px_180px_100px] md:items-center"
                            >
                                <div>
                                    <Link href={route('admin.blog.edit', post.id)} className="font-semibold hover:text-[#C07A50]">
                                        {post.title}
                                    </Link>
                                    <p className="mt-1 text-xs text-slate-500">/{post.slug}</p>
                                </div>
                                <span className="text-sm text-slate-500">
                                    {post.categories.map((category) => category.name).join(', ') || 'Uncategorized'}
                                </span>
                                <span className="text-sm text-slate-600">
                                    <span
                                        className={`mr-2 inline-block px-2 py-1 text-xs font-semibold ${displayStatus(post) === 'published' ? 'bg-[#E4F1E6] text-emerald-800' : displayStatus(post) === 'scheduled' ? 'bg-amber-50 text-amber-800' : 'bg-slate-100 text-slate-600'}`}
                                    >
                                        {displayStatus(post)}
                                    </span>
                                    {post.published_at ? new Date(post.published_at).toLocaleString() : ''}
                                </span>
                                <div className="flex items-center gap-3 md:justify-end">
                                    <span title="Pending comments" className="inline-flex items-center gap-1 text-xs text-slate-500">
                                        <MessageCircle size={14} /> {post.pending_comments_count}
                                    </span>
                                    <button
                                        aria-label={`Delete ${post.title}`}
                                        onClick={() => window.confirm('Delete this post?') && router.delete(route('admin.blog.destroy', post.id))}
                                        className="text-slate-400 hover:text-red-600"
                                    >
                                        <Trash2 size={16} />
                                    </button>
                                </div>
                            </div>
                        ))}
                        {!posts.data.length && <p className="p-8 text-center text-sm text-slate-500">No posts yet.</p>}
                    </div>
                    {posts.links.length > 3 && (
                        <nav aria-label="Admin blog pages" className="mt-6 flex justify-center gap-2">
                            {posts.links.map((link, index) => (
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
