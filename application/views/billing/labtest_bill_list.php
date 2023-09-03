<?php $currency_symbol = $global_config['currency_symbol']; ?>
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
		<h4 class="panel-title"><i class="fas fa-list-ol"></i> <?php echo translate('test') . " " . translate('bill') . " " . translate('list'); ?></h4>
	</header>
	<div class="panel-body">
		<div class="export_title">Lab Test Bill Summary</div>
		<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th><?php echo translate('sl'); ?></th>
					<th><?php echo translate('bill_no'); ?></th>
					<th><?php echo translate('patient') . " " . translate('name'); ?></th>
					<th><?php echo translate('delivery') . " " . translate('date'); ?></th>
					<th><?php echo translate('delivery') . " " . translate('status'); ?></th>
					<th><?php echo translate('payment') . " " . translate('status'); ?></th>
					<th><?php echo translate('net') . " " . translate('payable'); ?></th>
					<th><?php echo translate('paid'); ?></th>
					<th><?php echo translate('due'); ?></th>
					<th><?php echo translate('date'); ?></th>
					<th class="min-w-sm"><?php echo translate('action'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$count = 1;
				if (!empty($billlist)){ 
				foreach ($billlist as $row):
				?>	
				<tr>
					<td><?php echo $count++ ; ?></td>
					<td><?php echo html_escape($row['bill_no']); ?></td>
					<td><?php echo html_escape($row['patient_name'] . " - " . $row['patient_id']); ?></td>
					<td><?php echo html_escape(_d($row['delivery_date']) . " - " . date("h:i A", strtotime($row['delivery_time']))); ?></td>
					<td>
					<?php
					if ($row['delivery_status'] == 2) {
						echo "<span class='label label-success-custom'>" . translate('completed') . "</span>";
					}else{
						echo "<span class='label label-danger-custom'>" . translate('undelivered'). "</span>";
					}
					?>
					</td>
					<td>
						<?php
							$labelMode = "";
							$status = $row['status'];
							if($status == 1) {
								$status = translate('unpaid');
								$labelMode = 'label-danger-custom';
							} elseif($status == 2) {
								$status = translate('partly_paid');
								$labelMode = 'label-info-custom';
							} elseif($status == 3 || $row['total_due'] == 0) {
								$status = translate('total_paid');
								$labelMode = 'label-success-custom';
							}
							echo "<span class='label " . $labelMode. "'>" . $status . "</span>";
						?>
					</td>
					<td><?php echo html_escape($currency_symbol . number_format($row['net_amount'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['paid'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['due'], 2, '.', '')); ?></td>
					<td><?php echo html_escape(_d($row['date'])); ?></td>
					<td class="min-w-c">
						<a href="<?php echo base_url('billing/test_bill_invoice/' . html_escape($row['id']) . "/" . html_escape($row['hash'])); ?>" class="btn btn-circle btn-default"> <i class="fas fa-eye"></i>
							<?php echo translate('invoice'); ?>
						</a>

						<?php if (get_permission('lab_test_bill', 'is_delete')): ?>
							<?php echo btn_delete('billing/test_bill_delete/' . html_escape($row['id'])); ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; }?>
			</tbody>
		</table>
	</div>
</section>

