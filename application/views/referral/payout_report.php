<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"> <?php echo translate('select_ground'); ?></h4>
	</header>
    <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
		<div class="panel-body">
			<div class="row mb-sm">		
				<div class="col-md-6 mb-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('referral') ?> <span class="required">*</span></label>
						<?php
							echo form_dropdown("staff_id", $stafflist, set_value('staff_id'), "class='form-control' required
							data-plugin-selectTwo data-width='100%'");
						?>
					</div>
				</div>
				<div class="col-md-6 mb-sm">
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
		<h4 class="panel-title"><i class="fas fa-list-ol"></i> <?php echo translate('withdrawal') . " " . translate('history'); ?></h4>
	</header>
	<div class="panel-body">
		<!-- Hidden information for printing -->
		<div class="export_title">Payout Report : <?php echo _d($daterange[0]); ?> To <?php echo _d($daterange[1]); ?></div>
		<table class="table table-bordered table-condensed table-hover" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th><?php echo translate('sl'); ?></th>
					<th><?php echo translate('staff'); ?></th>
					<th><?php echo translate('staff_id'); ?></th>
					<th><?php echo translate('department'); ?></th>
					<th><?php echo translate('payslip') . " " . translate('no'); ?></th>
					<th><?php echo translate('payout') . " " . translate('amount'); ?></th>
					<th><?php echo translate('pay_via'); ?></th>
					<th><?php echo translate('date'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total_amount = 0;
				if(!empty($results)) {
					$i = 1;
					foreach($results as $row):
						$total_amount += $row['amount'];
				?>	
				<tr>
					<td><?php echo $i++; ?></td>
					<td><?php echo html_escape($row['staff_name']); ?></td>
					<td><?php echo html_escape($row['staffid']); ?></td>
					<td><?php echo html_escape($row['department_name']); ?></td>
					<td><?php echo html_escape($row['bill_no']); ?></td>
					<td><?php echo html_escape($global_config['currency_symbol'] . number_format($row['amount'], 2, '.', '')); ?></td>
					<td><?php echo html_escape($row['payvia']); ?></td>
					<td><?php echo html_escape(_d($row['created_at']) . " " . date("h:i A", strtotime($row['created_at']))); ?></td>
				</tr>
				<?php endforeach; } ?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th><?php echo html_escape($global_config['currency_symbol']) . number_format($total_amount, 2, '.', ''); ?></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
</section>
<?php endif; ?>

