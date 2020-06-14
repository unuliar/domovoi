app.controller('RatingController', function ($scope, $rootScope) {
    $rootScope.checkAuth();

    $rootScope.pageTitle = "Коллективные письма в УК";

    $scope.organisations = [];

    $rootScope.setLoader(true);

    $rootScope.apiCall(
        'GET',
        'organisations/get',
        {},
        (result) => {
            $rootScope.setLoader(false);
            $scope.organisations = result.data.orgs;
        }
    );
});