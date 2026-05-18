<ul class="nav-list" id="popup-menu" style="">
                        @foreach ($headerCategories as $cat)
                            @if ($cat->english_name=='Accessories')
                                <li  class="nav-item" style="">
                                    <a href="{{url('/'.$cat->id.'/7/'.$cat->english_name)}}">{{$cat->english_name}}</a>
                                </li>
                            @else
                                @php
                                    $sub=$cat->subCategories;
                                @endphp
                                @if(count($sub)==0)
                                    <li class="nav-item" style="">
                                        <a  href="{{url($cat->id.'/2/'.$cat->english_name)}}" class="read4">{{$cat->english_name}}</a>

                                    </li>
                                @else
                                    <li class="nav-item">     <a  href="{{url($cat->id.'/2/'.$cat->english_name)}}" class="read4">{{$cat->english_name}}</a>

                                        <ul class="sbnav-list sbnav-list1 subcategory" style=""> <!-- ul sub -->
                                            @foreach( $sub as $subcat)
                                                @php
                                                    $sub1=$subcat->subCategory1s;
                                                @endphp
                                                @if(count($sub1) ==0)
                                                    <li class="sbnav-item sbnav-item1" >     <a  href="{{url($subcat->id.'/3/'.$subcat->english_name)}}" class="read4">{{$subcat->english_name}}</a>

                                                    </li>
                                                @else
                                                    <li class="nav-item">    <a  href="{{url($subcat->id.'/3/'.$subcat->english_name)}}" class="read4">{{$subcat->english_name}}</a>
                                                        <ul class="sbnav-list sbnav-list1 subcategory" style=""> <!-- ul sub1 -->
                                                            @foreach( $sub1 as $subcat1)
                                                                @php
                                                                    $family=$subcat1->families;
                                                                @endphp
                                                                @if(count($family)==0)
                                                                    <li class="sbnav-item sbnav-item1" style="">    <a  href="{{url($subcat1->id.'/4/'.$subcat1->english_name)}}" class="read4">{{$subcat1->english_name}}</a>         </li>
                                                                @else
                                                                    <li class="nav-item">
                                                                        <a  href="{{url($subcat1->id.'/4/'.$subcat1->english_name)}}" class="read4">{{$subcat1->english_name}}</a>
                                                                        <ul class="sbnav-list sbnav-list1 subcategory" style=""> <!-- ul sub1 -->
                                                                            @foreach ($family as $fam)
                                                                                <li class="sbnav-item sbnav-item1" style=""> <a  href="{{url($fam->id.'/5/'.$fam->english_name)}}" class="read4">{{$fam->english_name}}</a>
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
                    </ul>