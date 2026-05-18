<script>
        function showMe() {
            
         var category = document.getElementById('hidden-div');
       if(category.style.display == '' || category.style.display == 'none'){
            category.style.display = 'block';
       }
       else {
            category.style.display = 'none';
       }
    }
    </script>
    <script>
            function hideme() {
           var category = document.getElementById('hidden-div');
        
           if(category.style.display == '' || category.style.display == 'none'){
                category.style.display = 'block';
           }
           else {
                category.style.display = 'none';
           }
        }
    </script>
    <script>
function onch()
{
        var email = document.getElementById('email').value;
        var countries;
        xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", "readuserss",true);
        xmlhttp.send();
 xmlhttp.onreadystatechange=function()
    {
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {
        countries1  = JSON.parse(xmlhttp.responseText);
        countries = [];
        for(var i=0; i<countries1.length; i++) {
            console.log(countries1[i].email);
            countries.push(countries1[i].email);
         
         }
          if (countries.includes(email))
    {
        alert("Email is duplicated");
        return false;
    }
      }
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
     <div class="k-window-titlebar k-header" style="margin-top: -53.2667px;">&nbsp;<span class="k-window-title">Add</span>
       <div class="k-window-actions"><a role="button" href="#" onclick='hideme()' class="k-window-action k-link">
           <span role="presentation" class="k-icon k-i-close"></span></a></div></div>
                <div  class="k-popup-edit-form k-window-content k-content" style="width:100% !important" data-role="window" tabindex="0">   
                  <form method="POST" action="addsystem_user" enctype="multipart/form-data">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="k-edit-form-container">
                            <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                <div class="col-md-6">
                                   <div class="k-edit-label">
                                       <label for="fname">Email</label>
                                       <br>
                                       <input value="{{old('email')}}" onchange="onch()" id="email" type="email" required class="k-input k-textbox" name="email" required="required" data-required-msg="is required.">
                                        
                                   </div>
                                </div>
                                <div class="col-md-6">
                                        <div class="k-edit-label">
                                            <label for="mname">Password</label>
                                            <br>
                                            <input required type="password" class="k-input k-textbox" name="password"  data-required-msg="is required.">
                                        </div>
                                 </div>
                            </div>
                            
                            <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Name</label>
                                                    <br>
                                                    <input value="{{old('name')}}" type="text" class="k-input k-textbox" name="name" required="required" data-required-msg="is required.">
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                            <div class="k-edit-label">
                                                    <label for="lname">Roll</label>
                                                    <br>
                                        <select name ="role"  class="k-input k-textbox" required>
                                        <option selected disabled>Select Roll</option>
                                        <option value="1">Administrator</option>
                                        <option value="2">User B</option>
                                        <option value="3">User C</option>
                                        <option value="4">User D</option>
                                            
                                        </select>
                                                    
                                             </div>
                                     </div>
                                   
                                    
                                </div>
                               
                              
           <div class="">
                        <input type="submit" id="btnleft" class="k-button k-button-icontext k-primary k-grid-update"   name="submitadd" value="Add" >
                   <a onclick='hideme()' id="btnleft" class="k-button k-button-icontext k-grid-cancel" href="#">
                    Cancel</a>
                    </div>
                </form>
     </div></div>  </div>
    </div>
    
    