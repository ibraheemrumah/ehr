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

<?php if (isset($result)): ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-list"></i> <?php echo translate('investigation') . " " . translate('list'); ?></h4>
	</header>
	<div class="panel-body">
		<div class="export_title">Lab Test Report List : <?php echo _d($daterange[0]); ?> To <?php echo _d($daterange[1]); ?></div>
		<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th><?php echo translate('sl'); ?></th>
					<th><?php echo translate('bill_no'); ?></th>
					<th><?php echo translate('patient') . " " . translate('name'); ?></th>
					<th><?php echo translate('bill') . " " . translate('date'); ?></th>
					<th><?php echo translate('delivery') . " " . translate('date'); ?></th>
					<th><?php echo translate('reporting') . " " . translate('date'); ?></th>
					<th><?php echo translate('action'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($result)){ $count = 1; foreach($result as $row): ?>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo html_escape($row['bill_no']); ?></td>
					<td><?php echo html_escape($row['patient_name']); ?></td>
					<td><?php echo _d($row['bill_date']); ?></td>
					<td><?php echo _d($row['delivery_date']) . " - " . date("h:i A", strtotime($row['delivery_time'])); ?></td>
					<td><?php echo _d($row['reporting_date']) . " - " . date("h:i A", strtotime($row['reporting_date'])); ?></td>
					<td>
						<?php if (get_permission('patient', 'is_edit')): ?>
							<a href="<?php echo base_url('test/investigation_edit/' . $row['id']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip"
							data-original-title="<?php echo translate('edit'); ?>">
								<i class="fas fa-pen-nib"></i>
							</a>
						<?php endif;?>
						<a href="<?php echo base_url('test/investigation_print/'.$row['id']); ?>" class="btn btn-circle icon btn-default" data-toggle="tooltip"
						data-original-title="<?php echo translate('view'); ?>">
							<i class="fas fa-eye"></i>
						</a>
					</td>
				</tr>
				<?php endforeach; }?>
			</tbody>
		</table>
	</div>
</section>
<?php endif; ?>