var domain = "http://www.mjtrends.com/";


// JS for timed tab
var timeout = 3000, paused = false, stop=0; counter=0;

$(document).ready(function() {
	$("#headLogin").click(function(){
		return openSigninModal();
	});

	$("#headLogout").click(function(){
		return signOut();
	});

	$("#earnPoints").click(function(){
		return earnPoints();
	});

	$("#searchFrm input")
	  .focus(function() {
	    $(this).val('');
	    $(this).parent().addClass('active');
	  })
	  .blur(function() {
	    if( $(this).val() == '' ) $(this).val('Search');
	    $(this).parent().removeClass('active');
	  });
        
    $('.filter-cat select, .filter-type select').change(function() {
        filterCategories($(this).parent());
    });


    $('#save_poll').click(function() {
        save_poll();
    });


	$('.recommended-products').on('data-loaded.recommended', function() {
		$(this).bxSlider({
			minSlides: 1,
			maxSlides: 6,
			slideWidth: 160,
			slideMargin: 20
		})
	});

	$('.cart-recommended-products').on('data-loaded.recommended', function() {
		$(this).bxSlider({
			minSlides: 1,
			maxSlides: 6,
			slideWidth: 100,
			slideMargin: 20
		})
	});
});

function hideSigninErrors() {
    document.getElementById('password_err').style.display = 'none';
    document.getElementById('email_err').style.display = 'none';
    document.getElementById('email_2_err').style.display = 'none';
}

function passCheck(){
	var pwd = document.getElementById('pwd').value;
	var confirm = document.getElementById('confirm').value
	if( pwd != confirm ){
		document.getElementById('confirm').value = '';
		document.getElementById('confirm2_err').style.display = 'block';
		return false;
	}
}

function rotateSlides(weight){
    var allSlides = document.getElementById('visible_slides').childNodes;
    list_length = allSlides.length;
    if ((counter > 0 && weight < 0) || (counter < list_length-3 && weight > 0)) {counter = (counter + weight) % 6;}
    var count = counter;
    for (var i=0; i<list_length; i++) {allSlides[i].className="invisible";}  
    for (var i=0; i<3; i++) {
        allSlides[count].className="visible";
        count = (count + 1) % 6;
    }
}

function startStopRot(button) {
    if (button.value == 'Pause') {pauseTabPlay(button);}
    else {
        stop = 0;
        paused = false;
        button.value = 'Pause';
        rotateTab();
    }
}

function rotateTab() {    
    var visImg = document.getElementById('visibleTab');
    var visImgId = visImg.childNodes[0].childNodes[0].id; 
    var nextId = (visImgId.charAt(visImgId.length - 1) + 1)%3; 
    var nextImgId = 'img' +  nextId;
    t=setTimeout('changeTab(\"' + nextImgId + '\",' + paused + ')', timeout)
}

function changeTab (curTabId, rot_paused) {
    if (stop == 1 && rot_paused == true) stop = 0;
    if (stop != 1) {
        if(rot_paused == true) {pauseTabPlay(document.getElementById('pause_btn'));}
            //startStopRot(document.getElementById('pause_btn'))}
        var visImg = document.getElementById('visibleTab');
        var current = parseInt(curTabId.charAt(curTabId.length - 1));
        var previous = current - 1;
        var next = current + 1;
        if (current === 0) previous = 2;  
        if (current === 2) next = 0;
            
        var selImgId = 'img' + current;
        var selLinkId = 'link' + current;
        var prevLinkId = 'link' + previous;
        var nextLinkId = 'link' + next;
        var tabId = 'tab' + current;
        
        var selImg = document.getElementById(selImgId);
        var selLink = document.getElementById(selLinkId);
        var prevLink = document.getElementById(prevLinkId);
        var nextLink = document.getElementById(nextLinkId);
        var selTab = document.getElementById(tabId);
        
        selImg.childNodes[0].className="selected";
        selLink.className="sel";
        prevLink.className="";
        nextLink.className="";
        selTab.className = tabId;
        
        visImg.innerHTML = selImg.innerHTML;
        if (!paused) {rotateTab()}
    }    
}

// JS for top nav fly-out
//global vars

var subNav = {
	nav: true,
	
	setNav: function(bool){
		subNav.nav = bool;
	},
	
	delayedHide: function(id, h6){
		var timer = setTimeout('subNav.checkState("'+id+'", "'+h6+'")', 1000);
	},
	
	checkState: function(id, h6){
		if(subNav.nav == false) {
			node = document.getElementById(h6);
			setClass(node, '');
			subNav.showHide(id, 'none');
		}
	},
	
	showHide: function(id, style){
		navArray = new Array("tutorial", "forums", "article", "fabrics", "latex", "notions", "sale", "upholstery", "swatch", "wholesale");
		for(i = 0; i < navArray.length; i++){
			document.getElementById(navArray[i]).style.display = 'none';
			document.getElementById('nav'+(i+1)).className = '';
			if(id == navArray[i]) node = document.getElementById('nav'+(i+1));
		}
		document.getElementById(id).style.display = style;
		if(style == 'block') {setClass(node, 'active')};
	}
}

function showHide(id, style){
	document.getElementById(id).style.display = style;
}

function setClass(node, className){
	node.className = className;
}

function showId(id){
	document.getElementById(id).style.display = "block";
}

function hideId(id){
	document.getElementById(id).style.display = "none";
}

function hideClass(id){
var alltags=document.all? document.all : document.getElementsByTagName("*")
	for (i=0; i<alltags.length; i++){
		if (alltags[i].className==document.frm.sort.value){
			alltags[i].style.display='none';
		}
	}
}

function popup(url,w,h){
	winpops=window.open(url,"","width="+w+",height="+h+",");
	return false;
}

function pwdCheck(){
    var pass_field = document.getElementById('pwd');
    var pass_err = document.getElementById('pwd_err');

    if(pass_field.value.length < 6){
	pass_err.style.display = 'block';
	pass_field.className = pass_field.className + ' inputErr';
	return false;
    } else {
        pass_err.style.display = "none";
	pass_field.className = pass_field.className.replace(/inputErr/,"");
	return true;
    }
}


function verifyEmail(e, err){
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(e)){
		err.style.display = "none";
		document.getElementById('email').className = document.getElementById('email').className.replace(/inputErr/,"");
		return true;
	} else {
		err.style.display="block";
		document.getElementById('email').className = document.getElementById('email').className+' inputErr';
		bool = false;
		return false;
	}
}

function setTotalPins(el, num){
	var quant = el.selectedIndex;
	var price = document.getElementById('prodRetail'+num).innerHTML;
	var total = quant*price;
	document.getElementById('total'+num).innerHTML = '$'+total.toFixed(2);

	setGrandPins();
}

function setGrandPins(){
	var table = document.getElementById('gridTable');
	var rows = table.getElementsByTagName('tr');
	var tot = 0;
	for(var i = 0; i < (rows.length-1); i++){
		var val = document.getElementById('total'+i).innerHTML;
		val = val.split('$');
		tot += parseFloat(val[1]);
	}
	var grand = document.getElementById('grand');
	grand.innerHTML = tot.toFixed(2);
}


function setTotal(el, num){
	var quant = el.selectedIndex;
	var price = document.getElementById('price'+num).innerHTML;
	var total = quant*price;
	document.getElementById('total'+num).innerHTML = '$'+total.toFixed(2);

	setGrand();
}

function setGrand(){
	var table = document.getElementById('gridTable');
	var rows = table.getElementsByTagName('tr');
	var tot = 0;
	for(var i = 0; i < (rows.length-1); i++){
		var val = document.getElementById('total'+i).innerHTML;
		val = val.split('$');
		tot += parseFloat(val[1]);
	}
	var grand = document.getElementById('grand');
	grand.innerHTML = tot.toFixed(2);
}

function setGrid(prod){
	var inpArr = document.getElementById('gridTable').getElementsByTagName('select');
	var pattern_style_id = false;
	var no_pattern_products = false;
	for(var i = 0; i < inpArr.length; i++){
		if(inpArr[i].selectedIndex != 0) {
			if (inpArr[i].getAttribute('pattern') == "true") {
				pattern_style_id = inpArr[i].value;
			} else {
				no_pattern_products = true;
				document.getElementById('grid').value += inpArr[i].value + '*';
			}
		}
	}
	if (pattern_style_id>0) {
		$.colorbox({
			html: "<p>You'll need to select sizing to add your pattern to your cart.  Input sizing <a href='pattern-sizing.php?styleID="+pattern_style_id+"'>here</a></p>",
			width:460,
			height:215,
			opacity: .7,
			title: 'Next Step',
			onClosed: function () {
				window.location = "pattern-sizing.php?styleID="+pattern_style_id
			}
		});
		if (no_pattern_products) {
			$.post('cart.php', {"grid":document.getElementById('grid').value});
		}
	} else {
		var a = new Date();
		a = new Date(a.getTime()+1000*60*60*24*30);//30 days
		$.post( "logic/ajaxController.php", { function: "getID"} ) //have to set cookie prior to loading cart page
			.done(function(data){
				document.cookie = "custid="+data+"; path=/; expires="+a.toGMTString();
				document.getElementById('gridFrm').submit();
			});
	}
}

function hideState(el){
	var val = el[el.selectedIndex].value;
	if(val == "US" || val == "CA"){
		showId('state');
	} else {
		hideId('state');
	}
}

function setCo(el){
	document.getElementById('uspsCo').value = el.options[el.selectedIndex].innerHTML;
}

function disableForm(e,btn){
   	btn.disabled = true;

   var key;
	if(window.event){
		key = window.event.keyCode; //IE
	}else{
		key = e.which; //firefox
	}

	if(key == 13){
		return false;
	}else{
		return true;
	}
}

function copyField(el, target){
	document.getElementById(target).value = el.value;
}

function copySelect(el, target){
	target = document.getElementById(target);
	target.options.selectedIndex = el.options.selectedIndex;
}

function setSelect(id,value){
	var el = document.getElementById(id);
	if (el) {
		for (var i = 0; i < el.length; i++) {
			if (el.options[i].value == value) {
				el.options.selectedIndex = i;
				break;
			}
		}
	}
}

function setBilling(el){
	if(el.checked == true){
		for(var i = 1; i <= 11; i++){
			copyField(document.getElementById('s'+i),'b'+i);
		}
		document.getElementById('b12').selectedIndex = document.getElementById('s12').selectedIndex;
		document.getElementById('b13').selectedIndex = document.getElementById('s13').selectedIndex;
	}else{
		for(var i = 1; i <= 11; i++){
			document.getElementById('b'+i).value = '';
		}
		document.getElementById('b12').selectedIndex = 0;
		document.getElementById('b13').selectedIndex = 0;
	}
}

function calcTotal(el){
	var shipping = el.value.split(",");
	var tax 	 = 0;
	var coupon 	 = 0;
	var shipRollCost = $('#shipRollCost').html();
	addedShipRollCost	= parseFloat(shipRollCost);
	shipMeth 	 = shipping[1];
	shipRate 	 = shipping[3];

	var subtotal = document.getElementById('subT2').innerHTML;

	if(!isNaN(document.getElementById('tax2').innerHTML)) var tax = parseFloat(document.getElementById('tax2').innerHTML);
	if(document.getElementById('coupon') != undefined && !isNaN(document.getElementById('coupon').innerHTML)) var coupon = document.getElementById('coupon').innerHTML;
	if(isNaN(addedShipRollCost)) addedShipRollCost = 0;

	document.getElementById('gTotal2').innerHTML = (parseFloat(subtotal)+parseFloat(shipRate) + parseFloat(tax) + addedShipRollCost - parseFloat(coupon)).toFixed(2);
	document.getElementById('rate').innerHTML = (parseFloat(shipRate)).toFixed(2);
	document.getElementById('methSub').innerHTML = shipMeth;
}

function swatchColorSelect(){
	var color = '';
	for(var i = 1; i <= 5; i++){
		if(document.getElementById('color'+i).value != '' && document.getElementById('color'+i).value != 'Select') color += document.getElementById('color'+i).value+", ";
	}
	document.getElementById('color').value = color.substring(0,color.length-2);

	if(color == ''){
		$('#errorMessage')
			.css('display', 'block')
			.html('ERROR: please select a color');
		return false;
	} else {
		return true;
	}
}

function checkSwatch(){


	if(swatchColorSelect()){
		var quant = $('#quant').val();
		var invid = $('#invid').val();
		var color = $('#color').val();
		postItem(invid, quant, color);
	}

	return false;
}

function checkForErrors() {
	if (window.location.search) {
		var params = window.location.search.split('?')[1].split('=');
		if (params[0] == 'login' && params[1] == 'false') {
			document.getElementById('login_err').style.display = 'block';
	    } else if (params[0] == 'req' && params[1] == 'false') {
			showHideList('lost' , 'signin,register');
			hideSigninErrors();
			document.getElementById('email_err').style.display = 'block';
	    } else if (params[0] == 'registered' && params[1] == 'false') {
			showHideList('signup' , 'signin,register,email_sent');
	        hideSigninErrors();
			document.getElementById('emails_err').style.display = 'block';
		} else if(params[0] == 'captcha' && params[1] == 'false'){
			showHideList('signup' , 'signin,register,email_sent');
		}
	}
}

function showHideList(show, hide){
	var showArr = new Array();
	var hideArr = new Array();
	var showArr = show.split(',');
	var hideArr = hide.split(',');
	for(var i=0; i<hideArr.length; i++) {
        if(hideArr[i]) {document.getElementById(hideArr[i]).style.display = 'none';}
	}
	for(var j=0; j<showArr.length; j++) {
		if(showArr[j]) {document.getElementById(showArr[j]).style.display = 'block';}
	}
}

function setCookie(c_name,value,expiredays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}

function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function showSignin(){
	$.colorbox({
		href: domain+"authenticate.php",
		iframe: true,
		width:580,
		height:425,
		opacity: .7,
		title: "Sign In",
	});
}

function openNewsModal(email){
	$.colorbox({
		href: domain+"newsletterMod.php?email="+email,
		iframe:true,
		width:770,
		height:485,
		opacity:.7,
		title:"Sign up for our newsletter"
	});
}

function authenticate(){
	$('.error').fadeOut();
	var email = $('#email').val();
	var pwd = $('#pwd').val();

	$.post(domain+"logic/ajaxController.php", { "function": "signin", "email" : email, "pwd" : pwd},
		function(data){
			if(data == 'success'){
				parent.window.location.href = parent.window.location.href;
			} else {
				$('#signInForm').prepend('<div class="error">Oops - your username or password was invalid.</div>');
			}
		}
	);

	return false;
}

function createAccount(){
	$('.error').fadeOut();
	var valid = true;
	inputArray = Array('userCreate', 'emailCreate', 'pwdCreate');

	for(var i = 0; i < inputArray.length; i++){
		if( $('#'+inputArray[i]).val() == '' ){ //ensure that inputs have been filled out
			valid = false;
		}
	}

	if(valid == false){
		$('#signInForm').prepend('<div class="error">Oops - please fill out all fields.</div>');
	} else {
		$.post(domain+"logic/ajaxController.php", { "function": "createAcc", "email" : $('#emailCreate').val(), "username" : $('#userCreate').val(), "pwd" : $('#pwdCreate').val(), "thread" : 0 },
			function(data){
				if(data == 'success'){//pwd was sent
					$('#signInForm').prepend('<div class="error">We have emailed you a confirmation link.  Please check your email to proceed.</div>');
				} else {//validation error
					$('#signInForm').prepend(data);
				}
			}
		);
	}
}

function saveForumSettings(){
	//ensure that website url conforms
	//ensure that about is a minimum of x chars
	valid = true;
	if($('#about').val().length < 150 && $('#about').val().length != 0){
		$('#aboutTitle .error').remove();
		$('#aboutTitle').append('<div class="error">Please input a minimum of 150 characters.</div>');
		valid = false;
	}

	if($('#website').val().length != 0 && isUrl($('#website').val()) == false){
		$('#website .error').remove();
		$('#website').append('<p class="error">Please input a valid website url.</p>');
		valid = false;
	}

	if(valid == true){
		$('#accForm').submit();
	}
	return valid;

}

function isUrl(s) {
	//check for http - if not there add it
	if(s.substring(0, 7).toLowerCase()  != 'http://'){
 		s = 'http://'+s;
	}

    var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    return regexp.test(s);
}

function checkSwatchColor(el){
//compare value against all other dropdown values - if match throw error and reset value to null
	var color = $(el).val();
	$(".sinfo").each(function(){
		if($(this).attr("id") != $(el).attr("id") && $(el).val() != "Select"){
			if($(this) != $(el)){
				if($(this).val() == color){
					$(el).val("Select");
					$("#errorMessage")
						.css("display", "block")
						.html("ERROR: please do not select the same colors.");
					return false;
				}
				else{
					$("#errorMessage").hide();
				}
			}
		}
	});
}

///////////  +++++++ signin / auth ++++++++++///////
function openSigninModal(){
	$.colorbox({
		href: domain+"authenticate.php?referrer=notForum",
		iframe: true,
		width:770,
		maxWidth:'95%',
		height:455,
		opacity: .7,
		title: 'Login or create an account'
	});
	return false;
}

function signIn(){
	$('.error').fadeOut();
	var email = $('#email').val(),
		pwd = $('#pwd').val(),
		$parentWindow = $(window.parent.document),
		$appendPlace = $parentWindow.find("#mainmenu"),
		$removeDiv = $appendPlace.find("#hamburger-login"),
		$logoutHtml = '<a id="hamburger-user" title="Manage account" href="'+domain+'account.php"><span class="glyphicon glyphicon-cog icon"></span>' +
			'<span class="text">Manage account</span></a><button id="hamburger-logout" title="Logout" onclick="signOut(); mainMenu.logBtnsToggle();">' +
			'<span class="glyphicon glyphicon-log-out icon"></span> <span class="text">Logout</span></button>';

	$.post(domain+"logic/ajaxController.php", { "function": "signin", "email" : email, "pwd" : pwd},
		function(data){
			if(data == 'success'){
				if(referrer == 'notForum'){
					$removeDiv.remove();
					$appendPlace.append($logoutHtml);
					parent.$.colorbox.close();
					parent.location.href=parent.location.href
				} else {
					parent.$.colorbox.resize({
						width: 770,
						height: 545
					});
					window.location.href = domain+"forum/respond.php?thread="+thread;
				}
			} else if(data == 'not confirmed'){
				parent.$.colorbox.resize({
					width: 340,
					height: 500
				});
				$('#signInForm').prepend('<div class="error">Oops - your account hasn\'t been confirmed.  <button class="gButton" id="recoverAcc" onclick="resendConfirmation();">Resend confirmation</button></div>');
			} else {
				$('#signInForm').prepend('<div class="error">Oops - your username or password was invalid.</div>');
			}
		}
	);

	return false;
}

function signOut(){
	$.post(domain+"logic/ajaxController.php", { "function": "signout"});
	$(".topnav .login").fadeOut("slow", function(){
			$('.topnav .login').html('<li><li class="first"><a href="" id="headLogin" onclick="return openSigninModal();">Login</a></li><li><a href="" id="headRegister" onclick="return openSigninModal();">Register Now</a></li></li>');
			$('.topnav .login').fadeIn();
		});
	return false;
}

function resendConfirmation(){
	var email = $('#email').val();

	$.post(domain+"logic/ajaxController.php", { "function": "resendConfirmation", "email" : email, "thread" : thread},
	function(data){
		$('.error').fadeOut();
		$('#signInForm').prepend('<div class="error">We have emailed you a confirmation link.  Please check your email to proceed.</div>');
	});
}

function recoverPWD(){
	$('.error').fadeOut();
	var email = $('#emailRecover').val();

	$.post(domain+"logic/ajaxController.php", { "function": "recoverPWD", "email" : email, "referrer" : parent.window.location.href},
		function(data){
			if(data == 'success'){//pwd was sent
				$('#signInForm').prepend('<div class="error">We have emailed you the password.</div>');
				$('#recoverForm').fadeOut(function(){
		 			$('#signInForm').fadeIn();
				 });
			} else {//email was not found
				$('#recoverForm').prepend('<div class="error">Oops - your email address wasn\'t found: '+email+'</div>');
			}
		}
	);
}

function createAcc(thread){
	$('.error').fadeOut();
	var valid = true;
	inputArray = Array('username', 'pwdCreate', 'emailCreate');

	for(var i = 0; i < inputArray.length; i++){
		if( $('#'+inputArray[i]).val() == '' ){ //ensure that inputs have been filled out
			valid = false;
		}
	}

	if(valid == false){
		$('#createAcc').prepend('<div class="error">Oops - please fill out all fields.</div>');
	} else {
		$.post(domain+"logic/ajaxController.php", { "function": "createAcc", "email" : $('#emailCreate').val(), "username" : $('#username').val(), "pwd" : $('#pwdCreate').val(), "thread" : thread },
			function(data){
				if(data == 'success'){//pwd was sent
					$('#signInForm').prepend('<div class="error">We have emailed you a confirmation link.  Please check your email to proceed.</div>');

					$('#createAcc').fadeOut(function(){
						$('#signInForm').fadeIn();
					});

				} else {//validation error
					$('#createAcc').prepend(data);
				}
			}
		);
	}
}

function authCreate(){
	parent.$.colorbox.resize({
		width: 300,
		height: 450
	});

	$('#notSignedIn').fadeOut(function(){
	 	$('#createAcc').fadeIn();
	 });
}

function accRecoverPwd(){
	$('#createAcc').fadeOut(function(){
	 	$('#recoverForm').fadeIn();
	});
}

function confirmError(){
	$.colorbox({
		html: 'Foo! An error has occurred.  Please contact support: <a mailto:forums@MJTrends.com>Forums@MJTrends.com</a>',
		width:400,
		height:300,
		opacity: .7,
		title: 'Error has occurred'
	});
}

function earnPoints(){
	$.colorbox({
		href: domain+"earnpoints.php",
		iframe: true,
		width:480,
		height:340,
		opacity: .7,
		title: 'How to earn points'
	});
	return false;
}

function filterCategories(filter_button) {
	var params = {};
	$.each($('select', $(filter_button).parent()), function(i, el) {
		if ($(el).val()) {
			params[$(el).attr('name')] = $(el).val();
		}
	});
	if (!jQuery.isEmptyObject(params)) {
		params["function"] = "filter_categories";
		$.post(domain+"logic/ajaxController.php", params, function(data){
			if(data.message == 'success') {
				$('.col').remove();
				$('.product-empty').remove();
				$(data.html).insertAfter('div[class="filterCategories"]');
				if( $( window).width() > 340 ) {
					$('.filter-button').show();
				}
				else{
					$('.filter-button').hide();
				}
			} else {//validation error
				alert('error');
			}
		}, 'json');
	}
}

function submitEmail() {
    var email = $('#email').val();
    $.post("logic/newsletterGoog_f.php", { email: email },
        function(data) {
            if(data.status != 'success'){
                $('.googWrap').html('<b>Oops, '+data.error+'</b>');
            } else {
                $('.googWrap').html('<b>You\'ve just joined a community of artistic geniuses whose palette is fabric! </b><br><br>  A coupon has been emailed to you at:<b> '+email+'</b>');
            }
    },'json');
    return false;
}

function category_init() {
    $('.group-param').change(function () {
        var p = $(this).parent();
        $('a', p).attr('href', $('option:selected', this).attr('data-href'));
        $('a > h4', p).html($('option:selected', this).attr('data-title'));
        $('span', p).html($('option:selected', this).attr('data-price'));
    });
}

function getCart() {
    $.post(domain+"logic/ajaxController.php", { function: 'getCart'},
        function(data){
            if(data.status == 'error'){
                $( "#dialog" ).dialog({modal: true, dialogClass: "remaining" });
                $('#remaining').val(data.amt);
                $('#quant').val($('#remaining').val());
                return false;
            } else {
                $('#mcart').html('Shopping Cart : ($'+data.value+')');
            };
        }, "json"
    );
}

function postItem(invid, quant, color){
    $.post(domain+"logic/ajaxController.php", { function: 'addToCart', invid: invid, quant: quant, color: color},
        function(data){
			mjCart.loadCart();

			if(data.status == 'error'){
				$( "#stockLimitDialog" ).modal('show');
                $('#remaining').val(data.amt);
                $('#quant').val($('#remaining').val());
                return false;
            } else {

				if($('#saleprice').val() > 0){
					subtotal = quant * $('#saleprice').val();
				} else {
					subtotal = quant * $('#price').val();
				}
				$('#CMsubtotal').html('$'+parseFloat(subtotal).toFixed(2));
				$('#CMquant').html(quant);

				$('#cartModal').modal('show');
				setTimeout(function() {
					$(window).resize();
				}, 400);

				if(color){
					var itemId = invid.split("-");
					document.getElementById('invid').value = itemId[0]+'-'+Math.floor(Math.random() * 999);
				}
            };
        }, "json"
    );
}

function checkProd(){
	var quant = $('#quant').val();
	var invid = $('#invid').val(),
	leftInStock = parseFloat($('#remaining').val()) || 0;

	if(!quant.match(/^\d+$/)){
		$('#err')
			.css('display', 'block')
			.html('ERROR: whole numbers only');
        return false;
    } else {
		$('#err').hide();
	}

    if(parseInt(quant) > leftInStock){
        $( "#stockLimitDialog" ).modal('show');
        $('#quant').val(leftInStock);
        return false;
    }

	postItem(invid, quant);
	return false;
}

function checkKit() {
	var quant = $('#quant').val();
	var invid = $('#invid').val(),
		leftInStock = parseFloat($('#remaining').val()) || 0;

	if ( !quant.match(/^\d+$/) ) {
		$('#err')
			.css('display', 'block')
			.html('ERROR: whole numbers only');
		return false;
	} else {
		$('#err').hide();
	}


	if(!leftInStock) {
		$('#err')
			.css('display', 'block')
			.html('OUT OF STOCK');
		return false;
	}

	if ( parseInt(quant) > leftInStock) {
		$( "#stockLimitDialog" ).modal('show');
		$('#quant').val(leftInStock);
		return false;
	}



	return true;
}

function getInvAmount(invid){
	$.post(domain+"logic/ajaxController.php?invid="+invid, {function: 'getInvamount'},
		function( data ) {
			$("#remaining").val( data );
			$(".inv").html(data);

			$('#stockLimitDialog .inv').html(data);
	});
}

function loadProduct(el) {
	id = $(el).val();
	$.getJSON( "http://mjtrends.b-cdn.net/cache/prod/ajax/"+id+".js?v="+ajax_version, {} )
	.done(function( json ) {
        getInvAmount(id);
		$('#prodTitle').text(json.title);
		$('.productPrice').empty();
		if(json.saleprice > 0){
			$('.productPrice').append("<meta itemprop='priceCurrency' content='USD'/><span class='strike' id='prodRetail'>" +
			"$" + json.retail + "</span><span itemprop='offers' itemscope itemtype='http://schema.org/Offer'>" +
				"<span itemprop='price' id='prodSale'>" + json.saleprice + "</span></span>");
		} else {
			$('.productPrice').append("<meta itemprop='priceCurrency' content='USD'/>" +
				"<span itemprop='offers' itemscope itemtype='http://schema.org/Offer'>" +
				"<span itemprop='price' id='prodRetail'>$" + json.retail + "</span></span>");
		}
		$('#prodFeatures').html(json.features);
        $('#invid').val(id);
        $('#price').val(json.retail);
        $('#sale').val(json.saleprice);
        $('#weight').val(json.weight);
        $('#volume').val(json.volume);
        var color = $('#color').val();
        $('#color').val(json.length+" "+color+" "+json.type);
        if(json.tutorials != "") $('#prodTuts').html(json.tutorials);
        if(json.posts != "") $('#prodPostContainer').html(json.posts);
        if(json.images != "") $('#prodImages').html(json.images);
        if(json.video != "") $('#prodVids').html(json.videos);
	})
	.fail(function( jqxhr, textStatus, error ) {
			alert('Product error');
	});
}

function popChooseShipping(id){

	$('#'+id).modal('show');
	$('#'+id).on('hide.bs.modal', function (e){
		$('#getRates').bind('click', 'getRates');
		$('#progressbar').hide();
		$('#getRates').show();
	});
	return false;
}

$(window).resize(function () {
    fluidDialog();
});

// catch dialog if opened within a viewport smaller than the dialog width
$(document).on("dialogopen", ".ui-dialog", function (event, ui) {
    fluidDialog();
});

function fluidDialog() {
    var $visible = $(".ui-dialog:visible");
    // each open dialog
    $visible.each(function () {
        var $this = $(this);
        var dialog = $this.find(".ui-dialog-content").data("ui-dialog");
        // if fluid option == true
        if (dialog.options.fluid) {
            var wWidth = $(window).width();
            // check window width against dialog width
            if (wWidth < (parseInt(dialog.options.maxWidth) + 50))  {
                // keep dialog from filling entire screen
                $this.css("max-width", "90%");
            } else {
                // fix maxWidth bug
                $this.css("max-width", dialog.options.maxWidth + "px");
            }
            //reposition dialog
            dialog.option("position", dialog.options.position);
        }
    });

}

function getPinsPage(page) {
	var n = {"function": "getPins", page: page};
	$.post("logic/ajaxController.php", n, function (n) {
		return "error" == n.status ? !1 : ($(".pins-container").empty().append(n.html))
	}, "json")
}
