<!-- START OF DHTML -->
<script type="text/javascript" src="/frontend/base/js/jquery.min.js,jquery.fancybox-1.3.4.pack.js,jquery.cookie.js"></script>
<style>
#fancybox-overlay { position:absolute;top:0;left:0;width:100%;z-index:1100;display:none; }
#fancybox-wrap { position:absolute;z-index:1101;outline:none;display:none;position:absolute;top:20px !important; }
#fancybox-outer { position:relative;width:100%;height:100%;background:#fff; }
#fancybox-close { position:absolute;top:-15px;right:-15px;width:30px;height:30px;cursor:pointer;z-index:1103;display:none;background:transparent url('http://pics.workitmom.com/fancy_close.png'); }
#fancybox-frame { width:100%;height:100%;border:none;display:block; }
</style>
<?php if (strtoupper(trim($_GET['dhtml'])) == 'Y') {
	$dhtml_url = 'http://wim.popularliving.com/subctr/forms/wim_3215.php';

	if (strtolower(trim($_GET['dpage'])) == 'destress') { $dhtml_url = 'http://wim.popularliving.com/subctr/forms/wim_3215.php'; }
	if (strtolower(trim($_GET['dpage'])) == 'msn') { $dhtml_url = 'http://wim.popularliving.com/subctr/forms/wim_3332.php'; }
	if (strtolower(trim($_GET['dpage'])) == 'tb') { $dhtml_url = 'http://wim.popularliving.com/subctr/forms/wim_3329.php'; }

	?>
	<script type="text/javascript">
	var wimDhtml = jQuery.noConflict();
	wimDhtml(document).ready(function() {
		wimDhtml.fancybox({
			'width'					: 620,
			'height'				: 440,
			'autoScale'				: false,
			'transitionIn'			: 'elastic',
			'transitionOut'			: 'elastic',
			'type'					: 'iframe',
			'scrolling'				: 'no',
			'padding'				: 0,
			'hideOnOverlayClick'	: false,
			'href'					: '<?php echo $dhtml_url; ?>?src=<?php echo trim($_GET['src']); ?>',
			'overlayColor'			: '#000',
			'overlayOpacity'		: 0.8
		});
	});
	</script>
<?php } else {
	if (strtoupper(trim($_GET['nl_signup'])) == 'Y') {
		/*$creative = strtolower(trim($_GET['c']));
		$email = strtolower(trim($_GET['e']));
		if (!eregi("^[A-Za-z0-9\._-]+[@]{1,1}[A-Za-z0-9-]+[\.]{1}[A-Za-z0-9\.-]+[A-Za-z]$", $email)) { $email = ''; }
		list($prefix, $domain) = split("@",$email);
		if (!getmxrr($domain, $mxhosts)) { $email = ''; }
		$querystring = "?email=$email";
		$url = '/dhtml/WantMore.php';
		if ($email !='') { ?>
			<script type="text/javascript">
				var WIMNLdhtml = jQuery.noConflict();
				function closethis() {
					WIMNLdhtml.fancybox.close();
				}
				function callFancyBoxiFrame() {
					WIMNLdhtml(document).ready(function() {
						WIMNLdhtml.fancybox({
							'width'					: '43',
							'height'				: '41',
							'autoScale'				: false,
							'transitionIn'			: 'elastic',
							'transitionOut'			: 'elastic',
							'type'					: 'iframe',
							'scrolling'				: 'no',
							'padding'				: 0,
							'hideOnOverlayClick'	: false,
							'href'					: '<?php echo $url; ?><?php echo $querystring; ?>',
							'overlayColor'			: '#000',
							'overlayOpacity'		: '.30',
							'showCloseButton'		: true
						});
					});
				}
				WIMNLdhtml.cookie('wim_first_time_dhtml', 'true', { path: '/', expires: 30}); // disable other popup
				window.setTimeout("callFancyBoxiFrame();", 10000);
			</script>
		<?php }*/
	} else {
		$random_num = mt_rand(1,3);
		if ($random_num == 1) {
			$dhtml_url = "http://wim.popularliving.com/subctr/forms/wim_1st_time_visitor2.php";
		}
		if ($random_num == 2) {
			$dhtml_url = "http://wim.popularliving.com/subctr/forms/wim_1st_time_visitor3.php";
		}
		if ($random_num == 3) {
			$dhtml_url = "http://wim.popularliving.com/subctr/forms/wim_1st_time_visitor5.php";
		}
		?>
		<script type="text/javascript">
		var wimFirstPopup = jQuery.noConflict();
		wimFirstPopup(document).ready(function() {
			<?php if (isset($_GET['gclid']) && $_GET['gclid'] !='') { ?>
				wimFirstPopup.cookie('wim_first_time_dhtml', 'true', { path: '/', expires: 30});
			<?php } ?>
			if (!(wimFirstPopup.cookie('wim_first_time_dhtml'))) {
				wimFirstPopup.fancybox({
					'width'					: 620,
					'height'				: 440,
					'autoScale'				: false,
					'transitionIn'			: 'elastic',
					'transitionOut'			: 'elastic',
					'type'					: 'iframe',
					'scrolling'				: 'no',
					'padding'				: 0,
					'hideOnOverlayClick'	: false,
					'href'					: '<?php echo $dhtml_url; ?>',
					'overlayColor'			: '#000',
					'overlayOpacity'		: 0.8,
					'onClosed'				: function() {
											    wimFirstPopup.cookie('wim_first_time_dhtml', 'true', { path: '/', expires: 30});
											}
				});
			}
		});
		</script>
	<?php } ?>
<?php } ?>
<!-- END OF DHTML -->
