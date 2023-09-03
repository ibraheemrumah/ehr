<section class="panel">
	<div class="panel-body">
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
								<p class="h5 mb-xs text-dark text-weight-semibold"><?php echo translate('bill_no'); ?> : <?php echo html_escape($test['bill_no']); ?></p>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="bill-data text-right">
								<p class="h5 mb-xs text-dark text-weight-semibold"><?php echo translate('reporting') . " " . translate('date'); ?> : <?php echo _d($test['reporting_date']); ?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive mb-md ">
					<table class="table table-bordered table-condensed text-dark">
						<tbody>
							<tr>
								<th width="25%"><?php echo translate('patient') . " " . translate('name'); ?></th>
								<td width="25%"><?php echo html_escape($test['patient_name']); ?></td>
								<th width="25%"><?php echo translate('sex') . " & " . translate('age'); ?></th>
								<td width="25%"><?php echo html_escape(ucfirst($test['sex'])) . " / " . html_escape($test['age']) ?></td>
							</tr>
							<tr>
								<th><?php echo translate('category'); ?></th>
								<td><?php echo html_escape($test['cname']); ?></td>
								<th><?php echo translate('referral'); ?></th>
								<td><?php echo html_escape($test['ref_name']); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php echo $test['report_description'] ; ?>
			</div>
		</div>
	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-md-12">
				<div class="pull-right">
					<button class="btn btn-default" onClick="fn_printElem('invoicePrint')"><i class="fas fa-print"></i> <?php echo translate('print'); ?></button>
				</div>
			</div>
		</div>
	</footer>
</section>