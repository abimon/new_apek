import BlogRichContent, { type RichNode } from '@/components/blog-rich-content';
import PublicSiteLayout from '@/components/public-site-layout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { FormEvent } from 'react';

type Comment = { id: number; name: string; body: string; created_at: string };
type Post = {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    content: RichNode;
    cover_image: string | null;
    published_at: string;
    seo_title: string | null;
    seo_description: string | null;
    author: { name: string } | null;
    categories: { id: number; name: string; slug: string }[];
    tags: { id: number; name: string; slug: string }[];
};

export default function BlogShow({ post, comments, canonicalUrl }: { post: Post; comments: Comment[]; canonicalUrl: string }) {
    const { flash } = usePage<{ flash?: { success?: string } }>().props;
    const form = useForm({ name: '', email: '', body: '' });
    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('blog.comments.store', post.slug), { onSuccess: () => form.reset() });
    };
    const date = new Date(post.published_at).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });

    return (
        <PublicSiteLayout>
            <Head title={post.seo_title || post.title}>
                <meta name="description" content={post.seo_description || post.excerpt} />
                <meta property="og:type" content="article" />
                <meta property="og:title" content={post.seo_title || post.title} />
                <meta property="og:description" content={post.seo_description || post.excerpt} />
                <meta property="og:url" content={canonicalUrl} />
                <link rel="canonical" href={canonicalUrl} />
            </Head>
            <main className="mx-auto max-w-4xl px-5 py-12 lg:py-20">
                <Link href={route('blog.index')} className="text-sm font-semibold text-[#9A5937]">
                    ← Back to journal
                </Link>
                <article className="mt-8">
                    <div className="flex flex-wrap gap-2">
                        {post.categories.map((category) => (
                            <Link
                                key={category.id}
                                href={route('blog.index', { category: category.slug })}
                                className="text-xs font-semibold tracking-wide text-[#9A5937] uppercase"
                            >
                                {category.name}
                            </Link>
                        ))}
                    </div>
                    <h1 className="mt-4 text-4xl leading-tight font-semibold tracking-tight md:text-6xl">{post.title}</h1>
                    <p className="mt-5 text-xl leading-8 text-slate-600">{post.excerpt}</p>
                    <p className="mt-5 text-sm text-slate-500">
                        {date}
                        {post.author ? ` · ${post.author.name}` : ''}
                    </p>
                    {post.cover_image && <img src={`/storage/${post.cover_image}`} alt="" className="mt-10 max-h-[560px] w-full object-cover" />}
                    <div className="mt-10 text-[17px] text-slate-700">
                        <BlogRichContent content={post.content} />
                    </div>
                    <div className="mt-10 flex flex-wrap gap-2">
                        {post.tags.map((tag) => (
                            <Link
                                key={tag.id}
                                href={route('blog.index', { tag: tag.slug })}
                                className="bg-slate-100 px-3 py-2 text-xs text-slate-600"
                            >
                                #{tag.name}
                            </Link>
                        ))}
                    </div>
                </article>
                <section className="mt-16 border-t border-slate-200 pt-10">
                    <h2 className="text-2xl font-semibold">
                        Comments <span className="text-slate-400">({comments.length})</span>
                    </h2>
                    <div className="mt-6 space-y-5">
                        {comments.map((comment) => (
                            <article key={comment.id} className="border-b border-slate-100 pb-5">
                                <p className="font-semibold">{comment.name}</p>
                                <p className="mt-1 text-xs text-slate-500">{new Date(comment.created_at).toLocaleDateString()}</p>
                                <p className="mt-3 leading-7 whitespace-pre-wrap text-slate-700">{comment.body}</p>
                            </article>
                        ))}
                    </div>
                    <form onSubmit={submit} className="mt-10 space-y-5 border border-slate-200 bg-white p-6 md:p-8">
                        <div>
                            <h3 className="text-xl font-semibold">Join the conversation</h3>
                            <p className="mt-2 text-sm text-slate-600">Comments are reviewed before appearing publicly.</p>
                        </div>
                        {flash?.success && (
                            <p role="status" className="bg-emerald-50 p-3 text-sm text-emerald-800">
                                {flash.success}
                            </p>
                        )}
                        <div className="grid gap-4 sm:grid-cols-2">
                            {(['name', 'email'] as const).map((field) => (
                                <label key={field} className="text-sm font-medium">
                                    {field === 'name' ? 'Name' : 'Email'}
                                    <input
                                        type={field === 'email' ? 'email' : 'text'}
                                        value={form.data[field]}
                                        onChange={(event) => form.setData(field, event.target.value)}
                                        className="mt-2 h-11 w-full border border-slate-300 px-3"
                                    />
                                    {form.errors[field] && <span className="mt-1 block text-xs text-red-600">{form.errors[field]}</span>}
                                </label>
                            ))}
                        </div>
                        <label className="block text-sm font-medium">
                            Comment
                            <textarea
                                value={form.data.body}
                                onChange={(event) => form.setData('body', event.target.value)}
                                rows={5}
                                className="mt-2 w-full border border-slate-300 p-3"
                            />
                            {form.errors.body && <span className="mt-1 block text-xs text-red-600">{form.errors.body}</span>}
                        </label>
                        <button disabled={form.processing} className="bg-[#2C3E50] px-5 py-3 text-sm font-semibold text-white disabled:opacity-50">
                            {form.processing ? 'Submitting…' : 'Submit for review'}
                        </button>
                    </form>
                </section>
            </main>
        </PublicSiteLayout>
    );
}
