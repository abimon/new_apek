<x-dash>
    <x-slot name="title">Admin Testimonials | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0; display: grid; gap: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div>
                <h1 style="margin: 0; color: #fff;">Testimonials</h1>
                <p style="margin: 0.5rem 0 0; color: #94a3b8;">Manage client testimonials for the portfolio.</p>
            </div>
            <a href="{{ route('admin.testimonials.create') }}" class="button-primary">New Testimonial</a>
        </div>

        @if(session('success'))
            <div style="background: #10b981; color: white; padding: 1rem; border-radius: 1rem;">{{ session('success') }}</div>
        @endif

        <div class="glass-panel" style="padding: 1.5rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.08);">
                        <th style="padding: 1rem 0;">Client</th>
                        <th>Company</th>
                        <th>Designation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testimonials as $testimonial)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                            <td style="padding: 1rem 0; color: #f8fafc;">{{ $testimonial->client_name }}</td>
                            <td style="color: #cbd5e1;">{{ $testimonial->company }}</td>
                            <td style="color: #cbd5e1;">{{ $testimonial->designation }}</td>
                            <td style="display: flex; gap: 0.5rem; flex-wrap: wrap; padding: 1rem 0;">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="button-secondary">Edit</a>
                                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button-secondary" style="background: rgba(248, 113, 113, 0.16);">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-dash>
