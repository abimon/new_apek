@extends('components.dash')
@section('content')
<div class="custom-container mt-2">
    <!-- row -->
    <div class="row mb-6 g-6">
        <div class="col-xl-10 col-lg-10">
            <div class="p-8 py-10 rounded-3 p-lg-7 glass-panel mb-2">
                <!--heading-->
                <h1 class="fs-3">👋 Hello {{auth()->user()->name}},</h1>
                <p class="mb-0">Welcome to your Dashboard! Monitor your content, track your progress, and gain valuable insights.</p>
            </div>
            <div class="card card-lg mt-3 mb-3">
                <!-- card header -->
                <div class="card-header border-bottom-0">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">Products</h5>
                        <button class="mt-2 mb-2 btn-sm btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#UploadProduct"><i class="bi bi-plus"></i> Add Product</button>
                        <div class="modal fade" id="UploadProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="UploadProduct">Upload Product</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.product.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="card p-5">
                                                <div class="form-group">
                                                    <label for="">Product Name</label>
                                                    <input type="text" name="filename" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Price (Leave it blank if free)</label>
                                                    <input type="text" name="price" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Description</label>
                                                    <textarea name="description" id="" class="form-control" rows="4"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">File</label>
                                                    <input type="file" name="file" id="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- table -->
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 table-centered table-hover">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Version</th>
                                <th>Expirely Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td>{{$product->id}}</td>
                                <td>{{$product->name}}</td>
                                <td>{{$product->version}}</td>
                                <td>{{$product->expirely_date}}</td>
                                <td>
                                    @if ($product->status == 1)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <i class="bi bi-inbox fs-6"></i><br>
                                    No Products Found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection