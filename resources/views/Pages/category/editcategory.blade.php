<script>
                function EshowMe() {
               var Ecategory = document.getElementById('Ehidden-div');
            
               if(Ecategory.style.display == '' || Ecategory.style.display == 'none'){
                    Ecategory.style.display = 'block';
               }
               else {
                    Ecategory.style.display = 'none';
               }
            }
            </script>
            <script>
                    function Ehideme() {
                   var Ecategory = document.getElementById('Ehidden-div');
                
                   if(Ecategory.style.display == '' || Ecategory.style.display == 'none'){
                        Ecategory.style.display = 'block';
                   }
                   else {
                        Ecategory.style.display = 'none';
                   }
                }
            </script>
            <script >  
                    function popedit(atts)
                   {
                       $name = atts.name;
                       xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function()
                        {
                           if (xmlhttp.readyState==4 && xmlhttp.status==200)
                            {
                                 var data = JSON.parse(xmlhttp.responseText);
                                 document.getElementById("Eenglish_name").value=data.data[0].english_name;
                                 // The id lives in the action URL now that update is a PUT to categories/{category}.
                                 document.getElementById("EditCategoryForm").action="{{ url('categories') }}/" + data.data[0].id;
                               }
                       }
                           xmlhttp.open("GET", "{{ url('categories') }}/" + atts.name , true);
                           xmlhttp.send();
                           EshowMe();
                         }
        </script>
            <div  id="Ehidden-div" style="display:none;">
            <div class="k-widget k-window" data-role="draggable" style="padding-top: 53.2667px;
            min-width: 90px;
            min-height: 50px;
            top: 2%;
            left: 10%;
            touch-action: none;
            z-index: 10003;
            opacity: 1;
            transform: scale(1);
            width: 80%;">
             <div class="k-window-titlebar k-header" style="margin-top: -53.2667px;">&nbsp;<span class="k-window-title">Edit</span>
               <div class="k-window-actions"><a role="button" href="#" onclick='Ehideme()' class="k-window-action k-link">
                   <span role="presentation" class="k-icon k-i-close"></span></a></div></div>
                        <div  class="k-popup-edit-form k-window-content k-content" style="width:100% !important" data-role="window" tabindex="0">   
                                       <form method="POST" id="EditCategoryForm" action="" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                                <div class="k-edit-form-container">
                                                     <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                <div class="col-md-11">
                                   <div class="k-edit-label">
                                       <label for="fname">Category Name</label>
                                       <br>
                                       <input type="text" class="k-input k-textbox" name="english_name" id="Eenglish_name" required="required" data-required-msg="is required.">
                                   </div>
                                </div>
                               
                            </div>
                         
								 <div class="row" style="margin-left: 0px;margin-right: 0px;">                                    
                                  
                                       <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Background</label>
                                                    <br>
                                                   <input type="file" style="    width: 98% !important;" name="background" id="Ebackground"  >                                          
                                                   </div>
                                     </div>
                                    
                                </div>
                   <div>
                                <input type="submit"  id="btnleft" class="k-button k-button-icontext k-primary k-grid-update"   name="submitadd" value="Update" >
                           <a onclick='Ehideme()' id="btnleft"  class="k-button k-button-icontext k-grid-cancel" href="#">
                           Cancel</a>
                            </div>
                        </form>
                </div></div></div>
            </div>
            
            