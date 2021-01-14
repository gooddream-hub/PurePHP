<?php
	
	$config = new stdClass();

	$config->domain = "http://localhost/";
	// $config->domain = "https://fabric-bolt.com/";

	$config->domain_ssl = "https://www.mjtrends.com/";
	$config->CDN = "//mjtrends.b-cdn.net/";

	$config->main_css = $config->CDN."cache/css/css-main.min.css";
	$config->secondary_css = $config->CDN."cache/css/secondary.min.css";
	$config->forum_css = $config->CDN."cache/css/forum-css-forum.min.css";
	$config->colorbox_css = $config->CDN."cache/css/jquery-colorbox-colorbox.min.css";
	$config->bootstrap_css = $config->CDN."cache/css/bootstrap.min-2.css";
	$config->media_queries_css = $config->CDN."cache/css/css-media_queries.min.css";
	$config->jquery_ui_css = "//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css";
	$config->pattern_css = $config->CDN."cache/css/css-pattern.min.css";
	$config->intlTelInput_css = $config->CDN."cache/css/css-intlTelInput.min.css";
	$config->checkout_css = $config->CDN."cache/css/css-checkout.min.css?v=1";
	$config->redactor_css = $config->CDN."cache/css/forum-redactor-redactor.min.css?v=1";

	$config->functions_js = $config->CDN."cache/js/logic-functions.min.js";
	$config->jquery_js = "//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js";
	$config->jquery_ui_js = "//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js";
	$config->colorbox_js = $config->CDN."cache/js/jquery-colorbox-jquery.colorbox-min.js";
	$config->forum_js = $config->CDN."cache/js/forum-js-functions.min.js";
	$config->custom_js = $config->CDN."cache/js/js-custom.min.js";
	$config->boostrap_js = $config->CDN."cache/js/bootstrap.min.js";
	$config->pattern_js = $config->CDN."cache/js/js-pattern.min.js";
	$config->intlTelInput_js = $config->CDN."cache/js/js-intlTelInput.min.js";
	$config->checkout_js = $config->CDN."cache/js/js-checkout.min.js?v=1.1";
	$config->coupon_js = $config->CDN."cache/js/js-coupon.min.js";
	$config->redactor_js = $config->CDN."cache/js/forum-redactor-redactor.min.js";

	$config->bxslider_js = $config->CDN."cache/js/jquery.bxslider.min.js";
	$config->my_js = $config->CDN."cache/js/js-my.min.js";
	$config->menu_function_js = $config->CDN."cache/js/js-menu_function.min.js";
	$config->pins_js = $config->CDN."cache/js/js-pins.min.js";
	$config->jquery_cookie_js = $config->CDN."cache/js/jquery-jquery.cookie.min.js";
	$config->smart_search = $config->CDN."cache/js/js-smart_search.min.js";

?>