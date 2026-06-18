<div id="miniSidebar" class="pt-5 mt-4">

  <div class="brand-logo">
    <a class="d-none d-md-flex align-items-center gap-2" href="/">
      <img src="/storage/images/logo.webp" alt="" />
      <span class="fw-bold fs-4 site-logo-text">Dasher</span>
    </a>
  </div>
  @include('partials.list')

</div>


<div class="offcanvasNav offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
  aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <a class="d-flex align-items-center gap-2" href="/">
      <img src="/storage/assets/images/brand/logo/logo-icon.svg" alt="" />
      <span class="fw-bold fs-4 site-logo-text">Dasher</span>
    </a>

    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    @include('partials.list')
  </div>
</div>