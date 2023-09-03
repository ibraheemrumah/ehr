<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('test/report_template'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('template') . " " . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('template'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="edit">
	            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>
	            <input type="hidden" name="id" value="<?php echo html_escape($template['id']); ?>">
					<div class="form-group <?php if (form_error('template_name')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('template') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-7">
							<input type="text" class="form-control" name="template_name" value="<?php echo set_value('template_name', html_escape($template['name'])); ?>" />
							<span class="error"><?php echo form_error('template_name'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('template')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('template'); ?> <span class="required">*</span></label>
						<div class="col-md-7">
							<textarea name="template" id="editor"><?php echo set_value('template', $template['template']); ?></textarea>
							<span class="error"><?php echo form_error('template'); ?></span>
						</div>
					</div>
					<footer class="panel-footer">
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