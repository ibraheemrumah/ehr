<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-notes-medical"></i> <?php echo translate('edit') . " " . translate('appointment'); ?></h4>
	</header>
    <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
		<input type="hidden" name="appointment_id" id="appointment_id" value="<?php echo html_escape($appointment['id']); ?>">
		<div class="panel-body">
			<div class="form-group mt-md">
				<label class="col-md-3 control-label"><?php echo translate('patient'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<?php
					echo form_dropdown("patient_id", $patientlist, set_value('patient_id',$appointment['patient_id']), "class='form-control'
					required data-plugin-selectTwo data-width='100%'");
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('doctor'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<?php echo form_dropdown("doctor_id", $doctorlist, set_value('doctor_id',$appointment['doctor_id']), "class='form-control' 
					id='adoctor_id' required data-plugin-selectTwo data-width='100%'");?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('appointment') . " " . translate('date'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="appointment_date" required value="<?php echo html_escape($appointment['appointment_date']); ?>" id="appointment_date" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('time_slot'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<select id="available_schedule" name="available_schedule" data-plugin-selectTwo data-width="100%" class="form-control" required>
						<option value=""><?php echo translate('select'); ?></option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('consultation_fees'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="consultation_fees" readonly value="<?php echo html_escape($appointment['consultation_fees']); ?>" id="consultation_fees" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('discount'); ?></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="discount" value="<?php echo html_escape($appointment['discount']); ?>" id="discount" onchange="netBillCalculation()" onkeyup="netBillCalculation()"  />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('net') . " " . translate('payable'); ?></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="net_payable" readonly value="0.00" id="net_payable" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('message'); ?> <span class="required">*</span></label>
				<div class="col-md-6 mb-md">
					<textarea name="remarks" required class="form-control"></textarea>
				</div>
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
</section>

<script type="text/javascript">
	var appointment_id = $('#appointment_id').val();
    $(document).ready(function () {
        $('#appointment_date').datepicker({
        	orientation: "bottom",
            format: "yyyy-mm-dd",
            autoclose: true,
        })

		$("#adoctor_id, #appointment_date").on("change", function() {
			var adoctor_id = $('#adoctor_id').val();
			var appointment_date = $('#appointment_date').val();
			getDoctorSchedule(adoctor_id, appointment_date, 0)
        });

		var doctor_id = "<?php echo html_escape($appointment['doctor_id']); ?>";
		var appointment_date = "<?php echo html_escape($appointment['appointment_date']); ?>";
		var schedule = "<?php echo html_escape($appointment['schedule']); ?>";
		getDoctorSchedule(doctor_id, appointment_date, schedule);
	})

	function getDoctorSchedule(doctor_id, appointment_date, schedule_id){
		if (doctor_id !== "" && appointment_date !== "") {
			$("#available_schedule").html("<option value=''><?php echo translate('exploring'); ?>...</option>");
	        $.ajax({
	            url: "<?php echo base_url('ajax/get_appointment_schedule'); ?>",
	            type: "POST",
	            data: {'appointment_id' : appointment_id, 'appointment_date' : appointment_date, 'doctor_id' : doctor_id, 'schedule_id' : schedule_id},
	            dataType: 'html',
	            success: function (data) {
	            	var response = JSON.parse(data);
	                $('#available_schedule').html(response.schedule);
	                $('#consultation_fees').val(response.fees);
	                netBillCalculation();
	            }
	        });
		}
	}
</script>