import PublicPageMeta from '@/components/public-page-meta';
import PublicSiteLayout from '@/components/public-site-layout';
import { useForm, usePage } from '@inertiajs/react';
import { ArrowUpRight, Check } from 'lucide-react';
import { type FormEvent } from 'react';

type Inquiry = {
    name: string;
    email: string;
    company: string;
    service: string;
    message: string;
};

export default function Contact({ canonicalUrl }: { canonicalUrl: string }) {
    const { flash } = usePage<{ flash?: { success?: string } }>().props;
    const { data, setData, post, processing, errors, reset } = useForm<Inquiry>({
        name: '',
        email: '',
        company: '',
        service: '',
        message: '',
    });
    const title = 'Contact APEK Technologies | Start a Project in Kenya';
    const description =
        'Contact APEK Technologies to discuss a web application, API, mobile product, business system, or software and hardware project in Kenya and East Africa.';
    const schema = {
        '@context': 'https://schema.org',
        '@type': 'ContactPage',
        name: title,
        description,
        url: canonicalUrl,
        mainEntity: { '@type': 'Organization', name: 'APEK Technologies', url: route('home') },
    };

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        post(route('contact.store'), { onSuccess: () => reset() });
    };

    const field = (key: keyof Inquiry, label: string, type = 'text', required = false) => (
        <label htmlFor={key} className="text-sm font-medium">
            {label}
            <input
                id={key}
                name={key}
                type={type}
                required={required}
                value={data[key]}
                onChange={(event) => setData(key, event.target.value)}
                autoComplete={key === 'name' ? 'name' : key === 'email' ? 'email' : key === 'company' ? 'organization' : undefined}
                className="mt-2 w-full border border-slate-300 bg-white px-3 py-3 text-[#1E293B] outline-none focus:border-[#C07A50]"
                aria-invalid={Boolean(errors[key])}
                aria-describedby={errors[key] ? `${key}-error` : undefined}
            />
            {errors[key] && (
                <span id={`${key}-error`} className="mt-1 block text-xs text-red-700">
                    {errors[key]}
                </span>
            )}
        </label>
    );

    return (
        <>
            <PublicPageMeta title={title} description={description} canonicalUrl={canonicalUrl} schema={schema} />
            <PublicSiteLayout>
                <main>
                    <section className="bg-[#2C3E50] text-white">
                        <div className="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
                            <p className="text-xs font-bold tracking-[.22em] text-[#D4A373] uppercase">Contact APEK Technologies</p>
                            <h1 className="mt-6 max-w-4xl text-5xl leading-[1.05] font-semibold tracking-[-.04em] md:text-7xl">
                                Tell us about the problem you’re solving.
                            </h1>
                            <p className="mt-7 max-w-2xl text-lg leading-8 text-slate-300">
                                Share a little context about your idea, product, or workflow. We work with teams in Nairobi, across Kenya, and
                                throughout East Africa, and aim to reply within one business day.
                            </p>
                        </div>
                    </section>
                    <section className="mx-auto grid max-w-7xl gap-12 px-5 py-16 lg:grid-cols-[.7fr_1.3fr] lg:px-8 lg:py-24">
                        <div>
                            <p className="label">Start a conversation</p>
                            <h2 className="section-title">A useful first step.</h2>
                            <p className="mt-6 text-base leading-7 text-slate-600">
                                You do not need a finished brief. A description of the challenge, who it affects, and what a better outcome might look
                                like is enough to get started.
                            </p>
                            <div className="mt-8 space-y-4 border-t border-[#CBD5E1] pt-6 text-sm leading-6 text-slate-600">
                                <p>
                                    <strong className="text-[#2C3E50]">Web & business systems</strong>
                                    <br />
                                    Applications, workflow tools, and product platforms.
                                </p>
                                <p>
                                    <strong className="text-[#2C3E50]">APIs & mobile products</strong>
                                    <br />
                                    Connected services and cross-platform experiences.
                                </p>
                                <p>
                                    <strong className="text-[#2C3E50]">Software & hardware</strong>
                                    <br />
                                    Integrated digital and embedded product work.
                                </p>
                            </div>
                        </div>
                        <form onSubmit={submit} className="grid gap-5 border border-[#CBD5E1] bg-white p-6 sm:grid-cols-2 sm:p-8">
                            {field('name', 'Your name', 'text', true)}
                            {field('email', 'Email address', 'email', true)}
                            {field('company', 'Company (optional)')}
                            {field('service', 'What can we help with?')}
                            <label htmlFor="message" className="text-sm font-medium sm:col-span-2">
                                Project or challenge
                                <textarea
                                    id="message"
                                    name="message"
                                    required
                                    minLength={20}
                                    maxLength={5000}
                                    value={data.message}
                                    onChange={(event) => setData('message', event.target.value)}
                                    rows={6}
                                    className="mt-2 w-full resize-y border border-slate-300 bg-white px-3 py-3 text-[#1E293B] outline-none focus:border-[#C07A50]"
                                    aria-invalid={Boolean(errors.message)}
                                    aria-describedby={errors.message ? 'message-error' : undefined}
                                />
                                {errors.message && (
                                    <span id="message-error" className="mt-1 block text-xs text-red-700">
                                        {errors.message}
                                    </span>
                                )}
                            </label>
                            <button
                                type="submit"
                                disabled={processing}
                                className="inline-flex w-fit items-center gap-2 bg-[#2C3E50] px-5 py-3 font-semibold text-white disabled:opacity-60"
                            >
                                {processing ? 'Sending...' : 'Send inquiry'} <ArrowUpRight size={17} />
                            </button>
                            {flash?.success && (
                                <p role="status" className="flex items-center gap-2 text-sm text-green-800 sm:col-span-2">
                                    <Check size={16} /> {flash.success}
                                </p>
                            )}
                        </form>
                    </section>
                </main>
            </PublicSiteLayout>
        </>
    );
}
