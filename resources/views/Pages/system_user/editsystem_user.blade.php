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
                                 console.log(data);
                                 document.getElementById("Eid").value=data.data[0].id;
                                 document.getElementById("Ename").value=data.data[0].name;
                                 document.getElementById("Epassword").value=data.data[0].password;
                                 document.getElementById("Eemail").value=data.data[0].email;
                                 document.getElementById("Erole").value=data.data[0].role;

                              
                           
                               }
                       }     
                           xmlhttp.open("GET", "/getsystem_user/" + atts.name , true);
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
                                       <form method="POST" action="editsystem_user" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="text"  name="Eid" id="Eid" style="display:none;">
                                             
                                                <div class="k-edit-form-container">
                                                     <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                <div class="col-md-6">
                                   <div class="k-edit-label">
                                       <label for="fname">Email</label>
                                       <br>
                                       <input type="email" class="k-input k-textbox" name="Eemail" readonly id="Eemail" required="required" data-required-msg="is required.">
                                   </div>
                                </div>
                                <div class="col-md-6">
                                        <div class="k-edit-label">
                                            <label for="mname">Password</label>
                                            <br>
                                            <input required type="password" class="k-input k-textbox" name="Epassword" id="Epassword"  data-required-msg="is required.">
                                        </div>
                                 </div>
                            </div>
                            
                            <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Name</label>
                                                    <br>
                                                    <input required type="text" class="k-input k-textbox" name="Ename" id="Ename" required="required" data-required-msg="is required.">
                                            </div>
                                    </div>
                                     <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Roll</label>
                                                    <br>
                                        <select name ="Erole" id="Erole"  class="k-input k-textbox" required>
                                        <option selected disabled>Select Roll</option>
                                        <option value="1">Administrator</option>
                                        <option value="2">User B</option>
                                        <option value="3">User C</option>
                                        <option value="4">User D</option>
                                            
                                        </select>
                                                    
                                             </div>
                                     </div>
                                   
                                    
                                </div>
                                 
                                <input type="submit"  id="btnleft" class="k-button k-button-icontext k-primary k-grid-update"   name="submitadd" value="Update" >
                           <a onclick='Ehideme()' id="btnleft"  class="k-button k-button-icontext k-grid-cancel" href="#">
                           Cancel</a>
                            </div>
                        </form>
                </div></div></div>
            </div>
            
            