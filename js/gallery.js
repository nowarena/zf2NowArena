$(document).ready(function(){

//console.log("width : " + $(window).width());
//console.log("screen width: " + screen.width);
	
	// num thumbs to show in nav
	// thumbs are 100px wide, try and calc num thumbs to show based on window width
	monthlyNavThumbLimit = (Math.floor(($(window).width())/100)) - 1;
	width = 100 * monthlyNavThumbLimit + 70;
	//$(".monthlyNavCont").css("width", width);
	//$(".links").css("width", width);
	actualWidth = $(window).width();
	// galleryImageContainer: 100 wide + 1px margin right + 2px margin left + 1 px border left + 1 px border right = 105
	galleryTableWidth = (Math.floor(actualWidth/105)) * 105;
	//console.log("r:"+galleryTableWidth);
	//$(".galleryTable").css("width", galleryTableWidth);
	//$(".galleryTable").css("margin", "0px auto");

	// hide the previous aka left nav link when starting at position 0
	function updateNavLinkVisibility(offset, id) {

		if (offset == 0) {
			$(id).css("visibility", "hidden");
		} else {
			$(id).css("visibility", "visible");
		}
	
	}
	
	$(document).on("click", '.galleryImageNavLink', function() {
		dateYMon = $(this).data('dateymon');
		page = $(this).data('page');
		frompage = $(this).data('frompage');
		if (typeof frompage == 'undefined') {
			frompage = 0;
		}
		direction = $(this).data('direction');
		//loadGalleryThumbs(dateYMon, page, direction, frompage);
		hash = dateYMon + "_" + page + "_" + frompage + "_" + direction;
		location.hash = hash;
	});
	
	// get date YYYY-Mon from url
	dateYMon = window.location.pathname.substr(9,8);
	if (dateYMon == '') {
		dateYMon = '0000-aaa';
	}
	// get page from url
	page = window.location.pathname.substr(18);
	if (page == '') {
		page = 0;
	}
	// get path from url. if the path is a gallery page and not a gallery/picture page, display gallery of thumbs
	path = window.location.pathname;
	if (path == '/' || path.substr(0,8) == '/gallery' && path.substr(0,17) != '/gallery/picture/') {
		if (location.hash.length > 0) {
			// break up the hash into variables for the url
			fetchGalleryThumbs();
		} else {
			loadGalleryThumbs(dateYMon, page, 'next');
		}
	}

	// listen for hash to change and fetch appropriate gallery of thumbs when it does
	$(window).bind('hashchange', function(e) {

		if (location.hash.length > 0) {
			fetchGalleryThumbs();
		} else {
			dateYMon = '0000-aaa';
			page = 0;
			direction = 'next';
			frompage = 0;
			loadGalleryThumbs(dateYMon, page, direction, frompage);
		}
		
	});

	function fetchGalleryThumbs() 
	{
		hashArr = location.hash.split("_");
		dateYMon = hashArr[0].substring(1,9);
		page = hashArr[1];
		direction = hashArr[2];
		frompage = hashArr[3];
		loadGalleryThumbs(dateYMon, page, direction, frompage);
	}
	
	//////////////////////////////
	//LOAD GALLERY THUMBNAILS PICS
	//
	function loadGalleryThumbs(dateYMon, page, direction, frompage) {
		
		//urlSegment = window.location.pathname.substr(8);
		var showNextPageLink = false;
		var showPrevPageLink = false;
		var showNextMonthLink = false;
		var showPrevMonthLink = false;
		var prevDateYMon = '';
		var nextDateYMon = '';

		url = "/gallery/getthumbs/" + dateYMon + "/" + page;
		jqxhr = $.ajax({
			
			dataType: "json",
			url: url,
			cache:true,
			data: { width : $(window).width(), height : $(window).height(), screen_width : screen.width, screen_height : screen.height, direction: direction}
	        
		}).done(function(data) {
			
			div = '';
			$.each( data.result, function( key, obj) {
				if (typeof obj.nav !== 'undefined') {
					dateYMon = obj.nav.dateYMon;
					$(".galleryNavDate").text(dateYMon);
					nextDateYMon = obj.nav.nextDateYMon;
					prevDateYMon = obj.nav.prevDateYMon;
					page  = obj.nav.page;
					prevMaxPage = obj.nav.prevMaxPage;
					showNextMonthLink = obj.nav.showNextMonthLink;
					showNextPageLink = obj.nav.showNextPageLink;
				} else {
					div+= "<div class='galleryImageContainer'>";
					div+="<a href='/gallery/picture/current/" + obj.id + "?frompage=" + page + "&frommonth=" + dateYMon + "'>";
					div+="<img class='galleryImage' src='" + obj.thumb + "'>";
					//div+="<span class='dateLabel'>" + obj.dateYMon + "</span>";
					div+="</a>";
					div+="</div>";
				}
		    });
			$("#galleryThumbs").html('');
			$("#galleryThumbs").html(div);
		}).fail(function() {
			//console.log( "error" );
		}).always(function() {
			//console.log( "complete" );
		});

		// GALLERY PAGE NAV
		jqxhr.complete(function() {

			nextPage = page + 1;
			if (page > 0) {
				prevPage = page - 1;
				showPrevPageLink = true;
			} else if (prevDateYMon != 0 && prevDateYMon != dateYMon) {
				showPrevMonthLink = true;
			}

			// display link nav
			navLeft = '';
			navRight = '';
		    if (showNextMonthLink && nextDateYMon != 0) {
		    	navRight = "<a href='javascript:void(0);' " +
		    			"class='galleryImageNavLink' " +
		    			"data-dateymon='" + nextDateYMon + "' " +
		    			"data-page='0'" + 
		    			"data-direction='next'" + 
		    			">Next&raquo;</a>";
		    } else if (showNextPageLink) {
		    	navRight = "<a href='javascript:void(0);' " +
		    			"class='galleryImageNavLink' " +
		    			"data-dateymon='" + dateYMon + "' " +
		    			"data-page='" + nextPage + "'" +
		    			"data-direction='next'" + 
		    			">Next&raquo;</a>";
			} 
	    	if (showPrevPageLink) {
		    	navLeft = "<a href='javascript:void(0);' " +
		    			"class='galleryImageNavLink' " +
		    			"data-dateymon='" + dateYMon + "' " +
		    			"data-direction='prev'" + 
		    			"data-page='" + prevPage + "'>&laquo;Prev</a>";
	    	} else if (showPrevMonthLink) {
		    	navLeft = "<a href='javascript:void(0);' " +
		    			"class='galleryImageNavLink' " +
		    			"data-dateymon='" + prevDateYMon + "' " +
		    			"data-direction='prev'" + 
		    			"data-page='" + prevMaxPage + "'>&laquo;Prev</a>";
	    	} 
	    	if (navLeft == '') {
	    		$(".galleryNavLinkLeft").css('visibility', 'hidden');
	    	} else {
	    		$(".galleryNavLinkLeft").css('visibility', 'visible');
	    		$(".galleryNavLinkLeft").html(navLeft);
	    	}
	    	if (navRight == '') {
	    		$(".galleryNavLinkRight").css('visibility', 'hidden');
	    	} else {
	    		$(".galleryNavLinkRight").css('visibility', 'visible');
	    		$(".galleryNavLinkRight").html(navRight);
	    	}
	
		});
	
	}	
	//
	////////////
	
	//////////////
	// DISALLOW PIC 
	//
	$(document).on('click', "#disallowPic", function(){
		var id = $("#disallowPic").data('id');
		var frompage = $("#disallowPic").data('frompage');
		var frommonth = $("#disallowPic").data('frommonth');
		$.ajax({
			  url: "/gallery/disallow/" + id,
			  cache: false,
		      type: "post",
			}).done(function( ) {
				url = '/gallery/picture/next/' + id + '?frompage=' + frompage + '&frommonth=' + frommonth;
				window.location = url;
			});
	});
	//
	/////////////////

	////////////
	// MONTHLY THUMB NAV
	//
	getNavData(0, 'noaction', setNav);

	$(document).on("click", '#prevMonthlyNavLink', function() {
		var offset = parseInt($("#offset").val());
		if (offset >= monthlyNavThumbLimit) {
			offset-= monthlyNavThumbLimit;
		}
		updateNavLinkVisibility(offset, "#prevMonthlyNavLink");
		getNavData(offset, 'prev', setNav);
	});
	
	$(document).on("click", '#nextMonthlyNavLink', function() {
		var offset = parseInt($("#offset").val());
		offset+=monthlyNavThumbLimit;
		updateNavLinkVisibility(offset, "#prevMonthlyNavLink");
		getNavData(offset, 'next', setNav);
	});
	
	$(document).on("click", '.monthlyNavReload', function() {
		getNavData(0);
	});

	var navObj = {};
	var ageInSeconds = 0;
	function getNavData(offset, action, callback) {
		
   		nowInSeconds = Math.round(new Date().getTime() / 1000);
   		if (typeof ageInSeconds == 'undefined') {
   			ageInSeconds = 0;
   		}
		if (nowInSeconds - ageInSeconds > 86400) {
		
			var url = "/getmonthlynav";
			jqxhr = $.ajax({
		    	url: url,
		    	dataType: 'json',
		    	success: function(data) {
		    		ageInSeconds = Math.round(new Date().getTime() / 1000);
		    		navObj = data.result;
		    	}
		    });
			
			jqxhr.complete(function(){
				if (navObj.length > 0) {
					callback(navObj, offset, action);
				}
			});
		
		} else {
			setNav(navObj, offset, action);
		}
			
	}
	
	function setNav(navObj, offset, action) {

		navObjSlice = navObj.slice(offset, monthlyNavThumbLimit + offset);
		
		if (navObjSlice.length > 0) {

			// clear previous nav
			$(".monthlyNavThumbCont").html('');
			
			// fill the nav up
			div = '';
			$.each(navObjSlice, function(key, obj) {
				
				div+= "<div class='monthlyNavMonth'>";
				div+= "<a href='/gallery/" + obj.dateYMon + "'>";
				div+= "<img class='galleryImage' src='" + obj.media_url + "'>";
				div+= "</a>";
				div+= "<div class='dateLabel'>" + obj.dateYMon + "</div>";
				div+="</div>";

				
		    });
			
			$(".monthlyNavThumbCont").append(div);
			// set the offset value 
			if (action == 'next') {
				$("#offset").val(offset);
			} else if (action == 'prev') {
				if (offset >= 0) {// monthlyNavThumbLimit) {
					$("#offset").val(offset);
				}
			}
		}
		
	}
	//
	// END MONTHLY NAV
	
});