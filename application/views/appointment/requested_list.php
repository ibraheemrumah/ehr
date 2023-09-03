<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"> <?php echo translate('select_ground'); ?></h4>
	</header>
    <?php echo form_open($this->uri->uri_string()); ?>
		<div class="panel-body">
			<div class="col-md-offset-3 col-md-6 mb-lg">		
				<div class="form-group">
					<label class="control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fas fa-calendar-check"></i></span>
						<input type="text" class="form-control daterange" name="daterange" value="<?php echo set_value('daterange', date("Y/m/d") . ' - ' . date("Y/m/d")); ?>" required />
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-offset-10 col-md-2">
					<button type="submit" name="search" value="1" class="btn btn btn-default btn-block"> <i class="fas fa-filter"></i> <?php echo translate('filter'); ?></button>
				</div>
			</div>
		</footer>
	<?php echo form_close(); ?>
</section>

<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?php echo translate('requested') . " " . translate('list'); ?></h4>
	</header>
	<div class="panel-body">
		<div class="export_title">Appointment Requested List</div>
		<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th><?php echo translate('sl'); ?></th>
					<th><?php echo translate('appointment') . " " . translate('id'); ?></th>
					<th><?php echo translate('doctor') . " " . translate('name'); ?></th>
					<th><?php echo translate('patient') . " " . translate('name'); ?></th>
					<th><?php echo translate('create_at'); ?></th>
					<th><?php echo translate('appointment') . " " .translate('date'); ?></th>
					<th><?php echo translate('consultation') . " " . translate('schedule'); ?></th>
					<th><?php echo translate('serial'); ?></th>
					<th><?php echo translate('message'); ?></th>
					<th><?php echo translate('status'); ?></th>
					<th><?php echo translate('action'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($appointmentlist)){ $count = 1; foreach($appointmentlist as $row): ?>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo html_escape($row['appointment_id']); ?></td>
					<td><?php echo html_escape($row['doctor_name']); ?></td>
					<td><?php echo html_escape($row['patient_name']); ?></td>
					<td><?php echo _d($row['created_at']) . " " . date("h:i a", strtotime($row['created_at'])); ?></td>
					<td><?php echo _d($row['appointment_date']); ?></td>
					<td><?php echo html_escape($this->appointment_model->get_schedule_details($row['doctor_id'], $row['appointment_date'], $row['schedule'])); ?></td>
					<td><?php echo html_escape($row['schedule']); ?></td>
					<td><?php echo html_escape($row['remarks']); ?></td>
					<td><?php 
						$labelMode = "";
						$status = $row['status'];
						if($status == 3) {
							$status = translate('canceled');
							$labelMode = 'label-danger-custom';
						} elseif($status == 4) {
							$status = translate('request');
							$labelMode = 'label label-warning-custom';
						}
						echo "<span class='label " . $labelMode . "'>" . $status . "</span>";
					?></td>
					<td class="min-w-c">
						<?php if (get_permission('appointment_request', 'is_edit')): ?>
							<a href="javascript:void(0);" onclick="viewDetails(<?php echo html_escape($row['id']); ?>)"  class="btn btn-circle icon btn-default"> 
								<i class="fas fa-bars"></i>
							</a>
						<?php endif; if (get_permission('appointment_request', 'is_delete')): ?>
							<?php echo btn_delete('appointment/request_delete/' . $row['id']); ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; }?>
			</tbody>
		</table>
	</div>
</section>

<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-bars"></i> <?php echo translate('details'); ?></h4>
		</header>
		<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered', 'id' => 'frm_appointment')); ?>
			<input type="hidden" name="appointment_id" id="eappointment_id">
			<input type="hidden" name="doctor_id" id="edoctor_id">
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"><?php echo translate('doctor') . " " . translate('name'); ?></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="doctor_name" readonly required value="" id="edoctor_name" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo translate('appointment') . " " . translate('date'); ?></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="appointment_date" readonly required value="" id="eappointment_date" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo translate('request') . " " . translate('schedule'); ?></label>
					<div class="col-md-9">
                        <select name="schedule_time" id='available_schedule' class="form-control" data-plugin-selectTwo data-width='100%'>
                            <option value=""></option>
                        </select>
                        <span class="error" id="schedule_error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo translate('status'); ?></label>
					<div class="col-md-9 mb-md">
						<?php
						$scheduleStatus = array(
							'' => translate('select'),
							'1' => translate('confirmed'),
							'3' => translate('canceled')
						);
						echo form_dropdown("appointment_status", $scheduleStatus, set_value("appointment_status"), "class='form-control' 
						id='status' data-plugin-selectTwo data-width='100%'");
						?>
						<span class="error" id="status_error"></span>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button class="btn btn-default mr-xs" type="submit" id="savebtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?php echo translate('apply'); ?>
						</button>
						<button class="btn btn-default modal-dismiss"><?php echo translate('close'); ?></button>
					</div>
				</div>
			</footer>
		<?php echo form_close(); ?>
	</section>
</div>

<script type="text/javascript">
	function viewDetails(id) {
		var html_data = "";
		$('#schedule_error').html("");
		$('#status_error').html("");
		$('#available_schedule').html("<option value=''><?php echo translate('select'); ?></option>");
        $.ajax({
            url: "<?php echo base_url('ajax/get_appointment_request_details'); ?>",
            type: 'POST',
            data: {'id': id},
            dataType: "json",
            success: function (data) {
				$('#eappointment_id').val(data.id);
				$('#edoctor_id').val(data.doctor_id);
				$('#edoctor_name').val(data.staff_name);
				$('#eappointment_date').val(data.appointment_date);
            	var schedule_id = data.schedule;
                $.each(data.ava_schedule, function (key, value)
                {
                    var select = "";
                    if (schedule_id == value.id) {
                        select = "selected";
                    }
                    html_data += "<option value='" + value.id + "' " + select + ">" + value.name + "</option>";
                });
                $('#available_schedule').append(html_data);
				mfp_modal('#modal');
            }
        });
	}

    $(document).ready(function (e) {
        $("#frm_appointment").on('submit', (function (e) {
            e.preventDefault();
            var btn = $("#savebtn");
            btn.button('loading');
            $.ajax({
                url: "<?php echo base_url('appointment/approvedRequested'); ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
	                if (data.status == "fail") {
	                	$.each(data.error, function (key, value) {
	                        $('#' + key).html(value);
	                    });  
	                }else{
	                	window.location.reload(true);
	                }
	                btn.button('reset');
                }
            });
        }));
    });
</script>