MyApp.controller("tutorHistoryCtrl", ["$scope", "$http", function($scope, $http) {

    $scope.errorMessage = '';
    $scope.history = null;
    $scope.init = function() {
        $('.d-none-result').removeClass('d-none');
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                order: [[ 1, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text:'<i class="far fa-file-excel"></i> Excel',
                        className: 'btn-primary btn-sm',
                        filename: function(){
                            return `Conexiones - Historial de pagos`

                        },
                        title:function(){
                            return 'Conexiones - Historial de pagos'
                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="far fa-file-pdf"></i> PDF',
                        className: 'btn-primary btn-sm',
                        filename: function(){
                            return `Conexiones - Historial de pagos`

                        },
                        title:function(){
                            return 'Conexiones - Historial de pagos'
                        },
                        exportOptions: {
                            columns: [1,2,3,4,5]
                        },
                        customize : function(doc) {
                            doc.content[1].table.widths = ['28%', '22%','15%', '15%', '25%'];
                            var rowCount = document.getElementById("myTable").rows.length;
                            for (i = 0; i < rowCount; i++) {
                                doc.content[1].table.body[i][0].alignment = 'center';
                                doc.content[1].table.body[i][3].alignment = 'center';
                            };
                        }
                    }
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primeros",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                'ajax': {
                    'url': '/get_history_tutor/',
                    'dataSrc': function (json) {
                      var return_data = new Array();
                      var row = product = null;
					  for(indx in json.data ) {
						    row = json.data[indx];
					        return_data.push({
                              'date': row.payment_process_date || row.payment_init_date || row.updated_at,
                              'description': row.description,
                              'status': row.payment_status.name,
                              'price': '$'+row.total_price+' COP',
                              'approval_code': row.approval_code
                            });
                        
                      }
                      return return_data;
                    }
                  },
                  'columns': [
                    {data: 'date', className: 'text-left'},
                    {data: 'description', className: 'text-left'},
                    {data: 'price', className: 'text-right'},
                    {data: 'status', className: 'text-center'},
                    {data: 'approval_code', className: 'text-right'},
                    
                ]
            });
            new $.fn.dataTable.FixedHeader( table );
            table.on('click', '.viewContens', function (e) {
                $tr = $(this).closest('tr');
                let dataTable = table.row($tr).data();
                window.location="{{route('get_user_contracted_products_view')}}"+"/"+dataTable.id
            });

    };
}]);


