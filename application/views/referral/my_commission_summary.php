<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"> <?php echo translate('select_ground'); ?></h4>
	</header>
    <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
		<div class="panel-body">
			<div class="row mb-sm">
				<div class="col-md-offset-3 col-md-6 mb-sm">		
					<div class="form-group">
						<label class="control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fas fa-calendar-check"></i></span>
							<input type="text" class="form-control daterange" name="daterange" value="<?php echo set_value('daterange', date("Y/m/d") . ' - ' . date("Y/m/d")); ?>" required />
						</div>
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
<?php if (isset($results)): ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-list-ol"></i> <?php echo translate('summary'); ?></h4>
	</header>
	<div class="panel-body">
		<!-- Hidden information for printing -->
		<div class="export_title">Commission Summary : <?php echo _d($daterange[0]); ?> To <?php echo _d($daterange[1]); ?></div>
		<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th><?php echo translate('bill_no'); ?></th>
					<th><?php echo translate('patient') . " " . translate('name'); ?></th>
					<th><?php echo translate('bill') . " " . translate('qty'); ?></th>
					<th><?php echo translate('total') . " " . translate('bill'); ?></th>
					<th><?php echo translate('discount'); ?></th>
					<th><?php echo translate('net') . " " . translate('payable'); ?></th>
					<th><?php echo translate('total') . " " . translate('paid'); ?></th>
					<th><?php echo translate('due'); ?></th>
					<th><?php echo translate('due') . " " . translate('collect'); ?></th>
					<th><?php echo translate('net') . " " . translate('due'); ?></th>
					<th><?php echo translate('referral'); ?></th>
					<th><?php echo translate('payable') . " " .translate('commission'); ?></th>
					<th><?php echo translate('due') . " " . translate('commission'); ?></th>
					<th><?php echo translate('date'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total_amount = 0;
				$total_discount = 0;
				$total_net_amount = 0;
				$total_paid = 0;
				$total_due = 0;
				$total_duecollect = 0;
				$total_net_due = 0;
				$total_commission = 0;
				$total_duecommission = 0;
				$i = 0;
				if(!empty($results)) { 
					foreach($results as $row):
					$due = $row['due'] + $row['duecollect'];
					$total_amount += $row['total'];
					$total_discount += $row['discount'];
					$total_net_amount += $row['net_amount'];
					$total_paid += $row['totalpaid'];
					$total_due += $due;
					$total_duecollect += $row['duecollect'];
					$total_net_due += $row['due'];
				?>	
				<tr>
					<td><?php echo html_escape($row['bill_no']); ?></td>
					<td><?php echo html_escape($row['patient_name']); ?></td>
					<td>1 <?php $i++; ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['total'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['discount'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['net_amount'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['totalpaid'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($due, 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['duecollect'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['due'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($row['staff_name']); ?></td>
					<td>
						<?php
						if ($row['due'] == 0) {
							$total_commission += $row['commission'];
							echo html_escape($currency_symbol . number_format($row['commission'], 2, '.', ''));
						}else{
							echo html_escape($currency_symbol . number_format(0, 2, '.', ''));
						}
						?>
					</td>
					<td>
						<?php
						if ($row['due'] > 0) {
							$total_duecommission += $row['commission'];
							echo html_escape($currency_symbol . number_format($row['commission'], 2, '.', ''));
						}else{
							echo html_escape($currency_symbol . number_format(0, 2, '.', ''));
						}
						?>
					</td>
					<td><?php echo _d($row['date']); ?></td>
				</tr>
				<?php endforeach; } ?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th><?php echo $i; ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_amount, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_discount, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_net_amount, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_paid, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_due, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_duecollect, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_net_due, 2, '.', '')); ?></th>
					<th></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_commission, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_duecommission, 2, '.', '')); ?></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
</section>
<?php endif; ?>