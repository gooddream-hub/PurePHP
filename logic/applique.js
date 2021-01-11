$(document).ready(function() {
	$('#openReq').click(function() {
	    $("#requirements").dialog({
	      modal: true,
	      width:500
	    });
	    return false;
	});

	$('.appView').click(function() {
		var itemId = this.parent.attr('id');
	    $("#appImage").src = itemId;
	    var title = $("title-"+itemId).html();
	    $("#appTitle").html(title);

	    $("#appProd").dialog({
	      modal: true,
	      width:500
	    });
	    return false;
	});
});