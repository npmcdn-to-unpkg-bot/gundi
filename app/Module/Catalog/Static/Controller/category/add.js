CatalogApp.controller('CategoryAddCtrl', ['$scope', '$http', '$stateParams', 'toastr', '$location', function ($scope, $http, $stateParams, toastr, $location) {

    var sAdminUrl = Gundi.Setting['core.path'] + 'index.php/catalog';
    $scope.categories = [];
    $scope.category = {};
    $scope.isUpdate = false;

    $scope.index = function (){

        $http.get(sAdminUrl + '/categories/new.json', {params:{id:$stateParams.id}})
            .success(function (data, status, headers, config) {

                var categories = data.categories;
                for (var j = 0; j < categories.length; j++) {
                    $scope.categories.push(categories[j]);
                }
                if ($stateParams.id > 0){
                    $scope.isUpdate = true;
                    $scope.category = data.category;
                }else{
                    $scope.isUpdate = false;
                }
            });
    }

    $scope.add = function () {
        $http.post(sAdminUrl + '/categories.json', {category:$scope.category,secure_token:Gundi.token})
            .then(
                function (data, status, headers, config) {
                    toastr.success(data.data.response.message, 'Category successfully added');
                    $location.path('categories');
                },
                function (data, status) {
                    toastr.error(data.data.sErrorMessage, 'Couldn\'t add category');
                }
            );
    }

    $scope.update = function () {
        $http.put(sAdminUrl + '/categories/'+$stateParams.id+'/edit.json', {category:$scope.category,secure_token:Gundi.token})
            .then(
                function (data, status, headers, config) {
                    toastr.success('Category successfully updated');
                    $location.path('categories');
                },
                function (data, status) {
                    toastr.error(data.data.sErrorMessage, 'Couldn\'t update category');
                }
            );
    }

}]);
