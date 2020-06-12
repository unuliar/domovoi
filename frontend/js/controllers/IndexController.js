app.controller('IndexController', function ($scope, $rootScope, $cookies) {
    $rootScope.checkAuth();

    $rootScope.pageTitle = "Главная";
});