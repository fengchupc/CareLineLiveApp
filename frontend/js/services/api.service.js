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
            return $http.get(API_URL + '/carers')
                .catch(handleError);
        };

        this.createCarer = function(carer) {
            return $http.post(API_URL + '/carers', carer)
                .catch(handleError);
        };

        this.updateCarer = function(id, carer) {
            return $http.put(API_URL + '/carers/' + id, carer)
                .catch(handleError);
        };

        this.deleteCarer = function(id) {
            return $http.delete(API_URL + '/carers/' + id)
                .catch(handleError);
        };

        this.getClients = function() {
            return $http.get(API_URL + '/clients')
                .catch(handleError);
        };

        this.createClient = function(client) {
            return $http.post(API_URL + '/clients', client)
                .catch(handleError);
        };

        this.updateClient = function(id, client) {
            return $http.put(API_URL + '/clients/' + id, client)
                .catch(handleError);
        };

        this.deleteClient = function(id) {
            return $http.delete(API_URL + '/clients/' + id)
                .catch(handleError);
        };

        this.getShifts = function() {
            return $http.get(API_URL + '/shifts')
                .catch(handleError);
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