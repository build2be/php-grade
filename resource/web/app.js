var PGradeApp = angular.module('PGradeApp', ['ngRoute', 'PGradeControllers']);

PGradeApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'app/partials/build-list.html',
        controller: 'PGradeBuildListCtrl'
      }).
      when('/build/:sha1', {
        templateUrl: 'app/partials/file-list.html',
        controller: 'PGradeFileListCtrl'
      }).
      when('/build/:build/:file', {
        templateUrl: 'app/partials/file.html',
        controller: 'PGradeFileCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);


var PGradeControllers = angular.module('PGradeControllers', ['ngSanitize']);


PGradeControllers.controller('PGradeBuildListCtrl', function ($scope, $http) {
    $http.get('output/builds.json').success(function(data) {
    $scope.builds = data;
  });

});

PGradeControllers.controller('PGradeFileListCtrl' , function ($scope,$http, $routeParams) {
  $scope.sha1 = $routeParams.sha1
$http.get('output/'+$scope.sha1+'/index.json').success(function(data) {
    $scope.files = data;
  });
});
PGradeControllers.controller('PGradeFileCtrl' , function ($scope,$http, $routeParams) {
  $scope.build = $routeParams.build
  $scope.filehash = $routeParams.file
$http.get('output/'+$scope.build+'/'+$scope.filehash+'.json').success(function(data) {
    $scope.file = data;
  });
});

