<x-dash>
    <x-slot name="title">Shop | Admin Panel</x-slot>
    <!-- edit form goes here -->
    <section style="padding: 2.5rem 0; display: grid; gap: 1rem; max-width: 720px; margin: 0 auto;">
        <div>
            <h1 style="margin: 0; color: #fff;">Edit Product</h1>
            <p style="margin: 0.5rem 0 0; color: #94a3b8;">Update the details below to edit the product.</p>
        </div>

        <form action="{{ route('admin.shop.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="glass-panel" style="display: grid; gap: 1rem; padding: 1rem;">
            @csrf
            @method('PUT')

            <div>
                <label for="name" style="display: block; margin-bottom: 0.5rem;">Product Name*</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="form-control">
                @error('name')
                <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="description" style="display: block; margin-bottom: 0.5rem;">Description(Optional)</label>
                <textarea name="description" id="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
                @error('description')
                <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="category" style="display: block; margin-bottom: 0.5rem;">Category*</label>
                <select name="category" id="category" required class="form-select">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category }}" {{ old('category', $product->category) == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                    @endforeach
                </select>
                @error('category')
                <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="price" style="display: block; margin-bottom: 0.5rem;">Price (Ksh)*</label>
                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required class="form-control" min="1">
                @error('price')
                <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="cover" style="display: block; margin-bottom: 0.5rem;">Cover Image*</label>
                <div class="m-3 card p-3 border-dark bg-transparent" style="border-style:dashed;">
                    <img id="out" src="{{ asset('storage/' . $product->image_url) }}" style="width: 100%; object-fit:contain;" />
                    <input type="file" accept="image/jpeg, image/png, image/webp" name="image" id="cover" style="display: none;" class="form-control" onchange="loadCoverFile(event)">
                    <div class="pt-2" id="desc">
                        <div class="text-center" style="font-size: xxx-large; font-weight:bolder;">
                            <i class="bi bi-upload"></i>
                        </div>
                        <div class="text-center">
                            <label for="cover" class="btn btn-success text-white" title="Upload new profile image">Browse</label>
                        </div>
                    </div>
                </div>
                @error('image')
                <span style="color: red;">{{ $message }}</span>
                @enderror
                <script>
                    var loadCoverFile = function(event) {
                        var image = document.getElementById('out');
                        image.src = URL.createObjectURL(event.target.files[0]);
                    };
                </script>
            </div>
            <div>
                <label for="status" style="display: block; margin-bottom: 0.5rem;">Status*</label>
                <select name="status" id="status" required class="form-select">
                    <option value="">Select a status</option>
                    <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ old('status', $product->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    <option value="discontinued" {{ old('status', $product->status) == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                </select>
                @error('status')
                <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </section>
</x-dash>