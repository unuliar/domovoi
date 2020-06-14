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
    $routeProvider.when('/meeting/:meeting_id/protocol/printable',
        {
            templateUrl: '/templates/meeting_protocol_printable.html',
            controller: 'MeetingProtocolController'
        });

    $routeProvider.when('/payments',
        {
            templateUrl: '/templates/payments.html',
            controller: 'PaymentsController'
        });

    $routeProvider.when('/requests',
        {
            templateUrl: '/templates/requests.html',
            controller: 'RequestsController'
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
            data: data
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

const appComponent = {
    template: `
    <star-rating-component
      select="$ctrl.onSelect($event)"
      rating="$ctrl.average">
    </star-rating-component>
    <pre>Average: {{$ctrl.average}}%</pre>
    <pre>Ratings: {{$ctrl.ratings|json}}</pre>
  `,
    transclude: true,
    controller: 'appComponentCtrl'
};

function appComponentCtrl() {
    this.ratings = [];
    this.onSelect = (value) => {
        this.ratings.push(value);
    };
    Object.defineProperties(this, {
            average: {
                get() {
                    return this.ratings.reduce(
                        function(p,c,i) {
                            return p+(c-p)/(i+1)
                        }, 0
                    );
                }
            }
        }
    )}

const starRatingComponent = {
    template: `
  	<div
    	class="star-rating"
      ng-class="{selectable: $ctrl.select}">
  		<div class="star-rating-top" style="width: {{$ctrl.rating}}%">
      	<span
          class="star"
        	ng-repeat="star in ::$ctrl.stars"
          ng-click="$ctrl.onSelect(star.value)">★</span>
      </div>
  		<div class="star-rating-bottom">
      	<span
          class="star"
        	ng-repeat="star in ::$ctrl.stars"
          ng-click="$ctrl.onSelect(star.value)">☆</span>
      </div>    
    </div>
  `,
    controller: 'starRatingComponentCtrl',
    bindings: {
        rating: '<',
        select: '&',
        range: '<'
    }
}

function starRatingComponentCtrl(starRangeFilter) {
    this.$onInit = function(){
        this.stars = starRangeFilter(this.range || 5);
    }
    this.onSelect = function(value) {
        if (!this.select) return false;
        this.select({$event: value});
    }
}

function starRange() {
    return function(range) {
        stars = []
        range = parseInt(range);
        const step = 100/range;
        for (var i=0; i<range; i++) {
            var count = i+1;
            stars.push({
                count: count,
                value: step*count
            });
        }
        return stars;
    };
}
app
    .component('appComponent', appComponent)
    .controller('appComponentCtrl', appComponentCtrl)
    .component('starRatingComponent', starRatingComponent)
    .controller('starRatingComponentCtrl', starRatingComponentCtrl)
    .filter('starRange', starRange);
