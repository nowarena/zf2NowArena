$(document).ready(function() {
	
	$(".clearChecked").click(function(){
		$("#catform").find('input[type=checkbox]:checked').removeAttr('checked');
		$("#catform").find('input[type=radio]:checked').removeAttr('checked');
	});

	// on load, manage admin links 
    $('.unpubLink').each(function(){ 
    	$(this).show();
	});
    $('.editTwitterLink').each(function(){ 
    	$(this).show();
	});
    
	// on load, manage read more, read less links
    $('.socialContent').each(function(){ 
		var divid = $(this).attr('id');
		mngReadMore(divid);
	});

	// when end of feed is reached, a link to next biz is offered, listen for click
	$(document).on("click", '.nextFeedLink', function() {
		var id = $(this).parents('.bizContainer').find('.nextBizLink').attr('id');
		$("#" + id).click();
	});
	
	// don't show biz categories until page is fully loaded
	$("footer").ready(function(){
		$(".bizContainer").show();
	});

	/////////////////////////////////
	// START CONFIRMATION DIALOG
	//
	if (typeof(dialog) == 'object') {
		
		$("#dialog").dialog({
	        bgiframe: true,
	        autoOpen: false,
	        minHeight: 200,
	        width: 350,
	        modal: true,
	        closeOnEscape: false,
	        draggable: true,
	        resizable: false,
	        buttons: {
	            'Yes': function(){
	                $(this).dialog('close');
	                processResult(true);
	            },
	            'No': function(){
	                $(this).dialog('close');
	                processResult(false);
	            }
	        }
	    });
	}

	// Process the return from the confirmation dialog
	function processResult(result) {
		if (result) {
			var social_id = getSocialId();
			$.ajax({
			  url: "/unpublishsocialmedia/" + social_id + "/" + username,
			  cache: false,
			  type: "post",
			}).done(function() {
				if ($("#prevAjaxLink_" + blvd_id).length) {
					$("#prevAjaxLink_" + blvd_id).click();
				} else {
					location.reload();
				}
			});
		}
	}
	//
	// END CONFIRMATION DIALOG
	////////////////////////////
	
	//////////////////////
	// UNPUB LINK
	// the dialog is tied to this listener
	//
	$(document).on("click", ".unpubLink", function(){
 
		var social_id = $(this).data('social_id');
		getSocialId = function() { 
			return social_id;
		};
		username = $(this).data('username');
		blvd_id = $(this).data('blvdid');
		
		$("#dialog").dialog("open");

	});
	//
	// END UNPUB LINK
	/////////////////////////
	
	/////////////////////////
	// NEXT BIZ LINKS
	//
	$(document).on("click", '.reloadcategory', function() {
		var category_id = $(this).data('categoryid');
		$("#nextBizLink_" + category_id).data('offset', 0);
		$("#prevBizLink_" + category_id).data('offset', 0);
		$("#nextBizLink_" + category_id).click();
	});

	$(".nextBizLink, .prevBizLink").click(function() {
	
	    var offset = $(this).data('offset');//eg. 0
	    var category_id= $(this).data('category_id');//eg. 1 
	    blvdid = $(this).data('blvdid');

		// hide current social media
		$("#category_" + category_id).find('.socialContent').hide();
		$("#category_" + category_id + " > .header").html('');
		// show loading ani
		$("#loadingani_" + category_id).show();

		// 'elem' is $(this) for nextBizLink or prevBizLink click
		var elem = $(this);

		var endOfCategoryReached = $("#category_" + category_id + " > .rowContainer").find('.endOfCategoryContainer').length;
		// the user's position in the offset should be between the range. eg. user is at postion 3 when prevBizLink offset is 2 and nextBizOffset is 4
		if (elem.attr('class') == 'nextBizLink' && endOfCategoryReached == 0) {
			getOffset = offset + 1;
			elem.data("offset", getOffset);
			$("#prevBizLink_" + category_id).data("offset", getOffset );
		} else if (elem.attr('class') == 'nextBizLink' && endOfCategoryReached == 1) {
			// if end of category reached, don't move beyond offset
			getOffset = offset;
		} else if (elem.attr('class') == 'prevBizLink') {
			if (elem.data("offset") > 0 ) {
				getOffset = offset - 1;
			} else {
				getOffset = 0;
			}
			elem.data("offset", getOffset);
			$("#nextBizLink_" + category_id).data("offset", getOffset);
			
		}
	
		$.ajax({
		  url: "/nextbiz/" + category_id + "/" + getOffset,
		  cache: false,
	      type: "post",
		}).done(function( row ) {
	
			// add row to html
			$( "#category_" + category_id ).html( row );
			
		    $('.profilePic').each(function(){ 
		       	var dataSrc = $(this).data('src');
		       	$(this).attr('src', dataSrc);
		    });
		    
			// hide loading ani 
			$("#loadingani_" + category_id).hide();
		    	
		    divid = $("#category_" + category_id + " > .socialContent").attr('id');
		    mngReadMore(divid);
		    
			if ($("#nextBizLink_" + category_id).length > 0) {
				$("#" + divid).find(".nextFeedLink").show();
			}
	
			//$(document).on("ready", '.unpubLi', function() {
		    $('.unpubLink').each(function(){ 
		    	$(this).show();
			});
				
		    $('.editTwitterLink').each(function(){ 
		    	$(this).show();
			});
			
		});
		
	});
	//
	// END NEXT BIZ LINKS
	/////////////////////////////

	/////////////////////////////
	// START READMORE READLESS
	// when readMore links are clicked on
	//
	$(document).on("click", ".readMoreLink", function(){
		rowEl = $(this).parent().parent().parent().parent();
		rowEl.css('height', '100%');
		boxBorderEl = rowEl.find('.boxBorder');
		boxBorderEl.css('height', '100%');//boxBorder
		rowEl.find('.textBox').css('height', '100%');

		textBoxTwoEl = rowEl.find('.socialContent > .box:nth-child(2) > .boxBorder > .textBox');
		if (textBoxTwoEl.length > 0 ) {
			textBoxOneEl = rowEl.find('.socialContent > .box:nth-child(1) > .boxBorder > .textBox');
			if (textBoxOneEl.height() < textBoxTwoEl.height()) {
				height = textBoxTwoEl.height() + 4;
				textBoxOneEl.css('height', height + 'px');
				textBoxTwoEl.css('height', height + 'px');
			} else if (textBoxOneEl.height() > textBoxTwoEl.height()) {
				height = textBoxOneEl.height() + 4;
				textBoxOneEl.css('height', height + 'px');
				textBoxTwoEl.css('height', height + 'px');
			}
		}

		// if the readMore link is visible, set readLess to visible before hiding readMore
		$(rowEl.find('.readMoreLink')).each(function(){
			if ($(this).is(':visible')){
				$(this).next().show();
			}
		});
		rowEl.find('.readMoreLink').hide();
		
	});
	
	$(document).on("click", ".readLessLink", function(){
		rowEl = $(this).parent().parent().parent().parent();
		rowEl.css('height', '200px');
		rowEl.find('.boxBorder').css('height', '112px');//boxBorder
		rowEl.find('.textBox').css('height', '102px');
		// if the readLess link is visible, set readMore to visible before hiding readLess
		$(rowEl.find('.readLessLink')).each(function(){
			if ($(this).is(':visible')){
				$(this).prev().show();
			}
		});
		rowEl.find('.readLessLink').hide();
	});
	
	// for display of links: determine whether or not to show readMore and readLess links
	function mngReadMore(divid) {
		
		var textBoxEl = $("#" + divid + " > .box:nth-child(1) > .boxBorder > .textBox");
		// reloadCategory doesn't have textBox
		if (typeof textBoxEl[0] != 'undefined') {
			textBoxHeight = textBoxEl[0].offsetHeight;
			textBoxScrollHeight = textBoxEl[0].scrollHeight;
			// pad textBoxHeight with 10 as the font-size is 130% and that throws off the calculation
			if (10 + textBoxHeight < textBoxScrollHeight) {
				//$("#" + divid + " > .box:nth-child(1) > .boxBorder > .readMore").show();
				$("#" + divid + " > .box:nth-child(1) > .postHeader > .readMoreLink").show();
			}
	
			var textBoxEl = $("#" + divid + " > .box:nth-child(2) > .boxBorder > .textBox");
			if (textBoxEl.length >0 ) {
				textBoxHeight = textBoxEl[0].offsetHeight;
				textBoxScrollHeight = textBoxEl[0].scrollHeight;
				if (textBoxHeight < textBoxScrollHeight) {
					//$("#" + divid + " > .box:nth-child(2) > .boxBorder > .readMore").show();
					$("#" + divid + " > .box:nth-child(2) > .postHeader> .readMoreLink").show();
				}
			}
		}
		
	}
	//
	// END READMORE READLESS
	/////////////////////////

	///////////////////////
	// START READ SOCIALMEDIA FEED CALL
	// ajaxLink call to socialmedia
	//
	$(document).on("click", '.ajaxLink', function() {

		category_id = $(this).data('category_id');
	    var blvdid = $(this).data('blvdid');//eg. 33
		var divid = '_' + blvdid;
	    var offset = $(this).data('offset');//eg. 0
		//var numcols = $("#" + divid).find('numcols').data('numcols');
		var numcols = $(this).data('numcols');
		var lastid = $("#" + divid).find('lastid').data('lastid');
	    // nextBiz link (eg/ food raquo; link) causes first feed to not register with the dom, nextFeed link needs to clicked twice 
	    if (lastid == null) {
	    	lastid = 'start';
	    }
	
	    var parentObj = $(this).parent();

	    // see if reload feed icon was clicked
	    var reloadFeedClicked = false;
	    var reloadId = $(this).attr('id');
	    if ('reloadAjaxLink' + divid == reloadId) {
	    	var reloadFeedClicked = true;
	    }

	    var arrowElem = $(this).parent().attr('class');//nextFeed or prevFeed or reloadIcon

	    if (arrowElem == 'nextFeed') {
	    	if (lastid == 'start'){ 
	    		getOffset = 0;
	    	} else if (lastid != 'end') {
	    		getOffset = offset + numcols;
				$("#nextAjaxLink" + divid).data("offset", ( getOffset ));
				$("#prevAjaxLink" + divid).data("offset", ( getOffset ));
	    	} else {
	    		getOffset = offset;
	    	}
	    } else if (arrowElem == 'prevFeed') {
	    	if (offset != null && numcols !=null && offset - numcols >= 0) {
	    		getOffset = offset - numcols;
				$("#nextAjaxLink" + divid).data("offset", ( getOffset ));
				$("#prevAjaxLink" + divid).data("offset", ( getOffset ));
	    	} else {
	    		getOffset = 0;
	    	}
	    } else if (arrowElem == 'reloadIcon') {
			$("#nextAjaxLink" + divid).data("offset", 0 );
			$("#prevAjaxLink" + divid).data("offset", 0 );
			getOffset = 0;
	    }

		// hide curent social content feed 
		$("#" + divid).hide();
		// show loading ani
		$("#" + divid).next().show();

		// call new social content feed
		var urlStr = "/socialmedia/" + blvdid + "/" + getOffset;
		$.ajax({
		  url: urlStr,
		  cache: false,
	      type: "post",
		}).done(function( row ) {

			// hide loading ani
			$("#" + divid).next().hide();
			
			// show social content feed
			$("#" + divid).show();
			
			// add feed to html
			$( "#" + divid ).html( row );
			
			// show readMore link
			mngReadMore(divid);
			
			// get new data about displayed row
			var lastid = $("#" + divid).find('lastid').data('lastid');
			var firstid = $("#" + divid).find('firstid').data('firstid');

			if (lastid == 'end' && getOffset == 0 && reloadFeedClicked == false) {
				// they don't have any content
				$("#" + divid).hide();
				parentObj.hide();//nextFeed
				parentObj.next().hide();//prevFeed
				parentObj.next().next().hide();//reload 
				containerEl = parentObj.parent().parent();
				if (containerEl.attr('class') == 'rowContainer') {
					parentObj.parent().parent().addClass('rowContainerEmpty').removeClass('rowContainer');
				} else {
					parentObj.parent().parent().addClass('rowContainerBlvdEmpty').removeClass('rowContainerBlvd');
				}
			} else if (lastid == 'end') {
				if ($("#nextBizLink_" + category_id).length > 0) {
					$("#" + divid).find(".nextFeedLink").show();
				}
			}
			
		});
	});
	
	$( document ).on("mouseover", ".ajaxLink", function(event) {
		$(this).parent().css('background-color', 'lightblue');
	}).on("mouseout", ".ajaxLink", function(event) {
		$(this).parent().css('background-color', '#ffffff');
	});
	//
	// END SOCIALMEDIA FEED CALL
	
	//////////////
	// START SCROLL TRIGGERS TO LOAD SOCIALMEDIA
	// 
	function isScrolledIntoView(elem)
	{
	    var docViewTop = $(window).scrollTop()-100;//pad it by 100
	    var docViewBottom = docViewTop + $(window).height()+100;

	    var elemTop = $(elem).offset().top;
	    var elemBottom = elemTop + $(elem).height();

	    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
	}

	var scrollCallArr = [];
	
	// figure out how to not have this twice, here and in the scroll function
    $('.profilePic').each(function(){ 
        if (isScrolledIntoView($(this))) {
        	var dataSrc = $(this).data('src');
        	$(this).attr('src', dataSrc);
        }
    });
    
    $('.socialContent').each(function(){ 
        var divid = $(this).attr('id');
        if (isScrollCalled(divid) == false && $(this).html() == '' && isScrolledIntoView('div#' + divid)) {
        	scrollCallArr.push(divid);
            $('#nextAjaxLink' + divid).click(); 
        }
    });
	
	function isScrollCalled(divid) 
	{
		for(var i = 0; i < scrollCallArr.length; i++ ) {
			if (scrollCallArr[i] == divid) {
				return true;
			}
		}
		return false;
	}
    
	$( window ).scroll(function() {
        $('.socialContent').each(function(){ 
	        var divid = $(this).attr('id');
	        if (isScrollCalled(divid) == false && $(this).html() == '' && isScrolledIntoView('div#' + divid)) {
	        	scrollCallArr.push(divid);
	            $('#nextAjaxLink' + divid).click(); 
	        }
        });
        
	    $('.profilePic').each(function(){ 
	        if (isScrolledIntoView($(this))) {
	        	var dataSrc = $(this).data('src');
	        	$(this).attr('src', dataSrc);
	        }
	    });
	});
	//
	// END SCROLL TRIGGER TO LOAD SOCIALMEDIA 
	
});