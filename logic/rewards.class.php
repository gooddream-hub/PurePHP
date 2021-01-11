<?php
class Reward{
	public $nextReward;
	public $db;

	function __construct() {
		$this->db = DB::getInstance();
	}

	function getNextReward($points, $rewardArray){
		if($points < $rewardArray['100']){ // every 100
			$diff = $rewardArray['100'] - $points;
			$this->nextReward = "You only need ".$diff." more points to get a 20% off a single item coupon!";
		}
		
		if(($rewardArray['250'] - $points) < $this->nextReward){ //every 250 - show this message if fewer points diff than the 100
			$diff = $rewardArray['100'] - $points;
			$this->nextReward = "You only need ".$diff." more points to get a free shipping coupon!";
		}
		
		if( $points > 450 && $points < 500){ //one time award at 500 points
			$diff = 500 - $points;
			$this->nextReward = "You only need ".$diff." more points to be interviewed one on one with one of our in-house experts and be featured on our blog and homepage!";
		}
	}

	function setReward($points, $rewardArray, $name, $email){
		//check existing pointsClass against multiples of 100 for the 15% off single item coupon 
		if(($rewardArray['500'] != 500) && ($points >= 500)){ //one time award at 500 points
			$rewardArray['100'] += 100;
			$reward = "100 => ".$rewardArray['100'].", 250 => ".$rewardArray['250'].", 500 => 500";
			$sql = "UPDATE users SET reward = '$reward' ";
			$result = $this->db->query($sql);

			$code = 'Chat_'.RandomString(10);
			$ckey = '';

			$this->createCoupon($code, $ckey, $email);
			$this->sendReward('Have an interview with our expert staff and be highlighted on our blog and homepage.', $name, $email, $code);
		} elseif($points >= $rewardArray['100']){ // every 100
			$rewardArray['100'] += 100;
			$reward = "100 => ".$rewardArray['100'].", 250 => ".$rewardArray['250'].", 500 =>".$rewardArray['500'];
			$sql = "UPDATE users SET reward = '$reward' ";
			$result = $this->db->query($sql);

			$code = 'rew_'.RandomString(16);
			$ckey = 6;

			$this->createCoupon($code, $ckey, $email);
			$this->sendReward('20% off any single non-sale item', $name, $email, $code); 
		}
		
		if($points > $rewardArray['250']){ //every 250
			$rewardArray['250'] += 250;
			$reward = "100 => ".$rewardArray['100'].", 250 => ".$rewardArray['250'].", 500 =>".$rewardArray['500'];
			$sql = "UPDATE users SET reward = '$reward' ";
			$result = $this->db->query($sql);

			$code = 'rew_'.RandomString(16);
			$ckey = 2;//free shipping coupon

			$this->createCoupon($code, $ckey, $email);
			$this->sendReward('Free Ground shipping for US domestic or $10 off intenational shipping', $name, $email, $code);
		}
		
	}

	function sendReward($reward, $name, $email, $code){
		$to = $name." <".$email.">";
		$subject = 'You just got a reward!';

		$message = '
			<!DOCTYPE html>
			<html>
				<body>
					<table width="620" style="font-family: arial;">
						<tr>
							<td stlye="vertical-align:top">
							<p style="font-size:2.9em; font-weight:bold; padding:0; margin:0">MJTrends</p>
								<br>
								<p style="font-size:1.7em; font-weight:bold; margin:0; padding:0; font-style:italic;">We help create <span style="color:#dd0000">;)</span></p>
							</td>
							<td style="text-align:right; font-style: italic; font-size:.9em;">
								To ensure delivery to your inbox, please add<br>sales@MJTrends.com to your address book.<br>
								Don\'t want this:  <a href="http://www.mjtrends.com/unsubscribe.php" style="color:#000">unsubscribe</a> from future emails.
								<p><a href="http://www.mjtrends.com?adid=610" style="text-decoration:none; color:#dd0000; font-weight:bold; font-size:1.2em; font-style:normal">Shop Now <img src="http://www.mjtrends.com/images/red-play-icon.gif" style="vertical-align:middle"></a></p>
							</td>
						<tr>
							<td colspan="2" width="620">
								<div style="width: 100%; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
								<div style="width: 100%; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
							</td>
						</tr>

						<tr>
							<td colspan="2" width="620">
								<a href="http://www.MJTrends.com" style="text-decoration:none;"><span style="font-weight:bold; display:inline-block; background-color: #454545; color:#fff; padding: 20px 35px; margin-left:14px; font-size: 2.2em; border: 1px solid #666; -moz-border-radius: 6px 6px 6px 6px; -webkit-border-radius: 6px 6px 6px 6px; border-radius: 6px 6px 6px 6px;">Shop <span style="color: #fd0006"> > </span></span></a>
								<a href="http://www.MJTrends.com/forum" style="text-decoration:none;"><span style="font-weight:bold; display:inline-block; background-color: #454545; color:#fff; padding:20px 35px; margin-left:20px; font-size: 2.2em; border: 1px solid #666; -moz-border-radius: 6px 6px 6px 6px; -webkit-border-radius: 6px 6px 6px 6px; border-radius: 6px 6px 6px 6px;">Share <span style="color: #679b00"> > </span></span></a>
								<a href="http://www.mjtrends.com/articles.php" style="text-decoration:none;"><span style="font-weight:bold; display:inline-block; background-color: #454545; color:#fff; padding:20px 35px; margin-left:20px; font-size: 2.2em; border: 1px solid #666; -moz-border-radius: 6px 6px 6px 6px; -webkit-border-radius: 6px 6px 6px 6px; border-radius: 6px 6px 6px 6px;">Learn <span style="color: #7309aa"> > </span></span></a>
							</td>
						</tr>

						<tr>
							<td colspan="2" width="620">
								<div style="width: 100%; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
								<div style="width: 100%; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
							</td>
						</tr>

						<tr>
							<td style="padding-right:40px; font-size:1.2em; vertical-align:top" width="50%">
								<h3>'.$name.',</h3>
								<p style="padding:0 0 12px 0; margin:0;">You just got a reward!  You can use the following code to get <u>15% off a single item.</u></p>
								<p style="padding:0 0 12px 0; margin:0;">Your coupon will be valid for 90 days from today.</p>
								<p style="padding:0 0 12px 0; margin:0;">Your coupon code is:</p>
								<p><b>'.$code.'</b></p>  
							</td>
							<td>
								<div style="padding:4px; background-color:#d9d9d9">
									<p style="text-align:center"><b>Succcess!</b></p>
									<img src="http://www.mjtrends.com/images/newsletter_images/success.jpg">
								</div>
							</td>
						</tr>

						<tr>
							<td colspan="2" width="620">
								<div style="width: 100%; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
								<div style="width: 100%; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
							</td>
						</tr>

						<tr>
							<td colspan="2"><p style="font-size:1.7em; font-weight:bold; padding:0; margin:0;">New <span style="color:#dd0000">Arrivals</span></p></td>
						</tr>
					</table>

					<table width="620">
						<tr>
							<td>
								<div style="padding:4px; background-color: #d9d9d9; text-align:center;">
									<a href="http://www.mjtrends.com/categories-Cutting-Mat,Notions?adid=611"><img src="http://www.mjtrends.com/prod/rotary-cutting-mat-for-fabric-and-crafts.jpg">Cutting Mat</a>
								</div>
							</td>
							<td>
								<div style="padding:4px; background-color: #d9d9d9; text-align:center;">
									<a href="http://www.mjtrends.com/categories-Rotary-Cutter,Notions?adid=612"><img src="http://www.mjtrends.com/prod/45mm-rotary-cutter.jpg">Rotary Cutter</a>
								</div>
							</td>
							<td>
								<div style="padding:4px; background-color: #d9d9d9; text-align:center;">
									<a href="http://www.mjtrends.com/products.45mm-Single-Blade,Rotary-Cutter,Notions?adid=613"><img src="http://www.mjtrends.com/prod/rotary-cutting-blades-for-latex-sheeting.jpg">Rotary Blades</a>
								</div>
							</td>
							<td>
								<div style="padding:4px; background-color: #d9d9d9; text-align:center;">
									<a href="http://www.mjtrends.com/products.Black,Latex-Seam-Roller,Notions?adid=614"><img src="http://www.mjtrends.com/prod/latex-sheeting-seam-roller.jpg">Latex seam roller</a>
								</div>
							</td>
						</tr>
					</table>
					<br>

					<table width="670" style="font-family: arial;">
						<tr>
							<td>
								<div style="width: 620px; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
								<div style="width: 620px; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
								<div style="width: 620px; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
								<div style="width: 620px; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
								<div style="width: 620px; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
								<div style="width: 620px; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
								<div style="width: 620px; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
								<div style="width: 620px; line-height:2px; height:2px; border-style:dotted; border-width:2px 0 0 0; padding:0; margin:0"></div>
							</td>
						</tr>
					</table>
					<br><span style="font-weight:bold"><span style="color: #3871AB; font-size:1.5em; position:relative; bottom:-4px;">{</span>We help create!  Check out helpful links below<span style="color: #3871AB; font-size:1.5em; position:relative; bottom:-4px;">}</span></span>
					<br><a href="http://www.mjtrends.com/articles.php?adid=615">Vinyl and latex sheeting video tutorials</a>
					<br><a href="http://www.mjtrends.com/forum/index.php?adid=616">Community forums with tons of existing content and helpful posters.</a>
				</body>
			</html>';

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'From: MJTrends <forums@mjtrends.com>' . "\r\n";
		$headers .= 'Bcc: Mike <mharris@mjtrends.com>' . "\r\n";

		// Mail it
		mail($to, $subject, $message, $headers);

	}

	function createCoupon($code, $ckey, $cust_email){
		$sql = "INSERT INTO coupon(code, ckey, start_date, end_date, active, sales_rep, customer) VALUES('$code', $ckey, CURDATE(), DATE_ADD(CURDATE(),INTERVAL 90 DAY), 'rewards', '$cust_email') ";
		$result = $this->db->query($sql);
	}
}