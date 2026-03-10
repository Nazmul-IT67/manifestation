<!doctype html>
<html lang="en">

<head>
    <!-- ============================================= -->
    <!--                HEADER SECTION                 -->
    <!-- ============================================= -->
    @include('backend.partials.header')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- ============================================= -->
        <!--                NAVBAR SECTION                 -->
        <!-- ============================================= -->
        @include('backend.partials.navbar')

        <!-- ============================================= -->
        <!--                SIDEBAR SECTION                 -->
        <!-- ============================================= -->
        @include('backend.partials.sidebar')

        <!-- ============================================= -->
        <!--                MAIN SECTION                 -->
        <!-- ============================================= -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">{{ $page_title }}</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#"><i class="nav-icon bi bi-house-fill"></i> Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')

        </main>

        <!-- ============================================= -->
        <!--                FOOTER SECTION                 -->
        <!-- ============================================= -->
        @include('backend.partials.footer')

    </div>
    <!-- ============================================= -->
    <!--                SCRIPT SECTION                 -->
    <!-- ============================================= -->
    @include('backend.partials.script')

</body>

</html>
