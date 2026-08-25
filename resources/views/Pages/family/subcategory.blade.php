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
                                        @include('Pages.family.editsubcategory')
                                        @include('Pages.family.instant-image-upload')

                                            <!-- body Srart  -->
                                             <div id="grid"></div> 
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
                                  url: "{{ route('admin.families.listByCategory', $cid) }}" 
                               },
                               // Must stay an object: this Kendo build's RemoteTransport
                               // deep-extends transport.destroy, so a function value is
                               // dropped and the row is only removed client-side.
                               destroy:
                               {
                                  url: function(data) { return '{{ url("families") }}/' + data.id; },
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
                       { field: "english_name" ,title:"Name"},
                       { field: "background" ,title:"Background",template: "<img src='${background}' class='lazy-img' style='width: 37px; height: 37px; object-fit: contain;' width='37' height='37' loading='lazy' decoding='async' alt='Background image'>"},
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
