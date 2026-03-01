<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>

<body class="form">

    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <div class="auth-container d-flex">
        <div class="container mx-auto align-self-center">
            @yield('content')
        </div>
    </div>
    @include('partials.script')

</body>

</html>
