// [TODO:]  push data easyload, 
// [Future] : url string for copy, refactor php string to php partial
// [Done:] image ration, animation child(by adding new animate control) not parent, and avata, take out editor angular render animation duration, button next prev, button background, pagination load more ,
// [Next learn:]  elementor widget custom build , wedget setting params.
// [Learned:] wrapper is contianer, without it , it will modifies selector every where so wrapper help specify selector
(function(angular,$) {
	var wpRul = wpDdminUrl.url;
	var myApp = angular.module("elementorApp", ['ngSanitize']);
	myApp.controller("postCtrl", [
		"$scope",
        "$http",
        "$q",
		function($scope, $http,$q) {
			angular.element();
			$scope.try = "Get Data";
			$scope.posts = { a: "stiha" };
			$scope.template = {};
			$scope.dateCon = function(date) {
				return new Date(date.replace(/\s/, 'T')+'Z');
            };

			$scope.getPost = function(options,disableState,nav) {
                if(disableState) return;
                // decliar global variable
                var elParent = document.querySelector("[data-id='" + options.id + "']")
                var elHeight = elParent.clientHeight - document.querySelector("[data-id='" + options.id + "'] nav").clientHeight ;
                var loaderWidth = elParent.clientWidth * 0.05
               
                function loadLoader (addPlace, element){
                    document.querySelector("[data-id='" + options.id + "'] " + element)
					.insertAdjacentHTML(
						addPlace,
                        "<div id='loader' style='height:" + elHeight + "px'>" + 
                        "  <div><img  width='" + loaderWidth + "px' src='" +wpDdminUrl.loaderUrl + "'/></div>" +
                        "</div>"
                    );

                }
                // load more
                if(options.pagination_type === 'load_more'){
                    loadLoader('beforebegin','button');
                    
                    $('html, body').animate({
                        scrollTop: $('#loader').offset().top - 100
                    },800);
                    if (!$scope["_" + options.id + '_class']) {
                        $scope["_" + options.id + '_class'] = {}
                        $scope["_" + options.id + '_class'].current = 2
                    } 
                    options.paged = $scope["_" + options.id + '_class'].current;
    
                    $http({
                        method: "POST",
                        url: wpRul + "admin-ajax.php",
                        params: {
                            action: "ajax_request",
                        },
                        data: options,
                    }).then(
                        function(re) {
                            $scope["_" + options.id + '_class'].current++;
                            if ($scope["_" + options.id + '_class'].current == options.max_page + 1) $scope["_" + options.id + '_class'].hide = true;
                            if (!$scope["_" + options.id]) {
                                $scope["_" + options.id]=[]; 
                                //$scope["_" + options.id].thumbnail = "fake";
                            } 
                            $scope["_" + options.id] = $scope["_" + options.id].concat(re.data);
                            document.getElementById("loader").remove();
                            
                        },
                        function() {}
                    );

                } 

                if(options.pagination_type === 'numbers'){
                    
				
                    // this line can not go on top or we can't calculate the hight
                    // remove element
                    document.querySelectorAll(".server-side__"+options.id).forEach(function(el) {
                        el.remove();
                    });
                    // var mycloen = $('.client-side__'+options.id).clone();
                    // document.querySelectorAll(".client-side__"+options.id).forEach(function(el) {
                    //     el.remove();
                    // });
                    $scope["_" + options.id] = [];
                    var deferred = $q.defer();
                    setTimeout(function() {
                        deferred.resolve($scope["_" + options.id + '_class'].data[options.paged]);
                    }, 300);
                    
                    // add loader to dom 
                    loadLoader('afterbegin','');
                    // on click assignment
                    // delair current_page scope if already defined dont define, just use it

                    if (!$scope["_" + options.id + '_class']) {
                        $scope["_" + options.id + '_class'] = {}
                        $scope["_" + options.id + '_class'].data = []
                        $scope["_" + options.id + '_class'].current = 1
                    } 
    
                    // this is for button prev and next scope.current
                    if( nav == 'prev') {
                        options.paged = $scope["_" + options.id + '_class'].current - 1;
                    }
                    if (nav == 'next') {
                        options.paged = $scope["_" + options.id + '_class'].current + 1
                    } 
                    $scope["_" + options.id + '_class'].current = options.paged;
                    
                    function loadData (data){
                        $scope["_" + options.id] = data;      
                        document.getElementById("loader").remove(); 
                        $('html, body').animate({
                            scrollTop: $(elParent).offset().top - 100
                        },800)
                    }
                    // if the data already fetch from server just load them
                    if($scope["_" + options.id + '_class'].data[options.paged]){
                        deferred.promise.then(function(re){
                            loadData(re)
                        })
                        return;
                    }

                    $http({
                        method: "POST",
                        url: wpRul + "admin-ajax.php",
                        params: {
                            action: "ajax_request",
                        },
                        data: options,
                    }).then(
                        function(re) {
                            $scope["_" + options.id + '_class'].data[options.paged] = re.data;
                            $scope["_" + options.id].thumbnail = "fake";
                            loadData(re.data)

                        },
                        function() {}
                    );
                }
                
            };

            loadMore = function(options){

            }
		},
	]);
})(angular,jQuery);
