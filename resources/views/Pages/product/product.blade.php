
<!-- Menu sidebar static layout -->
@include('Layout.head')
<body>
@include('Layout.header')
     @if(Auth::user()->role!=1 && Auth::user()->role!=2  && Auth::user()->role!=3)
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
                                            <div class="k-header k-grid-toolbar" style="
    width: 100%;
    padding-top: 1%;
    padding-left: 2%;
    ">
    <a href="{{ route('admin.products.create') }}" class="k-button k-button-icontext k-grid-add">+ Add new record</a>	
    </div>
                                             <div id="grid"></div>
        @include('Pages.product.partials.gridhelpers')
        <script>
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
                                  url: "{{ route('admin.products.data') }}"
                               },
                               destroy:
                               {
                                  url: function(data) { return '{{ url("products") }}/' + data.id; },
                                  type: "DELETE",
                                  dataType: "text",
                                  headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                               },
                               parameterMap: function(data, type) {
                                  return type === "destroy" ? {} : data;
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
                       { field: "photo" ,title:"Photo",template: "<img src='${photo}' class='lazy-img' style='width: 37px; height: 37px; object-fit: contain;' width='37' height='37' loading='lazy' decoding='async' alt='Photo'>"},
                       { field: "name" ,title:"Name"},
                        @if(Auth::user()->role==1 || Auth::user()->role==2)
     
    
                       { field: "is_featured" ,width:"225px" ,title:"Action",template: "#if(is_featured==0){#<a title='Make Feature' class='k-button k-button-icontext' onclick='patchTo(\"{{ url("products") }}/${id}/feature\")' style='cursor:pointer'>Make Featured</a> #}else{#<a title='Make Feature' class='k-button k-button-icontext' onclick='patchTo(\"{{ url("products") }}/${id}/unfeature\")' style='cursor:pointer'>Remove Featured</a>#}# <br><a title='Edit' class='k-button k-button-icontext' href='{{ url("products") }}/${id}/edit'>Edit</a><br> <a title='gallery' class='k-button k-button-icontext' href='{{ url("products") }}/${id}/gallery'>Gallery</a> <br> <a title='Parameter' class='k-button k-button-icontext' href='{{ url("products") }}/${id}/parameters'>Parameter</a>"},
                       {command: ["destroy"], title: "Delete" }
@endif 
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
 
 @include('Layout.includefooter')

</body>

</html>


