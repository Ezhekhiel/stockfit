<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" id="display_sidebar" data-bs-theme="dark"
    style="background-color: #343A40;">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ asset('assets/img/AdminLTELogo v1.png') }}" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">PT. PWI 2</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-book nav-icon"></i>
                        <p>
                            <i>Stockfit</i>
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/setting_line') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Setting Line</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/output_stf') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Stockfit Line</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/output_stf/balance') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Balance</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-life-preserver nav-icon"></i>
                        <p>
                            <i>Tooling</i>
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/tooling/pad_press_stockfit') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Pad Press Stockfit</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-flask nav-icon"></i>
                        <p>
                            <i>LAB</i>
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/lab/chemical') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Chemical</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
