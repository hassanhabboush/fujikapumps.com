<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fujika</title>
     <!-- favicon -->
     <link rel=icon href="{{ asset('assets/web/assets/img/fav.png')}}" sizes="192x192" type="image/png">
    <!-- animate -->
    <link rel="stylesheet" href="{{ asset('assets/web/assets/css/animate.css')}}">
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/web/assets/css/bootstrap.min.css')}}">
    <!-- magnific popup -->
    <link rel="stylesheet" href="{{ asset('assets/web/assets/css/magnific-popup.css')}}">
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{ asset('assets/web/assets/css/owl.carousel.min.css')}}">
    <!-- slick carousel -->
    <link rel="stylesheet" href="{{ asset('assets/web/assets/css/slick.css')}}">
    <!-- fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/web/assets/css/font-awesome.min.css')}}">
    <!-- flaticon -->
    <link rel="stylesheet" href="{{ asset('assets/web/assets/fonts/flaticon.css')}}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ versioned_asset('assets/web/assets/css/style.css') }}">
    <!-- responsive Stylesheet -->
    <link rel="stylesheet" href="{{ versioned_asset('assets/web/assets/css/responsive.css') }}">
<style>
.preloaderhidden
{
    display:none;
}
    .breadcumb-area .breadcumb-inner .page-lists li a
    {
        font-size:14px;
        }
        .breadcumb-area .breadcumb-inner .page-lists li
    {
        font-size:14px !important;
    }
    .logo img
    {
           width: 286px;
    }
.owl-prev {
    width: 15px;
    height: 100px;
    position: absolute;
    top: 40%;
    margin-left: -20px;
    display: block !important;
    border:0px solid black;
}

.owl-next {
    width: 15px;
    height: 100px;
    position: absolute;
    top: 40%;
    right: -25px;
    display: block !important;
    border:0px solid black;
}
.owl-prev i, .owl-next i {transform : scale(1,6); color: #ccc;}

/* Lazy image blur-up effect */
img.lazy-img {
    filter: blur(8px);
    opacity: 0.8;
    transition: filter 0.4s ease, opacity 0.3s ease;
    will-change: filter, opacity;
}
img.lazy-img.loaded {
    filter: blur(0);
    opacity: 1;
}
    </style>
    <script>
/*$('.owl-carousel1').owlCarousel({
    loop:true,
    margin:10,
    autoplay:true,
    nav:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
});*/
    </script>
</head>