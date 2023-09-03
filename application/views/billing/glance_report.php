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

<?php if (isset($results)): ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-list-ol"></i> <?php echo translate('at_a_glance') . " " . translate('report');?></h4>
	</header>
	<div class="panel-body">
		<div class="export_title">At A Glance Test Bill : <?php echo _d($daterange[0]); ?> To <?php echo _d($daterange[1]); ?></div>
		<table class="table table-bordered table-hover" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th><?php echo translate('description'); ?></th>
					<th><?php echo translate('amount'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Total Invoice Quantity</td>
					<td><?php echo str_pad($results['billsum']['bill_qty'], 4, '0', STR_PAD_LEFT); ?></td>
				</tr>
				<tr>
					<td>Total Bill</td>
					<td><?php echo html_escape($currency_symbol . number_format($results['billsum']['total_amount'], 2, '.', '')); ?></td>
				</tr>
				<tr>
					<td>Total Discount</td>
					<td><?php echo html_escape($currency_symbol . number_format($results['billsum']['total_discount'], 2, '.', '')); ?></td>
				</tr>
				<tr>
					<td>Total Tax</td>
					<td><?php echo html_escape($currency_symbol . number_format($results['billsum']['total_tax'], 2, '.', '')); ?></td>
				</tr>
				<tr>
					<td>Total Net Payable</td>
					<td><?php echo html_escape($currency_symbol . number_format($results['billsum']['net_amount'], 2, '.', '')); ?></td>
				</tr>
				<tr>
					<td>Total Paid</td>
					<td><?php echo html_escape($currency_symbol . number_format($results['billsum']['totalpaid'], 2, '.', '')); ?></td>
				</tr>
				<tr>
					<td>Total Due</td>
					<td><?php echo html_escape($currency_symbol . number_format(($results['billsum']['total_due'] + $results['billsum']['duecollect']), 2, '.', '')); ?></td>
				</tr>
				<tr>
					<td>Total Due Collection</td>
					<td><?php echo html_escape($currency_symbol . number_format($results['billsum']['duecollect'], 2, '.', '')); ?></td>
				</tr>
				<tr>
					<td>Total Net Due</td>
					<td><?php echo html_escape($currency_symbol . number_format($results['billsum']['total_due'], 2, '.', '')); ?></td>
				</tr>
				<tr>
					<td>Total Referral Commission</td>
					<td><?php echo html_escape($currency_symbol . number_format($results['billsum']['total_commission'], 2, '.', '')); ?></td>
				</tr>
				<tr>
					<td>Total Production Cost</td>
					<td><?php echo html_escape($currency_symbol . number_format($results['cost']['total_production_cost'], 2, '.', '')); ?></td>
				</tr>
				<tr>
					<td>Total Profit (After Referral Commission And Production Cost)</td>
					<td><?php echo html_escape($currency_symbol . number_format(($results['billsum']['net_amount'] - $results['billsum']['total_commission'] - $results['cost']['total_production_cost']), 2, '.', '')); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</section>
<?php endif; ?>