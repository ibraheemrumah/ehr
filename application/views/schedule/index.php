<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('schedule') . " " . translate('list'); ?></a>
			</li>
<?php if (get_permission('schedule', 'is_add')){ ?>
			<li class="<?php echo (isset($validation_error) ? 'active' : ''); ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('create') . " " . translate('schedule'); ?></a>
			</li>
<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<div class="mb-md">
					<div class="export_title"><?php echo translate('schedule') . " " . translate('list'); ?></div>
					<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
						<thead>
							<tr>
								<th><?php echo translate('sl'); ?></th>
								<th><?php echo translate('doctor'); ?></th>
								<th><?php echo translate('consultation_fees'); ?></th>
								<th><?php echo translate('week_day'); ?></th>
								<th><?php echo translate('time_start'); ?></th>
								<th><?php echo translate('time_end'); ?></th>
								<th><?php echo translate('per_patient_duration'); ?></th>
								<th width="130"><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(!empty($schedulelist)) {
								$count = 1;
								foreach($schedulelist as $row): ?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo html_escape($row['doctor_name']); ?></td>
								<td><?php echo html_escape($currency_symbol . number_format($row['consultation_fees'], 2, '.', '')); ?></td>
                                <td><?php echo html_escape($row['day']); ?></td>
                                <td><?php echo html_escape(date("h:i A", strtotime($row['time_start']))); ?></td>
                                <td><?php echo html_escape(date("h:i A", strtotime($row['time_end']))); ?></td>
                                <td><?php echo html_escape($row['per_patient_time']); ?></td>
								<td>
									<?php if (get_permission('schedule', 'is_edit')): ?>
										<a href="<?php echo base_url('schedule/edit/' . $row['id']); ?>" class="btn btn-circle icon btn-default" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
											<i class="fas fa-pen-nib"></i>
										</a>
									<?php endif; ?>
									<?php if (get_permission('schedule', 'is_delete')): ?>
										<?php echo btn_delete('schedule/delete/' . $row['id']); ?>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; }?>
						</tbody>
					</table>
				</div>
			</div>
<?php if (get_permission('schedule', 'is_add')){ ?>
			<div class="tab-pane <?php echo (isset($validation_error) ? 'active' : ''); ?>" id="create">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
					<div class="form-group <?php if (form_error('doctor_id')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('doctor'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php echo form_dropdown("doctor_id", $doctorlist, set_value('doctor_id'), "class='form-control' data-plugin-selectTwo data-width='100%'");?>
							<span class="error"><?php echo form_error('doctor_id'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('consultation_fees')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('consultation_fees'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="consultation_fees" value="<?php echo set_value('consultation_fees'); ?>" />
							<span class="error"><?php echo form_error('consultation_fees'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('week_day'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$days = array(
									'' => translate('select'),
									'Sunday' => 'Sunday',
									'Monday' => 'Monday',
									'Tuesday' => 'Tuesday',
									'Wednesday' => 'Wednesday',
									'Thursday' => 'Thursday',
									'Friday' => 'Friday',
									'Saturday' => 'Saturday'
								);
								echo form_dropdown("week_day", $days, set_value('week_day'), "class='form-control' data-plugin-selectTwo data-width='100%'");
							?>
							<span class="error"><?php echo form_error('week_day'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('time_slot'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="row">
								<div class="col-xs-6">
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-clock"></i></span>
										<input type="text" data-plugin-timepicker class="form-control" name="time_start" value="<?php echo set_value('time_start'); ?>" required />
									</div>
								</div>
								<div class="col-xs-6">
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-clock"></i></span>
										<input type="text" data-plugin-timepicker class="form-control" name="time_end" value="<?php echo set_value('time_end'); ?>" required />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php if (form_error('per_patient')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('per_patient_duration'); ?> <span class="required">*</span></label>
						<div class="col-md-6 mb-sm">
							<input type="number" class="form-control" name="per_patient" placeholder="Duration In Minute" value="<?php echo set_value('per_patient'); ?>" />
							<span class="error"><?php echo form_error('per_patient'); ?></span>
						</div>
					</div>
					<footer class="panel-footer">
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