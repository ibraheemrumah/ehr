<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (isset($active_request) ? '' : 'active');  ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('appointment') . " " . translate('list'); ?></a>
			</li>
			<li class="<?php echo (!isset($active_request) ? '' : 'active');  ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('appointment') . " " . translate('request'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?php echo isset($active_request) ? '' : 'active';  ?>">
				<div class="mb-md">
					<div class="export_title"><?php echo translate('schedule') . " " . translate('list'); ?></div>
					<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
						<thead>
							<tr>
								<th><?php echo translate('sl'); ?></th>
								<th><?php echo translate('appointment') . " " . translate('id'); ?></th>
								<th><?php echo translate('doctor') . " " . translate('name'); ?></th>
								<th><?php echo translate('patient') . " " . translate('name'); ?></th>
								<th><?php echo translate('date'); ?></th>
								<th><?php echo translate('consultation') . " " . translate('schedule'); ?></th>
								<th><?php echo translate('serial'); ?></th>
								<th><?php echo translate('message'); ?></th>
								<th><?php echo translate('status'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if(!empty($appointmentlist)) { 
								foreach($appointmentlist as $row):
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo html_escape($row['appointment_id']); ?></td>
								<td><?php echo html_escape($row['doctor_name']); ?></td>
								<td><?php echo html_escape($row['patient_name']); ?></td>
								<td><?php echo html_escape(_d($row['appointment_date'])); ?></td>
								<td><?php echo html_escape($this->appointment_model->get_schedule_details($row['doctor_id'], $row['appointment_date'], $row['schedule'])); ?></td>
								<td><?php echo html_escape($row['schedule']); ?></td>
								<td><?php echo html_escape($row['remarks']); ?></td>
								<td><?php 
									$labelMode = "";
									$status = $row['status'];
									if($status == 1) {
										$status = translate('confirmed');
										$labelMode = 'label-success-custom';
									} elseif($status == 2) {
										$status = translate('closed');
										$labelMode = 'label-info-custom';
									} elseif($status == 3) {
										$status = translate('canceled');
										$labelMode = 'label-danger-custom';
									} elseif($status == 4) {
										$status = translate('request');
										$labelMode = 'label label-warning-custom';
									}
									echo "<span class='label " . $labelMode. "'>" . $status . "</span>";
								?></td>
								<td>
									<?php if ($row['status'] == 4){ ?>
										<?php echo btn_delete('appointment/my_request_delete/' . $row['id']); ?>
									<?php } ?>
								</td>
							</tr>
							<?php endforeach; }?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="tab-pane <?php echo ! isset($active_request) ? '' : 'active';  ?>" id="create">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('doctor'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php echo form_dropdown("doctor_id", $doctorlist, "", "class='form-control' id='adoctor_id' required data-plugin-selectTwo data-width='100%'");?>
							<span class="error"><?php echo form_error('doctor_id'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('appointment') . " " . translate('date'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" data-plugin-datepicker name="appointment_date" required value="<?php echo date('Y-m-d'); ?>" id="appointment_date" />
							<span class="error"><?php echo form_error('appointment_date'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('available') . " " . translate('schedule'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<select name="available_schedule" class="form-control" data-plugin-selectTwo data-width="100%" id="available_schedule" required>
								<option value=""><?php echo translate('select'); ?></option>
							</select>
							<span class="error"><?php echo form_error('available_schedule'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('consultation_fees'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="consultation_fees" readonly value="0.00" id="consultation_fees" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('message'); ?> <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
							<textarea name="remarks" required class="form-control"></textarea>
							<span class="error"><?php echo form_error('remarks'); ?></span>
						</div>
					</div>
		
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" name="save" value="1">
									<i class="fas fa-plus-circle"></i> <?php echo translate('request'); ?>
								</button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function () {
		$('#appointment_date, #adoctor_id').on('change', function(){
			var doctor_id = $('#adoctor_id').val();
			var appointment_date = $('#appointment_date').val();
			if (doctor_id !== "" && appointment_date !== "") {
				$('#discount').val('0.00');
				$("#available_schedule").html("<option value=''><?php echo translate('exploring'); ?>...</option>");
	            $.ajax({
	                url: "<?php echo base_url('ajax/get_appointment_schedule'); ?>",
	                type: "POST",
	                data: {'appointment_date' : appointment_date, 'doctor_id' : doctor_id},
	                dataType: 'html',
	                success: function (data) {
	                	var response = jQuery.parseJSON(data);
	                    $('#available_schedule').html(response.schedule);
	                    $('#consultation_fees').val(response.fees);
	                }
	            });
			}
		});
	});
</script>