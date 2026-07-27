
<!-- Menu sidebar static layout -->
@include('Layout.head')
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
                                            <!-- body Srart  -->
                                            @include('Pages.series.add')
                                             @include('Pages.series.edit')
                                             <div id="grid"></div> 
        <script>
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
                                  url: "{{ route('admin.series.data') }}"
                               },
                               destroy:
                               {
                                   url: function(series) {
                                       return "{{ url('series') }}/" + series.id;
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
                       { field: "photo" ,title:"Image",template: "<img src='${photo}' class='lazy-img' style='width: 60px; height: 60px; object-fit: contain;' width='60' height='60' loading='lazy' decoding='async' alt='Photo'>"},
                       {field: "english_name" ,title:"Name"},
                       {field: "link" ,title:"Link",template: "<a href='${link}' class='k-button k-button-icontext'>PDF</a>"},
                       { field: "id" ,title:"Edit",template: "<a title='Edit' class='k-button k-button-icontext' name='${id}'  onclick='popedit(this)'>Edit</a>"},
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
