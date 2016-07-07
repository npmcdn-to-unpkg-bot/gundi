CatalogApp.controller('ProductsCtrl', ['$scope', '$http', 'toastr', function ($scope, $http, toastr) {

    var sAdminUrl = Gundi.Setting['core.path'] + 'index.php/catalog';
    var iCurrentPage = 1;
    var iLastPage = 1;

    $scope.products = [];

    $scope.index = function (sort){

        if (sort != false){
            iCurrentPage = 1;
            iLastPage = 1;
            $scope.scrollDisable = false;
        }
        if ($scope.scrollDisable) return;
        $scope.scrollDisable = true;

        if (iCurrentPage <= iLastPage){
            $http.get(sAdminUrl + '/products.json',{params:{
                page:iCurrentPage,
                sort:sort,
                find:angular.element('#find').val()
            }}).success(function (data, status, headers, config) {
                    $scope.products = [];
                    var products = data.products.data;

                    iLastPage = data.products.last_page;
                    iCurrentPage = data.products.current_page + 1;
                    for (var j = 0; j < products.length; j++) {
                        $scope.products.push(products[j]);
                    }
                    sort = false;
                    $scope.scrollDisable = false;
                });
        }
    }

    $scope.delete = function () {
        var willDeleteProducts = [];

        angular.forEach($scope.products, function (product) {
            if (product.selected == true){
                willDeleteProducts.push(product.id);
            }
        });
        $http.post(sAdminUrl + '/productMassDelete.json', {products:willDeleteProducts,secure_token:Gundi.token})
            .then(
                function (data, status, headers, config) {
                    for(var i=0; i<willDeleteProducts.length;i++){
                        angular.element('#product_'+willDeleteProducts[i]).remove();
                    }
                    toastr.success('Products successfully deleted');
                },
                function (data, status) {
                    if (willDeleteProducts.length > 0){
                        toastr.error(data.data.sErrorMessage, 'Couldn\'t delete products');
                    }else{
                        toastr.error('Choose product for delete');
                    }
                }
            );
    }

    $scope.checkAll = function(){
        angular.forEach($scope.products, function (product) {
            if (angular.element("#all_products:checked").length > 0) {
                product.selected = true;
            }else{
                product.selected = false;
            }
        });
    }

    $scope.enable = function (id) {
        $http.get(sAdminUrl + '/productEnable/' + id + '.json').then(
            function (data, status, headers, config) {
                for (var j = 0; j < $scope.products.length; j++) {
                    if ($scope.products[j]['id'] == id){
                        $scope.products[j]['status'] = 'enable';
                    }
                }
                toastr.success(data.data.message, 'Product successfully enabled');
            },
            function (data, status) {
                toastr.error(data.data.sErrorMessage, 'Can not set status of product');
            }
        );
    }

    $scope.disable = function (id) {
        $http.get(sAdminUrl + '/productDisable/' + id + '.json').then(
            function (data, status, headers, config) {
                for (var j = 0; j < $scope.products.length; j++) {
                    if ($scope.products[j]['id'] == id){
                        $scope.products[j]['status'] = 'disable';
                    }
                }
                toastr.success(data.data.message, 'Product successfully disabled');
            },
            function (data, status) {
                toastr.error(data.data.sErrorMessage, 'Can not set status of product');
            }
        );
    }
}]);
