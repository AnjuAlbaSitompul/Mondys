<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>

<body class="layout-boxed">
    @yield('loader')
    @include('partials.navbar')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        @include('partials.sidebar')

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content container-xxl p-0">
                    @include('partials.breadcrumb')
                    @yield('content')
                </div>
            </div>
            @include('partials.footer')
        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->
    @include('partials.script')
</body>

</html>
