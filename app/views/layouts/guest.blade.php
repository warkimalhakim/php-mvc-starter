<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title', 'Guest Layout')</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            crossorigin="anonymous">

        @stack('css')
    </head>

    <body>

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('/') }}">PHP MVC Starter</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            @component('components.Button', ['url' => route('/'), 'class' => 'nav-link'])
                                Home
                            @endcomponent
                        </li>
                        <li class="nav-item">
                            @component('components.Button', ['url' => route('api', null, true), 'class' => 'nav-link'])
                                API (<i class="text-warning">guzzlehttp</i>)
                            @endcomponent
                        </li>
                        <li class="nav-item">
                            @component('components.Button', ['url' => route('users'), 'class' => 'nav-link'])
                                Users (<i class="text-warning">model</i>)
                            @endcomponent
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Content --}}
        <div class="container">
            <div class="row">
                <div class="col pt-3">
                    @yield('content')
                </div>
            </div>
        </div>



        {{-- Bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
            integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
        </script>

        {{-- Stack Javascript --}}
        @stack('script')
    </body>

</html>
