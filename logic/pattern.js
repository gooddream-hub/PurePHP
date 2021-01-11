var step_counter = 0;
var garmentStyle;

$(document).ready(function() {
	$('.gender').click(function(){
		getPatternType($(this).attr('id'));
	});
	$('#addPattern').click(function(){
		savePattern();
	});
	$('#addSizing').click(function(){
		validateSizing();
	});
});

function getPatternType(gender){
	$('#garment_type').html(''); //reset the div contents in case user coming back through
	$.post('logic/pattern_f.php', {func: 'getPatternType', dataInput: gender},
		function(data){
			$.each(data, function(index, val){
				$('<div id="'+val["patID"]+'"><p>'+val["type"]+'</p><img src="images/patterns/'+gender+'-'+val["type"]+'.gif"></div>').click(function(){getPatternStyle($(this).attr('id'))}).appendTo('#garment_type');	
			});
		moveLeft();
		
		$('#cusPrev').click(function(){
			moveRight();
			return false;
		});
		
		$('#cusPrev').addClass('activated');
		
		}, 'json');
}

function getPatternStyle(garmentType){
	$('#garment_style').html(''); //reset the div contents in case user coming back through
	$.post('logic/pattern_f.php', {func: 'getPatternStyle', dataInput: garmentType},
		function(data){
			$.each(data, function(index, val){
				$('<div id="'+val['style']+'"><img src="images/patterns/'+val["styleID"]+'.jpg"><p>'+val["style"]+'</p></div>').click(function(){getSizing(val['styleID'])}).appendTo('#garment_style');	
			});
		moveLeft();
		}, 'json');
}


function getSizing(garmentStyle){
	window.garmentStyle = garmentStyle;
	$('#garment_sizing').html(''); //reset the div contents in case user coming back through
	$.post('logic/pattern_f.php', {func: 'getPatternSizing', dataInput: garmentStyle},
		function(data){
			$.each(data, function(index, val){
				$('<label for="'+val+'">'+val+'</label><input type="text" class="required" name="'+val+'" id="'+val+'" MAXLENGTH="3">').appendTo('#garment_sizing');	
			});
		moveLeft();
		}, 'json');	
}

function validateSizing(){
	$('#garment_sizing input').each(function(){
		if( $( this ).val() == '') {
			alert('Please provide size information for all inputs');
			return false;
		} else {
			moveLeft();
			return false;
		}
	});
}

function savePattern(){
			
	sizing = '{';
	$('#garment_sizing input').each(function(){
		sizing += '"'+$(this).attr('id')+'":"'+$(this).val()+'",'; // {"inseam" : "32", "patID" : "432"}
	});
	sizing += '"fabricType":"'+$('#fabric_type').val()+'"';
	sizing += '}';
	
	data = window.garmentStyle+"*"+sizing;
	$.post('logic/pattern_f.php', {func: 'savePattern', dataInput: data},
		function(data){
			if(data == "success"){
				window.location.href = "/cart.php";
			} else {
				alert("Sorry, we encountered an error.  Please try again.");	
			}
	});

}

function moveLeft(){
	$("#pattern_wrap").animate({"left": "-=785px"}, {duration: 1800});
	step_counter++; 
}

function moveRight(){
	$("#pattern_wrap").animate({"left": "+=785px"}, {duration: 1800});
	step_counter--;
	
	if(step_counter == 0){
		$('#cusPrev').unbind('click');
		$('#cusPrev').removeClass('activated');
	}
}
