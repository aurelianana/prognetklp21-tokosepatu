<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }}</title>
    <!-- Styles -->
    <link rel="shortcut icon" href="{{ asset('assets/images/keranjang.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Filepond stylesheet -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">

    {{-- Select 2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm py-3">
            <div class="container">
                @if (!empty(auth()->user()->id))
                    <a class="navbar-brand" href="{{ route('admin.index') }}"><b>Toko Sepatu</b></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon text-dark pt-2"><i class="fas fa-bars"></i></span>
                    </button>
                @else
                    <a class="navbar-brand" href="{{ url('/') }}"><b>Toko Sepatu</b></a>
                @endif
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @if (!empty(auth()->user()->id))
                        <li class="nav-item dropdown" >
                            <a class="nav-link" href="#" type="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="notif">
                                <i class="far fa-bell"></i>
                                @if (Auth::guard('admin')->user()->notifications->where('read_at',null)->count() != null)
                                    <span class="badge bg-primary navbar-badge">{{Auth::guard('admin')->user()->notifications->where('read_at',null)->count() }}</span>
                                @endif
                            </a>
                            <div class="dropdown-menu" aria-labelledby="notif">
                                <span class="dropdown-item">User Notifications</span>
                                @forelse (Auth::guard('admin')->user()->notifications->where('read_at',null) as $data)
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">
                                        {{$data->data}}
                                        <div class="my-2">
                                            <span class="text-muted text-sm">{{$data->created_at->diffForHumans()}}</span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">
                                        Belum Terdapat Notifikasi
                                    </a>
                                @endforelse
                                @if (Auth::guard('admin')->user()->notifications->where('read_at',null)->count() != 0)
                                    <div class="dropdown-divider"></div>
                                    <a href="{{route('admin.mark-notifications')}}" class="dropdown-item dropdown-footer">Baca Semua Notifications</a>
                                @endif

                            </div>
                        </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page"
                                    href="{{ route('admin.index') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('admin.produk') }}">Data
                                    Produk</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page"
                                    href="{{ route('admin.kategori') }}">Data Kategori</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('admin.kurir') }}">Data
                                    Kurir</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page"
                                    href="{{ route('admin.transaksi.index') }}">Data
                                    Transaksi</a>
                            </li>
                            
                            
                        @endif
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                {{-- <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li> --}}
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('admin.profil') }}">
                                        Profil Akun
                                    </a>
                                    <div class="divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="clearfix mt-5"></div>
        <main>
            @yield('content')
        </main>
        <div class="clearfix mt-5 pt-4"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!-- Load FilePond library -->
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    

    @yield('javascript')
</body>

</html>
