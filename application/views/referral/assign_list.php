<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-users"></i> <?php echo translate('referral') . " " . translate('list'); ?></h4>
			</header>
			<div class="panel-body">
				<div class="export_title">Referral List</div>
				<table class="table table-bordered table-hover table-condensed tbr-top" cellspacing="0" width="100%" id="table-export">
					<thead>
						<tr>
							<th width="50"><?php echo translate('sl'); ?></th>
							<th><?php echo translate('role'); ?></th>
                            <th><?php echo translate('staff_id'); ?></th>
							<th><?php echo translate('name'); ?></th>
							<th><?php echo translate('department'); ?></th>
							<th><?php echo translate('designation'); ?></th>
							<th><?php echo translate('commission') . " " . translate('for'); ?></th>
							<th><?php echo translate('percentage'); ?> (%)</th>
							<th><?php echo translate('date'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						if (count($stafflist)){
							foreach ($stafflist as $row):
							?>	
						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo html_escape($row->role); ?></td>
							<td><?php echo html_escape($row->staffid); ?></td>
							<td><?php echo html_escape($row->staff_name); ?></td>
							<td><?php echo html_escape($row->department_name); ?></td>
							<td><?php echo html_escape($row->designation_name); ?></td>
							<td><?php echo html_escape($row->test_name); ?></td>
							<td><?php echo html_escape($row->percentage); ?></td>
							<td><?php echo html_escape(_d($row->date)); ?></td>
						</tr>
						<?php endforeach; }?>
					</tbody>
				</table>
			</div>
		</section>
	</div>
</div>