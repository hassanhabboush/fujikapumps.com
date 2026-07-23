
<!-- Menu sidebar static layout -->
@include('Layout.head')
<body>
@include('Layout.header')
  @if(Auth::user()->role!=1)
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
                                            @include('Pages.system_user.addsystem_user')
                                             @include('Pages.system_user.editsystem_user')
                                             <div id="grid"></div> 
        <script>
             // Activate/deactivate moved off GET, so submit a real form with the verb.
             function patchTo(url) {
                 var form = document.createElement('form');
                 form.method = 'POST';
                 form.action = url;
                 form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">'
                                + '<input type="hidden" name="_method" value="PATCH">';
                 document.body.appendChild(form);
                 form.submit();
             }

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
                                  url: "{{ route('admin.system_users.data') }}"
                               }
                               ,
                               
                               destroy:
                               {
                                   url: function(row) {
                                       return "{{ url('system_users') }}/" + row.id;
                                   },
                                   type: "DELETE"
                               }
                               
                           },
                         serverPaging: false,
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
                           total: function(response) {
                              return $(response.data).length;
                            }
                           } 
         
                       },
                       columns: [
                       { field: "name" ,title:"Name"},
                       { field: "email" ,title:"Email"},
                       { field: "role" ,title:"Role"},
                       { field: "active" ,title:"status"},
                       { field: "id" ,title:"action", width: "200px" , template: "<a title='Edit user' class='k-button k-button-icontext' name='${id}'  onclick='popedit(this)'> Edit</a><br> <a title='Active' class='k-button k-button-icontext' onclick='patchTo(\"{{ url("system_users") }}/${id}/activate\")' style='cursor:pointer'>Active</a> <a title='DisActive' class='k-button k-button-icontext' onclick='patchTo(\"{{ url("system_users") }}/${id}/deactivate\")' style='cursor:pointer'>Inactive</a>"},
                         {command: ["destroy"], title: "Delete" }
                        ],
                      editable:
                       {  mode: "popup",
                       confirmation: true,
                        confirmDelete: "Yes"
                        } ,
                       selectable: "multiple cell",
                        allowCopy: true,
                       // dataBound: ColorMeBad,                
                pageable: {
                    alwaysVisible: false,
                    pageSizes: [10, 25, 50, 100]
                },
               sortable: true,
               filterable: true,
               navigable: true,    
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
