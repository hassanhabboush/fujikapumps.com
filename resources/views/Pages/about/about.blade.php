
<!-- Menu sidebar static layout -->
@include('Layout.head')
<body>
@include('Layout.header')
      @if(Auth::user()->role!=1 && Auth::user()->role!=2  && Auth::user()->role!=3)
      <script> 
      window.location.href = '{{URL("/noaccess")}}'; //using a named route
      </script>
    @endif   
            <div class="pcoded-main-container">
              @include('Layout.sidebar')
              
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        <div class="row">
                                            <!-- body Srart  -->
                                            
      
            
            <script >  
            
                    function popedit()
                   {
                      
                       xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function()
                        {
                           if (xmlhttp.readyState==4 && xmlhttp.status==200)
                            {
                                 var data = JSON.parse(xmlhttp.responseText);
                                 console.log(data);
                                 document.getElementById("photo").value=data.data.photo;
                                 document.getElementById("link").value=data.data.linkyoutube;
                                 document.getElementById("title1").value=data.data.title1;
                                   document.getElementById("title2").value=data.data.title2
                                    document.getElementById("title3").value=data.data.title3;
                                     document.getElementById("title4").value=data.data.title4;
                                      document.getElementById("desc1").value=data.data.desc1;
                                       document.getElementById("desc2").value=data.data.desc2;
                                        document.getElementById("desc3").value=data.data.desc3;
                                         document.getElementById("desc4").value=data.data.desc4;
                                         document.getElementById("map").value=data.data.map;
                                 
                               }
                       }     
                           xmlhttp.open("GET", "getabout"  , true);
                           xmlhttp.send();
                           EshowMe();
                         }
  
        </script>
          <script>
           window.onload = function() {
          popedit();
};
        </script>
        <br><br>
            <div  style="width: 100%;">
            <div class="" data-role="draggable" style="padding-top: 53.2667px;
            min-width: 90px;
            width:100%;
            min-height: 50px;
            top: 2%;
            left: 10%;
            touch-action: none;
            z-index: 10003;
            opacity: 1;
            margin-top:3%;
            transform: scale(1);
            ">

                        <div  class="k-popup-edit-form k-window-content k-content" style="width:100% !important" data-role="window" tabindex="0">   
                                       <form method="POST" action="editabout" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="text"  name="Eid" id="Eid" style="display:none;">
                                            <input type="text"  name="photo" id="photo" style="display:none;">
                                            
                                                <div class="k-edit-form-container">
                                                    
                                 <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                      <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">photo</label>
                                                    <br>
                                                   <input type="file" name="Eimage" id="Elogo"  >                                            
                                                   </div>
                                     </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Youtube-Link</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="link" id="link"   data-required-msg="is required.">
                                                    
                                            </div>
                                    </div>
                                   
                                     
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">

                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Title1</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="title1" id="title1"   data-required-msg="is required.">
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Descreption1</label>
                                                    <br>
                                                    <input type="text"   class="k-input k-textbox" name="desc1" id="desc1"   data-required-msg="is required.">
                                            </div>
                                    </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">

                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Title2</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="title2" id="title2"   data-required-msg="is required.">
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Descreption2</label>
                                                    <br>
                                                    <input type="text"   class="k-input k-textbox" name="desc2" id="desc2"   data-required-msg="is required.">
                                            </div>
                                    </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">

                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Title3</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="title3" id="title3"   data-required-msg="is required.">
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Descreption3</label>
                                                    <br>
                                                    <input type="text"   class="k-input k-textbox" name="desc3" id="desc3"   data-required-msg="is required.">
                                            </div>
                                    </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">

                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Title4</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="title4" id="title4"   data-required-msg="is required.">
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Descreption4</label>
                                                    <br>
                                                    <input type="text"   class="k-input k-textbox" name="desc4" id="desc4"   data-required-msg="is required.">
                                            </div>
                                    </div>
                                    
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">

                                    <div class="col-md-12">
                                            <div class="k-edit-label">
                                                    <label for="lname">Map Link</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="map" id="map"   data-required-msg="is required.">
                                            </div>
                                    </div>
                                </div>
                                       <div>
                                <input type="submit"  id="btnleft"  class="k-button k-button-icontext k-primary k-grid-update"   name="submitadd" value="Update" >
                           
                            </div>
                        </form>
                </div></div></div>
            </div>
            
            

                                            <!-- body  end -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Warning Section Ends -->
 @include('Layout.includefooter')

</body>

</html>
