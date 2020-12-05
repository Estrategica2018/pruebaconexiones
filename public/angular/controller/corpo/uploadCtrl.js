MyApp.controller('FileUploadController', ['$scope', "$http", function ($scope, $http) {

    $scope.companyId = null;

    $scope.onChangeCompany = function () {

        for (var i = 0; i < $scope.companies.length; i++) {
            if ($scope.companies[i].id == $scope.companyId) {
                $scope.companyName = $scope.companies[i].name;
            }
        }

        $http.get("/get_company_sequences/" + $scope.companyId)
            .then(function (res) {
                $scope.sequences = res.data.companySequences;
            })

        $http.get("/get_company_groups/" + $scope.companyId)
            .then(function (res) {
                $scope.groups = res.data;
            })

        $http.get("/get_teachers_company/" + $scope.companyId)
            .then(function (res) {
                $scope.teachers = res.data;
            })
    }

    $scope.onChangeSecuence = function () {
        for (var i = 0; i < $scope.sequences.length; i++) {
            if ($scope.sequences[i].id == $scope.sequenceId) {
                $scope.sequenceName = $scope.sequences[i].name;
            }
        }
    }

    $scope.onChangeGroups = function () {
        for (var i = 0; i < $scope.groups.length; i++) {
            if ($scope.groups[i].id == $scope.groupId) {
                $scope.groupName = $scope.groups[i].name;
            }
        }
    }

    $scope.onChangeTeachers = function () {
        for (var i = 0; i < $scope.teachers.length; i++) {
            if ($scope.teachers[i].id == $scope.teacherId) {
                $scope.teacherName = $scope.teachers[i].name +' '+ $scope.teachers[i].last_name;
            }
        }
    }

    $http.get("/get_companies")
        .then(function (res) {
            $scope.companies = res.data.data;
        })

    
}]);


MyApp.controller('FileUploadLogsController', ['$scope', function ($scope) {
    $scope.showerror = function(fileid){
        $(fileid).toggleClass("d-none");
    } 
}]);
