<!DOCTYPE html>
<html lang="en">
<head>
<title>{{ @$page_title or "Fujika Dashboard" }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <!-- Favicon icon -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <link rel="icon" href="assets\images\logogeek.png" type="image/x-icon">
    <script src="{{asset('kendo/jquery.min.js')}}"></script>
    <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <!-- select Multi-->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ asset('bower_components\bootstrap\css\bootstrap.min.css') }}">
    <!-- radial chart.css -->
    <link rel="stylesheet" href="{{ asset('assets\pages\chart\radial\css\radial.css') }}" type="text/css" media="all">
     <link rel="stylesheet" href="{{ asset('css\custom.css') }}" type="text/css" media="all">
    <!-- feather Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets\icon\feather\css\feather.css') }}">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets\css\style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets\css\jquery.mCustomScrollbar.css') }}">
   
    <link rel="stylesheet" href="{{ asset('kendo/styles/kendo.common.min.css') }}">
 
    <link rel="stylesheet" href="{{ asset('kendo/styles/kendo.moonlight.min.css') }}"> 
<style>
    .select2-container {
    width: 93% !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
    border: thin solid #232d36;
    background: #414550;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: black;
    border: 1px solid white;
    padding: 5px 15px;
    color: black;
}
.select2-container--default .select2-selection--multiple {
    background-color: #414550;
    border: thin solid #232d36;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove
{
    border-right:none;
}
.card {
    position: relative;
    display: grid !important;
    flex-direction: column;
    min-width: 0;
    width: 98%;
    margin-left: 2%;
    word-wrap: break-word;
    background-color: #212a33;
    background-clip: border-box;
}
.card .card-header {
    background-color: aliceblue;
}
.note-editor .note-toolbar>.note-btn-group, .note-popover .popover-content>.note-btn-group
{
    margin-right:13px !important;
}
</style>
</head>