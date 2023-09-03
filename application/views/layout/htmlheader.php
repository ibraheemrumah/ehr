<head>
	<meta charset="UTF-8">
	<title><?php echo html_escape($title); ?></title>
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta name="author" content="techtune">
	<link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png'); ?>">
	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- Web Fonts  -->
	<link href="<?php echo is_secure('fonts.googleapis.com/css?family=Signika:300,400,600,700'); ?>" rel="stylesheet">
	<!-- Vendor -->
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/css/select2.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/daterangepicker/daterangepicker.css'); ?>">
	<!-- Jquery Datatables CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables/media/css/dataTables.bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/magnific-popup/magnific-popup.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/dropify/css/dropify.min.css'); ?>">
	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/custom-style.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/skins/default.css'); ?>">
	<!-- Sweetalert CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css'); ?>">
	<!-- Custom CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/diagnostic-app.css'); ?>">
	<!-- Jquery JS--> 
	<script src="<?php echo base_url('assets/vendor/jquery/jquery.js'); ?>"></script>
	
	<?php
	if(isset($headerelements)) {
		foreach ($headerelements as $type => $element) {
			if($type == 'css') {
				if(count($element)) {
					foreach ($element as $keycss => $css) {
						echo '<link rel="stylesheet" href="'. base_url('assets/' . $css) . '">' . "\n";
					}
				}
			} elseif($type == 'js') {
				if(count($element)) {
					foreach ($element as $keyjs => $js) {
						echo '<script type="text/javascript" src="' . base_url('assets/' . $js). '"></script>' . "\n";
					}
				}
			}
		}
	}
	?>
	
	<!-- If user have enabled CSRF proctection this function will take care of the ajax requests and append custom header for CSRF -->
	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';
		var theme_mode = '<?php echo ($theme_config['dark_skin'] == 'true' ? 'true' : 'false'); ?>';
		var csrfData = <?php echo json_encode(csrf_jquery_token()); ?>;
		$(function($) {
			$.ajaxSetup({
				data: csrfData
			});
		});
	</script>

	<?php if ($theme_config['border_mode'] == 'false'): ?>
		<link rel="stylesheet" href="<?php echo base_url('assets/css/skins/square-borders.css'); ?>">
	<?php endif; ?>
</head>