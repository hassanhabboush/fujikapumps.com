
<div class="pcoded-wrapper">
                    <nav class="pcoded-navbar">
                        <div class="pcoded-inner-navbar main-menu">
                            <div class="pcoded-navigatio-lavel">Navigation</div>
                            <ul class="pcoded-item pcoded-left-item">
                                 @if(Auth::user()->role==1 || Auth::user()->role==2 )
                                <li>
                                    <a href="{{route('admin.category')}}">
                                        <span class="pcoded-micon"><i class="feather icon-box"></i></span>
                                        <span class="pcoded-mtext">Category</span>
                                        <span class="pcoded-badge label label-danger">{{$count_user = DB::table('categories')->count()}}</span>
                                    </a>
                                    </li>
                                <li>
                                    <a href="{{route('admin.sub_category')}}">
                                        <span class="pcoded-micon"><i class="feather icon-package"></i></span>
                                        <span class="pcoded-mtext">Sub Category1</span>
                                        <span class="pcoded-badge label label-danger">{{$count_user = DB::table('sub_category')->count()}}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.sub_category1')}}">
                                        <span class="pcoded-micon"><i class="feather icon-package"></i></span>
                                        <span class="pcoded-mtext">Sub Category2</span>
                                        <span class="pcoded-badge label label-danger">{{$count_user = DB::table('sub_category_1')->count()}}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.family')}}">
                                        <span class="pcoded-micon"><i class="feather icon-package"></i></span>
                                        <span class="pcoded-mtext">Family</span>
                                        <span class="pcoded-badge label label-danger">{{$count_user = DB::table('family')->count()}}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.series')}}">
                                        <span class="pcoded-micon"><i class="feather icon-package"></i></span>
                                        <span class="pcoded-mtext">Series</span>
                                        <span class="pcoded-badge label label-danger">{{$count_user = DB::table('series')->count()}}</span>
                                    </a>
                                </li>
                               @endif
                            @if(Auth::user()->role==1 || Auth::user()->role==2 || Auth::user()->role==3)
                            <li class="product">
                                    <a href="{{route('admin.accessories')}}">
                                        <span class="pcoded-micon"><i class="feather icon-cpu"></i></span>
                                        <span class="pcoded-mtext">Accessories</span>
                                        <span class="pcoded-badge label label-danger">{{$count_user = DB::table('Accessories')->count()}}</span>
                                    </a>
                                </li>
                                <li class="product">
                                    <a href="{{route('admin.product')}}">
                                        <span class="pcoded-micon"><i class="feather icon-cpu"></i></span>
                                        <span class="pcoded-mtext">Products</span>
                                        <span class="pcoded-badge label label-danger">{{$count_user = DB::table('products')->count()}}</span>
                                    </a>
                                </li>
                                <li class="product">
                                    <a href="{{route('admin.featuredproduct')}}">
                                        <span class="pcoded-micon"><i class="feather icon-info"></i></span>
                                        <span class="pcoded-mtext">Featured Products</span>
                                        <span class="pcoded-badge label label-danger">{{$count_user = DB::table('products')->where('is_featured',1)->count()}}</span>
                                    </a>
                                </li>
                            @endif
                             @if(Auth::user()->role==1 || Auth::user()->role==2 || Auth::user()->role==3 || Auth::user()->role==4)
                                <li class="slider">
                                    <a href="{{route('admin.slider')}}">
                                        <span class="pcoded-micon"><i class="feather icon-aperture rotate-refresh"></i><b>A</b></span>
                                        <span class="pcoded-mtext">Sliders</span>
                                        <span class="pcoded-badge label label-danger">{{$count_user = DB::table('slider')->count()}}</span>
                                    </a>
                                </li>
                            @endif
                                @if(Auth::user()->role==1 || Auth::user()->role==2 || Auth::user()->role==3)
                                 <li class="source">
                                    <a href="{{route('admin.aboutdetails')}}">
                                        <span class="pcoded-micon"><i class="feather icon-feather"></i></span>
                                        <span class="pcoded-mtext">About</span>
                                    </a>
                                </li>
                                  <li class="source">
                                    <a href="{{route('admin.aboutgallery')}}">
                                        <span class="pcoded-micon"><i class="feather icon-feather"></i></span>
                                        <span class="pcoded-mtext">Gallery</span>
                                    </a>
                                </li>
                                  <li class="source">
                                    <a href="{{route('admin.team')}}">
                                        <span class="pcoded-micon"><i class="feather icon-feather"></i></span>
                                        <span class="pcoded-mtext">Team</span>
                                    </a>
                                </li>
                                 <li class="source">
                                    <a href="{{route('admin.contact')}}">
                                        <span class="pcoded-micon"><i class="feather icon-feather"></i></span>
                                        <span class="pcoded-mtext">Contact Info</span>
                                    </a>
                                </li>
                               
                                @endif
                                  @if(Auth::user()->role==1)
                                 <li class="source">
                                    <a href="{{route('admin.system_user')}}">
                                        <span class="pcoded-micon"><i class="feather icon-user"></i></span>
                                        <span class="pcoded-mtext">System User</span>
                                    </a>
                                </li>
                                @endif
                                
                            </ul>
                        </div>
                    </nav>