<?php
$currency_symbol = $global_config['currency_symbol'];
$currency = $global_config['currency'];
?>
<div id="invoicePrint" class="print-content">
	<div class="invoice">
		<header class="clearfix">
			<div class="row">
				<div class="col-xs-6">
					<div class="ib">
						<img src="<?php echo base_url('uploads/app_image/printing-logo.png'); ?>" alt="techtune Img" />
					</div>
				</div>
				<div class="col-xs-6 text-right">
					<address class="m-none">
						<?php 
						echo html_escape($global_config['institute_name']) . "<br/>";
						echo html_escape($global_config['address']) . "<br/>";
						echo html_escape($global_config['mobileno']) . "<br/>";
						echo html_escape($global_config['institute_email']) . "<br/>";
						?>
					</address>
				</div>
			</div>
		</header>
		<div class="bill-info">
			<div class="row">
				<div class="col-xs-6">
					<div class="bill-data">
						<p class="h5 mb-xs text-dark text-weight-semibold"><?php echo translate('payslip') . " " . translate('no'); ?> : <?php echo html_escape($payslip['bill_no']); ?></p>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="bill-data text-right">
						<p class="h5 mb-xs text-dark text-weight-semibold"><?php echo translate('date'); ?> : <?php echo date("Y-M-d h:i A", strtotime($payslip['created_at'])); ?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive mb-md">
			<table class="table table-bordered table-condensed text-dark">
				<tbody>
					<tr>
						<th width="25%"><?php echo translate('name'); ?></th>
						<td width="25%"><?php echo html_escape($payslip['staff_name']); ?></td>
						<th width="25%"><?php echo translate('staff_id'); ?></th>
						<td width="25%"><?php echo html_escape($payslip['staff_id']); ?></td>
					</tr>
					<tr>
						<th><?php echo translate('department'); ?></th>
						<td><?php echo html_escape($payslip['department_name']); ?></td>
						<th><?php echo translate('designation'); ?></th>
						<td><?php echo html_escape($payslip['designation_name']); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="table-responsive mb-lg">
			<table class="table invoice-items table-hover tbr-top mb-none">
				<thead>
					<tr class="text-dark">
						<th id="cell-id" class="text-weight-semibold">#</th>
						<th id="cell-item" class="text-weight-semibold"><?php echo translate("paid_by"); ?></th>
						<th id="cell-desc" class="text-weight-semibold"><?php echo translate("remarks"); ?></th>
						<th id="cell-price" class="text-weight-semibold"><?php echo translate("pay_via"); ?></th>
						<th id="cell-total" class="text-center text-weight-semibold"><?php echo translate('amount'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td class="text-weight-semibold text-dark"><?php echo get_type_name_by_id('staff', $payslip['paid_by']); ?></td>
						<td><?php echo (empty($payslip['remarks']) ? "N/A" : $payslip['remarks']); ?></td>
						<td><?php echo html_escape($payslip['pay_via_name']); ?></td>
						<td class="text-center"><?php echo html_escape($currency_symbol . $payslip['amount']); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="invoice-summary text-right mt-lg">
			<div class="row">
				<div class="col-md-6 pull-right">
					<ul class="amounts">
						<li><?php echo translate('balance'); ?> (<?php echo html_escape($currency_symbol);?>) : <?php echo number_format($payslip['before_payout'], 2, '.', ''); ?></li>
						<li><?php echo translate('withdrawal') . " " . translate('amount'); ?> (<?php echo html_escape($currency_symbol);?>) : <?php echo number_format($payslip['amount'], 2, '.', ''); ?></li>
						<li>
							<strong><?php echo translate('current') . " " . translate('balance'); ?> (<?php echo html_escape($currency_symbol);?>) : </strong> 
							<?php
							$cb = number_format(($payslip['before_payout'] - $payslip['amount']), 2, '.', '');
							$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
							echo html_escape($cb) . ' </br>( ' . ucwords($f->format($cb)) . " $currency )";
							?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>