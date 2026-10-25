import AppLayout from '@/layouts/app-layout';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { EditorContent, useEditor, type Editor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import {
    ArrowLeft,
    Bold,
    Code,
    Heading2,
    Heading3,
    Italic,
    Link as LinkIcon,
    List,
    ListOrdered,
    Quote,
    Redo,
    Save,
    Strikethrough,
    Undo,
} from 'lucide-react';
import { FormEvent, useEffect, useState } from 'react';

type Content = string;
type Post = {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    content: Content;
    cover_image: string | null;
    status: 'draft' | 'scheduled' | 'published';
    published_at: string | null;
    seo_title: string | null;
    seo_description: string | null;
    categories_text: string;
    tags_text: string;
} | null;
type FormData = {
    title: string;
    slug: string;
    excerpt: string;
    content: Content;
    status: 'draft' | 'scheduled' | 'published';
    published_at: string;
    categories: string;
    tags: string;
    seo_title: string;
    seo_description: string;
    cover: File | null;
};

function ToolButton({
    editor,
    label,
    active,
    onClick,
    children,
}: {
    editor: Editor | null;
    label: string;
    active?: boolean;
    onClick: () => void;
    children: React.ReactNode;
}) {
    return (
        <button
            type="button"
            aria-label={label}
            title={label}
            disabled={!editor}
            onClick={onClick}
            className={`border p-2 disabled:opacity-40 ${active ? 'border-[#2C3E50] bg-slate-100' : 'border-transparent hover:bg-slate-100'}`}
        >
            {children}
        </button>
    );
}

export default function BlogPostForm({ post }: { post: Post }) {
    const [submitError, setSubmitError] = useState<string | null>(null);
    const [coverPreview, setCoverPreview] = useState<string | null>(null);
    const form = useForm<FormData>({
        title: post?.title || '',
        slug: post?.slug || '',
        excerpt: post?.excerpt || '',
        content: JSON.stringify(post?.content || { type: 'doc', content: [{ type: 'paragraph' }] }),
        status: post?.status || 'draft',
        published_at: post?.published_at || '',
        categories: post?.categories_text || '',
        tags: post?.tags_text || '',
        seo_title: post?.seo_title || '',
        seo_description: post?.seo_description || '',
        cover: null,
    });
    const editor = useEditor({
        extensions: [StarterKit],
        content: JSON.parse(form.data.content),
        immediatelyRender: false,
        onUpdate: ({ editor }) => form.setData('content', JSON.stringify(editor.getJSON())),
    });

    useEffect(
        () => router.on('exception', () => setSubmitError('The post could not be saved due to a server or connection error. Please try again.')),
        [],
    );
    useEffect(() => {
        if (!form.data.cover) {
            setCoverPreview(null);
            return;
        }
        const url = URL.createObjectURL(form.data.cover);
        setCoverPreview(url);
        return () => URL.revokeObjectURL(url);
    }, [form.data.cover]);

    const submit = (event: FormEvent) => {
        event.preventDefault();
        setSubmitError(null);
        const url = post ? route('admin.blog.update', post.id) : route('admin.blog.store');
        form.transform((data) => (post ? { ...data, _method: 'put' } : data));
        form.post(url, { forceFormData: true, onSuccess: () => setSubmitError(null) });
    };
    const updateTitle = (title: string) => {
        form.setData((data) => ({
            ...data,
            title,
            slug: post
                ? data.slug
                : data.slug ||
                  title
                      .toLowerCase()
                      .trim()
                      .replace(/[^a-z0-9]+/g, '-')
                      .replace(/(^-|-$)/g, ''),
        }));
    };

    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Dashboard', href: '/dashboard' },
                { title: 'Blog', href: '/admin/blog' },
                { title: post ? 'Edit post' : 'New post', href: '#' },
            ]}
        >
            <Head title={post ? 'Edit blog post' : 'New blog post'} />
            <main className="min-h-full bg-[#F4F7F9] p-4 text-[#1E293B] md:p-8">
                <div className="mx-auto max-w-5xl">
                    <Link href={route('admin.blog.index')} className="inline-flex items-center gap-2 text-sm font-semibold text-slate-500">
                        <ArrowLeft size={15} /> Blog posts
                    </Link>
                    <h1 className="mt-4 text-3xl font-semibold">{post ? 'Edit post' : 'Create a post'}</h1>
                    <form onSubmit={submit} className="mt-6 space-y-6">
                        {submitError && (
                            <p role="alert" className="border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                                {submitError}
                            </p>
                        )}
                        <section className="space-y-5 border border-slate-200 bg-white p-5 md:p-7">
                            <div className="grid gap-5 md:grid-cols-2">
                                <label className="text-sm font-medium md:col-span-2">
                                    Title
                                    <input
                                        required
                                        value={form.data.title}
                                        onChange={(event) => updateTitle(event.target.value)}
                                        className="mt-2 h-11 w-full border border-slate-300 px-3"
                                    />
                                    {form.errors.title && <span className="text-xs text-red-600">{form.errors.title}</span>}
                                </label>
                                <label className="text-sm font-medium md:col-span-2">
                                    URL slug
                                    <input
                                        required
                                        value={form.data.slug}
                                        onChange={(event) => form.setData('slug', event.target.value)}
                                        className="mt-2 h-11 w-full border border-slate-300 px-3"
                                    />
                                    {form.errors.slug && <span className="text-xs text-red-600">{form.errors.slug}</span>}
                                </label>
                                <label className="text-sm font-medium md:col-span-2">
                                    Excerpt
                                    <textarea
                                        required
                                        rows={3}
                                        value={form.data.excerpt}
                                        onChange={(event) => form.setData('excerpt', event.target.value)}
                                        className="mt-2 w-full border border-slate-300 p-3"
                                    />
                                    {form.errors.excerpt && <span className="text-xs text-red-600">{form.errors.excerpt}</span>}
                                </label>
                            </div>
                            <div>
                                <p className="mb-2 text-sm font-medium">Article content</p>
                                <div className="flex flex-wrap gap-1 border border-b-0 border-slate-300 p-2">
                                    <ToolButton
                                        editor={editor}
                                        label="Bold"
                                        active={editor?.isActive('bold')}
                                        onClick={() => editor?.chain().focus().toggleBold().run()}
                                    >
                                        <Bold size={16} />
                                    </ToolButton>
                                    <ToolButton
                                        editor={editor}
                                        label="Italic"
                                        active={editor?.isActive('italic')}
                                        onClick={() => editor?.chain().focus().toggleItalic().run()}
                                    >
                                        <Italic size={16} />
                                    </ToolButton>
                                    <ToolButton
                                        editor={editor}
                                        label="Strikethrough"
                                        active={editor?.isActive('strike')}
                                        onClick={() => editor?.chain().focus().toggleStrike().run()}
                                    >
                                        <Strikethrough size={16} />
                                    </ToolButton>
                                    <ToolButton
                                        editor={editor}
                                        label="Add link"
                                        active={editor?.isActive('link')}
                                        onClick={() => {
                                            const href = window.prompt('Enter the link URL', editor?.getAttributes('link').href || 'https://');
                                            if (href === null) return;
                                            if (href.trim()) editor?.chain().focus().extendMarkRange('link').setLink({ href: href.trim() }).run();
                                            else editor?.chain().focus().extendMarkRange('link').unsetLink().run();
                                        }}
                                    >
                                        <LinkIcon size={16} />
                                    </ToolButton>
                                    <ToolButton
                                        editor={editor}
                                        label="Heading 2"
                                        active={editor?.isActive('heading', { level: 2 })}
                                        onClick={() => editor?.chain().focus().toggleHeading({ level: 2 }).run()}
                                    >
                                        <Heading2 size={16} />
                                    </ToolButton>
                                    <ToolButton
                                        editor={editor}
                                        label="Heading 3"
                                        active={editor?.isActive('heading', { level: 3 })}
                                        onClick={() => editor?.chain().focus().toggleHeading({ level: 3 }).run()}
                                    >
                                        <Heading3 size={16} />
                                    </ToolButton>
                                    <ToolButton
                                        editor={editor}
                                        label="Bullet list"
                                        active={editor?.isActive('bulletList')}
                                        onClick={() => editor?.chain().focus().toggleBulletList().run()}
                                    >
                                        <List size={16} />
                                    </ToolButton>
                                    <ToolButton
                                        editor={editor}
                                        label="Ordered list"
                                        active={editor?.isActive('orderedList')}
                                        onClick={() => editor?.chain().focus().toggleOrderedList().run()}
                                    >
                                        <ListOrdered size={16} />
                                    </ToolButton>
                                    <ToolButton
                                        editor={editor}
                                        label="Quote"
                                        active={editor?.isActive('blockquote')}
                                        onClick={() => editor?.chain().focus().toggleBlockquote().run()}
                                    >
                                        <Quote size={16} />
                                    </ToolButton>
                                    <ToolButton
                                        editor={editor}
                                        label="Code block"
                                        active={editor?.isActive('codeBlock')}
                                        onClick={() => editor?.chain().focus().toggleCodeBlock().run()}
                                    >
                                        <Code size={16} />
                                    </ToolButton>
                                    <ToolButton editor={editor} label="Undo" onClick={() => editor?.chain().focus().undo().run()}>
                                        <Undo size={16} />
                                    </ToolButton>
                                    <ToolButton editor={editor} label="Redo" onClick={() => editor?.chain().focus().redo().run()}>
                                        <Redo size={16} />
                                    </ToolButton>
                                </div>
                                <EditorContent
                                    editor={editor}
                                    className="min-h-80 border border-slate-300 bg-white p-4 [&_.tiptap]:min-h-72 [&_.tiptap]:outline-none [&_.tiptap_blockquote]:border-l-4 [&_.tiptap_blockquote]:pl-4 [&_.tiptap_h2]:mt-6 [&_.tiptap_h2]:text-2xl [&_.tiptap_h2]:font-semibold [&_.tiptap_h3]:mt-5 [&_.tiptap_h3]:text-xl [&_.tiptap_h3]:font-semibold [&_.tiptap_ol]:list-decimal [&_.tiptap_ol]:pl-6 [&_.tiptap_ul]:list-disc [&_.tiptap_ul]:pl-6"
                                />
                                {form.errors.content && <span className="text-xs text-red-600">{form.errors.content}</span>}
                            </div>
                            <div className="grid gap-5 md:grid-cols-2">
                                <label className="text-sm font-medium">
                                    Categories <span className="font-normal text-slate-500">(comma separated)</span>
                                    <input
                                        value={form.data.categories}
                                        onChange={(event) => form.setData('categories', event.target.value)}
                                        className="mt-2 h-11 w-full border border-slate-300 px-3"
                                    />
                                </label>
                                <label className="text-sm font-medium">
                                    Tags <span className="font-normal text-slate-500">(comma separated)</span>
                                    <input
                                        value={form.data.tags}
                                        onChange={(event) => form.setData('tags', event.target.value)}
                                        className="mt-2 h-11 w-full border border-slate-300 px-3"
                                    />
                                </label>
                            </div>
                        </section>
                        <section className="grid gap-6 border border-slate-200 bg-white p-5 md:grid-cols-2 md:p-7">
                            <div className="space-y-5">
                                <label className="block text-sm font-medium">
                                    Publication
                                    <select
                                        value={form.data.status}
                                        onChange={(event) => form.setData('status', event.target.value as FormData['status'])}
                                        className="mt-2 h-11 w-full border border-slate-300 bg-white px-3"
                                    >
                                        <option value="draft">Save as draft</option>
                                        <option value="published">Publish now</option>
                                        <option value="scheduled">Schedule publication</option>
                                    </select>
                                </label>
                                {form.data.status === 'scheduled' && (
                                    <label className="block text-sm font-medium">
                                        Publish at
                                        <input
                                            type="datetime-local"
                                            required
                                            value={form.data.published_at}
                                            onChange={(event) => form.setData('published_at', event.target.value)}
                                            className="mt-2 h-11 w-full border border-slate-300 px-3"
                                        />
                                        {form.errors.published_at && <span className="text-xs text-red-600">{form.errors.published_at}</span>}
                                    </label>
                                )}
                                <label className="block text-sm font-medium">
                                    Cover image
                                    <input
                                        type="file"
                                        accept="image/jpeg,image/png,image/webp,image/avif"
                                        onChange={(event) => form.setData('cover', event.target.files?.[0] || null)}
                                        className="mt-2 block w-full text-sm"
                                    />
                                    {form.errors.cover && <span className="text-xs text-red-600">{form.errors.cover}</span>}
                                </label>
                                {(coverPreview || post?.cover_image) && (
                                    <img
                                        src={coverPreview || `/storage/${post?.cover_image}`}
                                        alt="Cover preview"
                                        className="max-h-52 w-full object-cover"
                                    />
                                )}
                            </div>
                            <div className="space-y-5">
                                <label className="block text-sm font-medium">
                                    SEO title
                                    <input
                                        value={form.data.seo_title}
                                        onChange={(event) => form.setData('seo_title', event.target.value)}
                                        maxLength={180}
                                        className="mt-2 h-11 w-full border border-slate-300 px-3"
                                    />
                                </label>
                                <label className="block text-sm font-medium">
                                    SEO description
                                    <textarea
                                        value={form.data.seo_description}
                                        onChange={(event) => form.setData('seo_description', event.target.value)}
                                        maxLength={320}
                                        rows={3}
                                        className="mt-2 w-full border border-slate-300 p-3"
                                    />
                                </label>
                            </div>
                        </section>
                        <div className="flex justify-end">
                            <button
                                disabled={form.processing}
                                className="inline-flex items-center gap-2 bg-[#2C3E50] px-5 py-3 text-sm font-semibold text-white disabled:opacity-50"
                            >
                                <Save size={16} />
                                {form.processing ? 'Saving…' : 'Save post'}
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </AppLayout>
    );
}
