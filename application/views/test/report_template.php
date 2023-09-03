<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('template') . " " . translate('list'); ?></a>
			</li>
<?php if (get_permission('test_report_template', 'is_add')){ ?>
			<li class="<?php echo (isset($validation_error) ? 'active' : ''); ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('create') . " " . translate('template'); ?></a>
			</li>
<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<div class="mb-md">
					<div class="export_title"><?php echo translate('lab_test') . " " . translate('template'); ?></div>
					<table class="table table-bordered table-hover table-condensed table_default" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><?php echo translate('sl'); ?></th>
								<th><?php echo translate('template') . " " . translate('name'); ?></th>
								<th><?php echo translate('date'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($result)){ $count = 1; foreach($result as $row): ?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo html_escape($row['name']); ?></td>
								<td><?php echo _d($row['created_at']); ?></td>
								<td>
								<?php if (get_permission('test_report_template', 'is_edit')): ?>
									<a href="<?php echo base_url('test/report_template_edit/' . $row['id']); ?>" class="btn btn-circle btn-default icon" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('test_report_template', 'is_delete')): ?>
									<?php echo btn_delete('test/labreport_template_delete/' . $row['id']); ?>
								<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; }?>
						</tbody>
					</table>
				</div>
			</div>
<?php if (get_permission('test_report_template', 'is_add')){ ?>
			<div class="tab-pane <?php echo (isset($validation_error) ? 'active' : ''); ?>" id="create">
	            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>
					<div class="form-group <?php if (form_error('template_name')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('template') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-7">
							<input type="text" class="form-control" name="template_name" value="<?php echo set_value('template_name'); ?>">
							<span class="error"><?php echo form_error('template_name'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('template')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('template'); ?> <span class="required">*</span></label>
						<div class="col-md-7">
							<textarea name="template" id="editor"><?php echo set_value('template'); ?></textarea>
							<span class="error"><?php echo form_error('template'); ?></span>
						</div>
					</div>
					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" name="save" value="1">
									<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
								</button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php } ?>
		</div>
	</div>
</section>