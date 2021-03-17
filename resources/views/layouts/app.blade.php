<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel Vue</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="h-100%">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-warning shadow-sm">
            <div class="container flex justify-content-between">
                <div class="flex">
                    <router-link to="/" class="navbar-brand" >
                     Laravel
                    </router-link>
                </div>
                    

                @guest
                    
                <div class="flex">
                    <router-link :to="{name: 'Login'}" class="navbar-brand">
                     Login
                    </router-link>
                </div>
                
                @endguest
                @auth
                    <div class="flex ">
                        
                    <router-link :to="{name: 'Dashboard'}" class="navbar-brand">
                        Dashboard
                        </router-link>

                        <router-link :to="{name: 'Company'}" class="navbar-brand">
                            Company
                        </router-link>

                    {{-- <router-link :to="{name: 'Company'}" class="navbar-brand">
                     Employees
                    </router-link> --}}
                    </div>
                    
                @endauth
            </div>
            @auth
            <div class="flex justify-content-between">
                <div class="flex">
                    <strong>Hello {{ Auth::user()->email}}</strong> 
                </div>
            </div>
            @endauth
            
        </nav>

        <div class="container-fluid">
            <router-view></router-view>
        </div>
        
       
    </div>
</body>
</html>

<script>
    
</script>