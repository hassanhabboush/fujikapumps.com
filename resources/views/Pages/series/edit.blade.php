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
                                 document.getElementById("Ename").value=data.data[0].english_name;
                                  document.getElementById("Etext1").value=data.data[0].text1;
                                 document.getElementById("Etext2").value=data.data[0].text2;
                                 document.getElementById("Etext3").value=data.data[0].text3;
                                 document.getElementById("Elink").value=data.data[0].link;
                                 document.getElementById("Ecat_id").value=data.data[0].family_id;
                                
                                 document.getElementById("Elogo_name").value=data.data[0].photo;
                               }
                       }     
                           xmlhttp.open("GET", "getseries/" + atts.name , true);
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
                                       <form method="POST" action="{{url(
                                       'editseries')}}" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="text"  name="Eid" id="Eid" style="display:none;">
                                             <input type="text"  name="Elogo_name" id="Elogo_name" style="display:none;">
                                                <div class="k-edit-form-container">
                                                    
                                 <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">name</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="Ename" id="Ename"  >
                                            </div>
                                    
                                    </div>
                                     <div class="col-md-6">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Family Name</label>
                                                    <br>
                                                  <select class="k-input k-textbox selectbox" name="Ecat_id" id="Ecat_id">
                                                          @foreach(DB::table('family')->get() as $item)
                                                         
                                                  
                                                   <option style="color:gray;" value="{{ $item->id }}">{{ $item->english_name}}</option>

                                                    
                                                   @endforeach
                                                  </select>

                                           </div>
                                  </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Link</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="Elink" id="Elink"  >
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Text1</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="Etext1" id="Etext1"  >
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Text2</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="Etext2" id="Etext2"  >
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Text3</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="Etext3" id="Etext3"  >
                                            </div>
                                    </div>
                                
                                   <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Enabled</label>
                                                    <input type="checkbox" name="EEnabled" id="EEnabled" value="True" >
                                            </div>
                                    </div> 
                                    
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Image</label>
                                                    <br>
                                                   <input type="file" name="Eimage" id="Elogo"  >                                            
                                                   </div>
                                     </div>
                                    
                                </div>
                   <div>
                                <input type="submit"  id="btnleft"  class="k-button k-button-icontext k-primary k-grid-update"   name="submitadd" value="Update" >
                           <a onclick='Ehideme()' id="btnleft"  class="k-button k-button-icontext k-grid-cancel" href="#">
                              Cancel</a>
                            </div>
                        </form>
                </div></div></div>
            </div>
            
            