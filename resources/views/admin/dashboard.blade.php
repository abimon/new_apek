@extends('components.dash')
@section('content')
<div class="custom-container mt-2">
    <!-- row -->
    <div class="row mb-6 g-6">
        <div class="col-xl-8 col-lg-6">
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
                        <button class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> Add Product</button>
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
        <div class="col-xl-4 col-lg-6">
            <!-- card -->
            <div class="card card-lg">
                <!-- card body -->
                <div class="card-body">
                    <div class="mb-4 position-relative py-2">
                        <div>
                            <h5 class="mb-1">Ideas for You</h5>
                        </div>
                        <!-- swiper navigation-->

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <!-- swiper -->
                            <div class="swiper-container swiper" id="swiper-1" data-pagination-type="" data-speed="900"
                                data-space-between="100" data-pagination="false" data-navigation="true" data-autoplay="false"
                                data-autoplay-delay="2000"
                                data-breakpoints='{"480": {"slidesPerView": 1}, "768": {"slidesPerView": 1}, "1024": {"slidesPerView": 1}, "1200": {"slidesPerView": 1}}'>
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div>
                                            <h6>Create a Blog Post for your product</h6>
                                            <div>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem</p>
                                            </div>
                                            <div class="mt-2">
                                                <a href="" class="btn btn-light btn-sm">Read Now</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add more slides as needed -->
                                </div>
                                <!-- Add Pagination -->
                                <div class="swiper-pagination"></div>
                                <!-- Add Navigation -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection