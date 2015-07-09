<!--INFOLINKS_OFF-->
<?php

$message = '';
$pixel = '';
$EMAIL="";
$FNAME="";
$giveaway_title="";
$style="";


if ($_POST['submit'] == 'Enter to Win!') {
	$guid = strtoupper(trim($_POST['guid']));
	$EMAIL = trim($_POST['EMAIL']);
	$FNAME = trim($_POST['FNAME']);
	
	if ($EMAIL == '') {
		$message = 'Email address is invalid';
	}
	
	if (!eregi("^[A-Za-z0-9\._-]+[@]{1,1}[A-Za-z0-9-]+[\.]{1}[A-Za-z0-9\.-]+[A-Za-z]$", $EMAIL)) { $message = 'Email address is invalid'; }
	list($prefix, $domain) = split("@",$EMAIL);
	if (!getmxrr($domain, $mxhosts)) { $message = 'Email address is invalid'; }
	
	include_once("functions.php");
	if (LookupImpressionWise($EMAIL) == false) { $message = "Invalid email address!"; }
	if (BullseyeBriteVerifyCheck($EMAIL) == false) { $message = "Invalid email address!"; }
	
	if ($message == '') {
		
		switch($guid) {
            /*
			case '7173914311CC4DABB4C023DD53817A4B':
				$subcampid = '3497';
				break;
			case '1C63079B477A49FAA318655E0990F91E':
				$subcampid = '3498';
				break;
			case 'E0817722B67F4147BFF01C1DC8319F89':
				$subcampid = '3595';
				break;
			case '8C5FA101A6BB478486CDB870D67AA600':
				$subcampid = '3907';	// Shop At Home
				break;
			case 'A31CE44999AC48FE920D61E9774C7AE2':
				$subcampid = '3641';	// Facebook paid search
				break;
			case '9CB41935EFFC43B68753D4B6562EC716':
				$subcampid = '3779';	// Adjump
				break;
			case 'AA7AB7FDD5484E44980E4F504BC96C54':
				$subcampid = '3605';
				break;
			case '5D419A5D0F124FBEAA4A2DEBAB01DA6F':
				$subcampid = '3628';	// Quick Rewards
				break;
                */
			default:
				$subcampid = '4341';    // WIM Default Giveaway 0615
		}
		
		$ipaddr = trim($_SERVER['REMOTE_ADDR']);
		
		$FNAME = addslashes($FNAME);
		
		$fire_cake_pixel = "";
		// check for dupes before signing up...
		$dupes_response = strtoupper(file_get_contents("http://r4l.popularliving.com/check_record.php?email=$EMAIL&type=emailpluslistid&listid=410,448,411"));
		if (strstr($dupes_response, 'TRUE')) {
			$fire_cake_pixel = "<iframe src='http://sinettrk.com/p.ashx?o=13330&t=$EMAIL' height='1' width='1' frameborder='0'></iframe>";
		}
		
		$sPostingUrl = "http://fitfab.popularliving.com/wim_api_giveaway.php?email=$EMAIL&sublists=410,448,411&subcampid=$subcampid&ipaddr=$ipaddr&keycode=kfdj49358gkj359gjk55&fname=$FNAME";
		$response = strtolower(file_get_contents($sPostingUrl));

		$site_domain = trim($_SERVER['SERVER_NAME']);
		
		setcookie("EMAIL_ID", $EMAIL, time()+642816000, "/", ".workitmom.com");
		
		$gtm_pixel = "<!-- Google Tag Manager -->
				<noscript><iframe src=\"//www.googletagmanager.com/ns.html?id=GTM-WRKVZZ\"
				height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
				<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','GTM-WRKVZZ');
				dataLayer.push({'event': 'giveawayfitandfabliving'});</script>
				<!-- End Google Tag Manager -->";
		
		$message = 'Success! Check your email to confirm sign up.'.'<iframe frameborder="0" width="1" height="1" src="http://'.$site_domain.'/giveaway/thankyou.htm"></iframe>'."<img src='http://jmtkg.com/plant.php?email=$EMAIL' width=0 height=0></img>".$fire_cake_pixel.$gtm_pixel;
		
		$pixel = "<img src='http://fitfab.popularliving.com/subctr/forms/stats.php?a=s&f=WIMGiveaway$guid' width='0' height='0' border='0' />";
		$style = "color:green;font-weight:bold;";
		$EMAIL = '';
		$FNAME = '';
		$LNAME = '';
		
		$thankyou_page = "http://www.workitmom.com/giveaway/thankyou.htm?e=$email";
		
		echo "<tr><td colspan='2' style='color:red;padding:20px;' align='center' valign='top'>&nbsp;</td></tr><script type='text/javascript'>window.top.location = '$thankyou_page';</script>";
	} else {
		$style = "color:red;font-weight:bold;";
	}
} else {
	$guid = strtoupper(trim($_GET['guid']));
	$pixel = "<img src='http://fitfab.popularliving.com/subctr/forms/stats.php?a=d&f=WIMGiveaway$guid' width='0' height='0' border='0' />";
}

if (date('m') == 8) {
	$giveaway_title = "Win A Classic Box by The Baby Box Company!";
	$giveaway_text = "At Work It Mom, we give away some of the best mom gear out there, like The Classic Box by The Baby Box Company! Just sign up for our newsletters and you'll also be enetered to win! Entries will be accepted until August 31, 2014 at 11:59 PM CDT. Good luck!";
	$giveaway_top_img = "";
	$giveaway_right_img = "http://pics.workitmom.com/giveaway/BabyBox_Product.jpg";
	$giveaway_extra_right_img = '<br><br><img src="http://pics.workitmom.com/giveaway/BabyBox_Logo.png" style="max-width:300px;">';
}

if (date('m') == 9 || date('m') == 10) {
	$giveaway_title = "Win a fitmark sports tote - now extended through October!";
	$giveaway_text = "At Work It Mom, we give away some of the best mom gear out there, like The Fitmark Sports Tote! We've extended the giveaway so just sign up for our newsletters and you'll also be entered to win! Entries will be accepted until October 31, 2014 at 11:59 PM CDT. Good luck!";
	$giveaway_top_img = "";
	$giveaway_right_img = "http://pics.workitmom.com/giveaway/Fitmark_Product.png";
	$giveaway_extra_right_img = '<br><br><img src="http://pics.workitmom.com/giveaway/Fitmark_Logo.png" style="max-width:210px;">';
}

if (date('m') == 11 || date('m') == 12) {
	$giveaway_title = "Win Personalized Coasters And Clutchette Power!";
	$giveaway_text = "Hatch.Co Personalized Coasters: This set of 4 personalized coasters is custom made for you with your own photographs on travertine stone tile! What a great way to display memories from your favorite vacations, family events, or cherished pictures of family and pets! Each coaster measures just under 4x4 inches and is given 2 layers of protective sealer to withstand any hot or cold glasses. 
<br><br>
Chicbuds.com Clutchette Power: With Clutchette by Chic Buds, phone charging convenience is in the bag...literally! Inside Each Clutchette is a lightweight, ultrathin battery to recharge smart phones and USB devices on-the-go.  Clutchette Power is the secret weapon every modern Wonder Woman needs to charge through the day on full power.<br><br>
 We've extended the giveaway so just sign up for our newsletters and you'll also be entered to win! Entries will be accepted until December 31, 2014 at 11:59 PM CDT. Good luck!";
	$giveaway_top_img = "";
	$giveaway_right_img = "http://pics.workitmom.com/giveaway/Coasters-And-Power.jpg";
	$giveaway_extra_right_img = '';
}

if (date('m') == 3) {
	$giveaway_title = "Win The Exclusive Impulse Palette!";
	$giveaway_text = "Get a chance to win this exclusive Impulse Palette from Macy's simply by signing up for our newsletter below. This neutral palette has 20 assorted eye shadows in matte and shimmer and 3 blushes/bronzers in the exclusive gun metal quilted case that takes you from day to night. The deadline to enter is March 31, 2015 at 11:59 PM CDT. Good luck!";
	$giveaway_top_img = "";
	$giveaway_right_img = "http://pics.workitmom.com/giveaway/Impulse_Palette.jpg";
	$giveaway_extra_right_img = '';
}


function LookupImpressionWise($email_addr) {
	$isValid = true;
	$isValid_msg = 'Y';
	$sPostingUrl = "http://post.impressionwise.com/fastfeed.aspx?code=560020&pwd=SilCar&email=$email_addr";
	$response = strtolower(file_get_contents($sPostingUrl));
	
	//	code=560020&pwd=SilCar&email=testme@impressionwise.com&result=Key&NPD=NA&TTP=0.16
	$pieces = explode("&", $response);
	foreach ($pieces as $pair) {
		$data = explode("=", $pair);
		$$data[0] = $data[1];
	}
	
	if($npd=='041')
	{
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$today = date('Y-m-d H:m:s');		
		return true;
	}
	
	if (in_array($result, array("invalid", "seed", "trap", "mole"))) {
		$isValid = false;
		$isValid_msg = 'N';
	}	
	return $isValid;
}

function BullseyeBriteVerifyCheck ($email) {

	$emailInfo = array();
	if(!empty($email))
	{		
		$url = "https://bpi.briteverify.com/emails.json?address=$email&apikey=ad6d5755-ff3e-4a0b-8d63-c61bcffd57b1";
		$content = file_get_contents($url);
		$emailInfo = json_decode($content, true);
		
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	}
	
	if(!empty($emailInfo) && ($emailInfo["status"]=="valid" || $emailInfo["status"]=="unknown" || $emailInfo["status"]=="accept all" || $emailInfo["status"]=="accept_all"))
	{
		return true;
	}
	else
	{
		return false;
	}
}
?>
<html>
<head>
<title>Workitmom.com Giveaway - <?php echo $giveaway_title; ?></title>
<script language="JavaScript">
function check_fields() {
	if (document.getElementById('EMAIL').value == '') {
		alert ("* Please enter your email address.\n");
		return false;
	}
	if (document.getElementById('AGREE').checked == false) {
		alert ("* You must agree to terms and conditions.\n");
		return false;
	}
	return true;
}
</script>
<style>
* {
	/*font: 12px Arial, Helvetica, sans-serif;*/
	line-height: 1.25em; /* = 20px */
	/*color: #4e4e4e;*/
}
</style>
</head>
<body>

<div id="landing_title" class="block rounded-630-landing" style="width:630px;">
	<div class="top"></div>
	<div class="content" style="padding-left:10px;">
		<h1><?php echo $giveaway_title; ?></h1>
	</div>
	<div class="bot"></div>
</div>
<table width="750px">
  <tr>
	<td rowspan="2" align="center" valign="top"><img style="max-width:380px;" src="<?php echo $giveaway_right_img; ?>"><?php echo $giveaway_extra_right_img; ?></td>
	<td style="border:none;">		
		<?php if($giveaway_top_img!=""){ ?><p><img src="<?php echo $giveaway_top_img; ?>"></p><?php }?>
		<p style="font: 12px Arial, Helvetica, sans-serif;width:540px;padding-bottom: 25px;">
			<?php echo $giveaway_text; ?>
		</p>
		<p></p>
	</td>
  </tr>
<tr>
	<td valign="top" align="left">
	<!-- form starts -->
			<link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
			<style type="text/css">
				#mc_embed_signup{background:#E8E8E8; clear:left; font:14px Helvetica,Arial,sans-serif;  width:300px; margin:0 auto;}
				/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
				   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
				#mc_embed_signup .asterisk {color:#c60; font-size:125%;}
			</style>
			<div id="mc_embed_signup">
			<form action="/giveaway" method="POST">
			<input type="hidden" name="guid" id="guid" value="<?php echo $guid; ?>">
			<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
			<div class="mc-field-group">
				<label for="mce-FNAME">First Name  
			</label>
				<input type="text" value="<?php echo $FNAME; ?>" name="FNAME" class="" id="FNAME">
			</div>
			<div class="mc-field-group">
				<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
			</label>
				<input type="email" value="<?php echo $EMAIL; ?>" name="EMAIL" id="EMAIL">
			</div>
			<div class="mc-field-group input-group">
			    <strong></strong>
			    <ul><li><input type="checkbox" value="1" name="AGREE" id="AGREE"><label for="AGREE">
			    I understand that by subscribing, I will also receive special offers from third party partners, and agree to Work It Mom  
					<a href="/terms" target="_blank">Terms of Use</a>, and <a href="/privacy" target="_blank">Privacy Policy</a>.
			    </label></li>
			</ul>
			</div>
				<div id="mce-responses" style="clear:both;">
					<div style="<?php echo $style; ?>"><?php echo $message;echo $pixel; ?><br><br></div>
				</div>	<div style="clear:both;"><input style=" background:#aaa;" type="submit" value="Enter to Win!" name="submit" id="mc-embedded-subscribe" class="button" onclick="return check_fields();"></div>
			</form>
			</div>
			<!--End mc_embed_signup-->
	<!-- form ends -->
	</td>
	
</tr>
</table>

</body>
</html>
