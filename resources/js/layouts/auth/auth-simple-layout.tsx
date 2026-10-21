import { Link } from '@inertiajs/react';
import { ArrowLeft, ArrowUpRight } from 'lucide-react';

interface AuthLayoutProps {
    children: React.ReactNode;
    name?: string;
    title?: string;
    description?: string;
}

export default function AuthSimpleLayout({ children, title, description }: AuthLayoutProps) {
    return (
        <main className="min-h-svh bg-[#F4F7F9] text-[#1E293B] lg:grid lg:grid-cols-[.9fr_1.1fr]">
            <section className="relative flex min-h-[290px] flex-col overflow-hidden bg-[#2C3E50] px-6 py-7 text-white sm:px-10 lg:min-h-svh lg:px-14 lg:py-10">
                <div aria-hidden="true" className="pointer-events-none absolute -right-28 -bottom-40 h-[28rem] w-[28rem] rounded-full border border-white/10" />
                <div aria-hidden="true" className="pointer-events-none absolute -right-12 -bottom-24 h-[20rem] w-[20rem] rounded-full border border-[#D4A373]/30" />
                <Link href={route('home')} className="relative z-10 inline-flex w-fit items-center gap-3 text-lg font-semibold tracking-tight">
                    <span className="flex h-10 w-10 items-center justify-center bg-[#D4A373] text-sm font-bold text-[#2C3E50]">AT</span>
                    <span>
                        Apek<span className="text-[#D4A373]">Tech</span>
                    </span>
                </Link>
                <div className="relative z-10 mt-12 max-w-xl lg:my-auto lg:mt-0 lg:pb-14">
                    <p className="flex items-center gap-3 text-xs font-bold tracking-[.22em] text-[#D4A373] uppercase">
                        <span className="h-px w-8 bg-[#D4A373]" />
                        Software that moves work forward
                    </p>
                    <h2 className="mt-6 text-4xl leading-tight font-semibold tracking-tight sm:text-5xl lg:text-6xl">
                        Build with clarity.
                        <span className="block text-[#D4A373]">Grow with confidence.</span>
                    </h2>
                    <p className="mt-6 max-w-md text-base leading-7 text-slate-300">
                        Thoughtful software for ambitious teams, built to earn its place in the everyday.
                    </p>
                </div>
                <Link href={route('home')} className="relative z-10 mt-8 inline-flex w-fit items-center gap-2 text-sm font-semibold text-slate-300 transition-colors hover:text-white lg:mt-0">
                    Explore ApekTech <ArrowUpRight size={15} />
                </Link>
            </section>
            <section className="flex min-h-[calc(100svh-290px)] items-center justify-center px-5 py-12 sm:px-10 lg:min-h-svh lg:px-12 lg:py-16">
                <div className="w-full max-w-md">
                    <Link href={route('home')} className="mb-8 inline-flex items-center gap-2 text-xs font-semibold text-slate-500 transition-colors hover:text-[#C07A50]">
                        <ArrowLeft size={14} /> Back to ApekTech
                    </Link>
                    <div className="border border-[#CBD5E1] bg-white p-6 shadow-[0_18px_55px_-40px_rgba(44,62,80,0.45)] sm:p-9">
                        <div className="mb-7 h-1 w-12 bg-[#C07A50]" />
                        <div className="mb-7 space-y-2">
                            <p className="text-[11px] font-bold tracking-[.2em] text-[#C07A50] uppercase">Account access</p>
                            <h1 className="text-2xl font-semibold tracking-tight text-[#2C3E50] sm:text-3xl">{title}</h1>
                            <p className="text-sm leading-6 text-slate-500">{description}</p>
                        </div>
                        {children}
                    </div>
                    <p className="mt-5 text-center text-xs text-slate-400">ApekTech · Software built with intention</p>
                </div>
            </section>
        </main>
    );
}
