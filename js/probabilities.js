sectionTitleArr = new Array();
Qarr = new Array();
Aarr = new Array();
/*
testArr = [];
testArr[0] = [ 
   {title: "Starting hands odds"},
   {questions: [
		" pocket aces", 
		" pocket aces or pocket kings",
		]
   },
   {answers: [
       {odds: 220},
       {odds: 110}
       ]
   }
];
testArr[1] = [
   {title: "Stuff"},
   {questions: [
		" pocket deuces", 
		" pocket threes",
		]
   },
   {answers: [
       {odds: 9},
       {odds: 8}
       ]
   }
];
for(x in testArr) {
	console.log("x prop:"+x);
	for(y in testArr[x]) {
		console.log("y prop:"+y);
	console.log(testArr[x][y]['title']);
	index = Number(y) + 1;
	console.log(testArr[x][index]['questions']);
	index = Number(y) + 2;
	for(j in testArr[x][index]['answers']) {
		for(calcType in testArr[x][index]['answers'][j]) {
			console.log('calcType:'+calcType);
			console.log("answer: " +testArr[x][index]['answers'][j][calcType]);
		}
	}
	}
}
*/

// section zero
sectionTitleArr[0] = "Starting hands odds";
sectionZeroQuestionArr = 
	[
	"pocket aces",
	"pocket aces or pocket kings",
	"any pocket pair",
	"pocket suited ace king",
	"pocket off-suited ace king",
	"pocket any suited ace king",
	"pocket any two suited cards",
	"pocket aces, pocket kings or pocket ace king",
	"connectors",
	"suited connectors",
	"pocket tens or higher pocket pair",
	"pocket 9s or a lower pocket pair"
];
sectionZeroAnswerArr = 
	[
	{odds: 220},
	{odds: 110},
	{odds: 16},
	{odds: 331},
	{odds: 110},
	{odds: 82},
	{odds: 3.3},
	{odds: 46},
	{odds: 5.4},
	{odds: 25},
	{odds: 43},
	{odds: 27}
];

// section one
sectionTitleArr[1] = "Odds when holding pocket pairs";
sectionOneQuestionArr = [
	"flopping a set or better",
	"flopping a full house",
	"flopping quads",
	"making a set or better by the river"
];
sectionOneAnswerArr = [
	{odds: 7.5},
	{odds: 135},
	{odds: 407},
	{odds: 4.2},
];

// section two
sectionTitleArr[2] = "Odds when holding pocket suited";
sectionTwoQuestionArr = [
	"flopping a flush",
	"flopping a flush draw",
	"flopping a backdoor flush draw",
	"making flush by the river",
];
sectionTwoAnswerArr = [
	{odds: 118},
	{odds: 8.1},
	{odds: 1.4},
	{odds: 15},
];

// section 11
sectionTitleArr[3] = "Straights odds of";
sectionThreeQuestionArr = [
	"non-suited pocket connector cards",
	"suited pocket connector cards",
	"3 gapped pocket connectors hitting a straight on the flop",
	"2 gapped pocket connectors hitting a straight on the flop",
	"1 gapped pocket connectors hitting a straight on the flop",
	"consecutive pocket connectors hitting a straight on the flop",
	"open ended straight draw hitting a straight on the turn",
	"open ended straight draw hitting straight on the river",
	"gutshot straight draw at the turn hitting straight on the river",
	"gutshot straight draw on the flop hitting straight by the river",
	"open ended straight draw on the flop hitting a straight on the river",
];
sectionThreeAnswerArr = [
	{odds: 5.4},
	{odds: 25},
	{odds: 332},
	{odds: 142},
	{odds: 99},
	{odds: 76},
	{odds: 4.9},
	{odds: 4.8},
	{odds: 11},
	{odds: 5.1},
	{odds: 2.2}
];

// section four 
sectionTitleArr[4] = "Odds when holding two non-pair cards";
sectionFourQuestionArr = [
	"flopping at least a pair",
	"flopping two pair using both pocket cards",
	"flopping two pair using any cards",
	"flopping trips",
	"flopping a full house",
	"flopping quads"
];
sectionFourAnswerArr = [
	{odds: 2.1},
	{odds: 49},
	{odds: 24},
	{odds: 73},
	{odds: 1087},
	{odds: 9799},
];

// section five
sectionTitleArr[5] = "Odds of the flop";
sectionFiveQuestionArr = [
	"three suited cards",
	"two suited cards",
	"having a rainbow flop",
	"three cards in sequence",
	"two cards in sequence",
];
sectionFiveAnswerArr = [
	{odds: 18},
	{odds: .8},
	{odds: 1.5},
	{odds: 28},
	{odds: 1.5},
];


// section six
sectionTitleArr[6] = "Odds of the river card (not flop-to-river)";
sectionSixQuestionArr = [
	"making a full house or better from a set on the river card",
	"making a full house from two pair on the river card",
	"making a flush from a four-flush on the river card",
	"hitting an open-ended straight draw on the river card",
	"hitting a gutshot straight draw on the river card",
	"making a pair from two overcards on the river card"
];
sectionSixAnswerArr = [
	{odds: 3.6},
	{odds: 11},
	{odds: 4.1},
	{odds: 4.8},
	{odds: 11},
	{odds: 6.7},
];

// section seven
sectionTitleArr[7] = "Odds from flop to river";
sectionSevenQuestionArr = [
	"making a full house or better by the river from a set on the flop",
	"making a full house or better by the river from two pair on the flop",
	"making a flush by the river from a four-flush on the flop",
	"making a backdoor flush by the river",
	"making a straight by the river from an open-ended straight draw on the flop",
	"making a straight by the river from a gutshot straight draw on the flop",
	"making a pair or better by the river from two overcards on the flop",
	"full house or better by the river (flopped three of a kind)"
];
sectionSevenAnswerArr = [
	{odds: 2},
	{odds: 5.1},
	{odds: 1.9},
	{odds: 23},
	{odds: 2.2},
	{odds: 5.1},
	{odds: 3.2},
	{odds: 2},
];

// section eight
sectionTitleArr[8] = "Calculate the number of your outs";
sectionEightQuestionArr = [
  "outs in an open-ended straight draw",
  "outs in a gut-shot straight draw",
  "flush draw",
  "open-ender and flush draw",
];
sectionEightAnswerArr = [
    {outs: 8},
    {outs: 4},
    {outs: 9},
    {outs: 15},
];

// section 9
sectionTitleArr[9] = "(Three of a kind) Odds of";
sectionNineQuestionArr = [
	"pocket pair hitting a set on the flop",
	"pocket pair hitting a set on the turn",
	"pocket pair hitting a set on the river",
	"pocket pair making a set or better by the river",
	"three of a kind on the flop hitting quads on the turn",
	"three of a kind on the turn hitting quads on the river",
	"three of a kind on the flop hitting a full house on the turn",
	"three of a kind on the turn hitting a full house on the river"
];
sectionNineAnswerArr = [
	{odds: 7.5},
	{odds: 22.5},
	{odds: 22},
	{odds: 4.2},
	{odds: 46},
	{odds: 45},
	{odds: 6.7},
	{odds: 3.8},
];

// section 10
sectionTitleArr[10] = "Flush odds";
sectionTenQuestionArr = [
	"suited pocket cards hitting flush by the river",
	"suited pocket cards hitting flush on the flop",
	"suited pocket cards hitting four-flush on the flop",
	"suited pocket cards hitting backdoor flush draw on the flop",
	"four-flush by the flop hitting a flush on the turn",
	"backdoor flush draw hitting a four-flush on the turn",
	"four-flush on the turn hitting a flush on the river",
	"four-flush on the flop hitting a flush on the river"
];
sectionTenAnswerArr = [
	{odds: 15},
	{odds: 118},
	{odds: 8.1},
	{odds: 1.4},
	{odds: 4.2},
	{odds: 4.6},
	{odds: 4.1},
	{odds: 1.9}
];

// section 11
sectionTitleArr[11] = "Odds of the turn card";
sectionElevenQuestionArr = [
	"making a full house or better from three of a kind on the turn card",
	"making a full house from two pair on the turn card",
	"making a flush from a four-flush on the turn card",
	"making a straight from an open ended straight draw on the turn card",
	"making a straight from a gutshot straight draw on the turn card",
	"making a pair from two overcards on the turn card"
];

sectionElevenAnswerArr = [
  {odds: 5.7},
  {odds: 11},
  {odds: 4.2},
  {odds: 4.9},
  {odds: 11},
  {odds: 6.8},
];

// section 12
sectionTitleArr[12] = 'Essentials';
sectionTwelveQuestionArr = [
"pocket aces",	
"pocket aces or pocket kings",	
"any pocket pair",	
"pocket any suited ace king",	
"Unpaired pocket cards flopping two pair",	
"Pocket pair flopping a set or better",	
"Pocket pair making a set or better by the river",	
"Pocket suited making flush by the river",	
"Pocket suited flopping a flush draw",	
"consecutive pocket connectors hitting a straight on the flop",
"gutshot straight draw on the flop hitting straight by the river",
"open ended straight draw on the flop hitting a straight on the river",	
"four-flush on the flop hitting a flush on the river",	
];
sectionTwelveAnswerArr = [
  {odds: 220},
  {odds: 110},
  {odds: 16},
  {odds: 82},
  {odds: 49},
  {odds: 7.5},
  {odds: 4.2},
  {odds: 15},
  {odds: 8.1},
  {odds: 76},
  {odds: 5.1},
  {odds: 2.2},
  {odds: 1.9},
];

// 3 gapped, 2 gapped and 1 gapped straights

Qarr[0] = new Array();
Qarr[0] = sectionZeroQuestionArr;
Qarr[1] = new Array();
Qarr[1] = sectionOneQuestionArr;
Qarr[2] = new Array();
Qarr[2] = sectionTwoQuestionArr;
Qarr[3] = new Array();
Qarr[3] = sectionThreeQuestionArr;
Qarr[4] = new Array();
Qarr[4] = sectionFourQuestionArr;
Qarr[5] = new Array();
Qarr[5] = sectionFiveQuestionArr;
Qarr[6] = new Array();
Qarr[6] = sectionSixQuestionArr;
Qarr[7] = new Array();
Qarr[7] = sectionSevenQuestionArr;
Qarr[8] = new Array();
Qarr[8] = sectionEightQuestionArr;
Qarr[9] = new Array();
Qarr[9] = sectionNineQuestionArr;
Qarr[10] = new Array();
Qarr[10] = sectionTenQuestionArr;
Qarr[11] = new Array();
Qarr[11] = sectionElevenQuestionArr;
Qarr[12] = new Array();
Qarr[12] = sectionTwelveQuestionArr;

Aarr[0] = new Array();
Aarr[0] = sectionZeroAnswerArr;
Aarr[1] = new Array();
Aarr[1] = sectionOneAnswerArr;
Aarr[2] = new Array();
Aarr[2] = sectionTwoAnswerArr;
Aarr[3] = new Array();
Aarr[3] = sectionThreeAnswerArr;
Aarr[4] = new Array();
Aarr[4] = sectionFourAnswerArr;
Aarr[5] = new Array();
Aarr[5] = sectionFiveAnswerArr;
Aarr[6] = new Array();
Aarr[6] = sectionSixAnswerArr;
Aarr[7] = new Array();
Aarr[7] = sectionSevenAnswerArr;
Aarr[8] = new Array();
Aarr[8] = sectionEightAnswerArr;
Aarr[9] = new Array();
Aarr[9] = sectionNineAnswerArr;
Aarr[10] = new Array();
Aarr[10] = sectionTenAnswerArr;
Aarr[11] = new Array();
Aarr[11] = sectionElevenAnswerArr;
Aarr[12] = new Array();
Aarr[12] = sectionTwelveAnswerArr;


section = 0;
index = 0;
numCorrect = 0;

$(document).ready(function(){

	displaySectionTitle(0, 'odds');
	displayQuestion(section, index, 'odds');
	
	for(var i=0; i < sectionTitleArr.length; i++) {
		$("#tableOfContents>ul").append("<li><a href='javascript:void(0);' class='contentLink' data-section='" + i + "'>" + sectionTitleArr[i] + "</a></li>");
	}
	
	$(document).on("click", '.contentLink', function() {
		$("#feedback").html('');
		section = $(this).data('section');
		displaySectionTitle(section);
		index = 0;
		calcType = getCalcType(section, index);
		displayQuestion(section, index, calcType);
	});
	
	$(document).on("click", '.closeLayerLink', function() {
		$(".feedbackLayer").hide();
	});
	
	$(document).on("click", '.key', function() {
		clickedVal = $(this).data('value');
		val = $("#textfield").val();
		$("#textfield").val(val + clickedVal);
	});
	
	$(document).on("click", '#clearButton', function() {
		$("#textfield").val('');
	});

	$(document).on("click", "#submitButton", function(){
	
		answer = $("#textfield").val().trim();
		calcType = getCalcType(section, index);
		correctAnswer = getCorrectAnswer(section, index, calcType);
		
		isCorrect = false;
		if (correctAnswer == answer) {
			numCorrect++;
			isCorrect = true;
			$("#answerStatusFeedback").html("<span style='color:green';>&#10004;</span>");
		} else {
			if (calcType == 'perc') {
				correctAnswer+='%';
			} else {
				correctAnswer+=' to 1';
			}
			question = getQuestion(section, index);
			feedbackLayer = '<span class="incorrect">' + answer + ' is incorrect!</span><br><br> ';
			$(".feedbackLayer").show();
			$("#answerStatusFeedback").html("<span style='color:red';>&#x2717;</span>");
			title = replaceOddsAndPerc(sectionTitleArr[section], calcType);
			$(".feedbackText").html(feedbackLayer + title + "<br>" + question + "<br><br>" + correctAnswer + "<br><br>Try again!");
		}
		
		// 0 for 8 feedback
		$(".score").text(numCorrect + " for " + Qarr[section].length );
		$("#textfield").val('');
		
		if (isCorrect){ 	
			finishedMsg = '';
			finishedSection = false;
			if ( index + 1 == Qarr[section].length) {
				title = $("#sectionTitle").html();
				finishedMsg = "<span class='correct'>You finished<br><br>" + title + "<br><br>Click close to continue to the next section.</span><br><br>"; 
				$(".feedbackLayer").show();
				//feedback = $(".feedbackText").html();
				//$(".feedbackText").html(finishedMsg + feedback);
				$(".feedbackText").html(finishedMsg);
				finishedSection = true;
			}
			
			if (section + 1 == Qarr.length && finishedSection) {
				section = 0;
				index = 0;
				displaySectionTitle(section);
			} else if (finishedSection) {
				section++;
				index = 0;
				displaySectionTitle(section);
			} else {
				index++;
			}
		}
		
		displayQuestion(section, index, calcType);

	});
	$("input:radio[name=calcType]").click(function() {
		calcType = $(this).val();
	    displaySectionTitle(section, calcType);
	}); 
	
	function getCalcType(section, index) {
		
		isOuts = false;
		for(x in Aarr) {
			if (isOuts) {
				break;
			}
			for(y in Aarr[x]) {
				if (isOuts) {
					break;
				}
				for(propName in Aarr[x][y]) {
					if (propName == 'outs' && section == 8) {
						isOuts = true;
						break;
					}
				}
			}
		}
		
		if (isOuts) {
			calcType = 'outs';
		} else{
			calcType = $("input[name=calcType]:checked", '#calcTypeForm').val();
		}

		return calcType;
	
	}
	
	function displayQuestion(section, index, calcType) {
		//$("#question").text((index + 1) + ". " + Qarr[section][index] + ":");
		$("#question").text(getQuestion(section, index));
		if (calcType == 'odds') {
			label = 'to 1';
		} else if (calcType == 'perc') {
			label = ' %';
		} else if (calcType == 'outs') {
			label = 'outs';
		}
		$("#textfieldSuffixLabel").html(label);
	}
	
	function getQuestion(section, index) {
		console.log("sectioN:"+section+"|"+index);
		question = Qarr[section][index] + ":";
		question = question.trim();	
		question = question.charAt(0).toUpperCase() + question.slice(1);
		question = (index + 1) + ". " + question;
		return question;
	}
	
	function displaySectionTitle(section, calcType) {
		numCorrect = 0;
		title = replaceOddsAndPerc(sectionTitleArr[section], calcType);
		$("#sectionTitle").html(title);
	}
	
	function replaceOddsAndPerc(text, calcType) {
		
		if (calcType == 'perc') {
			
			text = text.replace("Odds", "% chance");
			text = text.replace("odds", "% chance");
			$("#textfieldSuffixLabel").html("%");
			$(".contentLink").each(function() {
				$(this).text($(this).text().replace("odds", "% chance"));
				$(this).text($(this).text().replace("Odds", "% chance"));
			});
			
		} else if (calcType == 'odds') {
			
			text = text.replace("% chance","Odds");
			//text = text.replace("% chance","odds");
			$("#textfieldSuffixLabel").html("to 1");
			$(".contentLink").each(function() {
				$(this).text($(this).text().replace(". % chance", ". Odds"));
				$(this).text($(this).text().replace("% chance","odds"));
			});
			
		} else if (calcType == 'outs') {
			
			$("#textfieldSuffixLabel").html("outs");
			
		}
			
		return text;
	}
	
	function getCorrectAnswer(section, index, calcType) {
		
		if (calcType == 'odds' || calcType == 'perc') {
			correctAnswer =  Aarr[section][index]['odds'];
			if (calcType == 'perc') {
				correctAnswer = getPercent(correctAnswer);
			}
		} else if (calcType == 'outs') {
			correctAnswer =  Aarr[section][index]['outs'];
		}
		
		correctAnswer = Number(correctAnswer);
		
		return correctAnswer;
		
	}
	function getPercent(odds) {
		
		perc = (1/(odds + 1)) * 100;
		perc = perc.toFixed(2);
		return perc;
	}
	
});