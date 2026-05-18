@include('web.Layout.head')
<style>
   
  .shop-page-area  .container
    {
        max-width:100% !important;  
    }
   
  .shop-page-area  .container
    {
        max-width:100% !important;  
    }
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
        font-size: 14px;
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
      #filterhead
    {
     font-size:16px;   
    }
    #filterbody
    {
        font-size:14px;
    }
    #filterbut
    {
        margin: auto;
    background-color: black;
    padding: 0px 11px;
    font-size: 12px;
    border-radius: 9px;
    color: white;
    text-align: center;
    font-weight: 600;
    }
  </style>
  <script>
      showproduct($id)
      {
          
      }
  </script>

<body>
    
@include('web.Layout.header')
@include('web.productpopup')
    <!-- Breadcumb area start  -->
    <div class="breadcumb-area breadcrumb-bg" style="padding-top:1%;"> 
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcumb-inner">
                        <ul class="page-lists">
                            <li><a href="{{ url ('/')}}">Home </a> </li>
                            <li> 
                            <a href="{{url('/1/1/products/')}}">Products</a>
                            </li> 
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
        <div class="col-md-9" style="    padding: 0px 4%;">
      <!--       <div class="row" style="border: solid thin; font-weight:700 !important; background:#cbcbcb; text-align:center;" id="filterhead">
                 <div class="col-6 col-md-6" style="border-right: solid thin; padding:4px;" id="filterhead">
                     Name
                 </div>
                 <div class="col-6 col-md-6" style="border-right: solid thin;padding:4px; font-weight:700 !important;">
PDF
                 </div>
                 </div>
                 @foreach ($products as $cat)
            <div class="row" style="border: solid thin;" id="filterbody">
                 <div class="col-6 col-md-6" style="border-right: solid thin; padding:4px;">
                     {{$cat->name}}
                 </div>
  <div class="col-6 col-md-6" style="padding:4px;text-align:center;">
<a class="button" target="_blank" href="{{$cat->link}}" id="filterbut" style="
"><span class="button_label">show pdf</span></a>
                 </div>
                 </div>-->

                
               <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="single-shop">
                        <div class="thumb">
                            <img src="{{url($cat->photo)}}" alt="shop" loading="lazy">
                            <div class="cart-btn">
                                <div class="cart-btn-wrap">
                                  <a href="{{$cat->link}}" class="read2">Show Pdf <i class="fa fa-info"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            
                                  <a href="{{$cat->link}}" class="read3">{{$cat->name}}</a>
                            
                        </div>
                    </div>
                </div>
@endforeach
</div>
 <div class="col-md-3">
    <div class="m-sdnav m-sdnav1">
  <ul class="nav-list" id="popup-menu" style="list-style:none;">
   @foreach ($category as $cat)
    @if ($cat->english_name=='Accessories')
                           <li  class="nav-item" style="font-size: 20px;
    font-size: 14px;
        line-height: 30px;
            font-weight: 500;
    color: #b7212e !important;">
                                    <a href="{{url('/'.$cat->id.'/7/'.$cat->english_name)}}">{{$cat->english_name}}</a>
                      </li>
    @else                  
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
                         
          <ul class="sbnav-list sbnav-list1 subcategory" style="list-style=none;"> <!-- ul sub -->
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
     @endif
     @endforeach
     </ul></div>
</div><!-- nav -->         
        </div>
    </div>
    {{$products->links("pagination::bootstrap-4")}}
    @include('web.Layout.footer')
</body>
</html>