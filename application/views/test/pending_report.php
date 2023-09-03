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
		<h4 class="panel-title"><i class="fas fa-list"></i> <?php echo translate('pending') . " " . translate('investigation'); ?></h4>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-hover table-condensed table_default" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th><?php echo translate('sl'); ?></th>
					<th><?php echo translate('bill_no'); ?></th>
					<th><?php echo translate('patient') . " " . translate('name'); ?></th>
					<th><?php echo translate('bill') . " " . translate('date'); ?></th>
					<th><?php echo translate('delivery') . " " . translate('date'); ?></th>
					<th><?php echo translate('remarks'); ?></th>
					
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
					<td><?php
						$string = $row['report_remarks'];
						if (strlen($string) > 50) {
							$trimstring = substr($string, 0, 50). ' ...';
						} else {
							$trimstring = $string;
						}
						echo $trimstring;
					?></td>
					
					<td>
						<?php 
						$due_amount = $row['due'];
						if ($due_amount > 0){
							echo "Make Payment";
						} else {
							?>
							<a href="<?php echo base_url('test/investigation_create/' . $row['id']); ?>" class="btn btn-circle btn-default">
							<i class="fas fa-vials"></i> <?php echo translate('add'); ?>
							</a>
						
						<?php }
						
						?>

						
					</td>
				</tr>
				<?php endforeach; }?>
			</tbody>
		</table>
	</div>
</section>