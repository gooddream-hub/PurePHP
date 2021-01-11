$( document ).ready(function() {
	$( "#print" ).click(function() {
		print_invoice();
	});

	$( "#delete" ).click(function() {
		delete_invoice();
	});

	$( "#get_shipping_label" ).click(function() {
		get_shipping_label();
	});
});

function get_id(){
	url_search = new URLSearchParams(window.location.search);
	id = url_search.get('custid');

	return id;	
}

function get_shipping_label(){
	$( "#get_shipping_label" ).attr('disabled',true);
	
	id = get_id();
	box = $('#ship_size option:selected').val();
	weight = $('#ship_weight').val();
	service = $('#ship_service').val();
	value = $('#ship_value').val();

	post = { custid: id, box: box, weight: weight, service: service, value: value};

	$.post( "../../controller/customer/ajax.php", post, function(data) {
		if(data == "success"){
			$("#delete").attr("disabled", false);
		} else if( data['label']!="" ) {
			$('#usps_now').prepend('<img style="width:4in;" src="'+data['label']+'" />')
			var divToPrint=document.getElementById("usps_now");
			newWin= window.open("");
			newWin.document.write(divToPrint.outerHTML);
			newWin.print();
			newWin.close();
			$("#delete").attr("disabled", false);
		}
	});

}

function delete_invoice(){
	id = get_id();
	post = { custid: id, delete: "true" };

	$.post( "../../controller/customer/ajax.php", post, function(data) {
		if(data == "success"){
			$("#delete").attr("disabled", true);
		} else {
			alert(data);
		}
	});
}

function print_invoice(){
	id = get_id();
	post = { custid: id, set_ship: "true" };

	$.post( "../../controller/customer/ajax.php", post, function(data) {
		if(data == "success"){
			$("#print").attr("disabled", true);
		} else {
			alert(data);
		}
	});

	window.print();
}