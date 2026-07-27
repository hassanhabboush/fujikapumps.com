
<!-- Menu sidebar static layout -->
@include('Layout.head')
<body>
@include('Layout.header')
    @if(Auth::user()->role!=1 && Auth::user()->role!=2)
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
                                            @include('Pages.category.addcategory')
                                             @include('Pages.category.editcategory')
                                             <div id="grid"></div> 
        <script>
             // Every grid transport below mutates over POST/PUT/DELETE, so CSRF
             // has to ride along on each jQuery-issued request.
             $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

             $(function()
                {

                   $("#grid").kendoGrid({
                                       
                           edit: function(e) {
                           e.container.find("label[for=id]").parent("div .k-edit-label").hide();
                           e.container.find("label[for=id]").parent().next("div .k-edit-field").hide();
        
                              },
                       dataSource: 
                       {
                       
                           transport:
                           {
                               
                               read:
                               {
                                  dataType: "json",
                                  url: "{{ route('admin.categories.data') }}"
                               },
                               destroy:
                               {
                                   url: function(category) {
                                       return "{{ url('categories') }}/" + category.id;
                                   },
                                   type: "DELETE"
                               }

                           },
                         serverPaging: true,
                         pageSize:8,
                       schema: 
                           {
                             data: "data",
                              model: 
                               {
                                   id: "id",
                                   fields: 
                                   {
                                   
                                   }
                           },
                           total: "total"
                           } 
         
                       },
                       columns: [
                           { field: "id" ,title:"ID"},
                       { field: "english_name" ,title:"Category Name"},
                       { field: "background" ,title:"Background",template: "<img src='${background}' class='lazy-img' style='width: 37px; height: 37px; object-fit: contain;' width='37' height='37' loading='lazy' decoding='async' alt='Background image'>"},
                       { field: "id" ,title:"Sub Details",template: "<a title='Show Sub category' class='k-button k-button-icontext ' href='{{ url("sub_categories/category") }}/${id}'>Sub Category</a>"},
                       { field: "id" ,title:"Edit",template: "<a title='Edit Category' class='k-button k-button-icontext' name='${id}'  onclick='popedit(this)'>Edit</a>"},
                       {command: ["destroy"], title: "Delete" }

                       ],
                      editable:
                       {  mode: "popup",
                       confirmation: true,
                        confirmDelete: "Yes"
                        } ,
                       
                       // dataBound: ColorMeBad,                
               pageable: true,
               sortable: true,
               filterable: true,
                 columnMenu: true,
                resizable: true,
               navigable: true
                       
                   });
              
               
              });
        
           function textareaEditor(container, options) 
       {
           $('<textarea data-bind="value: ' + options.field + '" cols="29" rows="5"></textarea>')
               .appendTo(container);
       }
       function categoryDropDownEditor(container, options) {
                    $('<input required name="' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            autoBind: false,
                            dataTextField: "title",
                            dataValueField: "title",
                            dataSource: _typedatasource1
                        });
                } 
    </script> 
<script>
    document.getElementsByClassName("k-i-close").textContent=" ";
    </script>
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
