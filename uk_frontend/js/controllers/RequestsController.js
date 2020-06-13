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

    $scope.assignWorker = () => {
        $rootScope.apiCall(
            'POST',
            'letter/assignWorker',
            {
                body: JSON.stringify({
                    worker: 3,
                    letter: $scope.activeRequest.id
                })
            },
            (result) => {
                $rootScope.setLoader(false);

                Swal.fire({
                    title: 'Успешно!',
                    text: 'Исполнитель успешно назначен',
                    type:'success',
                    icon:'success'
                }).then((result) => {
                    window.location.reload()
                })
            }
        );
    };

    $scope.changeStatus = form => {
        $rootScope.apiCall(
            'POST',
            'letter/changeStatus',
            {
                body: JSON.stringify({
                    status: form.status,
                    letter: $scope.activeRequest.id
                })
            },
            (result) => {
                $rootScope.setLoader(false);

                Swal.fire({
                    title: 'Успешно!',
                    text: 'Статус успешно изменен',
                    type:'success',
                    icon:'success'
                }).then((result) => {
                    window.location.reload()
                })
            }
        );
    };
});