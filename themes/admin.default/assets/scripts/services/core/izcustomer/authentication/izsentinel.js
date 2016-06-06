/**
 * Created by vjcspy on 19/05/2016.
 */
(function (angular) {
    "use strict";
    angular.module('stockboardApp')
           .service('IzSentinel', [
               '$q', 'urlManagement', '$http', '$mdDialog', '$mdMedia', '$rootScope',
               function ($q, urlManagement, $http, $mdDialog, $mdMedia, $rootScope) {
                   var vm = this;
                   var loginUrl = urlManagement.getUrl('auth_account');
                   var isLogged = false;
                   var userData = {};
                   var customFullscreen = $mdMedia('xs') || $mdMedia('sm');
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
                           if (res.data.logged) {
                               isLogged = true;
                               userData = res.data['user_data'];

                             // broadcast event when user logged
                             $rootScope.$broadcast('user_logged', userData);

                               return defer.resolve(userData);
                           }
                       });
                       return defer.promise;
                   };

                   /*
                    * Logout account
                    * */
                   this.logout = function () {
                       var defer = $q.defer();
                       $http.get(loginUrl + '/logout').then(function (res) {
                           if (!res.data.logged) {
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

                   this.openUserForm = function (ev) {
                       var useFullScreen = ($mdMedia('sm') || $mdMedia('xs')) && customFullscreen;
                       $mdDialog.show({
                                          controller: 'UserDialogController',
                                          templateUrl: './views/dialog/userForm.html',
                                          parent: angular.element(document.body),
                                          targetEvent: ev,
                                          clickOutsideToClose: true,
                                          fullscreen: useFullScreen
                                      })
                                .then(function (answer) {
                                    console.log('You said the information was "' + answer + '".');
                                }, function () {
                                    console.log('You cancelled the dialog.');
                                });
                       $rootScope.$watch(function () {
                           return $mdMedia('xs') || $mdMedia('sm');
                       }, function (wantsFullScreen) {
                           customFullscreen = (wantsFullScreen === true);
                       });
                   };

               }]);
})(angular);
