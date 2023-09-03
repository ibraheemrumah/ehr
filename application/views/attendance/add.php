<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><?php echo translate('select_ground'); ?></h4>
	</header>
    <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
		<div class="panel-body">
			<div class="row mb-sm">
				<div class="col-md-6 mb-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('role'); ?> <span class="required">*</span></label>
	                    <?php
	                        $roleList = $this->app_lib->getRoles();
	                        echo form_dropdown("staff_role", $roleList, set_value('staff_role'), "class='form-control' id='staff_role' required data-plugin-selectTwo data-width='100%'
	                        data-minimum-results-for-search='Infinity' ");
	                    ?>
						<span class="error"><?php echo form_error('staff_role'); ?></span>
					</div>
				</div>
				<div class="col-md-6 mb-sm">
					<div class="form-group <?php if (form_error('date')) echo 'has-error'; ?>">
						<label class="control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
						<div class="input-group">
							<input type="text" class="form-control" data-plugin-datepicker name="date" value="<?php echo set_value('date', date("Y-m-d")); ?>" required />
							<span class="input-group-addon"><i class="fas fa-calendar-check"></i></span>
						</div>
						<span class="error"><?php echo form_error('date'); ?></span>
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

<?php if (isset($stafflist)): ?>
	<section class="panel">
    <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
		<input type="hidden" name="date" value="<?php echo html_escape($date)?>">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-users"></i> <?php echo translate('staff') . " " . translate('list'); ?></h4>
		</header>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-offset-9 col-md-3">
					<div class="form-group mb-sm">
						<label class="control-label"><?php echo translate('select_all'); ?></label>
						<select name="selectall" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" onchange="fn_select_all(this.value)">
							<option value=""><?php echo translate('not_selected'); ?></option>
							<option value="1"><?php echo translate('present'); ?></option>
							<option value="2"><?php echo translate('absent'); ?></option>
							<option value="3"><?php echo translate('holiday'); ?></option>
							<option value="4"><?php echo translate('late'); ?></option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive mb-sm mt-xs">
						<table class="table table-bordered table-hover table-condensed tbr-middle mb-none">
							<thead>
								<tr>
									<th><?php echo translate('sl'); ?></th>
									<th><?php echo translate('staff') . " " . translate('name'); ?></th>
									<th><?php echo translate('staff') . " " . translate('id'); ?></th>
									<th class="min-w-xlg"><?php echo translate('status'); ?></th>
									<th class="min-w-sm"><?php echo translate('remarks'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$count = 1;
								if(count($stafflist)){
									foreach ($stafflist as $key => $row):
									?>
								<tr>
									<td><?php echo $count++; ?></td>
									<td><?php echo html_escape($row['staff_name']); ?></td>
									<td><?php echo html_escape($row['staffid']); ?></td>
									<td>
										<input type="hidden" name="attendance[<?php echo ($key); ?>][staff_id]" value="<?php echo html_escape($row['id']); ?>">
										<input type="hidden" name="attendance[<?php echo ($key); ?>][old_atten_id]" value="<?php echo html_escape($row['atten_id']); ?>">
										<div class="radio-custom radio-success radio-inline">
											<input type="radio" class="spresent" <?php echo ($row['att_status'] == 'P' ? 'checked' : ''); ?> name="attendance[<?php echo $key; ?>][status]" id="present<?php echo $key; ?>" value="P">
											<label for="present<?php echo $key; ?>"><?php echo translate('present'); ?></label>
										</div>

										<div class="radio-custom radio-danger radio-inline">
											<input type="radio" class="sabsent" <?php echo ($row['att_status'] == 'A' ? 'checked' : ''); ?> name="attendance[<?php echo $key; ?>][status]" id="absent<?php echo $key; ?>" value="A">
											<label for="absent<?php echo $key; ?>"><?php echo translate('absent'); ?></label>
										</div>

										<div class="radio-custom radio-info radio-inline">
											<input type="radio" class="sholiday" <?php echo ($row['att_status'] == 'H' ? 'checked' : ''); ?> name="attendance[<?php echo $key; ?>][status]" id="holiday<?php echo $key; ?>" value="H">
											<label for="holiday<?php echo $key; ?>"><?php echo translate('holiday'); ?></label>
										</div>

										<div class="radio-custom radio-inline">
											<input type="radio" class="slate" <?php echo ($row['att_status'] == 'L' ? 'checked' : ''); ?> name="attendance[<?php echo $key?>][status]" id="late<?php echo $key; ?>" value="L">
											<label for="late<?php echo $key; ?>"><?php echo translate('late'); ?></label>
										</div>
									</td>
									<td>
										<input class="form-control" name="attendance[<?php echo $key; ?>][remark]" type="text" value="<?php echo $row['remark']; ?>" />
									</td>
								</tr>
									<?php
										endforeach;
									}else{
										echo '<tr><td colspan="5"><h5 class="text-danger text-center">'.translate('no_information_available').'</td></tr>';
									}
									?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<div class="row">
				<div class="col-md-offset-10 col-md-2">
					<button type="submit" class="btn btn-default btn-block" name="attensave" value="1"><i class="fas fa-plus-circle"></i> <?php echo translate('save') . " " . translate('attendance'); ?></button>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</section>
<?php endif; ?>
