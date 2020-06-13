app.controller('RequestsController', function ($scope, $rootScope, $cookies) {
    $rootScope.checkAuth();

    $rootScope.pageTitle = "Заявки на проведение работ";

    $scope.activeRequest = null;

    $scope.requests = [];

    $scope.setActiveRequest = request => {
        $scope.requests.forEach((request) => {
            request.classList = {};
        });

        request.classList = {"list_item_active": true};

        $scope.activeRequest = request;
    };

    $scope.createRequest = form => {
        $rootScope.setLoader(true);

        $rootScope.apiCall(
            'POST',
            'letter/create',
            {
                body: JSON.stringify(form)
            },
            (result) => {
                $rootScope.setLoader(false);

                $rootScope.apiCall(
                    'GET',
                    'meeting/getByUser',
                    {
                        token: $cookies.get('token')
                    },
                    (result) => {
                        $rootScope.setLoader(false);
                        console.log(result);
                        $scope.meetings = result.data.meetings;
                    }
                );
            }
        );
    };
});