<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?php echo translate('select_ground'); ?></h4>
			</header>
            <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
				<div class="panel-body">
					<div class="row mb-sm">
	                    <div class="col-md-offset-3 col-md-6 mb-sm">
	                        <div class="form-group">
	                            <label class="control-label"><?php echo translate('role'); ?> <span class="required">*</span></label>
	                            <?php
									$roles = $this->app_lib->getRoles();
	                                echo form_dropdown("staff_role", $roles, set_value('staff_role'), "class='form-control' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
	                            ?>
	                        </div>
	                    </div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-10 col-md-2">
							<button type="submit" name="search" value="search" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?php echo translate('filter'); ?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
		
		<?php if (isset($stafflist)): ?>
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-users"></i> <?php echo translate('deactivate_account') . " " . translate('list'); ?></h4>
			</header>
            <?php echo form_open($this->uri->uri_string()); ?>
				<div class="panel-body mb-md">
					<table class="table table-bordered table-hover table-condensed mb-none table_default">
						<thead>
							<tr>
								<th width="40px">
									<div class="checkbox-replace">
										<label class="i-checks"><input type="checkbox" id="selectAllchkbox"><i></i></label>
									</div>
								</th>
								<th width="80"><?php echo translate('photo'); ?></th>
								<th><?php echo translate('name'); ?></th>
								<th><?php echo translate('staff_id'); ?></th>
								<th><?php echo translate('designation'); ?></th>
								<th><?php echo translate('department'); ?></th>
								<th><?php echo translate('email'); ?></th>
								<th><?php echo translate('mobile_no'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($stafflist as $row): ?>
							<tr>
								<td class="cb-chk-area">
									<div class="checkbox-replace">
										<label class="i-checks"><input type="checkbox" name="views_bulk_operations[]" value="<?php echo html_escape($row->id); ?>"><i></i></label>
									</div>
								</td>
								<td class="center"> <img class="rounded" src="<?php echo $this->app_lib->get_image_url('staff/' . $row->photo); ?>" width="40" height="40" /></td>
								<td><?php echo html_escape($row->name); ?></td>
								<td><?php echo html_escape($row->staff_id); ?></td>
								<td><?php echo html_escape($row->designation_name); ?></td>
								<td><?php echo html_escape($row->department_name); ?></td>
								<td><?php echo html_escape($row->email); ?></td>
								<td><?php echo html_escape($row->mobileno); ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-10 col-md-2">
							<button type="submit" name="auth" value="save" class="btn btn-default btn-block"> <i class="fas fa-unlock-alt"></i> <?php echo translate('authentication_activate'); ?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
		<?php endif; ?>
	</div>
</div>
