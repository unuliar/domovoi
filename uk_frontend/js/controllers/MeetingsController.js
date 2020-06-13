app.controller('MeetingsController', function ($scope, $rootScope, $cookies) {
    $rootScope.checkAuth();

    $rootScope.pageTitle = "Собрания";

    $scope.activeMeeting = null;

    $scope.meetings = [];

    $rootScope.setLoader(true);

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
    /**
     * Setting current letter to specified
     * @param id
     */
    $scope.setActiveMeeting = meeting => {
        $scope.meetings.forEach((meeting) => {
            meeting.classList = {};
        });

        meeting.classList = {"list_item_active": true};

        $scope.activeMeeting = meeting;
    };

    $scope.createMeeting = form => {
        $rootScope.setLoader(true);

        $rootScope.apiCall(
            'POST',
            'meeting/create',
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