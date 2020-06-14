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
                result.data.org.respect_index =  result.data.org.respect_index*10;
                console.log(result.data.org.respect_index);
                $scope.currentOrg = result.data.org;

                $rootScope.setLoader(false);
            }
        }
    );

    $rootScope.pageTitle = "Управляющая компания";
});