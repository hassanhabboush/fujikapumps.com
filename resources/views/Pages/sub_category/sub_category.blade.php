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
                                            @include('Pages.sub_category.addsubcategory')
                                             @include('Pages.sub_category.editsubcategory')
                                             <div id="grid"></div> 
        <script>
             // The grid destroys over DELETE, so CSRF has to ride along.
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
                                  url: "{{ route('admin.sub_categories.data') }}"
                               },
                               destroy:
                               {
                                   url: function(row) {
                                       return "{{ url('sub_categories') }}/" + row.id;
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
                       { field: "english_name" ,title:"Name"},
                       { field: "background" ,title:"Background",template: "<img src='${background}' style='width: 37px;' alt='Logo'>"},
                       { field: "id" ,title:"Sub Category",template: "<a title='Family' class='k-button k-button-icontext' href='{{ url("sub_categories1/parent") }}/${id}'>Sub Category</a>"},
                       { field: "id" ,title:"Edit",template: "<a title='Edit Store' class='k-button k-button-icontext' name='${id}'  onclick='popedit(this)'>Edit</a>"},
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
               navigable: true,
                 columnMenu: true,
                resizable: true
                       
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
