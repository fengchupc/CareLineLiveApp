angular.module('schedulerApp')
    .controller('ShiftsController', ['$scope', 'ApiService', function($scope, ApiService) {
        $scope.shifts = [];
        $scope.carers = [];
        $scope.clients = [];
        $scope.newShift = {};
        $scope.editingShift = null;
        $scope.errorMessage = '';
        $scope.successMessage = '';
        $scope.currentPage = 1;
        $scope.perPage = 10;
        $scope.totalPages = 1;
        $scope.totalItems = 0;

        function loadAll(page) {
            page = page || $scope.currentPage;
            ApiService.getShifts(page, $scope.perPage)
                .then(function(response) {
                    $scope.shifts = response.data.data;
                    $scope.currentPage = response.data.pagination.current_page;
                    $scope.totalPages = response.data.pagination.last_page;
                    $scope.totalItems = response.data.pagination.total;
                })
                .catch(function(error) {
                    $scope.errorMessage = 'Error loading shifts: ' + (error.data?.message || 'Unknown error');
                });

            ApiService.getCarers()
                .then(function(response) {
                    $scope.carers = response.data.data || response.data;
                })
                .catch(function(error) {
                    $scope.errorMessage = 'Error loading carers: ' + (error.data?.message || 'Unknown error');
                });

            ApiService.getClients()
                .then(function(response) {
                    $scope.clients = response.data.data || response.data;
                })
                .catch(function(error) {
                    $scope.errorMessage = 'Error loading clients: ' + (error.data?.message || 'Unknown error');
                });
        }

        function showSuccess(message) {
            $scope.successMessage = message;
            $scope.errorMessage = '';
            setTimeout(function() {
                $scope.$apply(function() {
                    $scope.successMessage = '';
                });
            }, 3000);
        }

        function showError(message) {
            $scope.errorMessage = message;
            $scope.successMessage = '';
            setTimeout(function() {
                $scope.$apply(function() {
                    $scope.errorMessage = '';
                });
            }, 5000);
        }

        $scope.createShift = function() {
            if (!$scope.newShift.carer_id || !$scope.newShift.client_id || !$scope.newShift.start_time || !$scope.newShift.end_time) {
                showError('Please fill in all required fields');
                return;
            }

            ApiService.createShift($scope.newShift)
                .then(function(response) {
                    $scope.newShift = {};
                    var modal = document.getElementById('createShiftModal');
                    var modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                    modalInstance.hide();
                    showSuccess('Shift created successfully');
                    ApiService.getShifts(1, $scope.perPage).then(function(res) {
                        var total = res.data.pagination.total;
                        var perPage = res.data.pagination.per_page;
                        var lastPage = Math.ceil(total / perPage);
                        $scope.goToPage(lastPage);
                    });
                })
                .catch(function(error) {
                    showError('Error creating shift: ' + (error.data?.message || 'Unknown error'));
                });
        };

        $scope.startEditing = function(shift) {
            $scope.editingShift = {
                id: shift.id,
                carer_id: (shift.carer_id || (shift.carer && shift.carer.id)) + '',
                client_id: (shift.client_id || (shift.client && shift.client.id)) + '',
                start_time: shift.start_time ? new Date(shift.start_time) : null,
                end_time: shift.end_time ? new Date(shift.end_time) : null,
                notes: shift.notes || ''
            };
        };

        $scope.updateShift = function() {
            if (!$scope.editingShift.carer_id || !$scope.editingShift.client_id || !$scope.editingShift.start_time || !$scope.editingShift.end_time) {
                showError('Please fill in all required fields');
                return;
            }

            ApiService.updateShift($scope.editingShift.id, $scope.editingShift)
                .then(function(response) {
                    const index = $scope.shifts.findIndex(s => s.id === response.data.id);
                    $scope.shifts[index] = response.data;
                    $scope.editingShift = null;
                    var modal = document.getElementById('editShiftModal');
                    var modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                    modalInstance.hide();
                    showSuccess('Shift updated successfully');
                })
                .catch(function(error) {
                    showError('Error updating shift: ' + (error.data?.message || 'Unknown error'));
                });
        };

        $scope.deleteShift = function(id) {
            if (confirm('Are you sure you want to delete this shift?')) {
                ApiService.deleteShift(id)
                    .then(function() {
                        if ($scope.shifts.length === 1 && $scope.currentPage > 1) {
                            $scope.goToPage($scope.currentPage - 1);
                        } else {
                            $scope.goToPage($scope.currentPage);
                        }
                        showSuccess('Shift deleted successfully');
                    })
                    .catch(function(error) {
                        showError('Error deleting shift: ' + (error.data?.message || 'Unknown error'));
                    });
            }
        };

        $scope.formatDateTime = function(dateString) {
            return new Date(dateString).toLocaleString();
        };

        $scope.goToPage = function(page) {
            if (page >= 1 && page <= $scope.totalPages) {
                loadAll(page);
            }
        };
        $scope.nextPage = function() {
            if ($scope.currentPage < $scope.totalPages) {
                loadAll($scope.currentPage + 1);
            }
        };
        $scope.prevPage = function() {
            if ($scope.currentPage > 1) {
                loadAll($scope.currentPage - 1);
            }
        };

        loadAll();
    }]); 