CatalogApp.controller('ProductAddCtrl', ['$scope', '$http', '$stateParams', 'toastr', '$location', function ($scope, $http, $stateParams, toastr, $location) {
    var sAdminUrl = Gundi.Setting['core.path'] + 'index.php/catalog';
    $scope.categories = [];
    $scope.product = {};
    $scope.product.status = 'enable';
    $scope.isUpdate = false;

    $scope.index = function (){

        $http.get(sAdminUrl + '/products/new.json', {params:{id:$stateParams.id}})
            .success(function (data, status, headers, config) {

                var categories = data.categories;
                for (var i = 0; i < categories.length; i++) {
                    $scope.categories.push(categories[i]);
                }
                if ($stateParams.id > 0){
                    $scope.isUpdate = true;
                    $scope.product = data.product;
                }else{
                    $scope.isUpdate = false;
                }
            });
    }

    $scope.add = function () {
        $http.post(sAdminUrl + '/products.json', {product:$scope.product,secure_token:Gundi.token})
            .then(
                function (data, status, headers, config) {
                    toastr.success(data.data.response.message, 'Product successfully added');
                    $location.path('products');
                },
                function (data, status) {
                    toastr.error(data.data.sErrorMessage, 'Couldn\'t add product');
                }
            );
    }

    $scope.update = function () {
        $http.put(sAdminUrl + '/products/'+$stateParams.id+'/edit.json', {product:$scope.product,secure_token:Gundi.token})
            .then(
                function (data, status, headers, config) {
                    toastr.success('Product successfully updated');
                    $location.path('products');
                },
                function (data, status) {
                    toastr.error(data.data.sErrorMessage, 'Couldn\'t update product');
                }
            );
    }

}]);
