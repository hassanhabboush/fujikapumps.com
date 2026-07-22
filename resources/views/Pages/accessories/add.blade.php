<script>
        function showMe() {
            
         var slider = document.getElementById('hidden-div');
       if(slider.style.display == '' || slider.style.display == 'none'){
           slider.style.display = 'block';
       }
       else {
            slider.style.display = 'none';
       }
    }
    </script>
    <script>
            function hideme() {
           var slider = document.getElementById('hidden-div');
        
           if(slider.style.display == '' || slider.style.display == 'none'){
                slider.style.display = 'block';
           }
           else {
                slider.style.display = 'none';
           }
        }
    </script>
    <div class="k-header k-grid-toolbar" style="
    width: 100%;
    padding-top: 1%;
    padding-left: 2%;
    ">
    <button type="button" onclick="showMe()" class="k-button k-button-icontext k-grid-add">+ Add new record</button>	
    </div>
    <div  id="hidden-div" style="display:none;">
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
       <div class="k-window-actions"><a role="button" href="#" onclick='hideme()' class="k-window-action k-link">
           <span role="presentation" class="k-icon k-i-close"></span></a></div></div>
                <div  class="k-popup-edit-form k-window-content k-content" style="width:100% !important" data-role="window" tabindex="0">   
                  <form method="POST" action="{{ route('admin.accessories.store') }}" enctype="multipart/form-data">
                      @csrf
                      {{-- Tells Layout/errors which modal to re-open on failure. --}}
                      <input type="hidden" name="_form" value="add">
                        <div class="k-edit-form-container">
                           
                                 <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Name</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="name" value="{{ old('name') }}" >
                                            </div>
                                    </div>

                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Link</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="link" value="{{ old('link') }}"  >
                                            </div>
                                    </div>
                                 
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Image</label>
                                                    <br>
                                                   <input type="file" name="image" id="logo"  required >                                            
                                                   </div>
                                     </div>
                                    
                                </div>
                              
           <div>
                        <input type="submit" id="btnleft"   class="k-button k-button-icontext k-primary k-grid-update"   name="submitadd" value="Update" >
                   <a onclick='hideme()' id="btnleft"   class="k-button k-button-icontext k-grid-cancel" href="#">
                       Cancel</a>
                    </div>
                </form>
     </div></div>  </div>
    </div>
    
    