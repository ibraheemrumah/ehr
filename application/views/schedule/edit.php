<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="">
				<a href="<?php echo base_url('schedule'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('schedule') . " " . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('schedule'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="create">
	            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
					<input type="hidden" name="schedule_id" value="<?php echo html_escape($schedule['id']); ?>">
					<div class="form-group <?php if (form_error('doctor_id')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('doctor'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
							echo form_dropdown("doctor_id", $doctorlist, set_value('doctor_id', $schedule['doctor_id']), "class='form-control'
							data-plugin-selectTwo data-width='100%'");
							?>
						</div>
					</div>
					<div class="form-group <?php if (form_error('consultation_fees')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('consultation_fees'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="consultation_fees" value="<?php echo set_value('consultation_fees', $schedule['consultation_fees']); ?>" />
							<span class="error"><?php echo form_error('consultation_fees'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('week_day')) echo 'has-error'; ?>">
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
								echo form_dropdown("week_day", $days, set_value('week_day', $schedule['day']), "class='form-control' data-plugin-selectTwo data-width='100%'");
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
										<input type="text" data-plugin-timepicker class="form-control" name="time_start" value="<?php echo set_value('time_start', $schedule['time_start']); ?>" required />
									</div>
								</div>
								<div class="col-xs-6">
									<div class="input-group">
										<span class="input-group-addon"><i class="far fa-clock"></i></span>
										<input type="text" data-plugin-timepicker class="form-control" name="time_end" value="<?php echo set_value('time_end', $schedule['time_end']); ?>" required />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group <?php if (form_error('per_patient')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('per_patient_duration'); ?> <span class="required">*</span></label>
						<div class="col-md-6 mb-sm">
							<input type="number" class="form-control" name="per_patient" value="<?php echo set_value('per_patient', $schedule['per_patient_time']); ?>" />
							<span class="error"><?php echo form_error('per_patient'); ?></span>
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