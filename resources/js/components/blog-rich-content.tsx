import { type ReactNode } from 'react';

export type RichNode = {
    type: string;
    text?: string;
    attrs?: { level?: number; href?: string };
    marks?: { type: string; attrs?: { href?: string } }[];
    content?: RichNode[];
};

function safeHref(href?: string): string | null {
    if (!href) return null;
    return (href.startsWith('/') && !href.startsWith('//')) || href.startsWith('#') || /^https?:\/\//i.test(href) ? href : null;
}

function renderNode(node: RichNode, key: number): ReactNode {
    let content: ReactNode = node.text ?? node.content?.map((child, index) => renderNode(child, index)) ?? null;

    for (const mark of node.marks ?? []) {
        if (mark.type === 'bold') content = <strong key={`b-${key}`}>{content}</strong>;
        if (mark.type === 'italic') content = <em key={`i-${key}`}>{content}</em>;
        if (mark.type === 'strike') content = <s key={`s-${key}`}>{content}</s>;
        if (mark.type === 'code')
            content = (
                <code key={`c-${key}`} className="rounded bg-slate-100 px-1.5 py-0.5 text-sm">
                    {content}
                </code>
            );
        if (mark.type === 'link') {
            const href = safeHref(mark.attrs?.href);
            if (href)
                content = (
                    <a key={`a-${key}`} href={href} rel="nofollow noopener noreferrer" className="text-[#9A5937] underline underline-offset-4">
                        {content}
                    </a>
                );
        }
    }

    switch (node.type) {
        case 'doc':
            return <>{content}</>;
        case 'paragraph':
            return (
                <p key={key} className="my-5 leading-8">
                    {content}
                </p>
            );
        case 'heading': {
            const level = Math.min(4, Math.max(2, node.attrs?.level ?? 2));
            if (level === 2)
                return (
                    <h2 key={key} className="mt-12 mb-4 text-3xl font-semibold tracking-tight">
                        {content}
                    </h2>
                );
            if (level === 3)
                return (
                    <h3 key={key} className="mt-10 mb-3 text-2xl font-semibold">
                        {content}
                    </h3>
                );
            return (
                <h4 key={key} className="mt-8 mb-3 text-xl font-semibold">
                    {content}
                </h4>
            );
        }
        case 'bulletList':
            return (
                <ul key={key} className="my-6 list-disc space-y-2 pl-7">
                    {content}
                </ul>
            );
        case 'orderedList':
            return (
                <ol key={key} className="my-6 list-decimal space-y-2 pl-7">
                    {content}
                </ol>
            );
        case 'listItem':
            return (
                <li key={key} className="pl-1">
                    {content}
                </li>
            );
        case 'blockquote':
            return (
                <blockquote key={key} className="my-8 border-l-4 border-[#D4A373] pl-6 text-lg text-slate-600">
                    {content}
                </blockquote>
            );
        case 'codeBlock':
            return (
                <pre key={key} className="my-8 overflow-x-auto bg-[#1E293B] p-5 text-sm text-slate-100">
                    <code>{content}</code>
                </pre>
            );
        case 'horizontalRule':
            return <hr key={key} className="my-10 border-slate-200" />;
        case 'hardBreak':
            return <br key={key} />;
        case 'text':
            return content;
        default:
            return content;
    }
}

export default function BlogRichContent({ content }: { content: RichNode }) {
    return <div className="blog-content">{renderNode(content, 0)}</div>;
}
