@extends('vendors.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/invoice_iframe.css') }}">
<style>
    #iframe {
        height: 100vh !important;
    }

    #sidebar {
        display: none;
    }
</style>

<iframe src="{{ $htmlContent }}" width="100%" height="100vh" id="iframe"></iframe>


@endsection
