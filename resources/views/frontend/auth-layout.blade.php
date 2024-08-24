<!DOCTYPE html>
<html lang="{{ $currentLanguageInfo->code }}" @if ($currentLanguageInfo->direction == 1) dir="rtl" @endif>

<head>
    {{-- required meta tags --}}
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- csrf-token for ajax request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- title --}}
    <title>@yield('pageHeading') {{ '| ' . $websiteInfo->website_title }}</title>

    <meta name="keywords" content="@yield('metaKeywords')">
    <meta name="description" content="@yield('metaDescription')">
    <meta name="theme-color" content="{{ $basicInfo->primary_color }}" />

    {{-- fav icon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

    {{-- include styles --}}
    @includeIf('frontend.partials.styles')
    <link rel="manifest" crossorigin="use-credentials" href="{{ asset('manifest.json') }}" />

    {{-- additional style --}}
    @yield('style')
</head>

<body>

    <style>
        /* body{
            background-image: url("{{ asset('home/bg4.jpg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 100vh;
        }  */
        
    </style>

    @yield('content')



    @includeIf('frontend.partials.scripts')

    @yield('script')
</body>

</html>