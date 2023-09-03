<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"> <?php echo translate('select_ground'); ?></h4>
	</header>
    <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
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
<?php if (isset($results)): ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-list-ol"></i> <?php echo translate('statement') . " " . translate('list'); ?></h4>
	</header>
	<div class="panel-body">
		<!-- Hidden information for printing -->
		<div class="export_title">Referral Statement : <?php echo _d($daterange[0]); ?> To <?php echo _d($daterange[1]); ?></div>
		<table class="table table-bordered table-condensed table-hover" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th><?php echo translate('sl'); ?></th>
					<th><?php echo translate('staff'); ?></th>
					<th><?php echo translate('staff_id'); ?></th>
					<th><?php echo translate('department'); ?></th>
					<th><?php echo translate('invoice') . " " . translate('qty'); ?></th>
					<th><?php echo translate('total') . " " . translate('bill'); ?></th>
					<th><?php echo translate('net') . " " . translate('payable'); ?></th>
					<th><?php echo translate('commission'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total_qty = 0;
				$total_amount = 0;
				$total_net_amount = 0;
				$total_commission = 0;
				if(!empty($results)) {
					$i = 1;
					foreach($results as $row):
						$total_amount += $row['total'];
						$total_net_amount += $row['net_amount'];
						$total_commission += $row['total_commission'];
						$total_qty += $row['invoice_q'];

				?>
				<tr>
					<td><?php echo $i++; ?></td>
					<td><?php echo html_escape($row['staff_name']); ?></td>
					<td><?php echo html_escape($row['staffid']); ?></td>
					<td><?php echo html_escape($row['department_name']); ?></td>
					<td><?php echo html_escape($row['invoice_q']); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['total'], 2, '.', ''));  ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['net_amount'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($currency_symbol . number_format($row['total_commission'], 2, '.', '')); ?></td>
				</tr>
				<?php endforeach; } ?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th><?php echo html_escape($total_qty); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_amount, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_net_amount, 2, '.', '')); ?></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_commission, 2, '.', '')); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</section>
<?php endif; ?>