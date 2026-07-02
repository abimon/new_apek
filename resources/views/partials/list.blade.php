<ul class="navbar-nav flex-column  ">
    <!-- Projects item -->
    <li class="nav-item">
        <a class="nav-link" href="/admin">
            <span class="nav-icon">
                <i class="bi bi-window-sidebar"></i>
            </span>
            <span class="text">Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.projects.index') }}">
            <span class="nav-icon">
                <i class="bi bi-kanban"></i>
            </span>
            <span class="text">Projects</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.testimonials.index') }}">
            <span class="nav-icon">
                <i class="bi bi-chat-left-quote-fill"></i>
            </span>
            <span class="text">Testimonials</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.blog-posts.index') }}">
            <span class="nav-icon">
                <i class="bi bi-text-paragraph"></i>
            </span>
            <span class="text">Articles</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.skills.index') }}">
            <span class="nav-icon">
                <i class="bi bi-tools"></i>
            </span>
            <span class="text">Skills</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.career.index') }}">
            <span class="nav-icon">
                <i class="bi bi-signpost-2"></i>
            </span>
            <span class="text">Career</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.product.index') }}">
            <span class="nav-icon">
                <i class="bi bi-basket2"></i>
            </span>
            <span class="text">Products</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.profile.index') }}">
            <span class="nav-icon">
                <i class="bi bi-person"></i>
            </span>
            <span class="text">Profile</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <span class="nav-icon">
                <i class="bi bi-people"></i>
            </span>
            <span class="text">Users</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.shop.index') }}">
            <span class="nav-icon">
                <i class="bi bi-shop"></i>
            </span>
            <span class="text">CHALK SHOP</span>
        </a>
    </li>
</ul>