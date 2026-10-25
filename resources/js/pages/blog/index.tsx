import PublicSiteLayout from '@/components/public-site-layout';
import { Head, Link, router } from '@inertiajs/react';
import { FormEvent } from 'react';

type Category = { id: number; name: string; slug: string };
type Tag = { id: number; name: string; slug: string };
type Post = {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    cover_image: string | null;
    published_at: string;
    author: { name: string } | null;
    categories: Category[];
    tags: Tag[];
};
type PageLink = { label: string; url: string | null; active: boolean };

export default function BlogIndex({
    posts,
    categories,
    tags,
    filters,
    canonicalUrl,
}: {
    posts: { data: Post[]; links: PageLink[] };
    categories: Category[];
    tags: Tag[];
    filters: { q: string; category: string; tag: string };
    canonicalUrl: string;
}) {
    const submitSearch = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const q = new FormData(event.currentTarget).get('q')?.toString() || '';
        router.get(route('blog.index'), { ...filters, q }, { preserveState: true, preserveScroll: true });
    };
    const date = (value: string) => new Date(value).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });

    return (
        <PublicSiteLayout>
            <Head title="Journal | APEK Technologies">
                <meta name="description" content="Ideas, lessons, and perspectives from the APEK Technologies team." />
                <link rel="canonical" href={canonicalUrl} />
            </Head>
            <main>
                <section className="bg-[#2C3E50] px-5 py-20 text-white lg:px-8 lg:py-28">
                    <div className="mx-auto max-w-7xl">
                        <p className="text-xs font-bold tracking-[.22em] text-[#D4A373] uppercase">Journal</p>
                        <h1 className="mt-5 max-w-3xl text-5xl font-semibold tracking-tight md:text-7xl">Thoughts on building useful technology.</h1>
                        <p className="mt-6 max-w-2xl text-lg leading-8 text-slate-200">Notes, practical guides, and lessons from our work.</p>
                    </div>
                </section>
                <section className="mx-auto grid max-w-7xl gap-10 px-5 py-14 lg:grid-cols-[1fr_280px] lg:px-8">
                    <div>
                        <form onSubmit={submitSearch} className="mb-8 flex gap-2">
                            <label htmlFor="blog-search" className="sr-only">
                                Search posts
                            </label>
                            <input
                                id="blog-search"
                                name="q"
                                defaultValue={filters.q}
                                placeholder="Search articles…"
                                className="h-12 min-w-0 flex-1 border border-slate-300 bg-white px-4 outline-none focus:border-[#C07A50]"
                            />
                            <button className="bg-[#2C3E50] px-5 text-sm font-semibold text-white">Search</button>
                        </form>
                        <div className="grid gap-6 sm:grid-cols-2">
                            {posts.data.map((post) => (
                                <article key={post.id} className="overflow-hidden border border-slate-200 bg-white">
                                    <Link href={route('blog.show', post.slug)} className="block">
                                        <div className="aspect-[16/9] bg-slate-100">
                                            {post.cover_image && (
                                                <img src={`/storage/${post.cover_image}`} alt="" className="h-full w-full object-cover" />
                                            )}
                                        </div>
                                        <div className="p-6">
                                            <div className="flex flex-wrap gap-2">
                                                {post.categories.map((category) => (
                                                    <span key={category.id} className="text-xs font-semibold text-[#9A5937]">
                                                        {category.name}
                                                    </span>
                                                ))}
                                            </div>
                                            <h2 className="mt-3 text-2xl font-semibold tracking-tight">{post.title}</h2>
                                            <p className="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">{post.excerpt}</p>
                                            <p className="mt-5 text-xs text-slate-500">
                                                {date(post.published_at)}
                                                {post.author ? ` · ${post.author.name}` : ''}
                                            </p>
                                        </div>
                                    </Link>
                                </article>
                            ))}
                        </div>
                        {!posts.data.length && (
                            <p className="border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600">
                                No articles found. Try another search or filter.
                            </p>
                        )}
                        {posts.links.length > 3 && (
                            <nav aria-label="Blog pages" className="mt-10 flex flex-wrap justify-center gap-2">
                                {posts.links.map((link, index) => (
                                    <Link
                                        key={`${link.label}-${index}`}
                                        href={link.url || '#'}
                                        preserveScroll
                                        className={`border px-3 py-2 text-sm ${link.active ? 'border-[#2C3E50] bg-[#2C3E50] text-white' : 'border-slate-300 bg-white'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </nav>
                        )}
                    </div>
                    <aside className="space-y-8">
                        <div>
                            <h2 className="font-semibold">Categories</h2>
                            <div className="mt-3 flex flex-wrap gap-2">
                                {categories.map((category) => (
                                    <Link
                                        key={category.id}
                                        href={route('blog.index', { ...filters, category: category.slug })}
                                        className={`border px-3 py-2 text-xs ${filters.category === category.slug ? 'border-[#2C3E50] bg-[#2C3E50] text-white' : 'border-slate-300 bg-white'}`}
                                    >
                                        {category.name}
                                    </Link>
                                ))}
                            </div>
                        </div>
                        <div>
                            <h2 className="font-semibold">Topics</h2>
                            <div className="mt-3 flex flex-wrap gap-2">
                                {tags.map((tag) => (
                                    <Link
                                        key={tag.id}
                                        href={route('blog.index', { ...filters, tag: tag.slug })}
                                        className={`border px-3 py-2 text-xs ${filters.tag === tag.slug ? 'border-[#2C3E50] bg-[#2C3E50] text-white' : 'border-slate-300 bg-white'}`}
                                    >
                                        {tag.name}
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </aside>
                </section>
            </main>
        </PublicSiteLayout>
    );
}
