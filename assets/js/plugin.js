// [TODO:]  animation and image ratio  does not live view, 
// [Future] : url string for copy, refactor php string to php partial
// [Done:]  image ration, animation child(by adding new animate control) not parent, and avata, 
//          take out editor angular render animation duration, button next prev, button background, 
//          pagination load more , push data easyload, remove elementor-pro name, excerpt , and pss rename,
//          and add git-hup to my plugin author, loader does not load for second time because elhEight is too big to view
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
                var viewHeight = $(window).height() ;
                var loaderWidth = elParent.clientWidth * 0.05
                console.log(viewHeight, elHeight)
                var loaderHeight = (viewHeight < elHeight ) ? viewHeight : elHeight ;
               
                function loadLoader (addPlace, element){
                    document.querySelector("[data-id='" + options.id + "'] " + element)
					.insertAdjacentHTML(
						addPlace,
                        "<div id='loader' style='height:" + loaderHeight + "px'>" + 
                        "  <div><img  width='" + loaderWidth + "px' src='" +wpDdminUrl.loaderUrl + "'/></div>" +
                        "</div>"
                    );

                }

                /**
                 * load more funtion 
                 * @param :  pagintaion_type
                 */

                if(options.pagination_type === 'load_more'){
                    // before loader we open a space with a clone height
                    
                    loadLoader('beforebegin','nav');
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
                            // console.log(re.data)
                            $scope["_" + options.id + '_class'].current++;
                            if ($scope["_" + options.id + '_class'].current == options.max_page + 1) $scope["_" + options.id + '_class'].hide = true;
                            if (!$scope["_" + options.id]) {
                                $scope["_" + options.id]=[]; 
                            } 
                            $scope["_" + options.id] = $scope["_" + options.id].concat(re.data);
                            document.getElementById("loader").remove();
                        },
                        function() {}
                    );

                } 

                /**
                 * nav button number funtion 
                 * @param :  pagintaion_type
                 */

                if(options.pagination_type === 'numbers'){
                    
				
                    // this line can not go on top or we can't calculate the hight
                    // remove element once when the element is from serverside but for angular just replace the data  
                    document.querySelectorAll(".server-side__"+options.id).forEach(function(el) {
                        el.remove();
                    });

                    $scope["_" + options.id] = [];
                    // add loader to dom 
                    loadLoader('afterbegin','');
                    $('html, body').animate({
                        scrollTop: $('#loader').offset().top - 100
                    },800)
                    // fake ajax request for aniamtion!
                    var deferred = $q.defer();
                    setTimeout(function() {
                        deferred.resolve($scope["_" + options.id + '_class'].data[options.paged]);
                    }, 300);
                    
                    // on click assignment
                    // delair current_page scope if already defined dont define, just use it

                    if (!$scope["_" + options.id + '_class']) {
                        $scope["_" + options.id + '_class'] = {}
                        $scope["_" + options.id + '_class'].data = []
                        $scope["_" + options.id + '_class'].current = 1
                    } 
    
                    // this is for button prev and next scope.current for active button
                    if( nav == 'prev') {
                        options.paged = $scope["_" + options.id + '_class'].current - 1;
                    }
                    if (nav == 'next') {
                        options.paged = $scope["_" + options.id + '_class'].current + 1
                    } 
                    $scope["_" + options.id + '_class'].current = options.paged;
                    
                    function loadData (data){   
                        document.getElementById("loader").remove();  
                        $scope["_" + options.id] = data; 
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
                            // console.log(re.data)
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
