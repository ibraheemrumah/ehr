<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('Service') . " " . translate('list'); ?></a>
			</li>
<?php if (get_permission('lab_test', 'is_add')){ ?>
			<li class="<?php echo (isset($validation_error) ? 'active' : ''); ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('create') . " " . translate('service'); ?></a>
			</li>
<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<div class="mb-md">
					<div class="export_title"><?php echo translate('service') . " " . translate('list'); ?></div>
					<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
						<thead>
							<tr>
								<th width="50"><?php echo translate('sl'); ?></th>
								<th><?php echo translate('service') . " " . translate('category'); ?></th>
								<th><?php echo translate('service') . " " . translate('name'); ?></th>
								<th><?php echo translate('service') . " " . translate('code'); ?></th>
								<th><?php echo translate('patient_price'); ?></th>
								<?php if (get_permission('lab_test', 'is_edit')): ?>
								<th><?php echo translate('production_cost'); ?></th>
								<?php endif; ?>
								<th><?php echo translate('created_by'); ?></th>
								<th><?php echo translate('date'); ?></th>
								<th class="min-w-xs"><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php $count = 1; if (!empty($testlist)){ foreach ($testlist as $row): ?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo html_escape($row['category_name']); ?></td>
								<td><?php echo html_escape($row['name']); ?></td>
								<td><?php echo html_escape($row['test_code']); ?></td>
								<td><?php echo html_escape($global_config['currency_symbol'] . $row['patient_price']); ?></td>
								<?php if (get_permission('lab_test', 'is_edit')): ?>
								<td><?php echo html_escape($global_config['currency_symbol'] . $row['production_cost']); ?></td>
								<?php endif; ?>
								<td><?php echo html_escape($row['creator_name']); ?></td>
								<td><?php echo _d($row['date']); ?></td>
								<td>
									<?php if (get_permission('lab_test', 'is_edit')): ?>
										<a href="<?php echo base_url('service/edit/' . $row['id']); ?>" class="btn btn-circle icon btn-default" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
											<i class="fas fa-pen-nib"></i>
										</a>
									<?php endif; if (get_permission('lab_test', 'is_delete')): ?>
										<?php echo btn_delete('service/delete/' . $row['id']); ?>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; }?>
						</tbody>
					</table>
				</div>
			</div>
<?php if (get_permission('lab_test', 'is_add')){ ?>
			<div class="tab-pane <?php echo (isset($validation_error) ? 'active' : ''); ?>" id="create">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered')); ?>
					<div class="form-group <?php if (form_error('category_id')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('service_category'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$array_categories = $this->app_lib->getSelectList('lab_test_category');
								echo form_dropdown("category_id", $array_categories, set_value('category_id'), "class='form-control' id='category_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"><?php echo form_error('category_id'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('service_name')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('service_name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="test_name" value="<?php echo set_value('test_name'); ?>" />
							<span class="error"><?php echo form_error('service_name'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('servcie_code')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('servcie_code'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="test_code" value="<?php echo set_value('test_code'); ?>" />
							<span class="error"><?php echo form_error('servcie_code'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('production_cost')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('production_cost'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="test" class="form-control" name="production_cost" value="<?php echo set_value('production_cost'); ?>" />
							<span class="error"><?php echo form_error('production_cost'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('patient_price')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('patient_price'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="test" class="form-control" name="patient_price" value="<?php echo set_value('patient_price'); ?>" />
							<span class="error"><?php echo form_error('patient_price'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('date')) echo 'has-error'; ?>">
						<label  class="col-md-3 control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
							<input type="text" class="form-control" name="date" value="<?php echo set_value('date', date('Y-m-d')); ?>" data-plugin-datepicker 
							data-plugin-options='{ "todayHighlight" : true }' />
							<span class="error"><?php echo form_error('date'); ?></span>
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