@extends('layouts.app_side')
@section('plugins')
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css" rel="stylesheet">
@endsection
@section('content')
    <!-- Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar fecha de expiración</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span class="font-weight-light" aria-hidden="true">&times;</span>
					</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div>
                            <h4 class="ml-3">{{$companyAffiliated->name}} {{$companyAffiliated->last_name}}</h4>
                            <h5 class="ml-3" id="descriptionPlan"></h5>
                            <hr>
                            <ul id="ulContent">

                            </ul>
                        </div>

                    </div>
                    <div class="row">
                        <div class=" col-lg-6 col-md-6">
                            <label class="">{{ __('Fecha de incio') }}</label>
                            <input id="init_date" placeholder="día/mes/año" type="date"
                                   class="form-control" value="" disabled>
                        </div>
                        <div class=" col-lg-6 col-md-6">
                            <label class="">{{ __('Fecha de expiración') }}</label>
                            <input id="end_date" placeholder="día/mes/año" type="date"
                                   class="form-control" value="">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary btn-sm" type="button" id="onEdit"><i id="move" class=""></i>Editar</button></div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="content">
            <div class="row">
                @include('roles.admin.sidebar')
                <div class="col-md-8" >
                    <div class="mb-3 card">
                        <div class="card-header bg-light">
                            <h5 class="">Contenidos vigentes</h5>
                            <div class="row justify-content-between mt-4">
                                <div class="col">
                                    <div class="media">
                                        <div class="avatar avatar-2xl">
                                        @if(isset($companyAffiliated->url_image)) 
                                            <img class="rounded-circle" src="{{asset($companyAffiliated->url_image)}}" />
                                        @else 
                                            <img class="rounded-circle" src="{{asset('images/icons/default-avatar.png')}}" />
                                        @endif
                                        </div>
                                        <div class="media-body align-self-center ml-2">
                                            <p class="mb-1 line-height-1"><a class="font-weight-semi-bold" href="">{{$companyAffiliated->name}} {{$companyAffiliated->last_name}}</a></p>
                                            <p class="mb-0 fs--1">Colombia - Facatativa <svg class="svg-inline--fa fa-globe-americas fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="globe-americas" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" data-fa-i2svg=""><path fill="currentColor" d="M248 8C111.03 8 0 119.03 0 256s111.03 248 248 248 248-111.03 248-248S384.97 8 248 8zm82.29 357.6c-3.9 3.88-7.99 7.95-11.31 11.28-2.99 3-5.1 6.7-6.17 10.71-1.51 5.66-2.73 11.38-4.77 16.87l-17.39 46.85c-13.76 3-28 4.69-42.65 4.69v-27.38c1.69-12.62-7.64-36.26-22.63-51.25-6-6-9.37-14.14-9.37-22.63v-32.01c0-11.64-6.27-22.34-16.46-27.97-14.37-7.95-34.81-19.06-48.81-26.11-11.48-5.78-22.1-13.14-31.65-21.75l-.8-.72a114.792 114.792 0 0 1-18.06-20.74c-9.38-13.77-24.66-36.42-34.59-51.14 20.47-45.5 57.36-82.04 103.2-101.89l24.01 12.01C203.48 89.74 216 82.01 216 70.11v-11.3c7.99-1.29 16.12-2.11 24.39-2.42l28.3 28.3c6.25 6.25 6.25 16.38 0 22.63L264 112l-10.34 10.34c-3.12 3.12-3.12 8.19 0 11.31l4.69 4.69c3.12 3.12 3.12 8.19 0 11.31l-8 8a8.008 8.008 0 0 1-5.66 2.34h-8.99c-2.08 0-4.08.81-5.58 2.27l-9.92 9.65a8.008 8.008 0 0 0-1.58 9.31l15.59 31.19c2.66 5.32-1.21 11.58-7.15 11.58h-5.64c-1.93 0-3.79-.7-5.24-1.96l-9.28-8.06a16.017 16.017 0 0 0-15.55-3.1l-31.17 10.39a11.95 11.95 0 0 0-8.17 11.34c0 4.53 2.56 8.66 6.61 10.69l11.08 5.54c9.41 4.71 19.79 7.16 30.31 7.16s22.59 27.29 32 32h66.75c8.49 0 16.62 3.37 22.63 9.37l13.69 13.69a30.503 30.503 0 0 1 8.93 21.57 46.536 46.536 0 0 1-13.72 32.98zM417 274.25c-5.79-1.45-10.84-5-14.15-9.97l-17.98-26.97a23.97 23.97 0 0 1 0-26.62l19.59-29.38c2.32-3.47 5.5-6.29 9.24-8.15l12.98-6.49C440.2 193.59 448 223.87 448 256c0 8.67-.74 17.16-1.82 25.54L417 274.25z"></path></svg><!-- <span class="fas fa-globe-americas"></span> --></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-light card-body">
                            <table id="myTable" class="display-1 table table-sm table-dashboard data-table no-wrap mb-0 fs--1 w-100" style="width: 100%">
                                <thead class="bg-200">
                                <tr>
                                    <th class="sort">Plan</th>
                                    <th class="sort">Estado</th>
                                    <th class="sort">Fecha de inicio</th>
                                    <th class="sort">Fecha de expiración</th>
                                    <th class="sort">Editar fecha</th>
                                </tr>
                                </thead>
                                <tfoot class="bg-200">
                                <tr>
                                    <th>plan</th>
                                    <th>Estado</th>
                                    <th>fecha de inicio</th>
                                    <th>fecha de expiración</th>
                                    <th>Editar fecha</th>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script>
        $(document).ready( function () {
            var accountServiceId = null;
            var originalEndDate = null;
            var plan = null;
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
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
                'ajax': "{{ route('get_user_contracted_products_dt',$companyAffiliated->id)}}",
                'columns': [
                    {data: 'plan', className: 'text-center'},
                    {data: 'state', className: 'text-center'},
                    {data: 'init_date', className: 'text-center'},
                    {data: 'end_date', className: 'text-center'},
                    {data: 'edit_date', className: 'text-center'},
                ]
            });

            new $.fn.dataTable.FixedHeader( table );
            table.on('click', '.edit_date', function (e) {
                $tr = $(this).closest('tr');
                let dataTable = table.row($tr).data();
                $('#init_date').val(dataTable.init_date)
                $('#end_date').val(dataTable.end_date)
                $('#descriptionPlan').html(dataTable.plan)
                originalEndDate = dataTable.end_date;
                plan = dataTable.plan
                $("#ulContent").empty();
                accountServiceId = dataTable.id;
                $(dataTable.affiliated_content_account_service).each(function(key,value){
                    $("#ulContent").append(`<li>${value.sequence.name}</li>`);
                });
                $('#exampleModal').modal('toggle');
            });

            $('#onEdit').on('click',function(){
                $('#move').addClass('fa fa-spinner fa-spin');
                $('#onEdit').attr('disabled',true);
                var route = "{{ route('update_date_expiration_content_user')}}"
                var typeAjax = 'POST';
                var async = async || true;
                var formDatas = new FormData();
                formDatas.append('accountServiceId',accountServiceId);
                formDatas.append('originalEndDate',originalEndDate);
                formDatas.append('end_date',$('#end_date').val());
                formDatas.append('email',"{{$companyAffiliated->email}}");
                formDatas.append('plan',plan);
                formDatas.append('full_name',"{{$companyAffiliated->name}}"+" "+"{{$companyAffiliated->last_name}}");

                $.ajax({
                    url: route,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    cache: false,
                    type: typeAjax,
                    contentType: false,
                    data: formDatas,
                    processData: false,
                    async: async,
                    beforeSend: function () {
                    },
                    success: function (response, xhr, request) {
                        $('#move').removeClass('fa fa-spinner fa-spin');
                        $('#onEdit').attr('disabled',false);
                        if(request.status === 200){
                            $('#exampleModal').modal('toggle');
                            table.ajax.reload();
                            swal({
                                text: response.message,
                                type: "success",
                                showCancelButton: false,
                                showConfirmButton: false
                            }).catch(swal.noop);
                        }else{
                            $('#exampleModal').modal('toggle');
                            table.ajax.reload();
                            swal({
                                text: response.message,
                                type: "warning",
                                showCancelButton: false,
                                showConfirmButton: false
                            }).catch(swal.noop);
                        }

                    },
                    error: function (response, xhr, request) {
                        $('#exampleModal').modal('toggle');
                        table.ajax.reload();
                        swal({
                            text: 'Algo salio mal, intente de nuevo',
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: false
                        }).catch(swal.noop);
                    }
                });

            })
        } );

    </script>
@endsection