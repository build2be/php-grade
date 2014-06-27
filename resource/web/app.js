var PGradeApp = angular.module('PGradeApp', ['ngRoute', 'PGradeControllers']);

PGradeApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'app/partials/file-list.html',
        controller: 'PGradeFileListCtrl'
      }).
      when('/file/:file', {
        templateUrl: 'app/partials/file.html',
        controller: 'PGradeFileCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);


var PGradeControllers = angular.module('PGradeControllers', ['ngSanitize']);


PGradeControllers.controller('PGradeFileListCtrl' , function ($scope,$http, $routeParams) {
  $scope.sha1 = $routeParams.sha1
$http.get('data/index.json').success(function(data) {
    $scope.files = data;
  });
});
PGradeControllers.controller('PGradeFileCtrl' , function ($scope,$http, $routeParams) {
  $scope.filehash = $routeParams.file
$http.get('data/'+$scope.filehash).success(function(data) {
    $scope.file = data;
  });
});

