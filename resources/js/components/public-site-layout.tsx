import { Link } from '@inertiajs/react';
import { ArrowUpRight, Menu, X } from 'lucide-react';
import { type ReactNode, useState } from 'react';

const navigation = [
    { label: 'Home', href: '/' },
    { label: 'About', href: '/about' },
    { label: 'Services', href: '/services' },
    { label: 'Contact', href: '/contact' },
];

export default function PublicSiteLayout({ children }: { children: ReactNode }) {
    const [menuOpen, setMenuOpen] = useState(false);

    return (
        <div className="min-h-screen bg-[#F4F7F9] text-[#1E293B]">
            <header className="sticky top-0 z-40 border-b border-[#CBD5E1]/70 bg-[#F4F7F9]/95 backdrop-blur">
                <div className="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 lg:px-8">
                    <Link
                        href={route('home')}
                        className="flex items-center gap-3 text-lg font-semibold tracking-tight"
                        aria-label="APEK Technologies home"
                    >
                        <span className="flex h-9 w-9 items-center justify-center bg-[#2C3E50] text-sm text-white">AT</span>
                        <span>
                            Apek<span className="text-[#C07A50]">Tech</span>
                        </span>
                    </Link>
                    <nav aria-label="Main navigation" className="hidden items-center gap-8 text-sm font-medium md:flex">
                        {navigation.map((item) => (
                            <Link key={item.label} href={item.href} className="transition-colors hover:text-[#C07A50]">
                                {item.label}
                            </Link>
                        ))}
                    </nav>
                    <Link
                        href={route('contact')}
                        className="hidden items-center gap-2 bg-[#2C3E50] px-4 py-2.5 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5 sm:flex"
                    >
                        Start a conversation <ArrowUpRight size={16} />
                    </Link>
                    <button
                        type="button"
                        aria-label={menuOpen ? 'Close navigation' : 'Open navigation'}
                        aria-expanded={menuOpen}
                        onClick={() => setMenuOpen(!menuOpen)}
                        className="p-2 md:hidden"
                    >
                        {menuOpen ? <X /> : <Menu />}
                    </button>
                </div>
                {menuOpen && (
                    <nav aria-label="Mobile navigation" className="border-t border-[#CBD5E1] bg-[#F4F7F9] px-5 py-5 md:hidden">
                        {navigation.map((item) => (
                            <Link key={item.label} href={item.href} onClick={() => setMenuOpen(false)} className="block py-2 font-medium">
                                {item.label}
                            </Link>
                        ))}
                    </nav>
                )}
            </header>
            {children}
            <footer className="border-t border-[#CBD5E1] bg-white">
                <div className="mx-auto flex max-w-7xl flex-col gap-5 px-5 py-8 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
                    <span>&copy; {new Date().getFullYear()} APEK Technologies. Built for the long run.</span>
                    <nav aria-label="Footer navigation" className="flex flex-wrap gap-x-5 gap-y-2">
                        {navigation.slice(1).map((item) => (
                            <Link key={item.label} href={item.href} className="hover:text-[#C07A50]">
                                {item.label}
                            </Link>
                        ))}
                    </nav>
                </div>
            </footer>
        </div>
    );
}
