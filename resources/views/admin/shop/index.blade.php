<x-dash>
    <x-slot name="title">Shop | Admin Panel</x-slot>

    <section style="padding: 2.5rem 0; display: grid; gap: 1rem; max-width: 720px; margin: 0 auto;">
        <div>
            <h1 style="margin: 0; color: #fff;">Shop</h1>
            <p style="margin: 0.5rem 0 0; color: #94a3b8;">Manage products in the shop.</p>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <a href="{{ route('admin.shop.create') }}" class="btn btn-primary">Add New Product</a>
        </div>
        <div class="table table-responsive">
            <table class="glass-panel table-stripped" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 1rem; text-align: center;">Product</th>
                        <th style="padding: 1rem; text-align: left;">Category</th>
                        <th style="padding: 1rem; text-align: left;">Price</th>
                        <th style="padding: 1rem; text-align: left;">Status</th>
                        <th colspan="2" style="padding: 1rem; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td style="padding: 1rem;" class="text-center"><img src="/storage/{{$product->image_url }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 12px;"><br>{{ $product->name }}</td>
                        <td style="padding: 1rem;">{{ $product->category }}</td>
                        <td style="padding: 1rem;">Ksh{{ number_format($product->price, 2) }}</td>
                        <td style="padding: 1rem;"><span class="badge rounded {{ $product->status === 'available' ? 'bg-success' : ($product->status === 'unavailable' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($product->status) }}</span></td>
                        <td style="padding: 1rem;">
                            <a href="{{ route('admin.shop.edit', $product) }}" class="btn btn-sm btn-secondary w-75">Edit</a>
                        </td>
                        <td style="padding: 1rem;">
                            <form action="{{ route('admin.shop.destroy', $product) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-75" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
    </section>
</x-dash>