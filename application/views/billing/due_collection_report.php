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
		<h4 class="panel-title"><i class="fas fa-list-ol"></i> <?php echo translate('due') . " " . translate('collect') . " " . translate('report'); ?></h4>
	</header>
	<div class="panel-body">
		<!-- Hidden information for printing -->
		<div class="export_title">Due Collection Report : <?php echo _d($daterange[0]); ?> To <?php echo _d($daterange[1]); ?></div>
		<table class="table table-bordered table-hover table-condensed tbr-top" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th><?php echo translate('sl'); ?></th>
					<th><?php echo translate('bill_no'); ?></th>
					<th><?php echo translate('patient') . " " . translate('name'); ?></th>
					<th><?php echo translate('pay_via'); ?></th>
					<th><?php echo translate('payment') . " " . translate('status'); ?></th>
					<th><?php echo translate('due') . " " . translate('collect'); ?></th>
					<th><?php echo translate('billing') . " " . translate('date'); ?></th>
					<th><?php echo translate('collect') . " " . translate('date'); ?></th>
					<th><?php echo translate('collected_by'); ?></th>
					<th><?php echo translate('action'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 1;
				$total_netpayable = 0;
				$total_due = 0;
				$total_collect = 0;
				$total_net_due = 0;
				if (!empty($results)){
					foreach ($results as $row):
						$total_collect += $row['amount'];
				?>	
				<tr>
					<td><?php echo $i++; ?></td>
					<td><?php echo html_escape($row['bill_no']); ?></td>
					<td><?php echo html_escape($row['patient_name']); ?></td>
					<td><?php echo html_escape($row['pay_via']); ?></td>
					<td>
						<?php
							$labelMode = "";
							$status = $row['status'];
							if($status == 2) {
								$status = translate('partly_paid');
								$labelMode = 'label-info-custom';
							} elseif($status == 3 || $row['total_due'] == 0) {
								$status = translate('total_paid');
								$labelMode = 'label-success-custom';
							}
							echo "<span class='label " . $labelMode . "'>" . $status . "</span>";
						?>
					</td>
					<td><?php echo html_escape($currency_symbol . number_format($row['amount'], 2, '.', '')); ?></td>
					<td><?php echo html_escape(_d($row['bill_date'])); ?></td>
					<td><?php echo html_escape(_d($row['paid_on'])); ?></td>
					<td><?php echo html_escape($row['collect_by']); ?></td>
					<td>
					<?php if (get_permission('lab_test_bill', 'is_view')): ?>
						<a href="<?php echo base_url('billing/test_bill_invoice/' . html_escape($row['bill_id']) . "/" . html_escape($row['hash'])); ?>" target="_blank" class="btn btn-circle icon btn-default" data-toggle="tooltip" data-original-title="<?php echo translate('bill_view'); ?>"> <i class="fas fa-eye"></i></a>
					<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; }?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th><?php echo html_escape($currency_symbol . number_format($total_collect, 2, '.', '')); ?></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
</section>
<?php endif; ?>