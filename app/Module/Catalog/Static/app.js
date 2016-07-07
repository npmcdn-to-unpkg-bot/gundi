var CatalogApp = angular.module('GundiCatalog', [
    'ui.router',
    'toastr',
    'infinite-scroll',
    'chieffancypants.loadingBar',
    'ui.bootstrap'
]);


CatalogApp.config(['$stateProvider', '$urlRouterProvider', '$httpProvider', function ($stateProvider, $urlRouterProvider, $httpProvider) {

    $urlRouterProvider.otherwise('/');
    var routes = new Array();
    routes.push(
        {
            'state':'products',
            'url':'/',
            'templateUrl':Gundi.Setting['core.path'] + 'app/Module/Catalog/Static/View/product/products.html',
            'controller':'ProductsCtrl'
        },
        {
            'state':'product-add',
            'url':'/product-add/:id',
            'templateUrl':Gundi.Setting['core.path'] + 'app/Module/Catalog/Static/View/product/add.html',
            'controller':'ProductAddCtrl'
        },
        {
            'state':'categories',
            'url':'/categories',
            'templateUrl':Gundi.Setting['core.path'] + 'app/Module/Catalog/Static/View/category/categories.html',
            'controller':'CategoriesCtrl'
        },
        {
            'state':'category-add',
            'url':'/category-add/:id',
            'templateUrl':Gundi.Setting['core.path'] + 'app/Module/Catalog/Static/View/category/add.html',
            'controller':'CategoryAddCtrl'
        }
    );
    for(var i=0; i<routes.length; i++){
        $stateProvider.state(
            routes[i]['state'],
            {
                url: routes[i]['url'],
                templateUrl: routes[i]['templateUrl'],
                controller: routes[i]['controller']
            }
        );
    }
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    $httpProvider.defaults.transformResponse = [function(data, headers){

        if (angular.isString(data)) {
            // Strip json vulnerability protection prefix and trim whitespace
            var tempData = data.replace(angular.JSON_PROTECTION_PREFIX, '').trim();
            if (tempData) {
                function isJson(tempData){
                    var JSON_START = /^\[|^\{(?!\{)/;
                    var JSON_ENDS = {
                        '[': /]$/,
                        '{': /}$/
                    };
                    var jsonStart = tempData.match(JSON_START);
                    return jsonStart && JSON_ENDS[jsonStart[0]].test(tempData);
                }
                var contentType = headers('Content-Type');
                if ((contentType && (contentType.indexOf(angular.APPLICATION_JSON) === 0)) || isJson(tempData)) {
                    data = angular.fromJson(tempData);
                    Gundi['token'] = data.meta.token;
                }
            }
        }

        return data;
    }];
    $httpProvider.defaults.transformRequest = [function(data)
    {
        /**
         * рабочая лошадка; преобразует объект в x-www-form-urlencoded строку.
         * @param {Object} obj
         * @return {String}
         */
        var param = function(obj)
        {
            var query = '';
            var name, value, fullSubName, subValue, innerObj, i;

            for(name in obj)
            {
                value = obj[name];

                if(value instanceof Array)
                {
                    for(i=0; i<value.length; ++i)
                    {
                        subValue = value[i];
                        fullSubName = name + '[' + i + ']';
                        innerObj = {};
                        innerObj[fullSubName] = subValue;
                        query += param(innerObj) + '&';
                    }
                }
                else if(value instanceof Object)
                {
                    for(subName in value)
                    {
                        subValue = value[subName];
                        fullSubName = name + '[' + subName + ']';
                        innerObj = {};
                        innerObj[fullSubName] = subValue;
                        query += param(innerObj) + '&';
                    }
                }
                else if(value !== undefined && value !== null)
                {
                    query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
                }
            }

            return query.length ? query.substr(0, query.length - 1) : query;
        };

        return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
    }];
}]);