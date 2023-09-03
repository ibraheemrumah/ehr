<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-notes-medical"></i> <?php echo translate('create') . " " . translate('appointment'); ?></h4>
	</header>
	<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
		<div class="panel-body">
			<div class="form-group mt-md">
				<label class="col-md-3 control-label"><?php echo translate('patient'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<?php
						$patientlist = $this->app_lib->getPatientList();
						echo form_dropdown("patient_id", $patientlist, set_value('patient_id'), "class='form-control' required data-plugin-selectTwo data-width='100%'");
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('doctor'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<?php echo form_dropdown("doctor_id", $doctorlist, "", "class='form-control' id='adoctor_id' required data-plugin-selectTwo data-width='100%'");?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('appointment') . " " . translate('date'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="text" class="form-control" data-plugin-datepicker name="appointment_date" required value="<?php echo date('Y-m-d'); ?>" id="appointment_date" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('time_slot'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<select name="available_schedule" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="available_schedule" required>
						<option value=""><?php echo translate('select'); ?></option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('consultation_fees'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="consultation_fees" readonly value="0.00" id="consultation_fees" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('discount'); ?></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="discount" value="0.00" id="discount" onchange="netBillCalculation()" onkeyup="netBillCalculation(this.value)"  />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('net') . " " . translate('payable'); ?></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="net_payable" readonly value="0.00" id="net_payable" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
				<div class="col-md-6 mb-md">
					<textarea name="remarks" class="form-control"></textarea>
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
	                    netBillCalculation();
	                }
	            });
			}
		});
	});
</script>