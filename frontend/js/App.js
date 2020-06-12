var app = angular.module('domovoi-app', ['ngRoute','ngCookies']).config(function($routeProvider) {
    $routeProvider.when('/letters',
        {
            templateUrl: '/templates/letters.html',
            controller: 'LetterController'
        });

    $routeProvider.when('/organizations',
        {
            templateUrl: '/templates/organizations.html',
            controller: 'OrganizationController'
        });
});

app.run(function ($rootScope, $http, $cookies) {
    /**
     * Path to API server
     *
     * @type {string}
     */
    $rootScope.api_path = 'http://localhost:81/api';

    /**
     * Current dialog
     *
     * @type {{}}
     */
    $rootScope.currentUser = {
        name: "Николай",
        last_name: "Иванов",
        email: "nikolai.ivanov@mail.ru",
        avatar: '/img/avatars/demo1.png'
    };

    $rootScope.pageTitle = "Главная";

    $rootScope.loaderState = false;

    /**
     * Calling API
     *
     * @param method
     * @param url
     * @param data
     * @param onSuccess
     */
    $rootScope.apiCall = (method, url, data, onSuccess) => {
        const token = $cookies.get('token');
        $http({
            method: method,
            url: `${$rootScope.api_path}/${url}?token=${token}`,
            params: data
        }).then(onSuccess)
    };

    /**
     * Checking authorization state
     */
    $rootScope.checkAuth = () => {
        const token = $cookies.get('token');

        if(token !== undefined) {
            $rootScope.apiCall(
                'POST',
                'auth/check-token',
                {
                    token: token
                },
                (result) => {
                    if (!result.data.status) {
                        window.location.href = 'login.html';

                        return 0;
                    }
                }
            );
        } else {
            window.location.href = 'login.html';
        }
    };

    $rootScope.setLoader = (state) => {
        $rootScope.loaderState = state;
    }
});