(function (angular) {
    "use strict";
    angular.module('app')
           .service('IzSentinel', [
               '$q', 'IzAdminConfigService', '$http', '$mdDialog', '$mdMedia', '$rootScope',
               function ($q, IzAdminConfigService, $http, $mdDialog, $mdMedia, $rootScope) {
                   var vm = this;
                   var loginUrl = IzAdminConfigService.getConfig('base_url') + '/izcustomer/account';
                   var isLogged = false;
                   var userData = {};
                   /*
                    * Check logged in to server. And save data if true
                    */
                   this.authenticate = function () {
                       var defer = $q.defer();
                       $http.get(loginUrl + '/is-logged').then(function (res) {
                           if (res.data['logged'] == true) {
                               isLogged = true;
                               userData = res.data['user_data'];

                             // broadcast event when user logged
                             $rootScope.$broadcast('user_logged');

                               return defer.resolve(true);
                           } else
                               return defer.resolve(false);
                       });
                       return defer.promise;
                   };

                   /*
                    * Register account
                    */
                   this.register = function (userData) {
                       var defer = $q.defer();
                       $http.post(loginUrl + '/register', userData).then(function (res) {
                           if (res.data) {
                               isLogged = true;
                               userData = res.data;

                             // broadcast event when user logged
                             $rootScope.$broadcast('user_logged', userData);

                               return defer.resolve(true);
                           }
                       });
                       return defer.promise;
                   };

                   /*
                    * Login account
                    */
                   this.login = function (userData) {
                       var defer = $q.defer();
                       $http.post(loginUrl + '/login', userData).then(function (res) {
                           if (res.data['logged']) {
                               isLogged = true;
                               userData = res.data['user_data'];

                             // broadcast event when user logged
                             $rootScope.$broadcast('user_logged', userData);

                               return defer.resolve(userData);
                           }
                           else
                               return defer.reject(false);
                       },function (rej) {
                           return defer.reject(rej);
                       });
                       return defer.promise;
                   };

                   /*
                    * Logout account
                    * */
                   this.logout = function () {
                       var defer = $q.defer();
                       $http.get(loginUrl + '/logout').then(function (res) {
                           if (!res.data['logged']) {
                               isLogged = false;
                               userData = {};

                             // broadcast event when user logged
                             $rootScope.$broadcast('user_logout', userData);

                               return defer.resolve(false);
                           }
                       });
                       return defer.promise;
                   };

                   /*
                    * Retrieve current logged state
                    */
                   this.isLogged = function () {
                       return isLogged;
                   };

                   /*
                    * Retrieve user data if logged
                    * if not logged return false
                    */
                   this.getUserData = function () {
                       return isLogged ? userData : false;
                   };

                   /*
                    * Sử dụng sau khi lấy được data user từ server
                    * Set user data.
                    * If have user data will change state logged to true
                    * */
                   this.setUserData = function (data) {
                       isLogged = true;
                       userData = data;
                       return vm;
                   };
               }]);
})(angular);
