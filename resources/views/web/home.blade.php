@include('web.Layout.head')
<script>
function validateForm() {
    var x = document.getElementById('hertz1').value;
    if (x == null || x == "") {
        alert("Hertz must be filled out");
        return false;
    }
}
</script>
<link rel="stylesheet" href="{{ asset('assets/css/pump-search.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
.faq-wrap .accordion li.active .answer {
    max-height: fit-content !important;
}
    .read
    {
            color: var(--secondary-color);
    background: transparent;
    border: none;
    font-size: 14px;
    }
    .w3-black {
    color: gray !important;
    background-color: transparent !important;
}

.mySlides {display:none;}
.w3-content
{
    max-width:100%;
}
    
.top-left {
position: absolute;
top: 25%;
left: 25px;
    
}




@media all and (min-width:321px) and (max-width: 700px)  {
    .search-form
    {
        padding:0% 4%;
    }
    .form-group {
    margin-bottom: 0rem !important;
}
    .about-us-area.about-bg {
    background: none;
    height: 60%;
    display: block;
    opacity: 1;
    content: "";
    right: 0;
    top: 0;
    border-bottom: solid #b7212e 4px;
    border-top: solid #b7212e 4px;
    }
}
@media (max-width: 768px) {
    .heroSwiper {
        height: 17vh !important;
    }

    .hero-overlay h2 {
        font-size: 1.5rem;
    }

    .heroSwiper img {
        object-fit: fill !important;
    }
}
.search-footer > button {
    margin-top:12px;
}

.hero-field label {
    margin-bottom: 0px !important;
}

.searchhome select {
    margin-top: 0px !important;
    margin-bottom: 0px !important;
}
.searchhome input {
    margin-top: 0px !important;
    margin-bottom: 0px !important;
}

.heroSwiper .swiper-slide img {
    -webkit-mask-image: linear-gradient(to bottom, black 70%, transparent 100%);
    mask-image: linear-gradient(to bottom, black 70%, transparent 100%);
}

.heroSwiper {
    height: 45vh;
    width: 100%;
}

.heroSwiper .swiper-slide {
    position: relative;
}

.heroSwiper img {
    width: 100%;
    height: 100%;
    /*object-fit: cover;*/
}

/* Dark overlay for readability */
.heroSwiper .swiper-slide::after {
    content: "";
    position: absolute;
    inset: 0;
    /*background: rgba(0,0,0,0.15);*/
}

/* Text overlay */
.hero-overlay {
    position: absolute;
    bottom: 20%;
    left: 8%;
    z-index: 2;
    color: white;
}

.hero-overlay h2 {
    font-size: 2.5rem;
    font-weight: 700;
}

.hero-overlay p {
    margin-bottom: 15px;
}

/* Pagination style */
.swiper-pagination-bullet {
    background: white;
    opacity: 0.6;
}

.swiper-pagination-bullet-active {
    opacity: 1;
}

.swiper-button-next,
.swiper-button-prev {
    color: white;
    width: 50px;
    height: 50px;
    background: rgba(0,0,0,0.4);
    border-radius: 50%;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
    background: rgba(0,0,0,0.7);
}

.swiper-button-next::after,
.swiper-button-prev::after {
    font-size: 18px;
    font-weight: bold;
}

.productSwiper {
    padding: 20px 0 40px 0;
}

/*.product-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}*/

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
}

.product-card img {
    width: 100%;
    height: 150px;
    object-fit: contain;
    background: #f4f4f4;
}
.cooli-image.js-defer-img:not([src]) {
    min-height: 0;
    background: #f4f4f4;
}
.families-grid-v2 .cooli-card-wrap {
    height: auto !important;
}
.families-grid-v2 .cooli-card,
.families-grid-v2 .cooli-card.h-100,
.families-grid-v2 .cooli-card.d-flex {
    height: auto !important;
    display: block !important;
    overflow: visible !important;
}
.families-grid-v2 .cooli-img-wrap {
    height: 200px !important;
    flex: 0 0 200px !important;
    display: block !important;
    margin: 0 !important;
    padding: 0 !important;
    line-height: 0 !important;
    overflow: hidden !important;
}
.families-grid-v2 .cooli-image,
.families-grid-v2 .cooli-image.lazy-img {
    width: 100% !important;
    height: 200px !important;
    object-fit: cover !important;
    object-position: center !important;
    display: block !important;
    margin: 0 !important;
    transform: scale(1.12) !important;
    transform-origin: center center !important;
}
.families-grid-v2 .cooli-card:hover .cooli-image {
    transform: scale(1.12) !important;
}
.families-grid-v2 .cooli-content {
    margin: 0 !important;
    padding: 8px 8px 10px !important;
    position: relative;
    z-index: 2;
    background: #c9ced299;
}
.families-grid-v2 .cooli-title {
    margin: 0 0 6px !important;
}

.productSwiper .swiper-button-next,
.productSwiper .swiper-button-prev {
    color: #000;
}
.productSwiper .swiper-slide {
    height: auto;
    display: flex;
}
.product-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 100%;
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}
.product-card h6 {
    font-size: 14px;
    margin-top: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 40px;
}
</style>



<body>
@include('web.Layout.header')


<!-- ========================= -->
<!-- header area start -->
<!-- ========================= -->
<div class="header-area">
    <!-- header slider area start -->
    <!-- Start WOWSlider.com BODY section -->
<!-- Start WOWSlider.com BODY section -->
    <div class="swiper heroSwiper">
        <div class="swiper-wrapper">

            @foreach($slider as $slide1)
                <div class="swiper-slide">
                    <img src="{{$slide1->image}}" alt="" @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                </div>
            @endforeach
<!--            <div class="swiper-slide">
                <img src="public/slideruploads/1686441600e1E.jpg" alt="">
                <div class="hero-overlay">
                    <h2>Premium Industrial Solutions</h2>
                    <p>Reliable • Efficient • Trusted</p>
                    <a href="#" class="btn btn-danger">Explore Products</a>
                </div>
            </div>

            <div class="swiper-slide">
                <img src="public/slideruploads/1686441600e2E.jpg" alt="">
            </div>

            <div class="swiper-slide">
                <img src="public/slideruploads/1686441600e3E.jpg" alt="">
            </div>-->

        </div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>







<!-- ========================= -->
<!-- SMART PUMP SEARCH AREA -->
<!-- Complete Redesign - Modern & Attractive -->
<!-- ========================= -->

<div class="searchhome about-us-area about-bg pump-search-card">

<form action="filter" 
        method="GET" 
        class="search-form pump-search-form" 
        name="search-form" 
        onsubmit="return validateForm()">

    <div class="search-header">
    <div class="header-icon">
        <i class="fa fa-crosshairs"></i>
    </div>
    <div class="header-text">
        <h3 class="search-title">Pump Selector</h3>
        <p class="search-subtitle">Enter your duty point to find the perfect match</p>
    </div>
    </div>

    <div class="core-search-section">
    <div class="row align-items-end">
            
        <div class="col-lg-4 col-md-4 col-12">
        <div class="field-group hero-field">
            <label for="hertz1"><i class="fa fa-bolt"></i> Frequency (Hz) <span class="required">*</span></label>
            <div class="input-container">
            <select id="hertz1" name="hertz" required>
                <option value="">Select Hz</option>
                @foreach ($Hertz as $h)
                <option value="{{$h->Hertz}}">{{$h->Hertz}} Hz</option>
                @endforeach
            </select>
            </div>
        </div>
        </div>

        <div class="col-lg-4 col-md-4 col-12">
        <div class="field-group hero-field">
            <label for="flow_input"><i class="fa fa-tint"></i> Flow Rate (Q)</label>
            <div class="input-container with-unit">
            <input type="number" 
                    id="flow_input" 
                    name="q" 
                    placeholder="0.00" 
                    step="0.001">
            <span class="unit-badge">m³/hr</span>
            </div>
        </div>
        </div>

        <div class="col-lg-4 col-md-4 col-12">
        <div class="field-group hero-field">
            <label for="head_input"><i class="fa fa-arrows-v"></i> Total Head (H)</label>
            <div class="input-container with-unit">
            <input type="number" 
                    id="head_input" 
                    name="h" 
                    placeholder="0.00" 
                    step="0.001">
            <span class="unit-badge">m</span>
            </div>
        </div>
        </div>

    </div>
    </div>

    <div class="toggle-container">
    <button style="color: white !important;" type="button" class="classification-btn" onclick="toggleAdvancedFields()">
        <span class="icon-box"><i class="fa fa-sliders"></i></span>
        <span id="toggle-text">More Classifications</span>
        <i class="fa fa-chevron-down arrow-indicator" id="arrow-icon"></i>
    </button>
    </div>

    <div id="advanced-fields-wrapper" class="advanced-wrapper">
    <div class="advanced-inner-content">
            
        <div class="row">
            
        <div class="col-lg-3 col-md-6 col-12">
            <div class="field-group">
            <label for="cat_id1">Category</label>
            <div class="input-container">
                <select class="k-input k-textbox selectbox select2" 
                        name="cat_id" 
                        id="cat_id1" 
                        onChange="fillsubcategory(this)">
                <option value="">Select category</option>
                @foreach ($category as $cat)
                    <option value="{{$cat->id}}">{{$cat->english_name}}</option>
                @endforeach
                </select>
            </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="field-group">
            <label for="volt1">Voltage (V)</label>
            <div class="input-container">
                <select id="volt1" name="volt">
                <option value="">Select voltage</option>
                @foreach ($volt as $v)
                    <option value="{{$v->v}}">{{$v->v}} V</option>
                @endforeach
                </select>
            </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="field-group">
            <label for="family1">Type</label>
            <div class="input-container">
                <select id="family1" name="family" disabled>
                <option value="">Select type</option>
                </select>
            </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="field-group">
            <label for="sub_cat_id11">Design</label>
            <div class="input-container">
                <select class="select3 k-input k-textbox selectbox" 
                        name="sub_cat_id" 
                        id="sub_cat_id11" 
                        onChange="fillsubcategory1(this)" 
                        disabled>
                <option value="">Select design</option>
                </select>
            </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="field-group">
            <label for="material1">Material</label>
            <div class="input-container">
                <select id="material1" name="material">
                <option value="">Select material</option>
                @foreach ($material as $mat)
                    <option value="{{$mat->Material}}">{{$mat->Material}}</option>
                @endforeach
                </select>
            </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="field-group">
            <label for="rpm1">Motor Speed</label>
            <div class="input-container">
                <select id="rpm1" name="rpm">
                <option value="">Select RPM</option>
                @foreach ($rpm as $rpm)
                    <option value="{{$rpm->RPM}}">{{$rpm->RPM}} RPM</option>
                @endforeach
                </select>
            </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="field-group">
            <label for="sub_cat_id111">Pumped Medium</label>
            <div class="input-container">
                <select class="select3 k-input k-textbox selectbox" 
                        name="sub_cat_id1" 
                        id="sub_cat_id111" 
                        onChange="fillfamily(this)" 
                        disabled>
                <option value="">Select medium</option>
                </select>
            </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="field-group">
            <label for="dm1">Discharge Size</label>
            <div class="input-container">
                <select id="dm1" name="dm">
                <option value="">Select size</option>
                @foreach ($dm as $dm)
                    <option value="{{$dm->Discharge_diameter}}">{{$dm->Discharge_diameter}}</option>
                @endforeach
                </select>
            </div>
            </div>
        </div>

        </div> </div>
    </div>
    <div class="search-footer">
    <button type="submit" class="submit-btn search-btn">
        <span class="btn-icon"><i class="fa fa-search"></i></span>
        <span class="btn-text">Find Matching Pumps</span>
    </button>
    </div>

</form>
</div>

<script>
function toggleAdvancedFields() {
var wrapper = document.getElementById('advanced-fields-wrapper');
var arrow = document.getElementById('arrow-icon');
var text = document.getElementById('toggle-text');
    
if (wrapper.classList.contains('open')) {
    wrapper.classList.remove('open');
    arrow.classList.remove('rotate');
    text.innerHTML = "More Classifications";
} else {
    wrapper.classList.add('open');
    arrow.classList.add('rotate');
    text.innerHTML = "Less Classifications";
}
}
</script>

<!-- ==================================================another design for categories section================================================================================ -->
<div class="category-section">
    <div class="container">
        <h2 class="section-title">Families</h2>
        <div class="row families-grid-v2 mobile-padding">
            @foreach($family as $fam)
                <div class="col-6 col-sm-6 col-md-3 mb-4 px-4">
                    <div class="cooli-card-wrap">
                        <div class="cooli-card">

                            <div class="cooli-img-wrap">
                                <img data-src="{{$fam->background}}"
                                    class="cooli-image js-defer-img" alt="{{$fam->english_name}}">
                            </div>

                            <div class="cooli-content">
                                <h4 class="cooli-title">{{$fam->english_name}}</h4>
                                <div class="cooli-actions">
                                    <a href="{{$fam->link}}" target="_blank" class="cooli-btn"><i class="fa fa-file-pdf-o pdf-btn"></i></a>
                                    <a class="btn btn-sm btn-outline-danger" href="{{url('/'.$fam->id.'/5/'.$fam->english_name)}}">Show More -></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- ========================= -->
<!-- About us area  -->
<!-- ========================= -->
<div class="about-us-area about-bg" style="padding-top:2%; padding-bototm:2%;">
    <div class="container-fluid">
        <div class="row justify-content-start">
            <div class="col-lg-5 remove-col-padding">
                <div class="about-image">
                    <img src="{{$about[0]->photo}}" class="img-fluid" alt="about image" loading="lazy">
                    <div class="hover">

                        <a href="{{$about[0]->linkyoutube}}" target="_blank" class="btn-ripple-animate video-play-btn video mfp-iframe">
                            <i class="fa fa-play"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 align-self-center mobnone">
                <div class="about-area-right mb-0">
                    <div class="section-title about text-left mb-0">
                        <span class="subtitle">ABOUT THE FUJIKA </span>
                        <h2 class="title">Well done of 70 years experience Pumps agency</h2>
                    </div>
                    <div class="faq-wrap about-page">
                        <ul class="accordion">
                            <li class="">
                                <div class="question"> <h4>{{$about[0]->title1}}</h4>
                                    <div class="plus-minus-toggle collapsed"></div>
                                </div>
                                <div class="answer">{{$about[0]->desc1}}</div>
                            </li>
                            <li>
                                <div class="question"><h4> {{$about[0]->title2}}</h4>
                                    <div class="plus-minus-toggle collapsed"></div>
                                </div>
                                <div class="answer">{{$about[0]->desc2}}</div>
                            </li>
                            <li>
                                <div class="question"><h4>{{$about[0]->title3}}</h4>
                                    <div class="plus-minus-toggle collapsed"></div>
                                </div>
                                <div class="answer">{{$about[0]->desc3}}</div>
                            </li>
                            <li>
                                <div class="question"> <h4>{{$about[0]->title4}}</h4>
                                    <div class="plus-minus-toggle collapsed"></div>
                                </div>
                                <div class="answer">{{$about[0]->desc4}}</div>
                            </li>
                        </ul>
                    </div>

                    <div class="btn-wrapper">
                        <a href="{{url('/about')}}" class="btn-hrv">More About Here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ========================= -->
<!--  About Us area  End-->
<!-- ========================= -->

<!-- Featured Products -->

<!-- ========================= -->
<!--our Specialized -->
<!-- ========================= -->
<div class="cooli-item-area" style="padding-top:0.3%;padding-bottom:2%">
    <h2 class="title section-title" style="
    text-align: center;
margin-bottom: 3px;
">Pumps Classification</h2>
    <div class="swiper productSwiper">
        <div class="swiper-wrapper">
            @foreach($sub_category1 as $sub1)
                <div class="swiper-slide">
                    <div class="product-card">
                        <img data-src="{{$sub1->background}}" alt="" class="js-defer-img" width="300" height="150">
                        <a href="{{url('/'.$sub1->id.'/4/'.$sub1->english_name)}}" class="read"><h6>{{$sub1->english_name}}</h6></a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>

<div class="service-area cooli-item-area  counterup-area pd-top-100 pd-bottom-70" data-bg="{{ asset('assets/web/assets/img/bg/counterup-bg.jpg') }}">

<!-- ========================= -->
<!-- About us area  -->
<!-- ========================= -->
<div class="about-us-area about-bg mobyes" style="padding-top:2%; padding-bototm:2%; display:none;">
    <div class="container-fluid">
        <div class="row justify-content-start">
            
            <div class="col-12 align-self-center">
                <div class="about-area-right mb-0">
                    <div class="section-title about text-left mb-0">
                        <span class="subtitle">ABOUT THE FUJIKA </span>
                        <h2 class="title">Well done of 70 years experience </h2>
                    </div>
                    <div class="faq-wrap about-page">
                        <ul class="accordion">
                                <li class="active">
                                    <div class="question"> <h4>History</h4> 
                                    <div class="plus-minus-toggle collapsed"></div>
                                    </div>
                                    <div class="answer">FUJIKA JAPAN was founded in 1948 in Tokyo - Japan, 
                                    the Famous trade mark was owned by Fujika Corporation of Arab Japan Enterprise and 
                                    has become the new proprietor of the famous Fujika trademark since 1990.
                                    Introducing Since a wide range of Engineered Products at the highest 
                                    Japanese and International Standards..</div>
                                </li>
                                <li>
                                    <div class="question"><h4>70+ Years Of Experience</h4>
                                    <div class="plus-minus-toggle collapsed"></div>
                                    </div>
                                    <div class="answer">FUJIKA is one of the leaders in the Field of Fuel and Electric 
                                        Heating-cooling Appliances, Solar heating and Home appliances since 
                                        1948 and has developed and introduced a wide range of intellectual 
                                        industries such as Water and Fuel Pumps,
                                        Liquid Level Sensors and Controls.</div>
                                </li>
                                <li>
                                    <div class="question"><h4> Business Partners</h4> 
                                    <div class="plus-minus-toggle collapsed"></div>
                                    </div>
                                    <div class="answer">FUJIKA Group had been open wide to the International Commerce Segments,
                                    thus gained a chain of trading and industrial partners Partners all over the world, 
                                    FUJIKA CORPORATION and ARAB-JAPAN ENTERPRISE in TOKYO/JAPAN are the Sister Companies 
                                    to endorsing commercial contracts and international dealership, We have a wide base of 
                                    dealers worldwide , such in the the Middle East we have AHED ALJAZEERA TRADING Co. In KSA 
                                    and AL-JAYATWA TRADING Co. in JORDAN is our Pumps Business Global Partner.</div>
                                    </li>
                                    <li>
                                        <div class="question"> <h4>SOLE REPRESENTATIVE</h4> 
                                        <div class="plus-minus-toggle collapsed"></div>
                                        </div>
                                        <div class="answer">AL-Jayatwa Co. a HighTech-Engineered Research 
                                        and development company with 30+ years of experience in Pumps and Fluids Controls industry</div>
                                    </li>
                        </ul>
                    </div>
                        
                    <div class="btn-wrapper" style="margin-top: 20px;
    margin-bottom: 12%;">
                        <a href="{{url('/about')}}" class="btn-hrv">More About Here</a>
                    </div>
                </div>
            </div>
                
        </div>
    </div>
</div>
<!-- ========================= -->
<!-- End About Us area  -->
<!-- ========================= -->


<!-- ========================= -->
<!-- facilities area start -->
<!-- ========================= -->
<div class="facilities-area pd-top-100 pd-bottom-70" style ="display:none;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="section-title">
                    <span class="subtitle">Why choose us</span>
                    <h2 class="title">Why Choose Our Service Facilities?</h2>
                </div>
            </div>  
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="single-facilities-item item-bg">
                    <div class="icon">
                        <i class="flaticon-emergency-call" aria-hidden="true"></i>
                    </div>
                    <h4 class="title">24X7 Support Services</h4>
                    <p class="details">Lorem ipsuelit, sed do eiusmod tempor
                        ofincidid labore et dolore magna</p>
                    
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="single-facilities-item item-bg">
                    <div class="icon">
                        <i class="flaticon-cogwheel" aria-hidden="true"></i>
                    </div>
                    <h4 class="title">Good Performance</h4>
                    <p class="details">Lorem ipsuelit, sed do eiusmod tempor
                        ofincidid labore et dolore magna</p>
                    
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="single-facilities-item item-bg">
                    <div class="icon">
                        <i class="flaticon-man" aria-hidden="true"></i>
                    </div>
                    <h4 class="title">Responsibility</h4>
                    <p class="details">Lorem ipsuelit, sed do eiusmod tempor
                        ofincidid labore et dolore magna</p>
                        
                </div>
            </div>
                
        </div>
    </div>
</div>
<!-- ========================= -->
<!-- facilities item  end -->
<!-- ========================= -->

<!-- ========================= -->
<!-- cta start -->
<!-- ========================= -->
<!--<div class="cta-area cta-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cta-wrapper">
                    <div class="left-content">
                        <div  class="single-info-item">
                            <div class="icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="content">
                                <span class="details">{{$contact->email}} <br> Support Sevices </span>
                            </div>
                        </div>
                    </div>
                    <div class="right-content">
                        <div  class="single-info-item">
                            <div class="icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="content">
                                <span class="details">{{$contact->phone1}} <br> {{$contact->phon2}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->
<!-- ========================= -->
<!-- cta end  -->
<!-- ========================= -->



<div class="history-area history-bg py-5">
    <div class="container">
        <div class="row contact-wrapper shadow-lg">

            <!-- LEFT INFO PANEL -->
            <div class="col-lg-5 contact-info">
                <div class="contact-info-inner">
                    <h3>Get in Touch</h3>
                    <p>We’d love to hear from you. Fill out the form and we’ll get back shortly.</p>

                    <div class="contact-block">
                        <i class="fa fa-envelope contact-icon"></i>

                        <p>{{$contact->email}} <br> Support Sevices</p>
                    </div>

                    <hr class="contact-divider">

                    <div class="contact-block">
                        <i class="fa fa-phone contact-icon"></i>

                        <a href="tel:{{$contact->phone1}}">{{$contact->phone1}}</a>
                        <a href="tel:{{$contact->phon2}}">{{$contact->phon2}}</a>
                    </div>
                </div>
            </div>

            <!-- RIGHT FORM PANEL -->
            <div class="col-lg-7 bg-white p-4 p-md-5">
                <h5 class="contact-title mb-4">Contact Form</h5>

                <form class="contact-form" action="sendemail" method="GET">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group input-icon">
                                <i class="fa fa-user"></i>
                                <input type="text" class="form-control" name="name" placeholder="Your Name *" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group input-icon">
                                <i class="fa fa-envelope"></i>
                                <input type="email" class="form-control" name="email" placeholder="Email Address *" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group input-icon">
                                <i class="fa fa-phone"></i>
                                <input type="text" class="form-control" name="phone" placeholder="Phone Number">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group input-icon">
                                <i class="fa fa-building"></i>
                                <input type="text" class="form-control" name="company" placeholder="Company">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group input-icon">
                                <i class="fa fa-comment comment-icon textarea-icon"></i>
                                <textarea class="form-control msg-txt" name="inquiry" rows="4" placeholder="Your Message *" required></textarea>
                            </div>
                        </div>

                        <div class="col-12 d-flex align-items-center mb-3">
                            <input type="checkbox" id="cb1" class="me-2">
                            <label for="cb1" class="mb-0 ml-2 save-details">Save my details for next time</label>
                        </div>

                        <div class="col-12 text-end">
                            <div class="btn-wrapper text-left">
                                <button  class="boxed-btn btn-rounded" value="Submit" style="float:right; border:none; border-radius:11px;">Submit </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- ========================= -->
<!-- newsletter end  -->
<!-- ========================= -->
@include('web.Layout.footer')

<script>
var slideIndex = 1;
var myIndex = 0;
    if (document.getElementsByClassName("mySlides").length > 0) {
        carousel();
    }
function plusDivs(n) {
showDivs(slideIndex += n);
}

function showDivs(n) {
var i;
var x = document.getElementsByClassName("mySlides");
if (x.length === 0) return;
if (n > x.length) {slideIndex = 1}
if (n < 1) {slideIndex = x.length}
for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
}
x[slideIndex-1].style.display = "block";  
}
function carousel() {
    var i;
    var x = document.getElementsByClassName("mySlides");

    if (x.length === 0) return;

    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    myIndex++;
    if (myIndex > x.length) { myIndex = 1; }
    x[myIndex-1].style.display = "block";
    setTimeout(carousel, 6000);
}
</script>


<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    const swiper = new Swiper(".heroSwiper", {
        loop: true,
        fadeEffect: {
            crossFade: true
        },
        speed: 1000,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });

    const productSwiper = new Swiper(".productSwiper", {
        loop: true,
        //speed: 800,
        spaceBetween: 30,
        autoplay: {
            delay: 0,
        },
        speed: 4000,
        freeMode: true,
        freeModeMomentum: false,
        /*autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },*/
        navigation: {
            nextEl: ".productSwiper .swiper-button-next",
            prevEl: ".productSwiper .swiper-button-prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 2
            },
            576: {
                slidesPerView: 2
            },
            768: {
                slidesPerView: 3
            },
            992: {
                slidesPerView: 4
            }
        }
    });
    if (typeof window.observeDeferImgs === 'function') {
        window.observeDeferImgs();
    }
</script>


</body>
</html>
