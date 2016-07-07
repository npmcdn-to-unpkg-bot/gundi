CatalogApp.controller('CategoriesCtrl', ['$scope', '$http', 'toastr', function ($scope, $http, toastr) {

    var sAdminUrl = Gundi.Setting['core.path'] + 'index.php/catalog';
    var iCurrentPage = 1;
    var iLastPage = 1;

    $scope.categories = [];

    $scope.index = function (){

        if ($scope.scrollDisable) return;
        $scope.scrollDisable = true;
        if (iCurrentPage <= iLastPage){
            $http.get(sAdminUrl + '/categories.json?page=' + iCurrentPage)
                .success(function (data, status, headers, config) {
                    var categories = data.categories.data;

                    iLastPage = data.categories.last_page;
                    iCurrentPage = data.categories.current_page + 1;
                    for (var j = 0; j < categories.length; j++) {
                        $scope.categories.push(categories[j]);
                    }

                    $scope.scrollDisable = false;
                });
        }
    }


    $scope.delete = function (id) {
        $http.delete(sAdminUrl + '/categories/'+id+'.json')
            .then(
                function (data, status, headers, config) {
                    angular.element('#category_'+id).remove();
                    toastr.success('Category successfully deleted');
                },
                function (data, status) {
                    toastr.error(data.data.sErrorMessage, 'Couldn\'t delete category');
                }
            );
    }
}]);
