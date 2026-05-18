@include('web.Layout.head')

<link rel='stylesheet' href='https://w3learnpoint.com/cdn/jquery-picZoomer.css'>
<link rel="stylesheet" href="{{ asset('assets/web/assets/pagedet/style.css')}}">
<style>
   
  .shop-page-area  .container
    {
        max-width:100% !important;  
    }
    .des{   font-size: 50%; }
      .read2
    {
            box-shadow: 0px 14px 18px #80001369;
    border-radius: 26px;
    height: 46px;
    line-height: 46px;
    padding: 0 35px;
    display: inline-block;
    background: var(--heading-color);
    color: #fff;
    }
    .read3
    {
            line-height: 1.2;
    font-size: 18px;
    margin-bottom: 14px;
    letter-spacing: 0.54px;
    color: var(--heading-color);
    font-weight: 500;
    display: block;
    transition: 0.4s;
    margin: auto;
    background: transparent;
    border: none;
    }
    .read4
    {
        font-size: 16px !important;
            color: #b7212e;
             font-size: inherit;
    font-weight: 500;
    line-height: 24px;
    background: transparent;
    border: none;
    
    }
    .read3:hover
    {
        color:#b7212e;
    }
    .subcategory
    {
       display:none;
    }
    .nav-item:hover > .subcategory
    {
         display:block;
    }
  </style>

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
                            <li><a href="{{ url ('/')}}">Home</a> </li>
                            <li>  <a href="{{url('/1/1/products/')}}">Products</a></li>
                        </ul>
                        <h5 class="title">See our Products</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcumb area end  -->


    <div class="shop-page-area pd-top-100" style="padding-bottom:2%;">
        <div class="container row">
        <div class="col-md-9">
        <section>
   <div class="container-fluid">
      <div class="row row-sm">
         <div class="col-md-6 _boxzoom">
            <div class="_product-images">
               <div class="picZoomer">
                  <img class="my_img" src="{{url($product[0]->photo)}}" alt="">
               </div>
            </div>
         </div>
</div>  
<div class="row row-sm"> 
         <div class="zoom-thumb" >
               <ul class="piclist">
                  <li><img src="{{url($product[0]->photo)}}" alt="" loading="lazy"></li>
                  @foreach($gallery as $gal)
                  <li><img src="{{url($gal->path)}}" alt="" loading="lazy"></li>
                 @endforeach
               </ul>
            </div>
   </div>
</section>
<div class="des">
{!!$product[0]->descreption!!}
</div>
       </div>
    <div class="col-md-3">
    <div class="m-sdnav m-sdnav1">
  <ul class="nav-list" id="popup-menu" style="list-style:none;">
   @foreach ($category as $cat)
   @php 
                    $sub=DB::table('sub_category')->select('sub_category.id','sub_category.english_name')->join('category_subcategory','category_subcategory.subcategory_id','sub_category.id')->where('category_subcategory.category_id',$cat->id)->get();
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
                        <a  href="{{url($cat->id.'/2/'.$cat->english_name)}}" class="read4">{{$cat->english_name}}</a>
                         
        </li>
        @else
        <li class="nav-item">     <a  href="{{url($cat->id.'/2/'.$cat->english_name)}}" class="read4">{{$cat->english_name}}</a>
                         
          <ul class="sbnav-list sbnav-list1 subcategory" style="list-style=none;     padding-left: 10%;"> <!-- ul sub -->
          @foreach( $sub as $subcat)
             @php 
                 $sub1=DB::table('sub_category_1')->join('subcategory_subcategory','subcategory_subcategory.subcategory_id','sub_category_1.id')->select('sub_category_1.id','sub_category_1.english_name','sub_category_1.background')->where('subcategory_subcategory.parent_id',$subcat->id)->get();
              @endphp
              @if(count($sub1) ==0)
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
    color: #000;">     <a  href="{{url($subcat->id.'/3/'.$subcat->english_name)}}" class="read4">{{$subcat->english_name}}</a>
                         
         </li>
              @else
              <li class="nav-item">    <a  href="{{url($subcat->id.'/3/'.$subcat->english_name)}}" class="read4">{{$subcat->english_name}}</a>
          <ul class="sbnav-list sbnav-list1 subcategory" style="list-style=none;"> <!-- ul sub1 -->
          @foreach( $sub1 as $subcat1)
          @php
                         $family=DB::table('family')->join('family_subcategory','family_subcategory.family_id','family.id')->select('family.id','family.english_name','family.background')->where('family_subcategory.sub_category_id',$subcat1->id)->get()
                          @endphp
           @if(count($family)==0)
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
    color: #000;">    <a  href="{{url($subcat1->id.'/4/'.$subcat1->english_name)}}" class="read4">{{$subcat1->english_name}}</a>         </li>
         @else
         <li class="nav-item"> 
         <a  href="{{url($subcat1->id.'/4/'.$subcat1->english_name)}}" class="read4">{{$subcat1->english_name}}</a>   
          <ul class="sbnav-list sbnav-list1 subcategory" style="list-style=none;"> <!-- ul sub1 -->
          @foreach ($family as $fam)
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
    color: #000;"> <a  href="{{url($fam->id.'/5/'.$fam->english_name)}}" class="read4">{{$fam->english_name}}</a>
         </li>
         @endforeach
</ul>
</li>
         @endif
          @endforeach
</ul> <!-- ul sub1 -->
</li>
              @endif
       

        @endforeach
        </ul>      <!-- ul sub-->
     </li>
     @endif
     @endforeach
     </ul></div>
</div><!-- nav -->    
        </div>
    </div>
    @include('web.Layout.footer')

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.21/jquery.zoom.min.js'></script>
<script  src="{{ asset('assets/web/assets/pagedet/script.js')}}"></script>

</body>
</html>