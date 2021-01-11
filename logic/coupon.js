$(document).ready(function () {
	$('#getCoupon').click(function(){
		Coupon.checkCode();
	});
});

var Coupon = new Object();

Coupon = {
	checkCode: function () {
		var couponCode = $('#couponCode').val();
		var meth = $('#meth').html();
		var cShipRate = $('#shipRate').html();
		cShipRate = cShipRate.replace(/\$/g, '');
		
		$.post("logic/coupon_f.php", {couponCode: couponCode, requestType: 'ajax'}, 
		function(data){
			$('#couponErr').remove();
			$('#couponMsg').remove();
			if(data.status == "failure"){
				Coupon.invalidCode(data);
			} else {
				Coupon.updateCart(data);	
				if(couponCode == 'freeship' && data.total_discount == 0) Coupon.freeShip(data);
				if(data.total_discount > 0) $('#getCoupon').unbind('click');
			}
		}, 'json');
	},
	
	invalidCode: function(data) {
		var error = '<p id="couponErr" style="display:none"><span>'+data.msg+'</span></p>'; //coupons has been used, coupon not valid, coupon date expired
		$('#couponMsg').remove();
		$('#getCoupon').parent().after(error);
		$('#couponErr').fadeIn();
	},
	
	updateCart: function (data) {
		var msg = '<p id="couponMsg">'+data.msg+'<span id="couponDiscount">-$'+data.total_discount.toFixed(2)+'</span></p>';
		$('#getCoupon').parent().after(msg);
				
		if(isNaN($('#shipRate').html()) == false){
			$('#shipRate').hide();
			$('#shipRate').html(parseFloat($('#shipRate').html()) - parseFloat(data.shipping_discount));
			$('#shipRate').html(parseFloat($('#shipRate').html()).toFixed(2)).fadeIn();			
		}
		$('#gTotal').hide();
		var total = parseFloat($('#gTotal').html()) - parseFloat(data.total_discount);
		total = total.toFixed(2);
		$('#gTotal').html(total).fadeIn();
		$('#gtotal').val(total);
	},
	
	freeShip: function(data){
		data.msg = 'Please click MODIFY SHIPPING to view discount';
		Coupon.invalidCode(data);
	}
} 
