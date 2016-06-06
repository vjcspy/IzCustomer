(function (angular) {
    angular.module('app')
           .service('izFacebookApi', [
               '$http', 'urlManagement',
               function ($http, urlManagement) {
                   "use strict";
                   var _self = this;

                   this.postLink = function (link, message) {
                       return $http.post(urlManagement.getUrl('facebook-api') + '/post-link', {
                           link: link,
                           message: message
                       });
                   };

               }]);
})(angular);