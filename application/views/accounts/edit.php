<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('accounts'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('account') . " " . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('account'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="create">
	            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered')); ?>
	            <input type="hidden" name="account_id" value="<?php echo html_escape($account['id']); ?>">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('account') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="account_name" value="<?php echo set_value('account_name', html_escape($account['name'])); ?>" />
							<span class="error"><?php echo form_error('account_name'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('description'); ?></label>
						<div class="col-md-6 mb-sm">
							<textarea class="form-control" id="description" name="description" placeholder="" rows="3"><?php echo set_value('description', html_escape($account['description'])); ?></textarea>
						</div>
					</div>
					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" name="save" value="1">
									<i class="fas fa-edit"></i> <?php echo translate('update'); ?>
								</button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>