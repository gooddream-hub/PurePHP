$.fn.reverse = [].reverse;

function initRecommended(){
	$('.recList').before('<span class="recLeft recBtn">&lsaquo;</span>');
	$('.cartRecommend').append('<span class="recRight recBtn">&rsaquo;</span>');
	$( ".recLeft" ).click(function() {
		recUpdate('left', this);
	});
	$( ".recRight" ).click(function() {
		recUpdate('right', this);
	});
}

function recUpdate(route, el){	
	if(route == 'left'){
		var parent = $(el).parent().get(0);
		var e = $(parent).find('.recom').slice(-4);
		var end = $(parent).find('.recom').slice(0);
		
		$( e ).reverse().each(function( index ) {
			$(parent).find('.recList').prepend($(this)); 		
		});	

	} 
	
	if(route == 'right') {
		var parent = $(el).parent().get(0);
		var e = $(parent).find('.recom').slice(0,4);
		var end = $(parent).find('.recom').slice(-1);

		$( e ).each(function( index ) {
			$(parent).find('.recList').append($(this)); 		
		});

	}

}

initRecommended();