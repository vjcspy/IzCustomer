/**
 * Created by vjcspy on 3/18/16.
 */
angular.module('stockboardApp')
       .service('facebookAuth', [
           '$http', '$q', 'urlManagement', 'IzSentinel',
           function ($http, $q, urlManagement, IzSentinel) {
               var _self = this;

               /*
                * Check status login facebook
                */
               this.checkStatus = function () {
                   var deferred = $q.defer();
                   FB.getLoginStatus(function (response) {
                       if (!response || response.error) {
                           deferred.reject('Error occured');
                       } else {
                           deferred.resolve(response);
                       }
                   });
                   return deferred.promise;
               };

               /*
                *  Check current token in server
                */
               this.checkLoggedFacebook = function () {
                   return $http.get(urlManagement.getUrl('facebook') + '/check-token');
               };

               /*
                * Login và extend token
                * Sẽ resolve 1 promise tiếp nếu connected vào facebook. Promise này làm nhiệm vụ connect đến app để login.
                * Sau khi login sẽ trả về data user
                * Mục đích là để làm giảm thời gian. Cho quá trình login vào app chạy ngầm
                */
               this.login = function () {
                   var deferred = $q.defer();
                   FB.login(function (response) {
                                return response.status ===
                                       'connected' ?
                                       deferred.resolve(loginIntoApp()) :
                                       response.status ===
                                       'not_authorized' ?
                                       deferred.reject('not_authorized') :
                                       deferred.reject('un_know');
                            },
                            {scope: 'public_profile,email,user_posts,publish_actions,read_page_mailboxes,manage_pages,publish_pages'});
                   return deferred.promise;
               };

               /*
                * Sau khi connected vào facebook thì đã lưu token key vào cookie
                * Gửi data cookie lên server để login bằng facebook và lấy data user sau khi login
                * Sau khi có data login thì save lại vào IzSentinel
                * */
               var loginIntoApp = function () {
                   var defer = $q.defer();
                   $http.post(urlManagement.getUrl('facebook') + '/login').then(function (response) {

                       // set user data get from facebook login
                       IzSentinel.setUserData(response.data);

                       return defer.resolve(response);
                   });
                   return defer.promise;
               }

           }]);
