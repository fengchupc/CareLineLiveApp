angular.module('schedulerApp', ['ngRoute'])
    .config(['$routeProvider', function($routeProvider) {
        $routeProvider
            .when('/', {
                redirectTo: '/shifts'
            })
            .when('/shifts', {
                templateUrl: 'views/shifts.html',
                controller: 'ShiftsController'
            })
            .otherwise({
                redirectTo: '/'
            });
    }]); 