<!DOCTYPE html>
<html lang="en">
<head>
<title>{{ @$page_title ?? "Fujika Dashboard" }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <link rel=icon href="{{asset('assets/web/assets/img/fav.png')}}" sizes="192x192" type="image/png">
    <script src="{{asset('kendo/jquery.min.js')}}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/feather/css/feather.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider-style.css') }}">
    <link rel="stylesheet" href="{{ asset('kendo/styles/kendo.common.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kendo/styles/kendo.moonlight.min.css') }}">
<style>
    .slick-dots li.slick-active:before {
    background-color: transparent !important;
}
.slick-next:hover:before {
    background-color: transparent !important;
}
.slick-prev:hover:before {
    background-color: transparent !important;
}
</style>
@include('Layout.lazyimages')
</head>
