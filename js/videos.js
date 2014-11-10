jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
    return this;
};

window.mobilecheck = function() {
	var check = false;
	(function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
	return check;
};

$(document).ready(function(){

//console.log("width : " + $(window).width());
//console.log("screen width: " + screen.width);
	
	// num thumbs to show in nav
	// thumbs are 100px wide, try and calc num thumbs to show based on window width
	actualWidth = $(window).width();
	// galleryImageContainer: 122 wide + 2px margin right + 1 px border left + 1 px border right = 126
	galleryTableWidth = (Math.floor(actualWidth/126)) * 126 - 126;
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

	// set the #hash when next/prev page is clicked on
	$(document).on("click", '.galleryImageNavLink', function() {
		page = $(this).data('page');
		frompage = $(this).data('frompage');
		if (typeof frompage == 'undefined') {
			frompage = 0;
		}
		direction = $(this).data('direction');
		hash = dateYMon + "_" + page + "_" + frompage + "_" + direction;
		location.hash = hash;
	});

	// dateYMon, direction aren't used, but leaving them in in case I want to paginate by month
	function setParamsObj() 
	{
		if (location.hash.length > 0) {
			hashArr = location.hash.split("_");
			dateYMon = hashArr[0].substring(1,9);
			page = hashArr[1];
			direction = hashArr[2];
			frompage = hashArr[3];
		} else {
			// set paramsObj defaults
			dateYMon = '0000-aaa';
			page = 0;
			direction = 'next';
			frompage = 0;
		}
		
		var paramsObj = {
			dateYMon : dateYMon,
			page : page,
			direction : direction,
			frompage : frompage 
		};
		
		return paramsObj;
	}
	
	// listen for hash to change and fetch appropriate gallery of thumbs when it does
	$(window).bind('hashchange', function(e) {

		paramsObj = setParamsObj();
		loadVideoThumbs(paramsObj);
		
	});

	paramsObj = setParamsObj();
	loadVideoThumbs(paramsObj);
	
	//////////////////////////////
	//LOAD VIDEO THUMBNAILS PICS 
	//
	function loadVideoThumbs(paramsObj) {
		
		dateYMon = paramsObj.dateYMon;
		page = paramsObj.page;
		direction = paramsObj.direction;
		frompage = paramsObj.frompage;
		
		//urlSegment = window.location.pathname.substr(8);
		var showNextPageLink = true;
		var showPrevPageLink = false;

		url = "/videos/index/getthumbs" + "?page=" + page;
		jqxhr = $.ajax({
			
			dataType: "json",
			url: url,
			cache:true,
			data: { width : $(window).width(), height : $(window).height(), screen_width : screen.width, screen_height : screen.height, direction: direction}
	        
		}).done(function(data) {
			
			div = '';
			$.each( data.result, function( key, obj) {
				if (typeof obj.nav !== 'undefined') {
					//dateYMon = obj.nav.dateYMon;
					//$(".galleryNavDate").text(dateYMon);
					//nextDateYMon = obj.nav.nextDateYMon;
					//prevDateYMon = obj.nav.prevDateYMon;
					page  = obj.nav.page;
					prevMaxPage = obj.nav.prevMaxPage;
					//showNextMonthLink = obj.nav.showNextMonthLink;
					showNextPageLink = obj.nav.showNextPageLink;
				} else {
					div+="<div class='videoThumbContainer'>";
					div+="<div class='videoImageContainer'>";
					div+="<a href='javascript:void(0);' class='videoLink' data-id='" + obj.video_id + "'>";
					div+="<img class='videoThumb' src='" + obj.thumb+ "'>";
					div+="</a>";
					div+="</div>";
					div+="<div class='videoTitle'>" + obj.title+ "</div>";
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
			}

			// display link nav
			navLeft = '';
			navRight = '';
			if (showNextPageLink) {
		    	navRight = "<a href='javascript:void(0);' " +
		    			"class='galleryImageNavLink' " +
		    			"data-dateymon='" + dateYMon + "' " +
		    			"data-page='" + nextPage + "'" +
		    			"data-direction='next'" + 
		    			">Next&raquo;</a> &nbsp; ";
			} 
			if (showPrevPageLink) {
		    	navLeft = " &nbsp; <a href='javascript:void(0);' " +
		    			"class='galleryImageNavLink' " +
		    			"data-dateymon='" + dateYMon + "' " +
		    			"data-direction='prev'" + 
		    			"data-page='" + prevPage + "'>&laquo;Prev</a>";
	    	} 
	    	if (navLeft == '') {
	    		$(".videoNavLinkLeft").css('visibility', 'hidden');
	    	} else {
	    		$(".videoNavLinkLeft").css('visibility', 'visible');
	    		$(".videoNavLinkLeft").html(navLeft);
	    	}
	    	if (navRight == '') {
	    		$(".videoNavLinkRight").css('visibility', 'hidden');
	    	} else {
	    		$(".videoNavLinkRight").css('visibility', 'visible');
	    		$(".videoNavLinkRight").html(navRight);
	    	}
	
		});
	
	}	
	//
	////////////
	
	////////////
	// DISPLAY VIDEO PLAYER IN LAYER
	$(document).on('click', ".videoLink", function() {

		id = $(this).data('id');
		if (window.mobilecheck()) {
			window.open('http://m.youtube.com/watch?v=' + id, '_blank');
		} else {
			$('#videoPlayerLayer').show();
			$('#videoPlayerLayer').center();
			src = '//www.youtube.com/embed/' + id;
			$('#videoPlayerIframe').attr('src', src);
		}
	});
	
	$(document).on('click', "#closeXLink", function() {
		$('#videoPlayerIframe').attr('src', '');
		$('#videoPlayerLayer').hide();
	});
	
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

	
});