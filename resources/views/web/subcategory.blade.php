@include('web.Layout.head')
<style>
   
  .shop-page-area  .container
    {
        max-width:100% !important;  
    }
    </style>
<body>
    
@include('web.Layout.header')
    <!-- Breadcumb area start  -->
    <div class="breadcumb-area breadcrumb-bg" style="padding-top:1%;"> 
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcumb-inner">
                        <ul class="page-lists">
                            <li><a href="{{url('web')}}">Home</a> </li>
                            <li><a href="{{url('web/categories')}}">Products</a> - <a href="{{url('web/'.$name)}}">{{$name}}</a></li>
                        </ul>
                        <h5 class="title">See our Products</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcumb area end  -->


    <div class="shop-page-area pd-top-100">
        <div class="container row">
        <div class="col-md-9">
            <div class="row">
                @foreach ($subcategory as $cat)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="single-shop">
                        <div class="thumb">
                            <img src="{{url($cat->background)}}" alt="shop" loading="lazy">
                            <div class="cart-btn">
                                <div class="cart-btn-wrap">
                                    <a class="btn btn-red" href="{{url('web/'.$name.'/'.$cat->english_name)}}">Show More <i class="fa fa-info"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            
                            <a href="{{url('web/'.$name.'/'.$cat->english_name)}}">{{$cat->english_name}}</a>
                            
                        </div>
                    </div>
                </div>
                @endforeach
</div>
</div>
    <div class="col-md-3">
    <div class="m-sdnav m-sdnav1">
  <ul class="nav-list" style="list-style:none;">
   @foreach ($category as $cat)
   @php 
                           $sub=DB::table('sub_category')->select('id','english_name')->where('parent_id',$cat->id)->get();
                           @endphp
                           @if(count($sub)==0)
        <li class="nav-item" style="position: relative;
    display: block;
    list-style:none !important;
    padding-top: 7px;
    padding-bottom: 7px;
    padding-right: 1%;
    line-height: 30px;
    font-size: 20px;
    font-size: 14px;
    font-weight: bold;
    color: #b7212e !important;
    border-bottom: 1px solid #999999;"> 
            <a href="{{url('web/'.$cat->english_name)}}" title="{{$cat->english_name}}">{{$cat->english_name}}</a>
        </li>
        @else
        <li class="nav-item"> <a href="{{url('web/'.$cat->english_name)}}" title="{{$cat->english_name}}" style="position: relative;
    display: block;
  list-style=none;
    padding-top: 7px;
    padding-bottom: 7px;
    padding-right: 1%;
    line-height: 30px;
    font-size: 20px;
    font-size: 14px;
    font-weight: bold;
    color: #b7212e !important;
    border-bottom: 1px solid #999999;">{{$cat->english_name}}</a>
          <ul class="sbnav-list sbnav-list1" style="list-style=none;">
          @foreach( $sub as $subcat)
          <li class="sbnav-item sbnav-item1" style="position: relative;
    display: block;
  list-style=none;
    padding-top: 7px;
    padding-bottom: 7px;
    padding-right: 1%;
    line-height: 30px;
    font-size: 20px;
    font-size: 14px;
    font-weight: bold;
    color: #000;"> <a href="{{url('web/'.$cat->english_name.'/'.$subcat->english_name)}}" title="{{$subcat->english_name}}">{{$subcat->english_name}}</a>
         </li>
        @endforeach
        </ul>      
     </li>
     @endif
     @endforeach
     </ul></div>
</div><!-- nav -->    
        </div>
    </div>
    {{$subcategory->links("pagination::bootstrap-4")}}
    @include('web.Layout.footer')
</body>
</html>