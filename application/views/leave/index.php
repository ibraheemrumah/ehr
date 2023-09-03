<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (empty(validation_errors()) ? 'active' : ''); ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('leave') . " " . translate('list'); ?></a>
			</li>
			<li class="<?php echo (!empty(validation_errors()) ? 'active' : ''); ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('request') . " " . translate('leave'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?php echo (empty(validation_errors()) ? 'active' : ''); ?>">
				<table class="table table-bordered table-condensed table-hover mb-none table_default" >
					<thead>
						<tr>
							<th><?php echo translate('sl'); ?></th>
							<th><?php echo translate('staff_name'); ?></th>
							<th><?php echo translate('leave') . " " . translate('type'); ?></th>
							<th><?php echo translate('date_of_start'); ?></th>
							<th><?php echo translate('date_of_end'); ?></th>
							<th><?php echo translate('days'); ?></th>
                            <th><?php echo translate('apply') . " " . translate('date'); ?></th>
							<th><?php echo translate('status'); ?></th>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $count = 1; if (count($leaves)) { foreach($leaves as $row) { ?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo (!empty($row['orig_file_name']) ? '<i class="fas fa-paperclip"></i> ' : ''); ?><?php echo html_escape($row['staff_name']); ?></td>
							<td><?php echo html_escape($row['type_name']); ?></td>
							<td><?php echo html_escape(_d($row['start_date'])); ?></td>
							<td><?php echo html_escape(_d($row['end_date'])); ?></td>
							<td><?php echo html_escape($row['leave_days']); ?></td>
							<td><?php echo html_escape(_d($row['apply_date'])); ?></td>
							<td>
								<?php
								if ($row['status'] == 1)
									$status = '<span class="label label-warning-custom text-xs">' . translate('pending') . '</span>';
								else if ($row['status']  == 2)
									$status = '<span class="label label-success-custom text-xs">' . translate('accepted') . '</span>';
								else if ($row['status']  == 3)
									$status = '<span class="label label-danger-custom text-xs">' . translate('rejected') . '</span>';
								echo ($status);
								?>
							</td>
							<td>
								<a href="javascript:void(0);" class="btn btn-circle icon btn-default" onclick="getStaffLeaveDetails('<?php echo html_escape($row['id']); ?>')"><i class="fas fa-bars"></i></a>

								<?php if ($row['status'] == 1 && get_permission('my_leave', 'is_delete')) { ?>
									<?php echo btn_delete('leave/request_delete/' . html_escape($row['id'])); ?>
								<?php } ?>
							</td>
						</tr>
						<?php } } ?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane <?php echo (!empty(validation_errors()) ? 'active' : ''); ?>" id="create">
				<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
					<div class="form-group <?php if (form_error('leave_type_id')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('leave_type'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayCategory = array('' => translate('select'));
								foreach ($leavecategory as $row) {
									$categoryid = $row['id'];
									$arrayCategory[$categoryid] = $row['name'] . " (" . $row['days'] . ")";
								}
								echo form_dropdown("leave_type_id", $arrayCategory, set_value('leave_type_id'), "class='form-control' data-plugin-selectTwo
								data-width='100%' required data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"><?php echo form_error('leave_type_id'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('start_date')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' >
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" class="form-control" name="start_date" required value="<?php echo set_value('start_date', date('Y-m-d', strtotime("+1 day"))); ?>" >
								<span class="input-group-addon">To</span>
								<input type="text" class="form-control" name="end_date" value="<?php echo set_value('end_date', date('Y-m-d', strtotime("+1 day"))); ?>">
							</div>
							<span class="error"><?php echo form_error('start_date'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('reason'); ?></label>
						<div class="col-md-6">
							<textarea class="form-control" name="reason" rows="3"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('attachment'); ?></label>
						<div class="col-md-6 mb-md">
							<input type="file" name="attachment_file" class="dropify" data-height="80" />
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" name="save" value="1" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>

<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-bars"></i> <?php echo translate('details'); ?></h4>
		</header>
		<div class="panel-body">
			<div id="quick_view"></div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss"><?php echo translate('close'); ?></button>
				</div>
			</div>
		</footer>
	</section>
</div>