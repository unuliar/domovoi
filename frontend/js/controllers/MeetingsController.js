app.controller('MeetingsController', function ($scope, $rootScope, $cookies) {
    $rootScope.checkAuth();

    $rootScope.pageTitle = "Собрания";

    $scope.activeMeeting = null;

    $scope.meetings = [];

    const getMeetings = () => {
        $rootScope.setLoader(true);

        $rootScope.apiCall(
            'GET',
            'meeting/getAllByOrg',
            {
                org: $rootScope.currentUser.ownings[0].house.org.id
            },
            (result) => {
                $rootScope.setLoader(false);
                console.log(result);
                $scope.meetings = result.data.meetings;
            }
        );
    };

    if($rootScope.currentUser.ownings != undefined) {
        getMeetings();
    } else {
        setTimeout(getMeetings, 1000);
    }



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
});