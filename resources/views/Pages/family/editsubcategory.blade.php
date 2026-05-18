@include('Layout.headproduct')

<script>
                function EshowMe() {
               var Estore = document.getElementById('Ehidden-div');
            
               if(Estore.style.display == '' || Estore.style.display == 'none'){
                    Estore.style.display = 'block';
               }
               else {
                    Estore.style.display = 'none';
               }
            }
            </script>
            <script>
                    function Ehideme() {
                   var Estore = document.getElementById('Ehidden-div');
                
                   if(Estore.style.display == '' || Estore.style.display == 'none'){
                        Estore.style.display = 'block';
                   }
                   else {
                        Estore.style.display = 'none';
                   }
                }
            </script>
            <script >  
                    function popedit(atts)
                   {
                       xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function()
                        {
                           if (xmlhttp.readyState==4 && xmlhttp.status==200)
                            {
                                 var data = JSON.parse(xmlhttp.responseText);
                                 console.log(data);
                                 $('#name').val(data.data['english_name']);
                                 $('#link').val(data.data['link']);
                                 $('#Eform').attr('action', '{{ url("family") }}/' + data.data['id']);
                                 $('.select3').select2();
                                 var selectedValues = new Array();
                                 selectedValues=data.data['sub'].split(',');
                                 console.log(selectedValues);
                                 $(".select3").val(selectedValues).trigger("change"); 
                               }
                       }     
                           var url = '{{ url("family") }}/' + atts.name;
                           xmlhttp.open("GET", url , true);
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
            z-index: 3;
            opacity: 1;
            transform: scale(1);
            width: 80%;">
             <div class="k-window-titlebar k-header" style="margin-top: -53.2667px;">&nbsp;<span class="k-window-title">Edit</span>
               <div class="k-window-actions"><a role="button" href="#" onclick='Ehideme()' class="k-window-action k-link">
                   <span role="presentation" class="k-icon k-i-close"></span></a></div></div>
                        <div  class="k-popup-edit-form k-window-content k-content" style="width:100% !important" data-role="window" tabindex="0">   
                                       <form id="Eform" method="POST" action="" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                             <div class="k-edit-form-container">
                                                    
                                <div class="row">
                                    <div class="col-md-11">
                                            <div class="k-edit-label">
                                                    <label for="lname">Name</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="name" id="name" required="required" data-required-msg="is required.">
                                            </div>
                                    </div>
                                    
                                    <div class="col-md-11">
                                            <div class="k-edit-label">
                                                    <label for="lname">Background</label>
                                                    <br>
                                                   <input type="file" name="background" id="background">                                            
                                                   </div>
                                     </div>
                                      <div class="col-md-11">
                                            <div class="k-edit-label">
                                                    <label for="lname">Family Link</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="link" id="link" required="required" data-required-msg="is required.">
                                            </div>
                                    </div>
                                    
                                </div>
                                 <div class="row">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Category Name</label>
                                                    <br>
                                                  <select class="k-input k-textbox selectbox select3" name="cat_id[]" multiple="" id="cat_id" require>
                                                  @foreach(DB::table('sub_category_1')->get() as $item)
                                                   <option value="{{ $item->id }}">{{ $item->english_name}}</option>
                                                              @endforeach
                                                  </select>

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
            
            