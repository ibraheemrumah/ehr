<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
                <a href="#list" data-toggle="tab">
                    <i class="fas fa-unlock-alt"></i> <span class="hidden-xs"> <?php echo translate('change') . " " . translate('password'); ?></span>
                </a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane box active" id="list">
				<?php echo form_open(base_url('profile/password'), array('class' => 'form-horizontal form-bordered validate')); ?>
					<div class="form-group mt-xs <?php if (form_error('current_password')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('current_password'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="password" class="form-control" name="current_password" value="<?php echo set_value('current_password'); ?>" required />
							<span class="error"><?php echo form_error('current_password'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('new_password')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('new_password'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="password" class="form-control" name="new_password" value="<?php echo set_value('new_password'); ?>" required />
							<span class="error"><?php echo form_error('new_password'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('confirm_password')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('confirm_password'); ?> <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
							<input type="password" class="form-control" name="confirm_password" value="<?php echo set_value('confirm_password'); ?>" required />
							<span class="error"><?php echo form_error('confirm_password'); ?></span>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" name="save" value="1"><i class="fas fa-key"></i> <?php echo translate('update'); ?></button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>