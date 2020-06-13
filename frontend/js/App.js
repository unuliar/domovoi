var app = angular.module('domovoi-app', ['ngRoute', 'ngCookies']).config(function ($routeProvider) {
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

    $routeProvider.when('/index',
        {
            templateUrl: '/templates/main.html',
            controller: 'IndexController'
        });

    $routeProvider.when('/meeting/:meeting_id',
        {
            templateUrl: '/templates/meeting_room.html',
        });

    $routeProvider.when('/meetings',
        {
            templateUrl: '/templates/meetings.html',
            controller: 'MeetingsController'
        });

    $routeProvider.otherwise({redirectTo: '/index'});
});

app.run(function ($rootScope, $http, $cookies) {
    /**
     * Path to API server
     *
     * @type {string}
     */
    $rootScope.host = '35.224.154.143';

    $rootScope.api_path = `http://${$rootScope.host}:81/api`;

    $rootScope.pageTitle = "Главная";
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
        }).then(onSuccess).then((error) => console.log(error))
    };

    /**
     * Calling API
     *
     * @param url
     * @param data
     * @param onSuccess
     */
    $rootScope.apiPostCall = ( url, data, onSuccess) => {
        const token = $cookies.get('token');
        data["token"] = token;
        console.log(JSON.stringify(data));
        $http({
            method: "POST",
            url: `${$rootScope.api_path}/${url}?token=${token}`,
            body: JSON.stringify(data)
        }).then(onSuccess).then((error) => console.log(error))
    };


    /**
     * Checking authorization state
     */
    $rootScope.checkAuth = () => {
        const token = $cookies.get('token');

        if (token !== undefined) {

        } else {
            window.location.href = 'login.html';
        }
    };

    $rootScope.setLoader = (state) => {
        $rootScope.loaderState = state;
    }

    $rootScope.formatDate = date => {
        const ruMoment = moment(date).locale('ru');
        return ruMoment.format('LLL');
    };
});