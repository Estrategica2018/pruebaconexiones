MyApp.controller("managmentKitElementCtrl", ["$scope", "$http","$compile",'$timeout', function($scope, $http, $compile,$timeout) {
    /*****
        Variables para leer el directorio
     */
    $scope.route_get_elements = null
    $scope.table
    $scope.typeEdit = null
    $scope.mbImageShow = false
    $scope.directoryPath = null
    $scope.directoryPath2 = null
    $scope.mbImageShow2 = false
    $scope.dataJstree = {};
    $scope.cover = ''
    $scope.sequences = null
    $scope.moments = null
    $scope.sequenceSelected = null
    $scope.momentSelected = null
    $scope.arraySequenceMoment = []
    $scope.arraySequenceMomentEdit = []
    $scope.onChangeFolderImage = function (path,callback) {
        $http.post('/conexiones/admin/get_folder_image', { 'dir': path }).then(function (response) {
            var list = response.data.scanned_directory;
            $scope.directoryPath = response.data.directory;
            $scope.directory = [];
            $scope.filesImages = [];
            var item = null;
            for (indx in list) {
                item = list[indx];
                if (item.includes('.png') || item.includes('.jpg') || item.includes('.jpeg')) {
                    var filedir = $scope.directoryPath + '/' + item;
                    $scope.filesImages.push({ 'type': 'img', 'url_image': filedir });
                }
                else if (!item.includes('.')) {
                    var dir = $scope.directoryPath + '/'+ item;
                    dir = dir.replace('//','/');
                    $scope.directory.push({ 'type': 'dir', 'name': item, 'dir': dir });
                }
                else if (item === '..') {
                    var dir = getLastPath($scope.directoryPath);
                    $scope.directory.push({ 'type': 'dir', 'name': item, 'dir': dir });
                }
            }
            if(callback) callback(response.data);
        },function(e){
            var message = 'Error consultando el directorio';
            if(e.message) {
                message += e.message;
            }
            $scope.errorMessage = angular.toJson(message);
            $scope.directoryPath = null;
        });
    }
    $scope.onChangeFolderImage2 = function (path,callback) {
        $http.post('/conexiones/admin/get_folder_image', { 'dir': path }).then(function (response) {
            var list = response.data.scanned_directory;
            $scope.directoryPath2 = response.data.directory;
            $scope.directory2 = [];
            $scope.filesImages2 = [];
            var item = null;
            for (indx in list) {
                item = list[indx];
                if (item.includes('.png') || item.includes('.jpg') || item.includes('.jpeg')) {
                    var filedir = $scope.directoryPath2 + '/' + item;
                    $scope.filesImages2.push({ 'type': 'img', 'url_image': filedir });
                }
                else if (!item.includes('.')) {
                    var dir = $scope.directoryPath2 + '/'+ item;
                    dir = dir.replace('//','/');
                    $scope.directory2.push({ 'type': 'dir', 'name': item, 'dir': dir });
                }
                else if (item === '..') {
                    var dir = getLastPath($scope.directoryPath2);
                    $scope.directory2.push({ 'type': 'dir', 'name': item, 'dir': dir });
                }
            }
            if(callback) callback(response.data);
        },function(e){
            var message = 'Error consultando el directorio';
            if(e.message) {
                message += e.message;
            }
            $scope.errorMessage2 = angular.toJson(message);
            $scope.directoryPath2 = null;
        });
    }
    function getLastPath(directory) {
        var dirSplit = directory.split('/');
        var dirName = '';
        for (var i = 0; i < dirSplit.length - 1; i++) {
            if (dirName.length > 0) dirName += '/';
            dirName += dirSplit[i];
        }
        return dirName;
    }
    $scope.onImgChange = function (field) {
        $scope.applyChange = true;
        $scope.cover = field.url_image;
        $scope.mbImageShow = false
        swal({
            text: 'Imagen seleccionada',
            type: "success",
            showCancelButton: false,
            showConfirmButton: false
        }).catch(swal.noop);
    }
    /** fin varilables para leer el directorio */
    $scope.element={}
    $scope.element.id = ''
    $scope.actionElement = 'Crear'
    $scope.route
    $scope.route_kit
    $scope.kit={}
    $scope.actionkit = 'Crear'
    $scope.route_kit
    $scope.list_elements = []
    $scope.elementsSelected = null

    $scope.init = function(route_element,route_kit,route_get_elements) {
        $scope.route_get_elements = route_get_elements
        $scope.route = route_element
        $scope.route_kit = route_kit
        $('.d-none-result').removeClass('d-none');
        $scope.table = $('#myTable').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
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
                'url': '/conexiones/admin/get_kit_element_dt/',
                'dataSrc': function (json) {
                    var return_data = new Array();
                    var row = product = null;
                    for(var i=0 ; i<json.elements.length; i++){
                        row = json.elements[i];
                        return_data.push({
                            'name':row.name,
                            'description':row.description,
                            'type':'Elemento',
                            'price':row.price,
                            'quantity':row.quantity,
                            'edit':`<button class="btn btn-sm btn-warning" ng-click="actionModalElement('Editar',${row.id})" style="padding: .0875rem .75rem;font-size: .575rem;">Editar</button>`,
                        })

                    }
                    for(var i=0 ; i<json.kits.length; i++){
                        row = json.kits[i];
                        return_data.push({
                            'name':row.name,
                            'description':row.description,
                            'type':'Kit',
                            'price':row.price,
                            'quantity':row.quantity,
                            'edit':`<button class="btn btn-sm btn-warning" ng-click="actionModalKit('Editar',${row.id})" style="padding: .0875rem .75rem;font-size: .575rem;">Editar</button>`,
                        })

                    }
                    return return_data;
                }
            },
            'columns': [
                {data: 'name', className: 'text-left'},
                {data: 'description', className: 'text-left'},
                {data: 'type', className: 'text-center'},
                {data: 'price', className: 'text-cneter'},
                {data: 'quantity', className: 'text-center'},
                {data: 'edit', className: 'text-right'},

            ],
            "createdRow": function ( row, data, index ) {
                $compile(row)($scope); //add this to compile the DOM
            }
        });
        var path = 'images/designerAdmin'
        $scope.onChangeFolderImage(path)
        $scope.onChangeFolderImage2(path)
        $http({
            url:'/get_all_sequences/',
            method: "GET",
        }).
        then(function (response) {
            $scope.sequences = response.data.data
        }).catch(function (e) {

        });
    }
    $scope.sequenceChange = (data) => {
        data = data.split('|')[0]
        const result = ($scope.sequences).filter(res=>res.id == data ).map(ele=>ele.moments);
        $scope.moments =  result[0]
    }
    $scope.addSequenceMoment = () => {
        let dataSequenceSelected =  $scope.sequenceSelected.split('|')
        let dataMomentSelected = []
        let momentsName = '';
        angular.forEach($scope.momentSelected, function (keyword, key) {
            keyword =  keyword.split('|')
            momentsName = momentsName+keyword[1]+','
            dataMomentSelected.push({
                'id':keyword[0],
                'name':keyword[1],
            })
        });
        $scope.arraySequenceMoment.push(
            {
                'id':dataSequenceSelected[0],
                'name':dataSequenceSelected[1],
                'moments': dataMomentSelected,
                'moments_name': momentsName,

            }
            );
    }
    $scope.deleteSequenceMoment = (index) => {
        $scope.arraySequenceMoment.splice(index,1)
    }
    $scope.actionModalElement = (action, id=false) => {
        $scope.arraySequenceMoment = []
        if(action === 'Crear'){
            $scope.element={}
            $scope.element.id = ''
            $scope.actionElement = 'Crear'
            $scope.arraySequenceMomentEdit = []
            $('#exampleModal').modal('show');
        }else{
            $scope.actionElement = 'Editar'
            $scope.arraySequenceMomentEdit = []
            $http.get('/conexiones/admin/get_element/'+id, { 'id': id }).then(function (response) {
                $scope.element.id = response.data.data.id
                $scope.element.name = response.data.data.name
                $scope.element.cost = response.data.data.price
                $scope.element.quantity = response.data.data.quantity
                $scope.element.description = response.data.data.description
                $scope.element.cover = response.data.data.url_image
                $scope.element.url_slider_images = response.data.data.url_slider_images
                $scope.element.init_date = new Date(response.data.data.init_date+' 00:00:00');
                angular.forEach(response.data.data.element_in_moment, function (keyword, key) {
                    $scope.arraySequenceMomentEdit.push({
                        id:keyword.moment.id,
                        id_moment_kit:keyword.id,
                        moment_name:keyword.moment.name,
                        sequence_name:keyword.moment.sequence.name
                    })
                });
                $('#exampleModal').modal('show');
            },function(e){
                $scope.swalfunction('Algo salio mal','error')
            })
        }
    }
    $scope.deleteSequenceMomentEdit = (id,index,id_moment_kit) => {
        swal({
            text: "Confirma para desvincular el elemento del momento",
            type: "warning",
            showConfirmButton: true,showCancelButton: true
        }).then((willConfirm) => {
            if (willConfirm) {
                $http.post('/conexiones/admin/delete_elementorkit_in_moment',
                    {
                        'id': id_moment_kit,
                    }).then(function (response){
                        $scope.arraySequenceMomentEdit.splice(index,1)
                        swal({
                            type: 'success',
                            text:response.data.message,
                            showConfirmButton: false,showCancelButton: false
                        });
                    })
            }
        });
    }
    $scope.actionModalKit = (action,id=false) => {
        $http({
            url:$scope.route_get_elements,
            method: "GET",
        }).
        then(function (response) {
            $scope.list_elements = response.data.data
        }).catch(function (e) {

        });
        $scope.arraySequenceMoment = []
        $scope.arraySequenceMomentEdit= []
        if(action === 'Crear'){
            $scope.actionKit = 'Crear'
            $('#exampleModalKit').modal('show');
        }else{
            $http.get('/conexiones/admin/get_kit/'+id, { 'id': id }).then(function (response) {
                console.log(response.data.data.url_slider_images,response.data.data.init_date)
                $scope.directoryPath = response.data.data.url_image
                $scope.directoryPath2 = response.data.data.url_slider_images
                $scope.kit.id = response.data.data.id
                $scope.kit.name = response.data.data.name
                $scope.kit.cost = response.data.data.price
                $scope.kit.quantity = response.data.data.quantity
                $scope.kit.description = response.data.data.description
                $scope.kit.cover = response.data.data.url_image
                $scope.kit.url_slider_images = response.data.data.url_slider_images
                $scope.kit.init_date = new Date(response.data.data.init_date+' 00:00:00');
                angular.forEach(response.data.data.moment_kits, function (keyword, key) {
                    $scope.arraySequenceMomentEdit.push({
                        id:keyword.moment.id,
                        id_moment_kit:keyword.id,
                        moment_name:keyword.moment.name,
                        sequence_name:keyword.moment.sequence.name
                    })
                });
                $scope.elementsSelected = []
                angular.forEach(response.data.data.kit_elements, function (keyword, key) {
                    $scope.elementsSelected.push(keyword.element_id)
                });
                $timeout(function () {
                    $(".selectpicker").select2({
                        placeholder: "Seleccione...",
                    });
                    $('#exampleFormControlSelect1prue').select2({placeholder: ''}).val($scope.elementsSelected).change();
                    $scope.$apply();
                });
                $('#exampleModalKit').modal('show');
            },function(e){
                $scope.swalfunction('Algo salio mal','error')
            })
            $scope.actionKit = 'Editar'
        }
    }
    $scope.createOrUpdateKit = (action) => {
        if(action === 'Crear'){
            $http.post('/conexiones/admin/get_folder_image', { 'dir': $scope.directoryPath2 }).then(function (response) {
                $scope.kit.url_slider_images = response.data.directory + '/'; 
                $scope.createOrUpdateKitService(action)
            })
        }
        if(action === 'Editar'){
            if($scope.directoryPath2 === 'images/designerAdmin'){
                $scope.createOrUpdateKitService(action)
            }else{
                $http.post('/conexiones/admin/get_folder_image', { 'dir': $scope.directoryPath2 }).then(function (response) {
                    console.log(response.data.directory)
                    $scope.kit.url_slider_images = response.data.directory  + '/'; 
                    $scope.createOrUpdateKitService(action)
                })
            }
        }
    }
    $scope.createOrUpdateElement = (action) => {
        if(action === 'Crear'){
            $http.post('/conexiones/admin/get_folder_image', { 'dir': $scope.directoryPath2 }).then(function (response) { 
                $scope.element.url_slider_images = response.data.directory + '/'; 
                $scope.createOrUpdateElementService(action)
            })
        }
        if(action === 'Editar'){
            if($scope.directoryPath2 === 'images/designerAdmin'){
                $scope.createOrUpdateElementService(action)
            }else{
                $http.post('/conexiones/admin/get_folder_image', { 'dir': $scope.directoryPath2 }).then(function (response) {  
                    $scope.element.url_slider_images = response.data.directory + '/'; 
                    $scope.createOrUpdateElementService(action)
                })
            }
        }
    }
    $scope.createOrUpdateElementService = (action) => {
        console.log($scope.element.init_date)
        var data = new FormData();
        data.append('id',$scope.element.id);
        data.append('name',$scope.element.name);
        data.append('price',$scope.element.cost);
        data.append('quantity',$scope.element.quantity);
        data.append('url_image',$scope.cover);
        data.append('url_slider_images',$scope.element.url_slider_images);
        data.append('description',$scope.element.description);
        let date = $scope.element.init_date
        if($scope.element.init_date != null){
            let new_startDate= new Date($scope.element.init_date);
            let date = moment(new_startDate).format('YYYY-MM-DD');
        }
        data.append('init_date',date)
        data.append('arraySequenceMoment',JSON.stringify($scope.arraySequenceMoment));
        data.append('action',action );
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $scope.route+'/'+action,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function (response, xhr, request){
                if(response.status === 'successfull'){
                    $('#exampleModal').modal('hide');
                    $scope.element = {}
                    $scope.element.id = ''
                    $scope.cover = ''
                    $scope.arraySequenceMoment = []
                    $scope.sequenceSelected = null
                    $scope.momentSelected = null
                    $scope.table.ajax.reload()
                    $scope.swalfunction(response.message,"success")
                }else{
                    $scope.swalfunction(response.message,"warning")
                }
            },
            error: function (response, xhr, request) {
                $scope.swalfunction('Algo salio mal',"warning")
            }
        });
    }
    $scope.createOrUpdateKitService = (action) => {
        var data = new FormData();
        data.append('id',$scope.kit.id);
        data.append('name',$scope.kit.name);
        data.append('price',$scope.kit.cost);
        data.append('quantity',$scope.kit.quantity);
        data.append('url_image',$scope.cover);
        data.append('url_slider_images',$scope.kit.url_slider_images);
        data.append('description',$scope.kit.description);
        let date = $scope.kit.init_date
        if($scope.kit.init_date != null){
            let new_startDate= new Date($scope.kit.init_date);
            let date = moment(new_startDate).format('YYYY-MM-DD');
        }
        data.append('init_date',date)
        data.append('arraySequenceMoment',JSON.stringify($scope.arraySequenceMoment));
        data.append('elements',JSON.stringify($scope.elementsSelected));
        data.append('description',$scope.kit.description);
        data.append('action',action );
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $scope.route_kit+'/'+action,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function (response, xhr, request){
                if(response.status === 'successfull'){
                    $('#exampleModalKit').modal('hide');
                    $scope.kit = {}
                    $scope.kit.id = ''
                    $scope.cover = ''
                    $scope.arraySequenceMoment = []
                    $scope.sequenceSelected = null
                    $scope.momentSelected = null
                    $scope.elementsSelected = null
                    $scope.table.ajax.reload()
                    $scope.swalfunction(response.message,"success")
                }else{
                    $scope.swalfunction(response.message,"warning")
                }
            },
            error: function (response, xhr, request) {
                $scope.swalfunction('Algo salio mal',"warning")
            }
        });
    }
    $scope.swalfunction = (text,type) => {
        swal({
            text: text,
            type: type,
            showCancelButton: false,
            showConfirmButton: false
        }).catch(swal.noop);
    }

}]);


