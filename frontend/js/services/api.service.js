angular.module('schedulerApp')
    .service('ApiService', ['$http', function($http) {
        const API_URL = 'http://127.0.0.1:8005/api';

        function handleError(error) {
            console.error('API Error:', error);
            if (error.data && error.data.message) {
                return Promise.reject(error);
            }
            if (error.data && error.data.errors) {
                const messages = Object.values(error.data.errors).flat();
                return Promise.reject({
                    data: {
                        message: messages.join(', ')
                    }
                });
            }
            return Promise.reject({
                data: {
                    message: 'An error occurred while processing your request.'
                }
            });
        }

        this.getCarers = function() {
            let url = API_URL + '/carers';
            return $http.get(url).catch(handleError);
        };

        this.getClients = function() {
            let url = API_URL + '/clients';
            return $http.get(url).catch(handleError);
        };

        this.getShifts = function(page, perPage) {
            let url = API_URL + '/shifts';
            if (page || perPage) {
                url += '?';
                if (page) url += 'page=' + page + '&';
                if (perPage) url += 'per_page=' + perPage;
            }
            return $http.get(url).catch(handleError);
        };

        this.createShift = function(shift) {
            return $http.post(API_URL + '/shifts', shift)
                .catch(handleError);
        };

        this.updateShift = function(id, shift) {
            return $http.put(API_URL + '/shifts/' + id, shift)
                .catch(handleError);
        };

        this.deleteShift = function(id) {
            return $http.delete(API_URL + '/shifts/' + id)
                .catch(handleError);
        };
    }]); 