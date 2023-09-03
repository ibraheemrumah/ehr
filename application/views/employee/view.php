<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<?php
			$this->db->where_not_in('id', array(1,7));
			$roles = $this->db->get('roles')->result();
			foreach ($roles as $role){
			?> 	
			<li class="<?php if ($role->id == $act_role) echo 'active'; ?>">
				<a href="<?php echo base_url('employee/view/'.$role->id); ?>">
					<i class="far fa-user-circle"></i>
					<span class="hidden-xs"> <?php echo html_escape($role->name)?></span>
				</a>
			</li>
			<?php } ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane box active">
				<div class="export_title"><?php echo translate('employee') . " " . translate('list'); ?></div>
				<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
					<thead>
						<tr>
							<th><?php echo translate('sl'); ?></th>
							<th><?php echo translate('photo'); ?></th>
							<th><?php echo translate('name'); ?></th>
							<th><?php echo translate('staff_id'); ?></th>
							<th><?php echo translate('designation'); ?></th>
							<th><?php echo translate('department'); ?></th>
							<th><?php echo translate('email'); ?></th>
							<th><?php echo translate('mobile_no'); ?></th>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1; foreach ($stafflist as $row): ?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td class="center">
								<img class="rounded" src="<?php echo $this->app_lib->get_image_url('staff/' . $row->photo); ?>" width="40" height="40" />
							</td>
							<td><?php echo html_escape($row->name); ?></td>
							<td><?php echo html_escape($row->staff_id); ?></td>
							<td><?php echo html_escape($row->designation_name); ?></td>
							<td><?php echo html_escape($row->department_name); ?></td>
							<td><?php echo html_escape($row->email); ?></td>
							<td><?php echo html_escape($row->mobileno); ?></td>
							<td class="min-w-c">
							<?php
							if ($act_role == 3) { 
								if (get_permission('doctor_short_bio', 'is_add')){
							?>
								<a href="<?php echo base_url('employee/add_short_bio/'.$row->id); ?>" class="btn btn-circle btn-default icon" data-toggle="tooltip" data-original-title="Short Bio">
									<i class="fas fa-book-reader"></i>
								</a>
							<?php }} ?>
							<?php if (get_permission('employee', 'is_edit')): ?>
								<a href="<?php echo base_url('employee/profile/'.$row->id); ?>" class="btn btn-circle btn-default icon" data-toggle="tooltip" data-original-title="<?php echo translate('profile'); ?>">
									<i class="far fa-arrow-alt-circle-right"></i>
								</a>
							<?php endif; if (get_permission('employee', 'is_delete')): ?>
								<?php echo btn_delete('employee/delete/'.$row->id); ?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>