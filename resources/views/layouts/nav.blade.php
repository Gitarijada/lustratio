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
                    <a class="nav-link active" aria-current="page" href="{{ url('/fame_all2') }}">BE FAMOUS</a>
                    <a class="nav-link active" aria-current="page" href="{{ url('/equ') }}">SLUCAJEVI</a>
                    <a class="nav-link active" aria-current="page" href="{{ url('/list-event') }}">EVENTS</a>

                    <div class="dropdown">
                        <a class="nav-link active" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            &nbsp;&nbsp;DATA Adminstration&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          </a>
                    
                        <div class="dropdown-content" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="{{ url('/upload_guess_img') }}">DODAJ GA POZNATIM</a>
                            <a class="dropdown-item" href="{{ url('/equ') }}">SLUCAJEVI</a>
                            <a class="dropdown-item" href="{{ url('/list-event') }}">EVENTS</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/create') }}">CREATE SLUCAJ</a>
                            <a class="dropdown-item" href="{{ url('/create-event') }}">CREATE EVENTS</a>
                            <a class="dropdown-item" href="{{ url('/add-valeevent') }}">ADD VALE TO EVENT</a>
                            <a class="dropdown-item" href="{{ url('/create_ve') }}">CREATE SLUCAJ I EVENT</a>
                            <div class="dropdown-divider"></div>
                            <!--LU
                            <a class="dropdown-item" href="{--{ url('/list-crew') }}">CREW</a>
                            <a class="dropdown-item" href="{--{ url('/add-crew') }}">ADD CREW TO EVENT</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{--{ url('/list-group-event') }}">SEASONS</a>
                            <a class="dropdown-item" href="{--{ url('/create-group-event') }}">CREATE SEASONS</a>
                            -->
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
                    <!--
                    <a class="nav-link" href="#">Features</a>
                    <a class="nav-link" href="#">Pricing</a>
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    -->
                </div>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @if (auth()->user() && isset(Auth::user()->id) && Auth::user()->id <= 3)
                            <div class="dropdown">
                                <a class="nav-link active" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    &nbsp;&nbsp;&nbsp;&nbsp;Der Rote&nbsp;&nbsp;
                                </a>
                    
                                <div class="dropdown-content" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="{{ url('/rote') }}">DER ROTE</a>
                                    <a class="dropdown-item" href="{{ url('/rote_unlimit') }}">DER UNLIMIT</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/rote_create') }}">NEW CASE</a>
                                    <a class="nav-link active" aria-current="page" href="{{ url('/fame') }}">GUESS LIST</a> <!--show list of images_guess folder-->
                                    <a class="nav-link active" aria-current="page" href="{{ url('/modaltest2') }}">MODALTEST2</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>
                        @endif
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
                            <!--li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {***{ Auth::user()->username }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{***{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {***{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{***{ route('logout') }}" method="POST" class="d-none">
                                        @***csrf
                                    </form>
                                </div>
                            </li-->
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