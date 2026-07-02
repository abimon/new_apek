<x-layout>
    <x-slot name="title">Login | Developer Portfolio</x-slot>
    <section style="padding: 4rem 0; display: grid; gap: 1.5rem; max-width: 520px; margin: 0 auto;">
        <div class="glass-panel" style="padding: 2rem;">
            @if(session()->has('errors'))
                <div class="alert alert-danger">{{ session()->get('error') }}</div>
            @endif
            <h1 style="margin-top: 0; color: #fff;">Admin Login</h1>
            <form method="POST" action="{{ route('login') }}" style="display: grid; gap: 1rem;">
                @csrf
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required style="width: 100%; padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
                <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
                <button type="submit" class="button-primary">Login</button>
            </form>
            <p style="color: #94a3b8; margin-top: 1rem;">No account? <a href="{{ route('register') }}" style="color: #38bdf8;">Register here</a>.</p>
        </div>
    </section>
</x-layout>
