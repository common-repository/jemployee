$(document).ready(function() {

    /*-----------------------------------
    Navbar
    -----------------------------------*/
    $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
		var $el = $(this);
		var $parent = $(this).offsetParent(".dropdown-menu");
		if (!$(this).next().hasClass('show')) {
			$(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
		}
		var $subMenu = $(this).next(".dropdown-menu");
		$subMenu.toggleClass('show');
      
		$(this).parent("li").toggleClass('show');

		$(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
			$('.dropdown-menu .show').removeClass("show");
		});
		if (!$parent.parent().hasClass('navbar-nav')) {
			$el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});
		}
		return false;
    });

    if($(window).width() < 1200) {
		$(document).on('click', function(event) {
			var clickover = $(event.target);
			var _opened = $('#navbarSupportedContent').hasClass('show');
			if(_opened === true && !(clickover.is('.navbar-nav li, .navbar-nav .dropdown *'))) {
				$('button.navbar-toggler').trigger('click');
			}
		});
    }

    /*-------------------------------------
    Custom Nav
    -------------------------------------*/
    $('.notification-button').on('click', function(e) {
		e.preventDefault();
		$('.notification-card').toggleClass('show');
		$('.notification-card').fadeToggle();
    });
 
    $(document).on('click', function(event){
		var clickovr = $(event.target);
		var _open =$('.notification-card').hasClass('show');
		if(_open && !clickovr.is('.notification-button')) {
			$('.notification-button').trigger('click');
		}
    });

    $('.account-button').on('click', function(e) {
		e.preventDefault();
		$('.account-card').toggleClass('show');
		$('.account-card').fadeToggle();
    });

    $(document).on('click', function(event){
		var clickovrAcc = $(event.target);
		var _openAcc =$('.account-card').hasClass('show');
		if(_openAcc && !clickovrAcc.is('.account-button')) {
			$('.account-button').trigger('click');
		}
    });

    /*---------------------------------------------
      Photo Upload
    ---------------------------------------------*/

    $('.upload-portfolio-image .file-input').change(function(){
        var curElement = $(this).parent().parent().find('.image');
        var reader = new FileReader();

        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            curElement.attr('src', e.target.result);
        };

        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });


    /*-------------------------------------
    Bootstrap Select
    -------------------------------------*/

    $('.selectpicker').selectpicker({
		size: 10
    });

    /*-------------------------------------
    Owl Carousel
    -------------------------------------*/

    $('.company-carousel').owlCarousel({
		loop:true,
		autoplay: false,
		margin: 20,
		dots: false,
		nav:true,
		navText: ['<span class="ti-angle-left"></span>','<span class="ti-angle-right"></span>'],
		responsive:{
			0:{
				items:1
			},
			480:{
				items:2
			},
			768:{
				items:3
			},
			992: {
				items:4
			},
			1200:{
				items:5
			}
		}
    });

    $('.portfolio-slider').owlCarousel({
		loop:true,
		autoplay: false,
		margin: 20,
		dots: false,
		nav:true,
		navText: ['<span class="ti-angle-left"></span>','<span class="ti-angle-right"></span>'],
		responsive:{
			0:{
				items:1
			},
			480:{
				items:2
			},
			768:{
				items:3
			},
      }
    });

    /*-----------------------------------
    CountTo 
    -----------------------------------*/
    function animateCountTo(ct) {
		if ($.fn.visible && $(ct).visible() && !$(ct).hasClass('animated')) {
			$(ct).countTo({
				speed: 1000,
				refreshInterval: 1
			});
			$(ct).addClass('animated');
		}
    }

    function initCountTo() {
		var counter = $('.count');
		counter.each(function () {
			animateCountTo(this);
		});
    }

    initCountTo();

    /*----------------------------------------------
    Job Filter Result View
    -----------------------------------------------*/

    $('.job-view-controller .controller, .candidate-view-controller .controller, .employer-view-controller .controller').on('click', function() {
		$('.job-view-controller .controller, .candidate-view-controller .controller, .employer-view-controller .controller').removeClass('active');
		$(this).addClass('active');
    });

    $('.job-view-controller .list, .candidate-view-controller .list, .employer-view-controller .list').on('click', function() {
		$('.job-filter-result, .candidate-filter-result, .employer-filter-result').removeClass('grid');
		$('.job-filter-result .job-list, .candidate-filter-result .candidate, .employer-filter-result .employer').removeClass('half-grid');
    });
	
    $('.job-view-controller .grid, .candidate-view-controller .grid, .employer-view-controller .grid').on('click', function() {
		$('.job-filter-result, .candidate-filter-result, .employer-filter-result').addClass('grid');
		$('.job-filter-result .job-list, .candidate-filter-result .candidate, .employer-filter-result .employer').addClass('half-grid');
    });
    
    /*----------------------------------------------
    Payment Card
    -----------------------------------------------*/

    $('.payment-method a').on('click', function(e) {
		e.preventDefault();
		$('.payment-method a').removeClass('active');
		$(this).addClass('active');
    })

    /*----------------------------------------------
    Category Filter
    -----------------------------------------------*/

    $('.job-filter .option-title, .candidate-filter .option-title, .employer-filter .option-title').click(function (event) {
		var clickover = $(event.target);
		$(this).each(function() {
			$(this).toggleClass('compress');
			$(this).siblings('ul, .price-range-slider').slideToggle();
		})
    });

    // $('.job-filter a, .candidate-filter a, .employer-filter a').on('click', function(e) {
		// e.preventDefault();
		// var cls = $(this).parents(".job-filter, .candidate-filter, .employer-filter").data("id");
		// var innerContent = '<a href="#">' + $(this).data("attr") + '</a><span class="ti-close"></span>';
		// var filteredList = '<li class="' + cls + '">' + innerContent + '</li>';

		// $('.selected-options .filtered-options li.' + cls).remove();
		// $('.selected-options .filtered-options').append(filteredList);
    // });
  
    $(document).on('click', ".selected-options .filtered-options li span", function() {
		$(this).parent('li').remove();
    });

    $(document).on('click', ".selected-options .selection-title a", function(e) {
		e.preventDefault();
		$('.selected-options .filtered-options li').remove();
		$('.selected-options').slideUp();
    });

    $(document).on('click', ".job-filter li a, .candidate-filter li a, .employer-filter li a", function() {
		$('.selected-options').slideDown();
    });

    if($('.selected-options .filtered-options li').lenght > 0) {
		$('.selected-options').slideDown();
    }

    /*----------------------------------------
      Price Range
    ----------------------------------------*/

    function priceRange() {
      $('.nstSlider').nstSlider({
			"left_grip_selector": ".leftGrip",
			"right_grip_selector": ".rightGrip",
			"value_bar_selector": ".bar",
			"value_changed_callback": function (cause, leftValue, rightValue) {
				$(this).parent().find('.leftLabel').text(leftValue);
				$(this).parent().find('.rightLabel').text(rightValue);
				$(this).parent().find('.mi_s').val(leftValue);
				$(this).parent().find('.mx_s').val(rightValue);
			}
		});
    }

    priceRange();
    
    /*-------------------------------------
      Plyr Js  
    -------------------------------------*/
    plyr.setup();
    
    /*-------------------------------------
      progressBar  
      -------------------------------------*/
    function animateProgressBar(pb) {
        if ($.fn.visible && $(pb).visible() && !$(pb).hasClass('animated')) {
            $(pb).css('width', $(pb).attr('aria-valuenow') + '%');
            $(pb).addClass('animated');
        }
    }

    function initProgressBar() {
        var progressBar = $('.progress-bar');
        progressBar.each(function () {
            animateProgressBar(this);
        });
    }

    initProgressBar();


    /*-------------------------------------------
      Listing Sidebar Switch
    -------------------------------------------*/

    var listingContainer = '.listing-with-map .job-listing-container';
    var windowWidth = $(window).innerWidth();

    if(windowWidth > 1199) {
        $(listingContainer).css({
			'width': 1040,
        });

        $('.sidebar-controller .sidebar-switch').on('click', function() {
			if($(this).hasClass('on')) {
				$('.slim-footer').css('width', 760);
			} else {
				$('.slim-footer').css('width', 1040);
			}
        })

		} else {
			$(listingContainer).css({
				'width': windowWidth,
			});
		}

      $(document).on('click','.sidebar-switch', function() {
			$(this).toggleClass('on');
			if($(this).hasClass('on')){

			$('.sidebar-controller label span').text('Hide');

			$('.job-filter-wrapper').css({
				'display': 'block',
				'margin-left': 0,
				'width': '280px'
			});

			$('.filtered-job-listing-wrapper').css({
				'width': '760px'
			});

			$('.job-listing-container').css({
				'width': '1040px'
			});

			var listingContainerWidth = $('.listing-with-map .job-listing-container').innerWidth();
			var mapWidth = windowWidth - listingContainerWidth;

			$('.listing-side-map').width(mapWidth);

			} else {
				$('.sidebar-controller label span').text('Show');

				$('.job-filter-wrapper').css({
					'display': 'none',
					'margin-left': '-280px',
					'width': '0'
				});

				$('.filtered-job-listing-wrapper').css({
					'width': '100%'
				});

				$('.filtered-job-listing-wrapper').addClass('change-padding');

				$('.job-listing-container').css({
					'width': '760px'
				})

				var listingContainerWidth = $('.listing-with-map .job-listing-container').innerWidth();
				var mapWidth = windowWidth - listingContainerWidth;
				$('.listing-side-map').width(mapWidth);
			}
		});


    /*-------------------------------------------
    Slick Nav
    -------------------------------------------*/
    $('.testimonial-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      autoplay: false,
      asNavFor: '.testimonial-nav'
    });

    $('.testimonial-nav').slick({
      slidesToShow: 5,
      slidesToScroll: 2,
      asNavFor: '.testimonial-for',
      dots: false,
      autoplay: false,
      arrows: false,
      centerPadding: '10px',
      centerMode: false,
      focusOnSelect: true
    });

    /*-------------------------------------------
    Feather Icon
    -------------------------------------------*/

    feather.replace();

    /*-----------------------------------
    Back to Top
    -----------------------------------*/
    $('.back-to-top a').on('click', function() {
      $("html, body").animate({
        scrollTop: 0
      }, 600);
      return false;
    })

    /*-------------------------------------
      PRICING CONTROL 
    -------------------------------------*/

    $('.switch-wrap').on('click', function() {
      $(this).children('.price-switch, .switch').toggleClass('year-active');
      $('.duration-month').toggleClass('active');
      $('.duration-year').toggleClass('active');
      $('.monthly-rate').toggleClass('hidden');
      $('.yearly-rate').toggleClass('hidden');
    })



    /*-------------------------------------
    Window Scroll
    -------------------------------------*/
    $(window).on('scroll', function () {
      initProgressBar();
      initCountTo();
    });

    /*-------------------------------------
    Window Resize
    -------------------------------------*/

    $(window).on('resize orientationchange', function () {
        priceRange();
    });
	
	

});

function initAutocomplete(){
	const id = document.getElementById('map1');
	if(!id){
		return false;
	}
	var mapLat = 23.02130504843336;
	var mapLng = 91.413633;
	var mapLatd = parseFloat(document.getElementById('mapLat').value);
	var mapLngd = parseFloat(document.getElementById('mapLng').value);
	
	if(!isNaN(mapLatd)){
		mapLat = mapLatd;
	}
	if(!isNaN(mapLngd)){
		mapLng = mapLngd;
	}
	var map = new google.maps.Map(id, {
		center: {lat: mapLat, lng: mapLng},
		zoom: 10,
		mapTypeId: 'roadmap'
	});
	var marker12 = new google.maps.Marker({
		position: {lat: mapLat, lng: mapLng},
		map: map,
		title: 'Hello World!'
	});

	// Create the search box and link it to the UI element.
	var input = document.getElementById('pac-input');
	var searchBox = new google.maps.places.SearchBox(input);
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	// Bias the SearchBox results towards current map's viewport.
	map.addListener('bounds_changed', function() {
		searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	// Listen for the event fired when the user selects a prediction and retrieve
	// more details for that place.
	searchBox.addListener('places_changed', function() {
		var places = searchBox.getPlaces();
		//console.log(places);
		if (places.length == 0) {
			return;
		}

		// Clear out the old markers.
		markers.forEach(function(marker) {
			marker.setMap(null);
		});
		markers = [];

		// For each place, get the icon, name and location.
		var bounds = new google.maps.LatLngBounds();
		places.forEach(function(place) {
			if (!place.geometry) {
				console.log("Returned place contains no geometry");
				return;
			}
			var icon = {
				url: place.icon,
				size: new google.maps.Size(71, 71),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(17, 34),
				scaledSize: new google.maps.Size(25, 25)
			};

			// Create a marker for each place.
			markers.push(new google.maps.Marker({
				map: map,
				icon: icon,
				title: place.name,
				position: place.geometry.location
			}));
			var lat,lng;
			if (place.geometry.viewport) {
			  // Only geocodes have viewport.
				lat = place.geometry.location.lat();
				lng = place.geometry.location.lng();
				bounds.union(place.geometry.viewport);
			} else {
				lat = place.geometry.location.lat();
				lng = place.geometry.location.lng();
				bounds.extend(place.geometry.location);
			}
			document.getElementById("mapLat").value = lat.toFixed(4);
			document.getElementById("mapLng").value = lng.toFixed(4);
		});
		map.fitBounds(bounds);
	});
}

if(document.getElementById('searchmap') != null){
    L.HtmlIcon = L.Icon.extend({
		options: {
			/*
			html: (String) (required)
			iconAnchor: (Point)
			popupAnchor: (Point)
			*/
		},

		initialize: function (options) {
			L.Util.setOptions(this, options);
		},

		createIcon: function () {
			var div = document.createElement('div');
			div.innerHTML = this.options.html;
			if (div.classList)
				div.classList.add('leaflet-marker-icon');
			else
				div.className += ' ' + 'leaflet-marker-icon';
			return div;
		},

		createShadow: function () {
			return null;
		}
    });

	var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
    }),
    latlng = L.latLng(addressPoints[0][0], addressPoints[0][1]);

    var map = L.map('searchmap', {center: latlng, zoom: 13, layers: [tiles]});

    var markers = L.markerClusterGroup();
    var k = 1;
	var url = addressPoints.pop();
    for (var i = 0; i < addressPoints.length; i++){
		var a = addressPoints[i];
        
		if(k == 30){
			k = 1;
		}
		var markerHTML = new L.HtmlIcon({
			html : "<img class='leaflet-marker-icon leaflet-zoom-animated leaflet-interactive' src='"+url+"' alt='markericon' />", 
		});
		var title = a[2];
		var marker = L.marker(new L.LatLng(a[0], a[1]), {icon: markerHTML});
		marker.bindPopup(title, {offset: new L.Point(0, -170)});
		markers.addLayer(marker);
		k++;
    }
    map.addLayer(markers);
}





