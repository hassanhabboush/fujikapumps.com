<!-- Menu sidebar static layout -->
@include('Layout.headproduct')
<style>
    .note-editable  {   font-size: 50%; background: white;}
  </style>
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
  
    <div  id="hidden-div">
    <div   style="padding-top:2%;margin-bottom:2%;
    min-width: 90px;
    min-height: 50px;
    height:100%;
    width: 100%;">
     
                <div  class="k-content" style="width:100% !important" data-role="window" tabindex="0">   
                  <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                      @csrf
                      <input type="hidden" name="_form" value="add">
                        <div class="k-edit-form-container">
                           
                                 <div class="row">
                                    <div class="col-md-11">
                                            <div class="k-edit-label">
                                                    <label for="lname">Product Name</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="name" required="required" data-required-msg="is required.">
                                            </div>
                                    </div>
                                     <div class="col-md-11">
                                            <div class="k-edit-label">
                                                    <label for="lname">Product Link</label>
                                                    <br>
                                                    <input type="text" class="k-input k-textbox" name="link" required="required" data-required-msg="is required.">
                                            </div>
                                    </div>
                                   <div class="col-md-11" style="display:none;">
                                            <div class="k-edit-label">
                                                    <label for="lname">Descreption</label>
                                                    <br>
                                                    <textarea class="form-control" name="shortdescreption" id="summernote" require></textarea>

                                            </div>
                                    </div>
                                    
                                    
                                </div>
                                 <div class="row">
                                 <div class="col-md-11">
                                            <div class="k-edit-label" style="margin:0px;">
                                                    <label for="lname">Family Name</label>
                                                    <br>
                                                  <select class="k-input k-textbox selectbox" name="cat_id">
                                                          @foreach(DB::table('family')->get() as $item)
                                                   <option style="color:gray;" value="{{ $item->id }}">{{ $item->english_name}}</option>
                                                              @endforeach
                                                  </select>

                                           </div>
                                  </div>
                          
                                    
                                </div>
                                <div class="row">
                                <div class="col-md-11">
                                            <div class="k-edit-label">
                                                    <label for="lname">Photo</label>
                                                    <br>
                                                   <input type="file"  name="background" accept="image/png, image/gif, image/jpeg" id="background"  >                                            
                                                   </div>
                                     </div>
                                <div class="col-md-11">
                                <div class="k-edit-label">
                                        <label for="gallry">Gallery</label>
                                        <br>
                                        <input  type="file"  name="images[]" placeholder="gallary" accept="image/png, image/gif, image/jpeg" multiple>
</div>
                                
                                     </div>
                                     <div class="col-md-11">
                                <div class="k-edit-label">
                                        <label for="gallry">Import Parameter Table</label>
                                        <br>
                                        <input type="file" name="parameter" placeholder="Parameter table" accept=".csv,text/csv,text/plain,.txt">
                                        <small style="display:block;color:#9aa4b2;margin-top:6px;">Optional. CSV only — not Excel (.xlsx). In Excel: File → Save As → CSV.</small>
</div>
                                </div>
                                     </div>
                              
           <div style="padding-bottom: 2%;">
                        <input type="submit"  id="btnleft"  class="k-button k-button-icontext k-primary k-grid-update"   name="submitadd" value="Add" >
                  
                    </div>
                </form>
     </div></div>  </div>
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
    
      <!-- summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script type="text/javascript">
    $('#summernote').summernote({
        height: 400
    });
    </script>
 <script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
  });
  $(function () {
    //Initialize Select2 Elements
    $('.select3').select2()
  });
  </script>
 @include('Layout.includefooter')

</body>

</html>
