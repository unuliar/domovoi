app.controller('OrganizationController', function ($scope, $rootScope) {
    $scope.currentOrg = null;

    $rootScope.apiCall(
        'GET',
        'organisation/getByToken',
        {},
        (result) => {
            if(result.data.status === 'ok') {
                $scope.currentOrg = result.data.org;
            }
        }
    );

    $rootScope.pageTitle = "Управляющая компания";
});