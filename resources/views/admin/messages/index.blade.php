<x-layout>
    <x-slot name="title">Admin Messages | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0; display: grid; gap: 1rem;">
        <div>
            <h1 style="margin: 0; color: #fff;">Messages</h1>
            <p style="margin: 0.5rem 0 0; color: #94a3b8;">View incoming contact messages from the website.</p>
        </div>

        <div class="glass-panel" style="padding: 1.5rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.08);">
                        <th style="padding: 1rem 0;">Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                            <td style="padding: 1rem 0; color: #f8fafc;">{{ $message->name }}</td>
                            <td style="color: #cbd5e1;">{{ $message->email }}</td>
                            <td style="color: #cbd5e1;">{{ $message->subject }}</td>
                            <td style="color: #cbd5e1;">{{ $message->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-layout>
