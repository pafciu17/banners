/**
 * Created by pawel on 12/10/13.
 */
var conf = {
	apiUrl: '/api.php'
};

angular.module('bannerApp', ['ngRoute'])
.config(function($routeProvider, $locationProvider) {
	$routeProvider
	.when('/slots/', {
		templateUrl: 'view/slots.html',
		controller: 'Slots'
	})
	.when('/contents/', {
		templateUrl: 'view/contents.html',
		controller: 'Contents'
	})
	.otherwise({
		redirectTo: '/slots/'
	})
})

.controller('Slots', function($scope, Crud, Backend) {
	Crud.handle('slots', $scope);
	$scope.prepareBannerAssociationForm = function(slot) {
		$('#assignBannersDialog').modal('show');
		$scope.editedSlot = slot;

		var reload = function() {
			Backend.get({
				module: 'slots',
				action: 'getAssociatedBanners',
				slotId: slot.id
			})
			.then(function(response) {
				if (response.data.success) {
					$scope.associatedContents = response.data.associatedContents;
					$scope.unAssociatedContents = response.data.unAssociatedContents;
				}
			});
		}
		reload();
		$scope.removeContentFromSlot = function(content) {
			Backend.post({
				module: 'slots',
				action: 'removeAssociatedContent'
			}, {
				slot_id: slot.id,
				content_id: content.id
			})
			.then(function(response) {
				if (response.data.success) {
					reload();
				}
			});
		}
		$scope.assignContentToSlot = function() {
			Backend.post({
				module: 'slots',
				action: 'addContentToSlot'
			}, {
				slot_id: slot.id,
				content_id: $scope.contentToAdd
			})
			.then(function(response) {
				if (response.data.success) {
					reload();
				}
			});
		}
		$scope.closeAssignDialog = function() {
			$('#assignBannersDialog').modal('hide');
		}
	}
})
.controller('Contents', function($scope, Crud) {
	Crud.handle('contents', $scope);
})

.factory('Backend', function($http, $q) {
	var callbacks = {};
	var getPromise = function(response) {
		var deferred = $q.defer();
		deferred.resolve(response);
		return deferred.promise;
	}
	var preprocessRequest = function(response) {
		if (typeof(callbacks['preprocessor']) === 'function') {
			callbacks['preprocessor'](response);
		}
		return getPromise(response);
	}
	return {
		setCallback: function(name, cb) {
			callbacks[name] = cb;
		},
		get: function(params) {
			return $http.get(conf.apiUrl + '?' + $.param(params))
				.then(function(response){
					return preprocessRequest(response);
				});
		},
		post: function(params, data) {
			return $http.post(conf.apiUrl + '?' + $.param(params), data)
				.then(function(response){
					return preprocessRequest(response);
				});
		}
	};
})
.factory('Crud', function(Backend) {
	return {
		handle: function(module, scope, optionalParams) {
			var baseParams = optionalParams || {};
			baseParams.module = module;
			function getParams(params) {
				return angular.extend(params, baseParams);
			}
			//load list items data from backend
			scope.reload = function() {
				Backend.get(getParams({action: 'get'}))
					.then(function(response) {
						if (response.data.success) {
							scope.items = response.data.items;
						}
					});
			}
			scope.reload();

			var orginalItem;
			scope.prepareItemForm= function(item) {
				orginalItem = item;
				scope.formMode = 'edit';
				scope.editedItem = angular.copy(item);
				$('#editDialog').modal('show');
			};
			scope.showNewItemForm = function() {
				scope.editedItem = {};
				scope.formMode = 'new';
				$('#editDialog').modal('show');
			}
			scope.saveItem = function(item) {
				Backend.post(getParams({action: 'save'}), {item: item})
					.then(function(response) {
						if (response.data.success) {
							$('#editDialog').modal('hide');
							scope.reload();
						}
					});
			}
			scope.deleteItem = function(item) {
				bootbox.confirm("Are you sure you want to delete item?", function(result) {
					if (result) {
						console.log('result');
						Backend.post(getParams({action: 'delete'}), {id: item.id})
							.then(function(response) {
								if (response.data.success) {
									scope.reload();
								}
							});
					}
				});
			}
		}
	}
})

