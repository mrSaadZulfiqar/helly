<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Offline {{ '| ' . $websiteInfo->website_title }}</title>
  {{-- csrf-token for ajax request --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
  {{-- bootstrap css --}}
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="manifest" crossorigin="use-credentials" href="{{ asset('manifest.json') }}" />

  {{-- main css --}}
  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

  @if ($currentLanguageInfo->direction == 1)
    {{-- right-to-left css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">

    {{-- right-to-left-responsive css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/rtl-responsive.css') }}">
  @endif
  <link rel="stylesheet" href="{{ asset('assets/css/offline.css') }}">
</head>

<body>
  <!--    Error section start   -->
  <div class="container">
    <div class="error-container">
      <div>
        <div class="offline text-center">
          <img src="{{ asset('assets/img/offline.png') }}" alt="">
        </div>
        <div class="text-center">
          <h2>{{ __('Sorry, you are offline') }}...</h2>
        </div>
      </div>
    </div>
  </div>
  <!--    Error section end   -->
</body>

</html>
