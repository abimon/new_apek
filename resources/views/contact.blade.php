<x-layout>
    <x-slot name="title">Contact | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0 1.5rem; display: grid; gap: 1.5rem;">
        <div class="section-title">Contact</div>
        <p class="section-subtitle">Get in touch for project inquiries, collaboration, or hiring.</p>
    </section>

    <section class="glass-panel" style="padding: 1.75rem; display: grid; gap: 1.25rem;">
        @if(session('success'))
            <div style="background: #10b981; color: white; padding: 1rem; border-radius: 1rem;">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div style="background: rgba(248, 113, 113, 0.18); color: #fee2e2; padding: 1rem; border-radius: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contact.send') }}" method="POST" style="display: grid; gap: 1rem;">
            @csrf
            <input type="hidden" name="website" value="" />
            <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" style="padding: 1rem; border-radius: 0.95rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" required />
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" style="padding: 1rem; border-radius: 0.95rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" required />
            </div>
            <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone" style="padding: 1rem; border-radius: 0.95rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject" style="padding: 1rem; border-radius: 0.95rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" required />
            </div>
            <textarea name="message" placeholder="Message" rows="6" style="padding: 1rem; border-radius: 0.95rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" required>{{ old('message') }}</textarea>
            <button type="submit" class="button-primary">Send Message</button>
        </form>

        <div style="display: grid; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.08);">
            <p style="margin: 0; color: #cbd5e1;">GitHub: <a href="https://github.com" style="color: #38bdf8;">https://github.com</a></p>
            <p style="margin: 0; color: #cbd5e1;">LinkedIn: <a href="https://linkedin.com" style="color: #38bdf8;">https://linkedin.com/</a></p>
            <p style="margin: 0; color: #cbd5e1;">Email: <a href="mailto:developer@example.com" style="color: #38bdf8;">developer@example.com</a></p>
        </div>
    </section>
</x-layout>
