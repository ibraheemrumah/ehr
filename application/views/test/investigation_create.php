<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-vials"></i> <?php echo translate('create') . " " . translate('report'); ?></h4>
	</header>
	<?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
		<div class="panel-body">
			<input type="hidden" name="labtest_report_id" value="<?php echo html_escape($test['id']); ?>">
			<div class="row">
				<div class="col-md-4 mt-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('bill_no'); ?></label>
						<input type="text" class="form-control" name="bill_no" value="<?php echo html_escape($test['bill_no']); ?>" readonly />
					</div>
				</div>
				<div class="col-md-4 mt-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('patient') . " " . translate('name'); ?></label>
						<input type="text" class="form-control" name="patient_name" value="<?php echo html_escape($test['patient_name']); ?>" readonly />
					</div>
				</div>
				<div class="col-md-4 mt-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('referred_by'); ?></label>
						<input type="text" class="form-control" name="referred_by" value="<?php echo html_escape($test['ref_name']); ?>" readonly />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 mt-sm">
					<div class="form-group <?php if (form_error('reporting_date')) echo 'has-error'; ?>">
						<label class="control-label"><?php echo translate('reporting') . " " . translate('date'); ?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="reporting_date" value="<?php echo date('Y-m-d'); ?>" data-plugin-datepicker required />
						<span class="error"><?php echo form_error('reporting_date'); ?></span>
					</div>
				</div>
				<div class="col-md-6 mt-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('template') ?></label>
						<?php
							echo form_dropdown("template_id", $templatelist, set_value('template_id'), "class='form-control' onchange='getReportTemplate(this.value)'
							data-plugin-selectTwo data-width='100%'");
						?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 mt-sm">
					<div class="form-group <?php if (form_error('report_description')) echo 'has-error'; ?>">
						<label class="control-label"><?php echo translate('report'); ?> <span class="required">*</span></label>
						<textarea name="report_description" id="editor"><?php echo set_value('report_description'); ?></textarea>
						<span class="error"><?php echo form_error('report_description'); ?></span>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer mt-lg">
			<div class="row">
				<div class="col-md-3 col-md-offset-9">
					<button type="submit" class="btn btn-default btn-block" name="save" value="1">
						<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
					</button>
				</div>
			</div>	
		</footer>
	<?php echo form_close(); ?>
</section>