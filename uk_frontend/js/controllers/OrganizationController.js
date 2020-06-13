app.controller('OrganizationController', function ($scope, $rootScope) {
    $rootScope.checkAuth();

    $scope.currentOrg = null;

    $rootScope.setLoader(true);

    $rootScope.apiCall(
        'GET',
        'organisation/getByToken',
        {},
        (result) => {
            if(result.data.status === 'ok') {
                $scope.currentOrg = result.data.org;
                $rootScope.setLoader(false);
            }
        }
    );

    $rootScope.pageTitle = "Управляющая компания";
});