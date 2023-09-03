<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-wheelchair"></i> <?php echo translate('patient') . " " . translate('list'); ?></h4>
			</header>
			<div class="panel-body mb-md">
				<div class="export_title"><?php echo translate('patient') . " " . translate('list'); ?></div>
				<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
					<thead>
						<tr>
							<th><?php echo translate('sl'); ?></th>
							<th><?php echo translate('photo'); ?></th>
							<th><?php echo translate('patient_id'); ?></th>
							<th><?php echo translate('name'); ?></th>
							<th><?php echo translate('category'); ?></th>
							<th><?php echo translate('gender'); ?></th>
							<th><?php echo translate('guardian'); ?></th>
							<th><?php echo translate('blood_group'); ?></th>
							<th><?php echo translate('email'); ?></th>
							<th><?php echo translate('mobile_no'); ?></th>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						if (count($patientlist)) { foreach($patientlist as $row):
						?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td class="center"><img class="rounded" src="<?php echo $this->app_lib->get_image_url('patient/' . $row->photo);?>" width="40" height="40"/></td>
							<td><?php echo html_escape($row->patient_id); ?></td>
							<td><?php echo html_escape($row->name); ?></td>
							<td><?php echo html_escape($row->category_name); ?></td>
							<td><?php echo html_escape(ucfirst($row->sex)); ?></td>
							<td><?php echo html_escape($row->guardian); ?></td>
							<td><?php echo html_escape($row->blood_group); ?></td>
							<td><?php echo html_escape($row->email); ?></td>
							<td><?php echo html_escape($row->mobileno); ?></td>
							<td>
							<?php if (get_permission('patient', 'is_edit')): ?>
								<a href="<?php echo base_url('patient/profile/' . $row->id); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('profile'); ?>"><i class="far fa-arrow-alt-circle-right"></i></a>
							<?php endif; if (get_permission('patient', 'is_delete')): ?>
								<?php echo btn_delete('patient/patient_delete/' . $row->id); ?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; }?>
					</tbody>
				</table>
			</div>
		</section>
	</div>
</div>