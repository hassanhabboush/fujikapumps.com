<div class="pcoded-wrapper">
                    <nav class="pcoded-navbar">
                        <div class="pcoded-inner-navbar main-menu">
                            <div class="pcoded-navigatio-lavel">Navigation</div>
                            <ul class="pcoded-item pcoded-left-item">
                                 @if(Auth::user()->role==1 || Auth::user()->role==2 )
                                <li>
                                    <a href="{{route('admin.categories.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-box"></i></span>
                                        <span class="pcoded-mtext">Category</span>
                                        <span class="pcoded-badge label label-danger">{{ $sidebarCounts['categories'] }}</span>
                                    </a>
                                    </li>
                                <li>
                                    <a href="{{route('admin.sub_categories.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-package"></i></span>
                                        <span class="pcoded-mtext">Sub Category1</span>
                                        <span class="pcoded-badge label label-danger">{{ $sidebarCounts['sub_category'] }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.sub_categories1.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-package"></i></span>
                                        <span class="pcoded-mtext">Sub Category2</span>
                                        <span class="pcoded-badge label label-danger">{{ $sidebarCounts['sub_category_1'] }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.families.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-package"></i></span>
                                        <span class="pcoded-mtext">Family</span>
                                        <span class="pcoded-badge label label-danger">{{ $sidebarCounts['family'] }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.series.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-package"></i></span>
                                        <span class="pcoded-mtext">Series</span>
                                        <span class="pcoded-badge label label-danger">{{ $sidebarCounts['series'] }}</span>
                                    </a>
                                </li>
                               @endif
                            @if(Auth::user()->role==1 || Auth::user()->role==2 || Auth::user()->role==3)
                            <li class="product">
                                    <a href="{{route('admin.accessories.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-cpu"></i></span>
                                        <span class="pcoded-mtext">Accessories</span>
                                        <span class="pcoded-badge label label-danger">{{ $sidebarCounts['accessories'] }}</span>
                                    </a>
                                </li>
                                <li class="product">
                                    <a href="{{route('admin.products.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-cpu"></i></span>
                                        <span class="pcoded-mtext">Products</span>
                                        <span class="pcoded-badge label label-danger">{{ $sidebarCounts['products'] }}</span>
                                    </a>
                                </li>
                                <li class="product">
                                    <a href="{{route('admin.products.featured')}}">
                                        <span class="pcoded-micon"><i class="feather icon-info"></i></span>
                                        <span class="pcoded-mtext">Featured Products</span>
                                        <span class="pcoded-badge label label-danger">{{ $sidebarCounts['featured'] }}</span>
                                    </a>
                                </li>
                            @endif
                             @if(Auth::user()->role==1 || Auth::user()->role==2 || Auth::user()->role==3 || Auth::user()->role==4)
                                <li class="slider">
                                    <a href="{{route('admin.sliders.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-aperture rotate-refresh"></i><b>A</b></span>
                                        <span class="pcoded-mtext">Sliders</span>
                                        <span class="pcoded-badge label label-danger">{{ $sidebarCounts['slider'] }}</span>
                                    </a>
                                </li>
                            @endif
                                @if(Auth::user()->role==1 || Auth::user()->role==2 || Auth::user()->role==3)
                                 <li class="source">
                                    <a href="{{route('admin.about.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-feather"></i></span>
                                        <span class="pcoded-mtext">About</span>
                                    </a>
                                </li>
                                  <li class="source">
                                    <a href="{{route('admin.about.gallery.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-feather"></i></span>
                                        <span class="pcoded-mtext">Gallery</span>
                                    </a>
                                </li>
                                  <li class="source">
                                    <a href="{{route('admin.about.team.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-feather"></i></span>
                                        <span class="pcoded-mtext">Team</span>
                                    </a>
                                </li>
                                 <li class="source">
                                    <a href="{{route('admin.contacts.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-feather"></i></span>
                                        <span class="pcoded-mtext">Contact Info</span>
                                    </a>
                                </li>
                               
                                @endif
                                  @if(Auth::user()->role==1)
                                 <li class="source">
                                    <a href="{{route('admin.system_users.index')}}">
                                        <span class="pcoded-micon"><i class="feather icon-user"></i></span>
                                        <span class="pcoded-mtext">System User</span>
                                    </a>
                                </li>
                                @endif
                                
                            </ul>
                        </div>
                    </nav>
