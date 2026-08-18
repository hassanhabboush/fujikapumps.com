<!DOCTYPE html>
<html>
 <head>
  <title>Fujika Dashboard</title>
  <script src="{{ asset('kendo/jquery.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/css/bootstrap.min.css') }}" />
  <script src="{{ asset('bower_components/bootstrap/js/bootstrap.min.js') }}"></script>
  <style type="text/css">
   .box{
     width: 600px;
    margin: 0 auto;
    border: 1px solid #ccc;
    margin-top: 10%;
    border-radius: 19px;
    background-color: #0000008f;
    color: white;
   }
   body {
       background: #1a1a1a;
       background: linear-gradient(160deg, #2a0a0d 0%, #1a1a1a 55%, #111 100%);
       min-height: 100vh;
   }
  </style>
 </head>
 <body>
  <br />
  <div class="container box">
   <h3 align="center">Fujika Dashboard</h3><br />

   @if(isset(Auth::user()->email)&& (Auth::user()->active==1))
    <script>window.location="{{route('admin.products.index')}}";</script>
   @endif

   @if ($message = Session::get('error'))
   <div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
   </div>
   @endif

   @if (count($errors) > 0)
    <div class="alert alert-danger">
     <ul>
     @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
     @endforeach
     </ul>
    </div>
   @endif

   <form method="post" action="{{ url('checklogin') }}">
    {{ csrf_field() }}
    <div class="form-group">
     <label>Enter Email</label>
     <input type="email" name="email" class="form-control" />
    </div>
    <div class="form-group">
     <label>Enter Password</label>
     <input type="password" name="password" class="form-control" />
    </div>
    <div class="form-group">
     <input type="submit" name="login" class="btn btn-primary" value="Login" />
    </div>
   </form>
  </div>
 </body>
</html>
