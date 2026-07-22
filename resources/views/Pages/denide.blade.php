
<!-- Menu sidebar static layout -->
@include('Layout.head')
<body>
@include('Layout.header')
    
            <div class="pcoded-main-container">
              @include('Layout.sidebar')
              
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        <div class="row">
                                            <!-- body Srart  -->
                                            
                                            <script>
                function EshowMe() {
               var Eslider = document.getElementById('Ehidden-div');
            
               if(Eslider.style.display == '' || Eslider.style.display == 'none'){
                    Eslider.style.display = 'block';
               }
               else {
                    Eslider.style.display = 'none';
               }
            }
            </script>
            <script>
                    function Ehideme() {
                   var Eslider = document.getElementById('Ehidden-div');
                
                   if(Eslider.style.display == '' || Eslider.style.display == 'none'){
                        Eslider.style.display = 'block';
                   }
                   else {
                        Eslider.style.display = 'none';
                   }
                }
            </script>
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
                                 document.getElementById("facebook").value=data.data[0].Facebook;
                                 document.getElementById("Linkedin").value=data.data[0].Linkedin;
                                   document.getElementById("Instagram").value=data.data[0].instagram;
                                   document.getElementById("twitter").value=data.data[0].Twitter;
                                   document.getElementById("Whatsapp").value=data.data[0].whatsapp;
                                   document.getElementById("Email").value=data.data[0].email;
                                   document.getElementById("phone1").value=data.data[0].phone1;
                                   document.getElementById("phone2").value=data.data[0].phon2;
                                   document.getElementById("address").value=data.data[0].address;
                                 
                               }
                       }     
                           xmlhttp.open("GET", "{{ url('contacts/1') }}"  , true);
                           xmlhttp.send();
                           EshowMe();
                         }
   popedit();
        </script>
        <br><br>
            <div  id="Ehidden-div">
            <div class="k-widget k-window" data-role="draggable" style="padding-top: 53.2667px;
            min-width: 90px;
            min-height: 50px;
            top: 2%;
            left: 10%;
            touch-action: none;
            z-index: 10003;
            opacity: 1;
            margin-top:3%;
            transform: scale(1);
            width: 80%;">

                        <div  class="k-popup-edit-form k-window-content k-content" style="width:100% !important" data-role="window" tabindex="0">   
                                       <form method="POST" action="{{ route('admin.contacts.update', 1) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="text"  name="Eid" id="Eid" style="display:none;">
                                            
                                                <div class="k-edit-form-container">
                                                    
                                 <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Facebook</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="Facebook" id="facebook"   data-required-msg="is required.">
                                            </div>
                                    </div>
                                   
                                      <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Twitter</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="Twitter" id="twitter"   data-required-msg="is required.">
                                            </div>
                                    </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">

                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Linkedin</label>
                                                    <br>
                                                    <input type="text" step="0.01" class="k-input k-textbox" name="Linkedin" id="Linkedin"   data-required-msg="is required.">
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Instagram</label>
                                                    <br>
                                                    <input type="text" step="0.01" class="k-input k-textbox" name="instagram" id="Instagram"   data-required-msg="is required.">
                                            </div>
                                    </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">

                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Whatsapp</label>
                                                    <br>
                                                    <input type="text" step="0.01" class="k-input k-textbox" name="whatsapp" id="Whatsapp"   data-required-msg="is required.">
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Email</label>
                                                    <br>
                                                    <input type="text" step="0.01" class="k-input k-textbox" name="email" id="Email"   data-required-msg="is required.">
                                            </div>
                                    </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">

                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Phone1</label>
                                                    <br>
                                                    <input type="text" step="0.01" class="k-input k-textbox" name="phone1" id="phone1"   data-required-msg="is required.">
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Phone2</label>
                                                    <br>
                                                    <input type="text" step="0.01" class="k-input k-textbox" name="phon2" id="phone2"   data-required-msg="is required.">
                                            </div>
                                    </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">


                                    <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Address</label>
                                                    <br>
                                                    <input type="text" step="0.01" class="k-input k-textbox" name="address" id="address"   data-required-msg="is required.">
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
