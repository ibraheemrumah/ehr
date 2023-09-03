<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width,initial-scale=1" name="viewport">
		<meta name="keywords" content="">
		<meta name="author" content="techtune">
		<meta name="description" content="">
		<title><?php echo translate('password_restoration'); ?></title>
		<link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png'); ?>">
		<!-- Web Fonts  -->
		<link href="<?php echo is_secure('fonts.googleapis.com/css?family=Signika:300,400,600,700'); ?>" rel="stylesheet">		
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
		<script src="<?php echo base_url('assets/vendor/jquery/jquery.js'); ?>"></script>
		<!-- sweetalert js/css -->
		<link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css'); ?>">
		<script src="<?php echo base_url('assets/vendor/sweetalert/sweetalert.min.js'); ?>"></script>
		<!-- login page style css -->
		<link rel="stylesheet" href="<?php echo base_url('assets/login_page/css/style.css'); ?>">
		<script type="text/javascript">
			var base_url = '<?php echo base_url() ?>';
		</script>
	</head>
	<body>
        <div class="auth-main">
            <div class="container">
                <!-- image and information -->
                <div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-12 col-xs-12 fitxt-center">
                    <div class="image-area">
                    <div class="content">
                        <div class="image-hader">
                            <h2><?php echo translate('welcome_to'); ?></h2>
                        </div>
                        <div class="center p-b-img">
                            <img src="<?php echo base_url('uploads/app_image/logo.png'); ?>" height="60" alt="img">
                        </div>
                        <div class="address">
                            <p><?php echo html_escape($global_config['address']); ?></p>
                        </div>			
                        <div class="f-social-links center">
                            <a href="<?php echo html_escape($global_config['facebook_url']); ?>" target="_blank">
                                <span class="fab fa-facebook-f"></span>
                            </a>
                            <a href="<?php echo html_escape($global_config['twitter_url']); ?>" target="_blank">
                                <span class="fab fa-twitter"></span>
                            </a>
                            <a href="<?php echo html_escape($global_config['linkedin_url']); ?>" target="_blank">
                                <span class="fab fa-linkedin-in"></span>
                            </a>
                            <a href="<?php echo html_escape($global_config['youtube_url']); ?>" target="_blank">
                                <span class="fab fa-youtube"></span>
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
                <!-- forgot -->
                <div class="col-lg-6 col-lg-offset-right-1 col-md-6 col-md-offset-right-1 col-sm-12 col-xs-12 no-padding">
                    <div class="sign-area">
                        <div class="sign-hader" style="padding-bottom: 15px;">
                            <img src="<?php echo base_url('uploads/app_image/logo.png'); ?>" height="54" alt="" >
                            <h2><?php echo html_escape($global_config['institute_name']); ?></h2>
                        </div>
						<?php 
							if($this->session->flashdata('reset_res')){
								if($this->session->flashdata('reset_res') == 'TRUE'){
									echo '<div class="alert-msg">Password reset email sent successfully. Check email</div>';
								}elseif($this->session->flashdata('reset_res') == 'FALSE'){
									echo '<div class="alert-msg danger">You entered the wrong Username</div>';
								}
							}
						?>
						<div class="forgot-header">
							<h4><i class="fas fa-fingerprint"></i> <?php echo translate('password_restoration'); ?></h4>
							Enter your username and receive reset instructions via email.
						</div>
						<?php echo form_open($this->uri->uri_string()); ?>
							<div class="form-group <?php if (form_error('username')) echo 'has-error'; ?>">
								<div class="input-group input-group-icon">
									<span class="input-group-addon">
										<span class="icon">
											<i class="far fa-user"></i>
										</span>
									</span>
									<input type="text" class="form-control" name="username" id="username" value="<?php echo set_value('username'); ?>" placeholder="<?php echo translate('username'); ?>" />
								</div>
								<span class="error"><?php echo form_error('username'); ?></span>
							</div>
							<div class="form-group">
								<button type="submit" id="btn_submit" class="btn btn-block ladda-button btn-round">
									<i class="far fa-paper-plane"></i> <?php echo translate('forgot'); ?>
								</button>
							</div>
							<div class="text-center">
								<a href="<?php echo base_url('authentication'); ?>"><i class="fas fa-long-arrow-alt-left"></i> <?php echo translate('back_to_login'); ?></a>
							</div>
							<div class="sign-footer">
								<p><?php echo html_escape($global_config['footer_text']); ?></p>
							</div>
						<?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

		<script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.js'); ?>"></script>
		<script src="<?php echo base_url('assets/vendor/jquery-placeholder/jquery-placeholder.js'); ?>"></script>
		<!-- backstretch js -->
		<script src="<?php echo base_url('assets/login_page/js/jquery.backstretch.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/login_page/js/custom.js'); ?>"></script>
	</body>
</html>