function hideSigninErrors(){document.getElementById("password_err").style.display="none";document.getElementById("email_err").style.display="none";document.getElementById("email_2_err").style.display="none"}function checkProd(){if(!parseInt(document.getElementById("quant").value)||parseInt(document.getElementById("quant").value)!=parseFloat(document.getElementById("quant").value)){document.getElementById("err").style.display="block";return false}if(parseInt(document.getElementById("quant").value)>parseInt(document.getElementById("remaining").value)){alert("There are only "+document.getElementById("remaining").value+" left in stock \n Please select "+document.getElementById("remaining").value+" or less.");document.getElementById("quant").value=document.getElementById("remaining").value;return false}return true}function formCheck(e,t,n){var r;r=true;if(e){var s=e.split(",");for(i=0;i<s.length;i++){if(document.getElementById(s[i]).value==""){document.getElementById(s[i]).style.background="#ffffcc";document.getElementById(s[i]+"_err").style.display="block";r=false}else{document.getElementById(s[i]).style.background="#fff";document.getElementById(s[i]+"_err").style.display="none"}}}if(t){val=document.getElementById(t).value;var o=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;if(o.test(val)){document.getElementById(t+"_err").style.display="none";document.getElementById(t).style.background="#fff"}else{document.getElementById(t).style.background="#ffffcc";document.getElementById(t+"_err").style.display="block";r=false}}if(r==false){window.scrollTo(0,130)}return r}function passCheck(){var e=document.getElementById("pwd").value;var t=document.getElementById("confirm").value;if(e!=t){document.getElementById("confirm").value="";document.getElementById("confirm2_err").style.display="block";return false}}function changeBgr(e){for(var t=0;t<3;t++){document.getElementById(cart_id[t]).className="hov"}}function resertBgr(e){for(var t=0;t<3;t++){document.getElementById(cart_id[t]).className=""}}function rotateSlides(e){var t=document.getElementById("visible_slides").childNodes;list_length=t.length;if(counter>0&&e<0||counter<list_length-3&&e>0){counter=(counter+e)%6}var n=counter;for(var r=0;r<list_length;r++){t[r].className="invisible"}for(var r=0;r<3;r++){t[n].className="visible";n=(n+1)%6}}function pauseTabPlay(e){stop=1;e.value="Play"}function startStopRot(e){if(e.value=="Pause"){pauseTabPlay(e)}else{stop=0;paused=false;e.value="Pause";rotateTab()}}function rotateTab(){var e=document.getElementById("visibleTab");var n=e.childNodes[0].childNodes[0].id;var r=(n.charAt(n.length-1)+1)%3;var i="img"+r;t=setTimeout('changeTab("'+i+'",'+paused+")",timeout)}function changeTab(e,t){if(stop==1&&t==true)stop=0;if(stop!=1){if(t==true){pauseTabPlay(document.getElementById("pause_btn"))}var n=document.getElementById("visibleTab");var r=parseInt(e.charAt(e.length-1));var i=r-1;var s=r+1;if(r===0)i=2;if(r===2)s=0;var o="img"+r;var u="link"+r;var a="link"+i;var f="link"+s;var l="tab"+r;var c=document.getElementById(o);var h=document.getElementById(u);var p=document.getElementById(a);var d=document.getElementById(f);var v=document.getElementById(l);c.childNodes[0].className="selected";h.className="sel";p.className="";d.className="";v.className=l;n.innerHTML=c.innerHTML;if(!paused){rotateTab()}}}function showHide(e,t){document.getElementById(e).style.display=t}function setClass(e,t){e.className=t}function showId(e){document.getElementById(e).style.display="block"}function hideId(e){document.getElementById(e).style.display="none"}function hideClass(e){var t=document.all?document.all:document.getElementsByTagName("*");for(i=0;i<t.length;i++){if(t[i].className==document.frm.sort.value){t[i].style.display="none"}}}function popup(e,t,n){winpops=window.open(e,"","width="+t+",height="+n+",");return false}function popDialog(e,t,n){if($.user.zip!=""){$("#shipZip").val($.user.zip)}if($.user.country!=""){$("#country").val($.user.country)}if(typeof $.popWidth!=="undefined"){t=$.popWidth}$("#"+e).dialog({height:n,width:t,modal:true,buttons:{CLOSE:function(){$("#getRates").bind("click","getRates");$("#progressbar").hide();$("#getRates").show();$(this).dialog("close")}}});return false}function getRates(){var e=$("#country").val();var t=$("#shipZip").val();var n=$("input:radio[name=dest]:checked").val();if(e==""||t==""){$("#shipHead").html("Error: Please provide a postal code.").attr("class","err")}else{$("#getRates").unbind("click");$("#shipHead").html("Please enter the following information:").attr("class","");$("#progressbar").progressbar({value:false,text:"Loading Shipping Rates"});$("#getRates").fadeOut(function(){$("#progressbar").fadeIn()});$.post("shipcalc.php",{country:e,zip:t,dest:n},function(e){setRates(e)},"json")}}function setRates(e){$.shipObj=new Object;$.shipObj=e;$.popWidth=775;var t;if(e.status=="success"){$(".ui-dialog").animate({width:775});$("#shipInput").fadeOut(function(){$("#showRates").fadeIn()});$.each(e.rates,function(n,r){t+="<tr><td class='one'><input type='radio' name='method' value='"+n+"'>"+e.rates[n].service+"</td><td class='two'>$"+e.rates[n].cost+"</td><td class='three'>"+e.rates[n].shipDate+"</td><td class='four'>"+e.rates[n].shipTime+"</td><td class='five'>"+e.rates[n].delDate+"</td><td class='center six'>"+e.rates[n].guaranteed+"</td></tr>"});$("#rateBody").html(t);$("#rateBody").find("input:radio")[0].checked=true}else{$("#shipHead").html("Error: "+e.msg).attr("class","err")}$("#progressbar").fadeOut(function(){$("#getRates").bind("click","getRates");$("#getRates").fadeIn()})}function resetShip(){$.popWidth=433;$("#showRates").fadeOut(function(){$("#shipInput").fadeIn()});$(".ui-dialog").animate({width:433})}function saveShip(){$("saveShipBtn").unbind("click");var e=$("#rateBody").find("input:radio:checked").val();$.post("logic/ajaxController.php",{"function":"shipping",code:$.shipObj.rates[e].code,cost:$.shipObj.rates[e].cost},function(t){$("#meth").html($.shipObj.rates[e].service+"<br>"+$.shipObj.rates[e].shipTime);$("#shipRate").html("$"+$.shipObj.rates[e].cost);$("#shipCode").val($.shipObj.rates[e].code);var n=$("#subtotal").val();var r=parseFloat($("#shipRollCost").html());var i=0;if(typeof $("#couponMsg span").html()!="undefined"){i=parseFloat($("#couponMsg span").html().substring(2))}if(isNaN(i))i=0;n=r+parseFloat(n)+parseFloat($.shipObj.rates[e].cost)-i;n=n.toFixed(2);$("#gTotal").html(n);$("#gtotal").val(n);$("#couponErr").remove();$("#shipCalc").dialog("close");$("saveShipBtn").bind("click",function(){saveShip()})})}function pwdCheck(){var e=document.getElementById("pwd");var t=document.getElementById("pwd_err");if(e.value.length<6){t.style.display="block";e.className=e.className+" inputErr";return false}else{t.style.display="none";e.className=e.className.replace(/inputErr/,"");return true}}function validate(){bool=true;for(j=0;j<2;j++){if(j==0)var e="b";if(j==1)var e="s";for(i=1;i<=9;i++){if(document.getElementById(e+i).value==""){document.getElementById(e+i+"_err").style.display="block";document.getElementById(e+i).className=document.getElementById(e+i).className+" inputErr";bool=false}else{document.getElementById(e+i).className=document.getElementById(e+i).className.replace(/inputErr/,"");document.getElementById(e+i+"_err").style.display="none"}}}verifyEmail(document.getElementById("email").value,document.getElementById("email_err"));if(document.getElementById("payType").value=="cc"){Mod10(document.getElementById("cNum").value)}if(document.getElementById("pwd")!=undefined){pwdCheck(e);if(bool==true)bool=pwdCheck(e)}if(bool==false){window.scrollTo(0,130)}setDest();return bool}function setDest(){if(document.getElementById("s10").value!=""){document.getElementById("dest").value=0}}function Mod10(e){var t="0123456789";var n=e.length;var r=parseInt(e);var i=e.toString();var s=0;var o=true;var u=false;var a;var f;for(var l=0;l<n;l++){a=""+i.substring(l,l+1);if(t.indexOf(a)=="-1"){o=false}}if(!o){bool=false}if(n<16&&bool){bool=false}else{if(n==16){for(var c=n;c>0;c--){f=parseInt(r)%10;f=parseInt(f);s+=f;c--;r=r/10;f=parseInt(r)%10;f=f*2;switch(f){case 10:f=1;break;case 12:f=3;break;case 14:f=5;break;case 16:f=7;break;case 18:f=9;break;default:f=f}r=r/10;s+=f}if(s%10==0){}else{bool=false}}}if(bool==false){document.getElementById("cc_err").style.display="block";document.getElementById("cNum").className="inputErr"}else{document.getElementById("cc_err").style.display="none";document.getElementById("cNum").className=""}if(document.getElementById("cvv2").value.length<2){bool=false;document.getElementById("cvv2_err").style.display="block";document.getElementById("cvv2").className="inputErr"}else{document.getElementById("cvv2_err").style.display="none";document.getElementById("cvv2").className=""}valDate()}function valDate(){var e=new Date;var t=e.getMonth();var n=e.getFullYear();if(document.getElementById("mon").value<=t&&parseInt(document.getElementById("year").value)==parseInt(n)){document.getElementById("exp_err").style.display="block";document.getElementById("mon").className="inputErr";document.getElementById("year").className="inputErr";bool=false}else{document.getElementById("exp_err").style.display="none";document.getElementById("mon").className="";document.getElementById("year").className=""}}function verifyEmail(e,t){var n=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;if(n.test(e)){t.style.display="none";document.getElementById("email").className=document.getElementById("email").className.replace(/inputErr/,"");return true}else{t.style.display="block";document.getElementById("email").className=document.getElementById("email").className+" inputErr";bool=false;return false}}function FillForm(){if(document.frm.rad.checked){for(var e=1;e<9;e++){document.frm["s"+e].value=document.frm["b"+e].value}}if(!document.frm.rad.checked){for(var e=1;e<9;e++){document.frm["s"+e].value=""}}}function setTotal(e,t){var n=e.selectedIndex;var r=document.getElementById("price"+t).innerHTML;var i=n*r;document.getElementById("total"+t).innerHTML="$"+i.toFixed(2);setGrand()}function setGrand(){var e=document.getElementById("gridTable");var t=e.getElementsByTagName("tr");var n=0;for(var r=0;r<t.length-1;r++){var i=document.getElementById("total"+r).innerHTML;i=i.split("$");n+=parseFloat(i[1])}var s=document.getElementById("grand");s.innerHTML=n.toFixed(2)}function setGrid(e){var t=document.getElementById("gridTable").getElementsByTagName("select");for(var n=0;n<t.length;n++){if(t[n].selectedIndex!=0)document.getElementById("grid").value+=t[n].value+"*"}document.getElementById("gridFrm").submit()}function hideState(e){var t=e[e.selectedIndex].value;if(t=="US"||t=="CA"){showId("state")}else{hideId("state")}}function setCo(e){document.getElementById("uspsCo").value=e.options[e.selectedIndex].innerHTML}function disableForm(e,t){t.disabled=true;var n;if(window.event){n=window.event.keyCode}else{n=e.which}if(n==13){return false}else{return true}}function payTab(e,t){var n=new Array("cc","pp","mp");document.getElementById("payType").value=t;for(var r=0;r<n.length;r++){if(e.id!=n[r]+"_top"){document.getElementById(n[r]+"_top").className="xmenu2";document.getElementById(n[r]).style.display="none"}else{e.className=e.className+" active";document.getElementById(n[r]).style.display="block"}}setPayPal(t)}function setPayPal(e){if(e=="pp"){document.getElementById("checkoutMJT").style.display="none";document.getElementById("checkoutPP").style.display="block"}else{document.getElementById("checkoutPP").style.display="none";document.getElementById("checkoutMJT").style.display="block"}}function copyField(e,t){document.getElementById(t).value=e.value}function copySelect(e,t){t=document.getElementById(t);t.options.selectedIndex=e.options.selectedIndex}function setSelect(e,t){var n=document.getElementById(e);for(var r=0;r<n.length;r++){if(n.options[r].value==t){n.options.selectedIndex=r;break}}}function setBilling(e){if(e.checked==true){for(var t=1;t<=11;t++){copyField(document.getElementById("s"+t),"b"+t)}document.getElementById("b12").selectedIndex=document.getElementById("s12").selectedIndex;document.getElementById("b13").selectedIndex=document.getElementById("s13").selectedIndex}else{for(var t=1;t<=11;t++){document.getElementById("b"+t).value=""}document.getElementById("b12").selectedIndex=0;document.getElementById("b13").selectedIndex=0}}function calcTotal(e){var t=e.value.split(",");var n=0;var r=0;var i=$("#shipRollCost").html();addedShipRollCost=parseFloat(i);shipMeth=t[1];shipRate=t[3];var s=document.getElementById("subT2").innerHTML;if(!isNaN(document.getElementById("tax2").innerHTML))var n=parseFloat(document.getElementById("tax2").innerHTML);if(document.getElementById("coupon")!=undefined&&!isNaN(document.getElementById("coupon").innerHTML))var r=document.getElementById("coupon").innerHTML;if(isNaN(addedShipRollCost))addedShipRollCost=0;document.getElementById("gTotal2").innerHTML=(parseFloat(s)+parseFloat(shipRate)+parseFloat(n)+addedShipRollCost-parseFloat(r)).toFixed(2);document.getElementById("rate").innerHTML=parseFloat(shipRate).toFixed(2);document.getElementById("methSub").innerHTML=shipMeth}function swatch(){var e="";for(var t=1;t<=5;t++){if(document.getElementById("color"+t).value!=""&&document.getElementById("color"+t).value!="Select")e+=document.getElementById("color"+t).value+", "}document.getElementById("color").value=e.substring(0,e.length-2)}function checkForErrors(){if(window.location.search){var e=window.location.search.split("?")[1].split("=");if(e[0]=="login"&&e[1]=="false"){document.getElementById("login_err").style.display="block"}else if(e[0]=="req"&&e[1]=="false"){showHideList("lost","signin,register");hideSigninErrors();document.getElementById("email_err").style.display="block"}else if(e[0]=="registered"&&e[1]=="false"){showHideList("signup","signin,register,email_sent");hideSigninErrors();document.getElementById("emails_err").style.display="block"}else if(e[0]=="captcha"&&e[1]=="false"){showHideList("signup","signin,register,email_sent")}}}function showHideList(e,t){var n=new Array;var r=new Array;var n=e.split(",");var r=t.split(",");for(var i=0;i<r.length;i++){if(r[i]){document.getElementById(r[i]).style.display="none"}}for(var s=0;s<n.length;s++){if(n[s]){document.getElementById(n[s]).style.display="block"}}}function setCookie(e,t,n){var r=new Date;r.setDate(r.getDate()+n);document.cookie=e+"="+escape(t)+(n==null?"":";expires="+r.toGMTString())}function showSignin(){$.colorbox({href:"http://www.mjtrends.com/authenticate.php",iframe:true,width:580,height:425,opacity:.7,title:"Sign In"})}function authenticate(){$(".error").fadeOut();var e=$("#email").val();var t=$("#pwd").val();$.post("../logic/ajaxController.php",{"function":"signin",email:e,pwd:t},function(e){if(e=="success"){parent.window.location.href=parent.window.location.href}else{$("#signInForm").prepend('<div class="error">Oops - your username or password was invalid.</div>')}});return false}function createAccount(){$(".error").fadeOut();var e=true;inputArray=Array("userCreate","emailCreate","pwdCreate");for(var t=0;t<inputArray.length;t++){if($("#"+inputArray[t]).val()==""){e=false}}if(e==false){$("#signInForm").prepend('<div class="error">Oops - please fill out all fields.</div>')}else{$.post("../logic/ajaxController.php",{"function":"createAcc",email:$("#emailCreate").val(),username:$("#userCreate").val(),pwd:$("#pwdCreate").val(),thread:0},function(e){if(e=="success"){$("#signInForm").prepend('<div class="error">We have emailed you a confirmation link.  Please check your email to proceed.</div>')}else{$("#signInForm").prepend(e)}})}}function saveForumSettings(){valid=true;if($("#about").val().length<150&&$("#about").val().length!=0){$("#aboutTitle .error").remove();$("#aboutTitle").append('<div class="error">Please input a minimum of 150 characters.</div>');valid=false}if($("#website").val().length!=0&&isUrl($("#website").val())==false){$("#website .error").remove();$("#website").append('<p class="error">Please input a valid website url.</p>');valid=false}if(valid==true){$("#accForm").submit()}return valid}function isUrl(e){if(e.substring(0,7).toLowerCase()!="http://"){e="http://"+e}var t=/(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;return t.test(e)}function clickCheckBox(e){var t=parseInt($("#shiponrollChecked").val());var n=$("#shiponrollSep").is(":checked");var r=parseFloat($("#gtotal").val());var i=$("#shiponroll_"+e).is(":checked");var s=parseFloat($("#shipRollCost").html());if(i){if(0==t){t+=1;s+=18;r+=18}else{t+=1;$("#fadeIn1").fadeIn("slow");$("#fadeIn2").fadeIn("slow");if(n){s+=18;r+=18}else{s+=6;r+=6}}}else{if(1==t){t-=1;s-=18;r-=18}else{t-=1;if(1==t){$("#shiponrollSep").attr("checked",false);$("#fadeIn1").fadeOut("slow");$("#fadeIn2").fadeOut("slow")}if(n){s-=18;r-=18}else{s-=6;r-=6}}}$("#shiponrollChecked").val(t);$("#gTotal").html(r.toFixed(2));$("#shipRollCost").html(s.toFixed(2));if(s>0){$("#shipRollTR").fadeIn()}else{$("#shipRollTR").fadeOut()}$("#gtotal").val(r.toFixed(2))}function shipSeperate(){var e=$("#shiponrollSep").is(":checked");var t=parseInt($("#shiponrollChecked").val());var n=parseFloat($("#shipRollCost").html());var r=parseFloat($("#gtotal").val());if(e){for(i=1;i<=t;i++){if(i>1){n+=12;r+=12}}}else{for(i=1;i<=t;i++){if(i>1){n-=12;r-=12}}}$("#gTotal").html(r.toFixed(2));$("#shipRollCost").html(n.toFixed(2));$("#gtotal").val(r.toFixed(2))}function getGetFormValues(){var e=parseInt($("#totalSele").val());var t=Array();var n=0;for(i=1;i<=e;i++){var r=$(".checkRoll_"+i).is(":checked");if(r){t[n++]=$(".checkRoll_"+i).val()}}var s="";if(t.length>0){s=t.join(":")}else{s="0"}$("#shipOnRollSeleItems").val(s);var o=$("#shiponrollSep").is(":checked")?"1":"0";$("#shipSepHidden").val(o)}function showShipOnRollInfo(e){var t=window.opener;if(document.getElementById("shipOnAvailable")){if(e!="US"){t.$("#shipOnAvailable").fadeOut("slow");t.$("#shiproll_1").fadeOut("slow");t.$(".clsShipChk").fadeOut("slow")}else{t.$("#shipOnAvailable").fadeOut("slow");t.$("#shiproll_1").fadeIn("slow");t.$(".clsShipChk").fadeIn("slow")}}}function checkSwatch(e){var t=$(e).val();$(".sinfo: select").each(function(){if($(this).attr("id")!=$(e).attr("id")&&$(e).val()!="Select"){if($(this)!=$(e)){if($(this).val()==t){$(e).val("Select");alert("Please do not select the same colors.");return false}}}})}function openSigninModal(){$.colorbox({href:"http://www.mjtrends.com/authenticate.php?referrer=notForum",iframe:true,width:770,height:435,opacity:.7,title:"Login or create an account"});return false}function signIn(){$(".error").fadeOut();var e=$("#email").val();var t=$("#pwd").val();$.post("http://www.mjtrends.com/logic/ajaxController.php",{"function":"signin",email:e,pwd:t},function(e){if(e=="success"){if(referrer=="notForum"){parent.$(".topnav .login").fadeOut().html('<li><a href="" class="first" id="headLogout" onclick="return signOut();">Log out</a></li><li><a href="http://www.mjtrends.com/account.php">Manage Account</a></li>').fadeIn();parent.$.colorbox.close()}else{parent.$.colorbox.resize({width:770,height:545});window.location.href="http://www.mjtrends.com/forum/respond.php?thread="+thread}}else if(e=="not confirmed"){parent.$.colorbox.resize({width:340,height:460});$("#signInForm").prepend('<div class="error">Oops - your account hasn\'t been confirmed.  <button class="gButton" id="recoverAcc" onclick="resendConfirmation();">Resend confirmation</button></div>')}else{$("#signInForm").prepend('<div class="error">Oops - your username or password was invalid.</div>')}});return false}function signOut(){$.post("http://www.mjtrends.com/logic/ajaxController.php",{"function":"signout"});$(".topnav .login").fadeOut("slow",function(){$(".topnav .login").html('<li><li class="first"><a href="" id="headLogin" onclick="return openSigninModal();">Login</a></li><li><a href="" id="headRegister" onclick="return openSigninModal();">Register Now</a></li></li>');$(".topnav .login").fadeIn()});return false}function resendConfirmation(){var e=$("#email").val();$.post("http://www.mjtrends.com/logic/ajaxController.php",{"function":"resendConfirmation",email:e,thread:thread},function(e){$(".error").fadeOut();$("#signInForm").prepend('<div class="error">We have emailed you a confirmation link.  Please check your email to proceed.</div>')})}function recoverPWD(){$(".error").fadeOut();var e=$("#emailRecover").val();$.post("http://www.mjtrends.com/logic/ajaxController.php",{"function":"recoverPWD",email:e,referrer:parent.window.location.href},function(t){if(t=="success"){$("#signInForm").prepend('<div class="error">We have emailed you the password.</div>');$("#recoverForm").fadeOut(function(){$("#signInForm").fadeIn()})}else{$("#recoverForm").prepend('<div class="error">Oops - your email address wasn\'t found: '+e+"</div>")}})}function createAcc(e){$(".error").fadeOut();var t=true;inputArray=Array("username","pwdCreate","emailCreate");for(var n=0;n<inputArray.length;n++){if($("#"+inputArray[n]).val()==""){t=false}}if(t==false){$("#createAcc").prepend('<div class="error">Oops - please fill out all fields.</div>')}else{$.post("http://www.mjtrends.com/logic/ajaxController.php",{"function":"createAcc",email:$("#emailCreate").val(),username:$("#username").val(),pwd:$("#pwdCreate").val(),thread:e},function(e){if(e=="success"){$("#signInForm").prepend('<div class="error">We have emailed you a confirmation link.  Please check your email to proceed.</div>');$("#createAcc").fadeOut(function(){$("#signInForm").fadeIn()})}else{$("#createAcc").prepend(e)}})}}function authCreate(){parent.$.colorbox.resize({width:300,height:410});$("#notSignedIn").fadeOut(function(){$("#createAcc").fadeIn()})}function accRecoverPwd(){$("#createAcc").fadeOut(function(){$("#recoverForm").fadeIn()})}function confirmError(){$.colorbox({html:"Foo! An error has occurred.  Please contact support: <a mailto:forums@MJTrends.com>Forums@MJTrends.com</a>",width:400,height:300,opacity:.7,title:"Error has occurred"})}function earnPoints(){$.colorbox({href:"http://www.mjtrends.com/earnpoints.php",iframe:true,width:480,height:340,opacity:.7,title:"How to earn points"});return false}function openNewsModal(){$.colorbox({href:"http://www.mjtrends.com/newsletterMod.php",iframe:true,width:770,height:465,opacity:.7,title:"Sign up for our newsletter"});return false}var timeout=3e3,paused=false,stop=0;counter=0;var cart_id=new Array("rcart","mcart","lcart");$(document).ready(function(){$("#headLogin").click(function(){return openSigninModal()});$("#headLogout").click(function(){return signOut()});$("#earnPoints").click(function(){return earnPoints()});$("#newsModule").click(function(){return openNewsModal()})});var subNav={nav:true,setNav:function(e){subNav.nav=e},delayedHide:function(e,t){var n=setTimeout('subNav.checkState("'+e+'", "'+t+'")',1e3)},checkState:function(e,t){if(subNav.nav==false){node=document.getElementById(t);setClass(node,"");subNav.showHide(e,"none")}},showHide:function(e,t){navArray=new Array("tutorial","forums","article","fabrics","latex","notions","sale","upholstery","swatch","wholesale");for(i=0;i<navArray.length;i++){document.getElementById(navArray[i]).style.display="none";document.getElementById("nav"+(i+1)).className="";if(e==navArray[i])node=document.getElementById("nav"+(i+1))}document.getElementById(e).style.display=t;if(t=="block"){setClass(node,"active")}}}