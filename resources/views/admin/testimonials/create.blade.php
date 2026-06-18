<x-dash>
    <x-slot name="title">New Testimonial | Admin Panel</x-slot>

    <section style="padding: 2.5rem 0; display: grid; gap: 1rem; max-width: 720px; margin: 0 auto;">
        <div>
            <h1 style="margin: 0; color: #fff;">New Testimonial</h1>
            <p style="margin: 0.5rem 0 0; color: #94a3b8;">Add client feedback for the portfolio.</p>
        </div>

        <form method="POST" action="{{ route('admin.testimonials.store') }}" class="glass-panel" style="padding: 1.5rem; display: grid; gap: 1rem;">
            @csrf
            <input type="text" name="client_name" value="{{ old('client_name') }}" placeholder="Client Name" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="text" name="company" value="{{ old('company') }}" placeholder="Company" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="text" name="designation" value="{{ old('designation') }}" placeholder="Designation" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <textarea name="testimonial" rows="5" placeholder="Testimonial" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;">{{ old('testimonial') }}</textarea>
            <input type="url" name="image" value="{{ old('image') }}" placeholder="Image URL" style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;"><button type="submit" class="button-primary">Save Testimonial</button><a href="{{ route('admin.testimonials.index') }}" class="button-secondary">Cancel</a></div>
        </form>
    </section>
</x-dash>
