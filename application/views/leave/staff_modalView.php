<?php
$this->db->select('leave_application.*,leave_category.name as type_name,staff.name as staff_name,staff.staff_id as staffid');
$this->db->from('leave_application');
$this->db->join('leave_category', 'leave_category.id = leave_application.category_id', 'left');
$this->db->join('staff', 'staff.id = leave_application.staff_id', 'left');
$this->db->where('leave_application.id', $leave_id);
$row = $this->db->get()->row_array();
?>
<div class="table-responsive">
	<table class="table borderless mb-none">
		<tbody>
<?php if ($row['status'] != 1) { ?>
			<tr>
				<th><?php echo translate('approved_by'); ?> : </th>
				<td><?php echo get_type_name_by_id('staff', $row['approved_by']); ?></td>
			</tr>
			<tr>
				<th><?php echo translate('comments'); ?> : </th>
				<td><?php echo html_escape($row['comments']); ?></td>
			</tr>
<?php } ?>
			<tr>
				<th><?php echo translate('staff') . " " . translate('name'); ?> : </th>
				<td><?php echo html_escape($row['staff_name']); ?></td>
			</tr>
			<tr>
				<th><?php echo translate('staff') . " " . translate('id'); ?> : </th>
				<td><?php echo html_escape($row['staffid']); ?></td>
			</tr>
			<tr>
				<th><?php echo translate('leave_type'); ?> : </th>
				<td><?php echo html_escape($row['type_name']); ?></td>
			</tr>
			<tr>
				<th><?php echo translate('apply') . " " . translate('date'); ?> : </th>
				<td><?php echo _d($row['apply_date']) . " " . date('h:i A' ,strtotime($row['apply_date'])); ?></td>
			</tr>
			<tr>
				<th><?php echo translate('start_date'); ?> : </th>
				<td><?php echo _d($row['start_date']); ?></td>
			</tr>
			<tr>
				<th><?php echo translate('end_date'); ?> : </th>
				<td><?php echo _d($row['end_date']); ?></td>
			</tr>
			<tr>
				<th><?php echo translate('reason'); ?> : </th>
				<td><?php echo html_escape($row['reason']); ?></td>
			</tr>
<?php if (!empty($row['enc_file_name'])) { ?>
			<tr>
				<th><?php echo translate('attachment'); ?> : </th>
				<td><a class="btn btn-default btn-sm" href="<?php echo base_url('leave/download/' . $row['id'] . '/' . $row['enc_file_name']); ?>"><i class="far fa-arrow-alt-circle-down"></i> <?php echo translate('download'); ?></a></td>
			</tr>
<?php } ?>
		</tbody>
	</table>
</div>