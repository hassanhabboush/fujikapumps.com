@include('web.Layout.head')
<body>
@include('web.Layout.header')

<!-- Breadcumb area start  -->
<div class="breadcumb-area breadcrumb-bg" style="padding-top:1%;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcumb-inner">
                    <ul class="page-lists">
                        <li><a href="{{url('/')}}">Home</a> </li>
                        <li>Contact Us</li>
                    </ul>
                    <h5 class="title">Please Contact Us</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcumb area end  -->

<!-- contact start -->
<div class="contact-area" >
    <div class="container">
        <div class="row justify-content-around">
            <div class="col-lg-5">
                <div class="left-content-area">
                    <h4 class="title">Contact info</h4>
                    <ul class="info-list pl-0">
                        <li>
                           <div class="single-info-item">
                                <div class="icon">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    
                                </div>
                                <h5 class="heading">Email:</h5>
                           </div>
                           <div class="content">
                            <span class="details">Email: {{$contact->email}}</span>
                        </div>
                        </li>
                        <li>
                           <div class="single-info-item">
                                <div class="icon">
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                </div>
                                <h5 class="heading">Phone:</h5>
                               
                           </div>
                           <div class="content">
                            <span class="details">{{$contact->phone1}}
                               </span>
                            <span class="details">{{$contact->phon2}}</span>
                        </div>
                        </li>
                        <li>
                            <div class="single-info-item">
                                 <div class="icon">
                                     <i class="fa fa-map-marker" aria-hidden="true"></i>
                                 </div>
                                 <h5 class="heading">Address:</h5>
                                
                            </div>
                            <div class="content">
                                <span class="details">{{$contact->address}} </span>
                            </div>
                         </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-6 col-lg-7">
               <div class="right-content-area">
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <h5 class="contact-title">Contact Form</h5>
                        <form class="contact-form" action="sendemail" method="GET">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="InputName">Your Name<span class="requred">*</span></label>
                                        <input type="text" class="form-control" name='name' id="InputName" placeholder="Name"
                                            required >
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="InputMail">Your E-mail<span class="requred">*</span></label>
                                        <input type="email" class="form-control" name='email' id="InputMail" placeholder="E-mail"
                                            required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="InputPhone">Phone Number<span class="requred"></span></label>
                                        <input type="text" class="form-control" name='phone' id="InputPhone" placeholder="Phone Number"
                                            >
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="InputSubject">Company<span class="requred"></span></label>
                                        <input type="text" class="form-control" name='company' id="InputSubject" placeholder="Company"
                                            >
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Inquiry <span class="requred">*</span></label>
                                        <textarea class="form-control"  name='inquiry' id="exampleFormControlTextarea1" rows="3" placeholder="Meassage"
                                            required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
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
        </div>
    </div>
</div>
<!-- contact start -->

<!-- newsletter start -->
<div class="cta-area newsletter" style="background-color:black;">
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
                                <span class="details">SignUp For Newsletter </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="right-content">
                        <div class="newsletter-subcribe">
                            <form id="news-subcribeform" class="subcribe-form">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Your mail here..." name="mail" required="">
                                    <button type="submit" class="boxed-btn subcribe-submit">Subcribe</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</div>
<!-- newsletter end  -->
<!-- history end  -->
@include('web.Layout.footer')
</body>
</html>