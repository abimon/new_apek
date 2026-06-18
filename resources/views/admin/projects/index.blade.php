<x-layout>
    <x-slot name="title">Admin Projects | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0; display: grid; gap: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div>
                <h1 style="margin: 0; color: #fff;">Projects</h1>
                <p style="margin: 0.5rem 0 0; color: #94a3b8;">Manage project entries in the portfolio.</p>
            </div>
            <a href="{{ route('admin.projects.create') }}" class="button-primary">New Project</a>
        </div>

        @if(session('success'))
            <div style="background: #10b981; color: white; padding: 1rem; border-radius: 1rem;">{{ session('success') }}</div>
        @endif

        <div class="glass-panel" style="padding: 1.5rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.08);">
                        <th style="padding: 1rem 0;">Title</th>
                        <th>Category</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                            <td style="padding: 1rem 0; color: #f8fafc;">{{ $project->title }}</td>
                            <td style="color: #cbd5e1;">{{ $project->category }}</td>
                            <td style="color: #cbd5e1;">{{ $project->featured ? 'Yes' : 'No' }}</td>
                            <td style="display: flex; gap: 0.5rem; flex-wrap: wrap; padding: 1rem 0;">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="button-secondary">Edit</a>
                                <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" style="display: inline;">
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
</x-layout>
