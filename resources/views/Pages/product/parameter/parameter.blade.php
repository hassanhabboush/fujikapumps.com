
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
                                            @include('Pages.product.parameter.add')
                                             <div id="grid"></div> 
        <script>
             $(function()
                {
                 
                   $("#grid").kendoGrid({
                    toolbar: [ "create" ],
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
                                  url: "{{url('readparameter/'.$id)}}" 
                               },
                               destroy:
                               {
                                   url: "{{url('deleteparameter')}}"
                               },
                               update: {
                            url: "{{url('updateparameter')}}",
                            type: "GET"
                        },
                        create: {
          url: "{{url('addparameter')}}",
          type: "GET"
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
                                    id: { editable: false, nullable: true },
                                    Model: {nullable: false },
                                    SerialNumber: {nullable: false },
                                    PowerKw: {nullable: false },
                                    PowerHp: {nullable: false },
                                    q: {nullable: false },
                                    h: {nullable: false },
                                    v: {nullable: false },
                                    Discharge_diameter: {nullable: false },
                                    Hertz: {nullable: false },
                                    Material: {nullable: false },
                                    RPM:{nullable: false},
                                    link:{nullable: false}


                                   }
                           },
                           total: function(response) {
                              return $(response.data).length;
                            }
                           } 
         
                       },
                       columns: [
                       { field: "id" ,title:"ID"},
                       { field: "Model" ,title:"Model"},
                       { field: "SerialNumber" ,title:"Serial Number"},
                       { field: "PowerKw" ,title:"POWER KW"},
                       { field: "PowerHp" ,title:"POWER HP"},
                       { field: "q" ,title:"Q(m3/h)"},
                       { field: "h" ,title:"Head(m)"},
                       { field: "v" ,title:"V"},
                       { field: "Discharge_diameter" ,title:"Discharge Diameter"},
                       { field: "Hertz" ,title:"HERTZ"},
                       { field: "Material" ,title:"Material"},
                       { field: "RPM" ,title:"RPM"},
                           { field: "link" ,title:"Link"},
                       { command: ["edit", "destroy"], title: "&nbsp;", width: "250px" }],
                      editable:
                       {  mode: "inline",
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
