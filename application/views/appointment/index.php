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
		<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?php echo translate('appointment') . " " . translate('list'); ?></h4>
	</header>
	<div class="panel-body">
		<div class="export_title"><?php echo translate('appointment') . " " . translate('list'); ?></div>
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
					<th><?php echo translate('remarks'); ?></th>
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
						}
						echo "<span class='label " . $labelMode. "'>" . $status . "</span>";
					?></td>
					<td>
						<?php
						if ($row['status'] != 2){
						if (get_permission('appointment', 'is_edit')): ?>
							<a href="javascript:void(0);" class="btn btn-circle icon btn-default" data-toggle="tooltip" data-original-title="<?php echo translate('make_closed'); ?>" onclick="make_closed(<?php echo html_escape($row['id']); ?>)" > 
								<i class="fas fa-check"></i>
							</a>
						<?php endif; if (get_permission('appointment', 'is_edit')): ?>
							<a href="<?php echo base_url('appointment/edit/' . $row['id']); ?>" class="btn btn-circle icon btn-default" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
								<i class="fas fa-pen-nib"></i>
							</a>
						<?php endif; } if (get_permission('appointment', 'is_delete')): ?>
							<?php echo btn_delete('appointment/delete/' . $row['id']); ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; }?>
			</tbody>
		</table>
	</div>
</section>

<script type="text/javascript">
	function make_closed(id) {
		swal({
			title: "<?php echo translate('are_you_sure'); ?>",
			text: "<?php echo translate('the_consultation_completed'); ?> ?",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn btn-default swal2-btn-default",
			cancelButtonClass: "btn btn-default swal2-btn-default",
			confirmButtonText: "<?php echo translate('yes_continue'); ?>",
			cancelButtonText: "<?php echo translate('cancel'); ?>",
			buttonsStyling: false
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: "<?php echo base_url('appointment/schedule_closed/'); ?>" + id,
					success:function(data) {
						swal({
						title: "<?php echo translate('done'); ?>",
						text: "<?php echo translate('the_consultation_has_been_closed'); ?>",
						buttonsStyling: false,
						showCloseButton: true,
						focusConfirm: false,
						confirmButtonClass: "btn btn-default swal2-btn-default",
						type: "success"
						}).then((result) => {
							if (result.value) {
								window.location.reload(true);
							}
						});
					}
				});
			}
		});
	}
</script>