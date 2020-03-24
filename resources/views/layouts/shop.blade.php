<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  @include('includes.head')
</head>
<body>
    <div id="app">
        <div class="wrapper">    
            @yield('content')
            @include('includes.footer')
        </div>
    </div>
</body>
</html>
