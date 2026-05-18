@include('Layout.headproduct')

<script>
        function showMe() {
            
         var store = document.getElementById('hidden-div');
       if(store.style.display == '' || store.style.display == 'none'){
           store.style.display = 'block';
       }
       else {
            store.style.display = 'none';
       }
    }
    </script>
    <script>
            function hideme() {
           var store = document.getElementById('hidden-div');
        
           if(store.style.display == '' || store.style.display == 'none'){
                store.style.display = 'block';
           }
           else {
                store.style.display = 'none';
           }
        }
    function validateMyForm()
{
  
        var cat = document.getElementById('name').value;
        xmlhttp = new XMLHttpRequest();
         url='{{url("getcat/name")}}';
      url = url.replace('name', cat);
        xmlhttp.open("GET",url,true);
        xmlhttp.send();
 xmlhttp.onreadystatechange=function()
    {
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {
        var data = JSON.parse(xmlhttp.responseText);
       
        
    if (data.data>0)
    {
    
        alert("Name is duplicated please choose another one");
        
    return false;
        
    }
    else
    {
        document.getElementById('myform').submit();
        
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
    z-index: 3;
    opacity: 1;
    transform: scale(1);
    width: 80%;">
     <div class="k-window-titlebar k-header" style="margin-top: -53.2667px;">&nbsp;<span class="k-window-title">Add</span>
       <div class="k-window-actions"><a role="button" href="#" onclick='hideme()' class="k-window-action k-link">
           <span role="presentation" class="k-icon k-i-close"></span></a></div></div>
                <div  class="k-popup-edit-form k-window-content k-content" style="width:100% !important" data-role="window" tabindex="0">   
                  <form method="POST" action="{{url('addsub_category1')}}" enctype="multipart/form-data" id="myform">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                                                   <input type="file" name="background" id="background"  required="required">                                            
                                                   </div>
                                     </div>
                                    
                                </div>
                                 <div class="row">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Category Name</label>
                                                    <br>
                                                  <select class="k-input k-textbox selectbox select2" name="cat_id[]" require="true" multiple >
                                                          @foreach(DB::table('sub_category')->get() as $item)
                                                   <option style="color:gray;" value="{{ $item->id }}">{{ $item->english_name}}</option>
                                                              @endforeach
                                                  </select>

                                           </div>
                                    
                                </div>
                              
           <div>
                        <input type="button"  id="btnleft"  class="k-button k-button-icontext k-primary k-grid-update"   name="submitadd" value="Add" onclick="return validateMyForm();" >
                   <a onclick='hideme()' id="btnleft"   class="k-button k-button-icontext k-grid-cancel" href="#">
                       Cancel</a>
                    </div>
                </form>
     </div></div>  </div>
    </div>
    <script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
  });
 </script>
    
    