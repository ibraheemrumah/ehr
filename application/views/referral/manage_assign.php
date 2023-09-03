<div class="row">
	<div class="col-md-12">
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
								$role_list = $this->app_lib->getRoles();
								echo form_dropdown("staff_role", $role_list, set_value('staff_role'), "class='form-control' required data-plugin-selectTwo
								data-width='100%' data-minimum-results-for-search='Infinity'");
								?>
							</div>
						</div>
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('commission') . " " . translate('for'); ?> <span class="required">*</span></label>
								<?php
								$test_list = $this->app_lib->getSelectList('lab_test');
								echo form_dropdown("commission_for", $test_list, set_value('commission_for'), "class='form-control' id='commission_for' required data-plugin-selectTwo data-width='100%'");
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-offset-10 col-md-2">
							<button type="submit" name="search" value="1" class="btn btn-default btn-block"><i class="fas fa-filter"></i> <?php echo translate('filter'); ?></button>
						</div>
					</div>
				</div>
			<?php echo form_close(); ?>
		</section>

		<?php if (isset($stafflist)): ?>
		<section class="panel">
			<header class="panel-heading">
				<div class="ref-f-all">
					<input type="number" name="for_everyone" class="form-control" value="" id="percentage_for_all" max="100" placeholder="All Percent Set" />
				</div>
				<h4 class="panel-title"><i class="fas fa-users"></i> <?php echo translate('staff') . " " . translate('list'); ?></h4>
			</header>
            <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
			<div class="panel-body">
				<div class="table-responsive mt-md mb-lg">
					<table class="table table-bordered table-condensed mb-none" id="staffList">
						<thead>
							<tr>
								<th width="60"><?php echo translate('sl'); ?></th>
								<th class="center" width="50px">
									<div class="checkbox-replace"> 
										<label class="i-checks"><input type="checkbox" name="cb_select_staff" id="selectAllchkbox" /><i></i></label>
									</div>
								</th>
								<th><?php echo translate('staff_id'); ?></th>
								<th><?php echo translate('staff_name'); ?></th>
								<th><?php echo translate('designation'); ?></th>
								<th><?php echo translate('department'); ?></th>
								<th><?php echo translate('percentage'); ?> (%)</th>
							</tr>
						</thead>
						<tbody>
							<input type="hidden" name="test_id" value="<?php echo html_escape($test_id)?>" >
							<?php
							$i = 1;
							if (count($stafflist)) {
								foreach ($stafflist as $key => $row):
								?>
							<tr>
								<input type="hidden" name="assign[<?php echo $key; ?>][staff_id]" value="<?php echo html_escape($row->id); ?>" >
								<td><?php echo $i++; ?></td>
								<td class="center cb-chk-area">
									<div class="checkbox-replace"> 
										<label class="i-checks"><input type="checkbox" name="assign[<?php echo $key; ?>][cb_select_staff]" <?php echo ($row->commission_id != 0 ? 'checked' : ''); ?> 
										value="<?php echo html_escape($row->commission_id); ?>" /><i></i></label>
									</div>
								</td>
								<td><?php echo html_escape($row->staff_id); ?></td>
								<td><?php echo html_escape($row->name); ?></td>
								<td><?php echo html_escape($row->designation_name); ?></td>
								<td><?php echo html_escape($row->department_name); ?></td>
								<td>
									<div class="form-group">
										<input type="number" name="assign[<?php echo $key; ?>][percentage]" class="form-control fn_percentage" value="<?php echo html_escape($row->percentage); ?>" max="100" required />
									</div>
								</td>
							</tr>
							<?php
								endforeach;
							}else{
								echo '<tr><td colspan="7"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button type="submit" name="save" value="1" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>
		</section>
		<?php endif; ?>
	</div>
</div>
