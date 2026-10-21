import { Head, Link, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

export default function FirstLoginPassword() {
    const form = useForm({ password: '', password_confirmation: '' });
    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.put(route('password.first-login.update'), {
            onSuccess: () => form.reset(),
        });
    };

    return (
        <main className="flex min-h-screen items-center justify-center bg-[#F4F7F9] px-4 py-12 text-[#1E293B]">
            <Head title="Change your temporary password" />
            <section className="w-full max-w-lg border border-[#CBD5E1] bg-white p-6 shadow-sm md:p-9">
                <p className="text-xs font-bold tracking-[.2em] text-[#C07A50] uppercase">Account security</p>
                <h1 className="mt-3 text-3xl font-semibold tracking-tight">Choose a new password</h1>
                <p className="mt-3 text-sm leading-6 text-slate-600">
                    Your temporary password must be replaced before you can continue. Use at least 12 characters with letters and numbers.
                </p>
                <form onSubmit={submit} className="mt-7 space-y-5">
                    <label className="grid gap-2 text-sm font-medium">
                        New password
                        <input
                            type="password"
                            autoComplete="new-password"
                            required
                            value={form.data.password}
                            onChange={(event) => form.setData('password', event.target.value)}
                            className="border border-slate-300 px-3 py-3 focus:border-[#C07A50] focus:outline-none"
                        />
                        {form.errors.password && <span className="text-xs text-red-700">{form.errors.password}</span>}
                    </label>
                    <label className="grid gap-2 text-sm font-medium">
                        Confirm new password
                        <input
                            type="password"
                            autoComplete="new-password"
                            required
                            value={form.data.password_confirmation}
                            onChange={(event) => form.setData('password_confirmation', event.target.value)}
                            className="border border-slate-300 px-3 py-3 focus:border-[#C07A50] focus:outline-none"
                        />
                    </label>
                    {form.errors.password_confirmation && <p className="text-xs text-red-700">{form.errors.password_confirmation}</p>}
                    <button disabled={form.processing} className="w-full bg-[#2C3E50] px-4 py-3 text-sm font-semibold text-white disabled:opacity-50">
                        {form.processing ? 'Updating password…' : 'Save new password'}
                    </button>
                </form>
                <div className="mt-5 text-center">
                    <Link href={route('logout')} method="post" as="button" className="text-xs font-semibold text-slate-500 hover:text-[#9A5636]">
                        Sign out
                    </Link>
                </div>
            </section>
        </main>
    );
}
