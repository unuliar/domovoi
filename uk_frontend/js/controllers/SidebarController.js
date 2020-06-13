app.controller('SidebarController', function ($scope, $rootScope, $cookies) {
    $rootScope.setLoader(true);

    $rootScope.apiCall(
        'GET',
        'user/getByToken',
        {},
        (result) => {
            if(result.data.status === 'ok') {
                $rootScope.currentUser = result.data.org;
                $rootScope.setLoader(false);
            }
        }
    );

    $scope.logout = () => {
          $cookies.remove('token');

          window.location.href = 'http://' + $rootScope.host + ':80/login.html';
    };
});