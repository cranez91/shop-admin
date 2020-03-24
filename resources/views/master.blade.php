<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Online Shop</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">

</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper" id="app">
  <v-app>
    <div class="content-wrapper">
      <div class="content">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
      </div>
    </div>
  </v-app>
</div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html> 