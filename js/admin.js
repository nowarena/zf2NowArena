$(document).ready(function() {
	
	$(".clearChecked").click(function(){
		$("#catform").find('input[type=checkbox]:checked').removeAttr('checked');
		$("#catform").find('input[type=radio]:checked').removeAttr('checked');
	});

	// if there is no radio button already checked, set the radio to the value of the checkbox selected
	$(".checkboxCat").click(function() {
		
		checkChecked = ($('.checkboxCat:checked').size());
		radioChecked = ($('.radioCat:checked').size());

		if (checkChecked == 1 && radioChecked == 0) {
			
			checkedVal = $(this).val();
			
			// NBA
			// get text name of checkbox
			text = $(this).parent().text();
			Atlantic = 27;
			Northwest = 29;
			Southwest = 26;
			Central = 28;
			Southeast = 30;
			Pacific = 31;

			region = 0;
			if (text == 'Minnesota Timberwolves' || text == 'Denver Nuggets' || text == 'Oklahoma City Thunder' || text == 'Portland Trail Blazers' 
				|| text == 'Utah Jazz'){
				region = Northwest;
			} else if (text == 'Boston Celtics' || text == 'Brooklyn Nets' || text == 'New York Knicks' || text == 'Philadelphia 76ers' || text == 'Toronto Raptors') {
				region = Atlantic;
			} else if ('Dallas Mavericks Houston Rockets Memphis Grizzlies New Orleans Pelicans San Antonio Spurs'.indexOf(text) != -1) {
				region = Southwest;
			} else if ('Chicago Bulls Cleveland Cavaliers Detroit Pistons Indiana Pacers Milwaukee Bucks'.indexOf(text) != -1) {
				region = Central;
			} else if ('Atlanta Hawks Charlotte Hornets Miami Heat Orlando Magic Washington Wizards'.indexOf(text) != -1) {
				region = Southeast;
			} else if ('Golden State Warriors Los Angeles Clippers Los Angeles Lakers Phoenix Suns Sacramento Kings'.indexOf(text) != -1) {
				region = Pacific;
			}
			
			if (region != 0) {
				$('input[name="primary"][value="' + region + '"]').prop('checked', true);
				$(".checkboxCat[value='" + region + "']").prop('checked', true);
			}
			
			val = $( "input:radio[name=primary]:checked" ).val();
			if (val == undefined) {
			    //$('input[name="primary"][value="' + checkedVal + '"]').prop('checked', true);
			}
		
		}
		
	});
/*	
	$(".radioCat").click(function() {
		checkedVal = $(this).val();
		console.log("checkedVal:"+checkedVal);
		val = $( ".checkboxCat:checked" ).val();
		console.log("checkedVal:"+checkedVal);
		if (val == undefined) {
			$(".checkboxCat[value='" + checkedVal + "']").prop('checked', true);
		}
	});
*/
	

	
});