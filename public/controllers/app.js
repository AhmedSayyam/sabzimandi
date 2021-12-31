var app = angular.module("myApp", ['ngRoute', 'datatables', 'pascalprecht.translate']);
// var app = angular.module("myApp", ['ngRoute']);
app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl: "public/views/admin/dashboard.html",
            controller: "dashboard_ctrl"
        })
        .when("/dashboard", {
            templateUrl: "public/views/admin/dashboard.html",
            controller: "dashboard_ctrl"
        })
        .when("/products", {
            templateUrl: "public/views/admin/products.html",
            controller: "products_ctrl"  
        })
        .when("/pos", {
            templateUrl: "public/views/admin/pos.html",
            controller: "pos_ctrl"  
        })
        .when("/package", {
            templateUrl: "public/views/admin/package.html",
            controller: "package_ctrl"
        })
        .when("/reports", {
            templateUrl: "public/views/admin/reports.html",
            controller: "reports_ctrl"
        })
        .when("/customers", {
            templateUrl: "public/views/admin/customers.html",
            controller: "customers_ctrl"
        }).
        otherwise({
            templateUrl: "public/views/admin/404.html"
        });;


});

app.run(['$rootScope', function($rootScope) {
    $rootScope.lang = 'en';
}])


app.config(["$translateProvider", function($translateProvider){

    $translateProvider.useStaticFilesLoader({
        prefix: 'public/locales/locale-',
        suffix: '.json'
    })
    .useSanitizeValueStrategy('sanitizeParameters')    
    .preferredLanguage('en');
}]);