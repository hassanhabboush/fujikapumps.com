@include('web.Layout.head')
<style>
   
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
    /*.subcategory
    {
       display:none;
    }
    .nav-item:hover > .subcategory
    {
         display:block;
    }*/


  .m-sdnav {
      background: #ffffff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.05);
  }
  .nav-list,
  .sbnav-list {
      list-style: none;
      margin: 0;
      border-left: 1px solid rgba(0,0,0,0.06);
      margin-left: 12px;
      padding-left: 12px;
  }

  .nav-list li {
      margin-bottom: 6px;
  }
  .nav-list a {
      display: block;
      padding: 8px 12px;
      font-size: 14px;
      font-weight: 500;
      color: #333;
      border-radius: 6px;
      transition: all 0.2s ease;
      text-decoration: none;
  }
  .nav-list a {
      transition:
              background 0.35s ease,
              color 0.25s ease,
              padding-left 0.35s ease;
  }
  .nav-list a:hover {
      background: rgba(183,33,46,0.08);
      color: #b7212e;
      padding-left: 14px;
  }
  /* Level 1 */
  .nav-list > li > a {
      font-weight: 600;
  }

  /* Level 2 */
  .nav-list .sbnav-list > li > a {
      padding-left: 25px;
      font-size: 13px;
  }

  /* Level 3 */
  .nav-list .sbnav-list .sbnav-list > li > a {
      padding-left: 40px;
      font-size: 12.5px;
  }
  .nav-list > li {
      border-bottom: 1px solid rgba(0,0,0,0.05);
      padding-bottom: 6px;
  }
  .subcategory {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
  }

  .nav-item:hover > .subcategory {
      /*max-height: 500px;*/
  }

  .nav-item > a::after {
      content: "›";
      float: right;
      font-size: 12px;
      opacity: 0.4;
      transition: transform 0.3s ease;
  }

  .nav-item:hover > a::after {
      transform: rotate(90deg);
      opacity: 0.7;
  }
  .nav-item.open > .subcategory {
      max-height: 1000px;
  }

  .category-section {
      padding: 10px 0;
  }
/* SECTION WRAPPER */
  .prod-section {
      padding: 10px 0;
  }

  /* CARD */
  .prod-card {
      background: #ffffff;
      border: 1px solid #eaeaea;
      border-radius: 14px;
      overflow: hidden;
      transition: all 0.25s ease;
  }

  /* Hover lift */
  .prod-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 28px rgba(0,0,0,0.08);
  }

  /* IMAGE AREA */
  .prod-image {
      width: 100%;
      height: 240px;
      overflow: hidden;
  }

  .prod-image img {
      width: 100%;
      height: 100%;
      /*object-fit: contain;*/
      display: block;
  }

  /* BODY */
  .prod-body {
      padding: 16px;
      text-align: center;
      background: #c9ced299;
  }

  .prod-title {
      font-size: 16px;
      font-weight: bold !important;
      /*margin-bottom: 12px;*/

      /* Line clamp */
      display: -webkit-box;
      -webkit-line-clamp: 2;      /* max 2 lines */
      -webkit-box-orient: vertical;
      overflow: hidden;

      min-height: 44px;           /* keeps height consistent */
  }

  /* PDF ICON */
  .prod-pdf {
      display: inline-block;
      margin-bottom: 8px;
      color: #b7212e;
      font-size: 18px;
  }

  /* BUTTON */
  .prod-button {
      display: inline-block;
      /*margin-top: 8px;*/
      padding: 6px 14px;
      font-size: 14px;
      border: 1px solid #b7212e;
      border-radius: .2rem;
      color: #b7212e;
      text-decoration: none;
      transition: all 0.25s ease;
  }

  .prod-button:hover {
      background: #b7212e;
      color: #ffffff;
  }
  </style>
<body>
    
@include('web.Layout.header')
@php
$linkType = $type + 1;
if ($linkType == 3) {
    $linkType = 4;
}
@endphp
    <!-- Breadcumb area start  -->
    <div class="breadcumb-area breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcumb-inner">
                        <ul class="page-lists">
                            <li><a href="{{url('/')}}">Home</a> </li>
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
            <div class="col-md-3">
                <div class="m-sdnav m-sdnav1">
                    @include('web.Layout.menu-bar')    
                </div>
            </div>

            <div class="col-md-9">
                <div class="prod-section">

                    <div class="row prod-grid">

                        @foreach($res as $cat)
                            <div class="col-6 col-md-3 mb-4">

                                <div class="prod-card">

                                    <div class="prod-image">
                                                    <img src="{{$cat->background}}"
                          alt="{{$cat->english_name}}" loading="lazy">
                                    </div>

                                    <div class="prod-body">
                                        <h4 class="prod-title">
                                            {{$cat->english_name}}
                                        </h4>

                                           @if($linkType==5)
                                                 <a href="{{$cat->link}}" target="_blank" class="prod-pdf">
                                                     <i class="fa fa-file-pdf-o"></i>
                                                 </a>
                                             @endif
                                             
                                             <a href="{{url($cat->id.'/'.$linkType.'/'.$cat->english_name)}}"
                                                class="prod-button">
                                                 Show More →
                                             </a>
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>
{{--    {{$res->links("pagination::bootstrap-4")}}--}}
    @include('web.Layout.footer')
<script>
    document.querySelectorAll('.nav-item > a').forEach(function(link) {

        link.addEventListener('click', function(e) {

            const parent = this.parentElement;
            const submenu = parent.querySelector('.subcategory');

            if (submenu) {
                e.preventDefault(); // prevent link navigation

                parent.classList.toggle('open');
            }

        });

    });
</script>
</body>
</html>
