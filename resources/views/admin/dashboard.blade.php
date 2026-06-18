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
                    <div>
                        <h5 class="mb-0">Orders</h5>
                    </div>
                </div>
                <!-- table -->
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 table-centered table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Shipping Method</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#DU005</td>
                                <td>$150</td>
                                <td>Standard</td>
                                <td>Jan 20, 2025</td>
                                <td><span class="badge text-info-emphasis bg-info-subtle">Shipped</span></td>
                                <td><a href="#!" class="btn btn-white btn-sm">View</a></td>
                            </tr>
                            <tr>
                                <td>#DU004</td>
                                <td>$200</td>
                                <td>Express</td>
                                <td>Jan 22, 2025</td>
                                <td><span class="badge text-warning-emphasis bg-warning-subtle">Pending</span></td>
                                <td><a href="#!" class="btn btn-white btn-sm">View</a></td>
                            </tr>
                            <tr>
                                <td>#DU003</td>
                                <td>$300</td>
                                <td>Overnight</td>
                                <td>Jan 18, 2025</td>
                                <td><span class="badge text-danger-emphasis bg-danger-subtle">Cancel</span></td>
                                <td><a href="#!" class="btn btn-white btn-sm">View</a></td>
                            </tr>
                            <tr>
                                <td>#DU002</td>
                                <td>$560</td>
                                <td>Overnight</td>
                                <td>Jan 13, 2025</td>
                                <td><span class="badge text-success-emphasis bg-success-subtle">Completed</span></td>
                                <td><a href="#!" class="btn btn-white btn-sm">View</a></td>
                            </tr>
                            <tr>
                                <td>#DU002</td>
                                <td>$560</td>
                                <td>Overnight</td>
                                <td>Jan 11, 2025</td>
                                <td><span class="badge text-success-emphasis bg-success-subtle">Completed</span></td>
                                <td><a href="#!" class="btn btn-white btn-sm">View</a></td>
                            </tr>
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
                        <div class="swiper-navigation position-absolute top-50 end-10 me-4 me-lg-8 me-xl-4">
                            <div class="swiper-button-prev ms-n3"></div>
                            <div class="swiper-button-next ms-6"></div>
                        </div>
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
                                            <h4>Create a Blog Post for your product</h4>

                                            <div>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem</p>
                                            </div>
                                            <div class="mt-4">
                                                <a href="#!" class="btn btn-white btn-sm">Read Now</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div>
                                            <h4>Create a Blog Post for your product</h4>

                                            <div>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem</p>
                                            </div>
                                            <div class="mt-4">
                                                <a href="#!" class="btn btn-white btn-sm">Read Now</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div>
                                            <h4>Create a Blog Post for your product</h4>

                                            <div>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem</p>
                                            </div>
                                            <div class="mt-4">
                                                <a href="#!" class="btn btn-white btn-sm">Read Now</a>
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