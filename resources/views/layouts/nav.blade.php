<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <!-- Scripts, Fonts, Styles of navbar -->
    <!--script src="{***{ asset('js/app.js') }}" defer></script-->
    <!--link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"-->

            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}     <!--in .env APP_NAME=LUSTRATIO-Ablution -- href="url('/')" def in web.php-->
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="navbar-nav">
                    <!--a class="nav-link active" aria-current="page" href="{***{ url('/') }}">Home</a-->
                    <a class="nav-link active" aria-current="page" href="{{ url('/fame-all2') }}">BE FAMOUS</a>
                    <a class="nav-link active" aria-current="page" href="{{ url('/equ') }}">SLUCAJEVI</a>
                    <a class="nav-link active" aria-current="page" href="{{ url('/list-event') }}">EVENTS</a>

                    <div class="dropdown">
                        <a class="nav-link active" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            &nbsp;&nbsp;DATA Adminstration&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          </a>
                    
                        <div class="dropdown-content" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="{{ url('/upload-guess') }}">DODAJ GA POZNATIM</a>
                            <a class="dropdown-item" href="{{ url('/equ') }}">SLUCAJEVI</a>
                            <a class="dropdown-item" href="{{ url('/list-event') }}">EVENTS</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/create') }}">CREATE SLUCAJ</a>
                            <a class="dropdown-item" href="{{ url('/create-event') }}">CREATE EVENTS</a>
                            <a class="dropdown-item" href="{{ url('/add-valeevent') }}">ADD VALE TO EVENT</a>
                            <a class="dropdown-item" href="{{ url('/create_ve') }}">CREATE SLUCAJ I EVENT</a>
                            <div class="dropdown-divider"></div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <a class="nav-link active" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            &nbsp;&nbsp;&nbsp;OUR GOAL-An aim we intend to achieve.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          </a>
                    
                        <div class="dropdown-content" aria-labelledby="dropdownMenuLink">
                            <a class="nav-link active" href="{{ URL('/goal')}}">OUR GOAL&nbsp;I</a>
                            <div class="dropdown-divider"></div>
                            <a class="nav-link active" href="{{ URL('/goal')}}">OUR GOAL&nbsp;II</a>
                            
                        </div>
                    </div>
					
                    <a class="nav-link active" aria-current="page" href="{{ URL('/about')}}">&nbsp;About</a>
                </div>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                        @if (auth()->user() && isset(Auth::user()->id) && Auth::user()->id < 10)
                            <div class="dropdown">
                                <a class="nav-link active" href="{{ URL('/news')}}" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    &nbsp;&nbsp;&nbsp;Novosti&nbsp;&nbsp;
                                </a>
                                @if (Auth::user()->id <= 3)
                                <div class="dropdown-content" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="{{ url('/rote') }}">DER ROTE</a>
                                    <a class="dropdown-item" href="{{ url('/rote_create') }}">DER CREATE</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/rote_create_ve') }}">DER C S&E</a>
                                    <a class="nav-link active" aria-current="page" href="{{ url('/modaltest2') }}">MODALTEST2</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                                @endif
                            </div>
                        @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <!--a class="nav-link" href="{-{ route('register') }}">{-{ __('Register') }}</a-->
                                    <a class="nav-link" href="{{ url('/auth-register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <div class="dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->username }}
                                    </a>

                                    <div class="dropdown-content mw-logout" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-center" aria-current="page" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-forma').submit();">{{ __('Logout') }}</a>
                                        <form id="logout-forma" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                        <div class="dropdown-divider"></div>
                                    </div>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>