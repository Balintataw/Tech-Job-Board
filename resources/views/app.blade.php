<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Vue SPA Demo</title>

      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <link href="{{ asset('css/app.css') }}" rel="stylesheet">

      <!-- Fonts -->
      <link rel="dns-prefetch" href="//fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


  </head>
  <body>
      <div id="app"> 
        <app></app>
      </div>

      <script src="{{ mix('js/app.js') }}"></script>
      <script src="https://kit.fontawesome.com/5a078d49c1.js" crossorigin="en">
  </body>
</html>