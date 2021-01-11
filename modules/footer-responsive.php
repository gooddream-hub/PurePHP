<?
include("../config/config.php");
include ("../logic/Minify_HTML.class.php");
include("../logic/article.class.php");
include("../logic/global.php");

// $article = new Article;
// $article->mysqli = mysqliConn();
// $article->getTitles(5);

ob_start();
?>

			<footer class="footer-page">
				<div class="container">
					<div class="row">
						<section class="subscribe col-xs-12 col-md-6">
							<h5 class="title">
								Get exclusive promotions and info
							</h5>

							<form id="join_email" class="row" onsubmit="join_mail();">
								<div class="col-xs-8 email-container">
									<input id="subscribe_email" type="email" placeholder="Email address">	
								</div>
								<div class="col-xs-4 submit-container">
									<input type="submit" value="Subscribe">
								</div>
							</form>

							<ul class="social hidden-xs">
								<li><a href="https://pinterest.com/mjtrends" target="_blank"><i class="flaticon-pinterest"></i></a></li>
								<li><a href="http://www.facebook.com/mjtrendsCreate" target="_blank"><i class="flaticon-facebook"></i></a></li>
								<li><a href="https://twitter.com/MJTrendsFabrics" target="_blank"><i class="flaticon-twitter"></i></a></li>
								<li><a href="https://www.youtube.com/user/MJTrends/videos" target="_blank"><i class="flaticon-youtube"></i></a></li>
							</ul>
						</section>
						<section class="accordion col-xs-12 col-md-2" onclick="return openAccordion(this);">
							<h5 class="title">Need help?</h5>
							<div class="hideAcc">
								<p>
									<a href="<?=$config->domain?>tracking.php">Track Order</a> <br>
									<a href="<?=$config->domain?>faq.php">Returns</a><br> 
										<br>
									Contact: <br>
									<a href="tel:888-292-0175">1-888-292-0175 </a><br>
									<a href="mailto:sales@MJTrends.com">sales@MJTrends.com</a>
								</p>
							</div>
						</section>
						<section class="accordion col-xs-12 col-md-2" onclick="return openAccordion(this);">
							<h5 class="title">Inspiration</h5>
							<div class="hideAcc">
								<p>
									<a href="<?=$config->domain?>articles.php">Tutorials</a> <br>
									<a href="<?=$config->domain?>forum">Forums</a> <br>
									<a href="<?=$config->domain?>blog">Blog</a> <br>
									<a href="<?=$config->domain?>pin-images.php">Shop the Look</a> <br>
									<a href="<?=$config->domain?>site-index.php">Site Index</a> <br>
								</p>
							</div>
						</section>
						<section class="accordion col-xs-12 col-md-2" onclick="return openAccordion(this);">
							<h5 class="title">Company info</h5>
							<div class="hideAcc">
								<p>
									<a href="<?=$config->domain?>about.php">About Us</a><br>
									<a href="<?=$config->domain?>press-kit.php">Press</a><br>
									<a href="<?=$config->domain?>careers.php">Careers</a>
								</p>
							</div>
						</section>

					</div>
				</div>
			</footer>
			<!-- Modal -->
			<div class="modal fade" id="subscribe_success" tabindex="-1" role="dialog" aria-labelledby="subscribe_success" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="modalSuccess">Success</h4>
						</div>
						<div class="modal-body">
							You were successfully subscribed
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="subscribe_popup" tabindex="-1" role="dialog" aria-labelledby="subscribe_popup" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="modalSubscribe">Subscribe</h4>
						</div>
						<div class="modal-body">
							<div class="input-group">
								<input id="popup_subscribe_email" type="text" class="form-control" placeholder="Your Email">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" onclick="popup_join_mail();">
										<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
									</button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div> 
			
			<!-- Start Google Analytics tag -->
			<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
						(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

				ga('create', 'UA-36049628-1', 'auto');
				ga('send', 'pageview');
			</script>
			<!-- End Google Analytics tag -->

<?php
$cachefile = "../cache/footer-responsive.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, Minify_HTML::minify(ob_get_contents()));
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>