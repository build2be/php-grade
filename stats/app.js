var PGradeApp = angular.module('PGradeApp', ['ngRoute', 'PGradeControllers']);

PGradeApp.config(['$routeProvider',
    function ($routeProvider) {
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


PGradeControllers.controller('PGradeFileListCtrl', function ($scope, $http, $routeParams) {
    $scope.sha1 = $routeParams.sha1;
    $scope.history = [];
    $http.get('data/index.json').success(function (data) {
        $scope.files = data;
    });
    $http.get('data/history.json').success(function (hist) {
        for(var idx=0; idx < hist.length; idx++){
            for(var fIdx=0; fIdx < hist[idx]['files'].length; fIdx++){
                if($scope.history[hist[idx]['files'][fIdx].filename] == undefined){
                    $scope.history[hist[idx]['files'][fIdx].filename] = [hist[idx]['files'][fIdx].messages];
                }else{
                    $scope.history[hist[idx]['files'][fIdx].filename].push(hist[idx]['files'][fIdx].messages);
                }
            }
        }
    });

    $scope.historyDelta = function(history){
        if(history == undefined){
            return '';
        }
        var len = history.length;
        if(len < 2){
            return '';
        }else{
            var newest = history[len - 1];
            var older = history[len - 2];

            var newest_total = newest['info'] + newest['error'] + newest['warning'];
            var older_total = older['info'] + older['error'] + older['warning'];
            var delta = newest_total - older_total;

            if(delta == 0){
                return '';
            }else{
                if(delta > 0){
                    return '<span class="count-block text-danger">' +
                           '<span class="glyphicon glyphicon-chevron-down"> ' + delta +
                           '</span>';
                }else{
                    return '<span class="count-block text-success">' +
                    '<span class="glyphicon glyphicon-chevron-up"> ' + delta +
                    '</span>';
                }
            }
        }
    };
});
PGradeControllers.controller('PGradeFileCtrl', function ($scope, $http, $routeParams) {
    $scope.filehash = $routeParams.file
    $http.get('data/' + $scope.filehash).success(function (data) {
        var state = undefined;
        for(var i=0; i < data.lines.length; i++){
            var code = hljs.highlight('php', data.lines[i].line, true, state);
            state = code.top;
            data.lines[i].line = code.value;
        }
        $scope.file = data;

    });

});

