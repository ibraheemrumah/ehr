<header class="header">
	<div class="logo-env">
		<a href="<?php echo base_url('dashboard'); ?>" class="page-title-icon"><h3 style="color: #777777; padding-left: 10px;text-decoration: none;">KANKIA G.H EHR</h3></a>
		<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

	<div class="header-left hidden-xs">
		<ul class="header-menu">
			<!-- Sidebar Toggle Button -->
			<li>
				<div class="header-menu-icon sidebar-toggle" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
					<i class="fas fa-bars" aria-label="Toggle sidebar"></i>
				</div>
			</li>
			<!-- Full Screen Button -->
			<li>
				<div class="header-menu-icon s-expand"><i class="fas fa-expand"></i></div>
			</li>
        <?php
        if(get_permission('patient', 'is_add') ||
        get_permission('lab_test_bill', 'is_add') ||
        get_permission('commission_withdrawal', 'is_view') ||
        get_permission('appointment', 'is_add') ||
        get_permission('voucher', 'is_view') ||
        get_permission('accounting_reports', 'is_view')){
        ?>
			<!-- Shortcut Box -->
			<li>
				<div class="header-menu-icon dropdown-toggle" data-toggle="dropdown">
					<i class="fas fa-th"></i>
				</div>
				<div class="dropdown-menu header-menubox">
					<div class="short-q">
						<div class="menu-icon-grid">
						<?php if(get_permission('patient', 'is_add')){ ?>
							<a href="<?php echo base_url('patient/create'); ?>"><i class="fas fa-wheelchair"></i> <?php echo translate('create') . " " . translate('patient'); ?></a>
						<?php } if(get_permission('lab_test_bill', 'is_add')) { ?>
							<a href="<?php echo base_url('billing/test_bill_add'); ?>"><i class="fas fa-donate"></i> <?php echo translate('create') . " " . translate('test') . " " . translate('bill'); ?></a>
						<?php } if(get_permission('commission_withdrawal', 'is_view')) { ?>
							<a href="<?php echo base_url('referral/withdrawal'); ?>"><i class="fas fa-hand-holding-usd"></i> <?php echo translate('commission') . " " . translate('withdrawal'); ?></a>
						<?php } if(get_permission('appointment', 'is_add')){ ?>
							<a href="<?php echo base_url('appointment/add'); ?>"><i class="fas fa-notes-medical"></i> <?php echo translate('create') . " " . translate('appointment'); ?></a>
						<?php } if(get_permission('voucher', 'is_view')){ ?>
							<a href="<?php echo base_url('accounts/voucher'); ?>"><i class="fas fa-money-check"></i> <?php echo translate('create') . " " . translate('voucher'); ?></a>
						<?php } if(get_permission('accounting_reports', 'is_view')){ ?>
							<a href="<?php echo base_url('accounts/incomevsexpense'); ?>"><i class="fas fa-chart-line"></i> <?php echo translate('income_vs_expense'); ?></a>
						<?php } ?>
						</div>
					</div>
				</div>
			</li>
		<?php } ?>
		</ul>

		<?php if (get_permission('patient', 'is_view')) { ?>
		<!-- Search Box -->
		<span class="separator hidden-sm"></span>
        <form method="post" action="<?php echo base_url('patient/search'); ?>" class="search nav-form">
            <?php echo $this->app_lib->generate_csrf(); ?>
			<div class="input-group input-search">
				<input type="text" class="form-control" name="search_text" placeholder="<?php echo translate('patient') . " " . translate('search'); ?>">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
				</span>
			</div>
		<?php echo form_close(); ?>
		<?php } ?>
	</div>

	<div class="header-right">
		

		<?php
			if (loggedin_role_id() == 7) {
				$userProfileImg = $this->app_lib->get_image_url($this->session->userdata('logger_photo'));
			}else{
				$userProfileImg = $this->app_lib->get_image_url('staff/' . $this->session->userdata('logger_photo'));
			}
		?>
		<!-- Profile Box -->
		<span class="separator"></span>
		<div id="userbox" class="userbox">
			<a href="#" data-toggle="dropdown">
				<figure class="profile-picture">
					<img src="<?php echo html_escape($userProfileImg); ?>" alt="user-image" height="35">
				</figure>
			</a>
			<div class="dropdown-menu">
				<ul class="dropdown-user list-unstyled">
					<li class="user-p-box">
						<div class="dw-user-box">
							<div class="u-img">
								<img src="<?php echo html_escape($userProfileImg); ?>" alt="user">
							</div>
							<div class="u-text">
								<h4><?php echo html_escape($this->session->userdata('name')); ?></h4>
								<p class="text-muted"><?php echo ucfirst(loggedin_role_name()); ?></p>
								<a href="<?php echo base_url('authentication/logout'); ?>" class="btn btn-danger btn-xs"><i class="fas fa-sign-out-alt"></i> <?php echo translate('logout'); ?></a>
							</div>
						</div>
					</li>
					<li role="separator" class="divider"></li>
					<li><a href="<?php echo base_url('profile'); ?>"><i class="fas fa-user-shield"></i> <?php echo translate('profile'); ?></a></li>
					<li><a href="<?php echo base_url('profile/password'); ?>"><i class="fas fa-mars-stroke-h"></i> <?php echo translate('reset_password'); ?></a></li>
				<?php if(get_permission('global_setting', 'is_view')) { ?>
					<li role="separator" class="divider"></li>
					<li><a href="<?php echo base_url('settings'); ?>"><i class="fas fa-toolbox"></i> <?php echo translate('global') . " " . translate('settings'); ?></a></li>
				<?php } ?>
					<li role="separator" class="divider"></li>
					<li><a href="<?php echo base_url('authentication/logout'); ?>"><i class="fas fa-sign-out-alt"></i> <?php echo translate('logout'); ?></a></li>
				</ul>
			</div>
		</div>
	</div>
</header>