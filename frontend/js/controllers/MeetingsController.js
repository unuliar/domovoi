app.controller('MeetingsController', function ($scope, $rootScope, $cookies) {
    $rootScope.checkAuth();

    $rootScope.pageTitle = "Собрания";

    $scope.activeMeeting = null;

    $scope.meetings = [
        {
            id: 1,
            title: "Очень дорого",
            date: "23 мая, 2020",
            text: "",
            user: {
                name: "Николай",
                last_name: "Иванов",
                email: "nikolai.ivanov@mail.ru",
                avatar: '/img/avatars/demo1.png'
            },
            signatories: [],
            attachments: [],
            classList: {}
        }];

    /**
     * Setting current letter to specified
     * @param id
     */
    $scope.setActiveMeeting = meeting => {
        $scope.meetings.forEach((letter) => {
            meeting.classList = {};
        });

        meeting.classList = {"list_item_active": true};

        $scope.activeMeeting = meeting;
    };
});