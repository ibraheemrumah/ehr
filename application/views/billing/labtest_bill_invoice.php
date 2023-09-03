<?php
$status = $test_bill['status'];
$currency = $global_config['currency'];
$currency_symbol = $global_config['currency_symbol'];
$total = number_format($test_bill['total'], 2, '.', '');
$total_discount = number_format($test_bill['discount'], 2, '.', '');
$tax_amount = number_format($test_bill['tax_amount'], 2, '.', '');
$paid = number_format($test_bill['paid'], 2, '.', '');
$due_amount = number_format($test_bill['due'], 2, '.', '');
$net_amount = number_format($test_bill['net_amount'], 2, '.', '');
$active_tab = $this->session->flashdata('active_tab');
?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (empty($active_tab) || $active_tab == 1 ? 'active' : ''); ?>">
				<a href="#invoice" data-toggle="tab"><i class="far fa-credit-card"></i> <?php echo translate('invoice'); ?></a>
			</li>
<?php if($paid > 1 && get_permission('test_bill_payment', 'is_view')):?>
			<li class="<?php echo ($active_tab == 2 ? 'active' : ''); ?>">
				<a href="#payment_history" data-toggle="tab"><i class="fas fa-dollar-sign"></i> <?php echo translate('payment_history'); ?></a>
			</li>
<?php endif; if($status != 3 && get_permission('test_bill_payment', 'is_add')): ?>
			<li class="<?php echo ($active_tab == 3 ? 'active' : ''); ?>">
				<a href="#add_payment" data-toggle="tab"><i class="far fa-money-bill-alt"></i> <?php echo translate('add_payment'); ?></a>
			</li>
<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div id="invoice" class="tab-pane <?php echo (empty($active_tab) || $active_tab == 1 ? 'active' : ''); ?>">
				<div id="bill_print">
					<div class="invoice">
						<header class="clearfix">
							<div class="row">
								<div class="col-xs-6">
									<div class="ib">
										<img src="<?php echo base_url('uploads/app_image/printing-logo.png'); ?>" alt="Img" />
									</div>
								</div>
								<div class="col-xs-6 text-right">
									<h4 class="mt-none mb-none text-dark">Bill No #<?php echo html_escape($test_bill['bill_no']); ?></h4>
									<p class="mb-none">
										<span class="text-dark"><?php echo translate('payment') . " " . translate('status'); ?> : </span>
										<span class="value">
											<?php
												$payment_a = array(
													'1' => translate('unpaid'),
													'2' => translate('partly_paid'),
													'3' => translate('total_paid')
												);
												echo ($payment_a[$status]);
											?>
										</span>
									</p>
									<p class="mb-none">
										<span class="text-dark"><?php echo translate('referral'); ?> : </span> <?php echo html_escape($test_bill['referral_name']); ?>
									</p>
									<p class="mb-none">
										<span class="text-dark"><?php echo translate('date'); ?> : </span> <span class="value"><?php echo _d($test_bill['date']); ?></span>
									</p>
								</div>
							</div>
						</header>
						<div class="bill-info">
							<div class="row">
								<div class="col-xs-6">
									<div class="bill-data">
										<p class="h5 mb-xs text-dark text-weight-semibold"><?php echo translate('patient'); ?> :</p>
										<address>
											<?php 
											echo html_escape($test_bill['p_name']) . '<br>';
											echo translate('sex') . ' & ' . translate('age') . ' : ' . ucfirst($test_bill['p_sex']) . ' / ' . $test_bill['p_age'] . '<br>';
											echo translate('category') . ' : ' . $test_bill['p_category'] .  '<br>';
											echo translate('mobile_no') .' : '. $test_bill['p_mobileno'] . '<br>'; 
											echo html_escape($test_bill['p_address']);
											?>
										</address>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="bill-data text-right">
										<p class="h5 mb-xs text-dark text-weight-semibold"><?php echo translate('from'); ?> :</p>
										<address>
											<?php 
											echo html_escape($global_config['institute_name']) . "<br/>";
											echo html_escape($global_config['address']) . "<br/>";
											echo html_escape($global_config['mobileno']) . "<br/>";
											echo html_escape($global_config['institute_email']) . "<br/>";
											?>
										</address>
									</div>
								</div>
							</div>
						</div>

						<div class="table-responsive">
							<table class="table invoice-items table-hover tbr-top mb-none">
								<thead>
									<tr class="text-dark">
										<th id="cell-id" class="text-weight-semibold">#</th>
										<th id="cell-desc" class="text-weight-semibold"><?php echo translate("test") . " " . translate("category"); ?></th>
										<th id="cell-item" class="text-weight-semibold"><?php echo translate("lab") . " " . translate("test"); ?></th>
										<th id="cell-price" class="text-weight-semibold"><?php echo translate("price"); ?></th>
										<th id="cell-qty" class="text-weight-semibold"><?php echo translate("discount"); ?></th>
										<th id="cell-total" class="text-center text-weight-semibold"><?php echo translate("total"); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$count = 1;
										foreach ( $bill_details as $row ) {
											$price = $row['price'];
											$discount = $row['discount'];
									?>
									<tr>
										<td><?php echo $count++; ?></td>
										<td><?php echo html_escape($row['test_category']); ?></td>
										<td><?php echo html_escape($row['test_name']); ?></td>
										<td><?php echo html_escape($currency_symbol . $price); ?></td>
										<td><?php echo html_escape($currency_symbol . $discount); ?></td>
										<td class="text-center"><?php echo html_escape($currency_symbol . ($price - $discount)); ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<div class="invoice-summary text-right mt-lg">
							<div class="row">
								<div class="col-lg-5 pull-right">
									<ul class="amounts">
										<li><?php echo translate('sub') . " " . translate('total'); ?> (<?php echo html_escape($currency_symbol); ?>) : <?php echo html_escape($total); ?></li>
										<li><?php echo translate('discount'); ?> (<?php echo html_escape($currency_symbol); ?>) : <?php echo html_escape($total_discount); ?></li>
										<li><?php echo translate('tax'); ?> (<?php echo html_escape($currency_symbol); ?>) : <?php echo html_escape($tax_amount); ?></li>
<?php if ($status == 3){?>
										<li>
											<strong><?php echo translate('net_payable'); ?> (<?php echo html_escape($currency_symbol); ?>) : </strong> 
											<?php
											$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
											echo html_escape($net_amount) . " </br>( ". ucwords($f->format($net_amount)) . " $currency )";
											?>
										</li>
<?php }else{ ?>
										<li><?php echo translate('net_payable'); ?> (<?php echo html_escape($currency_symbol); ?>) : <?php echo html_escape($net_amount); ?></li>
										<li><?php echo translate('paid'); ?> (<?php echo html_escape($currency_symbol); ?>) : <?php echo html_escape($paid); ?></li>
										<li>
											<strong><?php echo translate('due'); ?> (<?php echo html_escape($currency_symbol); ?>) : </strong> 
											<?php
											$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
											echo html_escape($due_amount) . " </br>( " . ucwords($f->format($due_amount)) . " $currency )";
											?>
										</li>
<?php } ?>
									</ul>
								</div>
							</div>
						</div>
						<div class="row mt-xxlg">
							<div class="col-xs-6">
								<div class="text-left">
									<?php echo translate('prepared_by') . " - " . get_type_name_by_id('staff', $test_bill['prepared_by']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="auth-signatory">
									<?php echo translate('authorised_by'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="text-right mr-lg hidden-print">
						<button onClick="fn_printElem('bill_print')" class="btn btn-default ml-sm"><i class="fas fa-print"></i> <?php echo translate('print'); ?></button>
					</div>
				</div>
			</div>
<?php if($paid > 1 && get_permission('test_bill_payment', 'is_view')): ?>
			<div class="tab-pane <?php echo ($active_tab == 2 ? 'active' : ''); ?>" id="payment_history">
				<div id="payment_print">
					<div class="invoice">
						<header class="clearfix">
							<div class="row">
								<div class="col-md-6">
									<div class="ib">
										<img src="<?php echo base_url('uploads/app_image/printing-logo.png'); ?>" alt="techtune Img" />
									</div>
								</div>
								<div class="col-md-6 text-right">
									<h4 class="mt-none mb-none text-dark">Bill No #<?php echo html_escape($test_bill['bill_no']); ?></h4>
									<p class="mb-none">
										<span class="text-dark"><?php echo translate('payment') . " " . translate('status'); ?> : </span>
										<span class="value">
											<?php
												$payment_a = array(
													'1' => translate('unpaid'),
													'2' => translate('partly_paid'),
													'3' => translate('total_paid')
												);
												echo ($payment_a[$status]);
											?>
										</span>
									</p>
									<p class="mb-none">
										<span class="text-dark"><?php echo translate('referral'); ?> : </span> <?php echo html_escape($test_bill['referral_name']); ?>
									</p>
									<p class="mb-none">
										<span class="text-dark"><?php echo translate('date'); ?> : </span> <span class="value"><?php echo _d($test_bill['created_at']); ?></span>
									</p>
								</div>
							</div>
						</header>
						<div class="bill-info">
							<div class="row">
								<div class="col-xs-6">
									<div class="bill-data">
										<p class="h5 mb-xs text-dark text-weight-semibold"><?php echo translate("patient"); ?> :</p>
										<address>
											<?php 
											echo html_escape($test_bill['p_name']) . '<br>';
											echo translate('sex') . ' & ' . translate('age') . ' : ' . ucfirst(html_escape($test_bill['p_sex'])) . ' / ' . html_escape($test_bill['p_age']) .  '<br>';
											echo translate('category') . ' : ' . $test_bill['p_category'] .  '<br>';
											echo translate('mobile_no') .' : '. $test_bill['p_mobileno'] . '<br>'; 
											echo html_escape($test_bill['p_address']);
											?>
										</address>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="bill-data text-right">
										<p class="h5 mb-xs text-dark text-weight-semibold"><?php echo translate("from"); ?> :</p>
										<address>
											<?php 
											echo html_escape($global_config['institute_name']) . "<br/>";
											echo html_escape($global_config['address']) . "<br/>";
											echo html_escape($global_config['mobileno']) . "<br/>";
											echo html_escape($global_config['institute_email']) . "<br/>";
											?>
										</address>
									</div>
								</div>
							</div>
						</div>

						<div class="table-responsive">
							<table class="table invoice-items table-hover tbr-top mb-none">
								<thead>
									<tr class="text-dark">
										<th id="cell-id" class="text-weight-semibold">#</th>
										<th id="cell-item" class="text-weight-semibold"><?php echo translate("collect_by"); ?></th>
										<th id="cell-price" class="text-weight-semibold"><?php echo translate("pay_via"); ?></th>
										<th id="cell-desc" class="text-weight-semibold"><?php echo translate("remarks"); ?></th>
										<th id="cell-qty" class="text-weight-semibold"><?php echo translate("paid_on"); ?></th>
										<th id="cell-total" class="text-center text-weight-semibold"><?php echo translate("amount"); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$count = 1;
									foreach($paymentHistory as $payment) {
									?>
									<tr>
										<td><?php echo $count++; ?></td>
										<td class="text-weight-semibold text-dark"><?php echo html_escape($payment['collect_by']) ; ?></td>
										<td><?php echo html_escape($payment['pay_via']); ?></td>
										<td><?php echo html_escape($payment['remarks']); ?></td>
										<td><?php echo html_escape(_d($payment['paid_on'])); ?></td>
										<td class="text-center"><?php echo html_escape($currency_symbol . $payment['amount']); ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<div class="invoice-summary text-right author">
							<div class="row">
								<div class="col-md-7 authorised">
									<div class="auth-signatory-sm">
										<?php echo translate('authorised_by'); ?>
									</div>
								</div>
								<div class="col-md-5 pull-right">
									<ul class="amounts">
										<li><?php echo translate('sub') . " " . translate('total'); ?> (<?php echo html_escape($currency_symbol); ?>) : <?php echo html_escape($total); ?></li>
										<li><?php echo translate('discount'); ?> (<?php echo html_escape($currency_symbol); ?>) : <?php echo html_escape($total_discount); ?></li>
										<li><?php echo translate('tax'); ?> (<?php echo html_escape($currency_symbol); ?>) : <?php echo html_escape($tax_amount); ?></li>
										<li><?php echo translate('net_payable'); ?> (<?php echo html_escape($currency_symbol); ?>) : <?php echo html_escape($net_amount); ?></li>
										<li><?php echo translate('paid'); ?> (<?php echo html_escape($currency_symbol); ?>) : <?php echo html_escape($paid); ?></li>
										<li>
											<strong><?php echo translate('due'); ?> (<?php echo html_escape($currency_symbol); ?>) : </strong> 
											<?php
											$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
											echo html_escape($due_amount) . ' </br>( ' . ucwords($f->format($due_amount)) . " $currency )";
											?>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="text-right mr-lg hidden-print">
						<button onClick="fn_printElem('payment_print')" class="btn btn-default"><i class="fas fa-print"></i> <?php echo translate('print'); ?></button>
					</div>
				</div>
			</div>
<?php endif; ?>
<?php if($status != 3 && get_permission('test_bill_payment', 'is_add')): ?>
			<!-- due collection form -->
			<div id="add_payment" class="tab-pane <?php echo ($active_tab == 3 ? 'active' : ''); ?>">
				<?php echo form_open(base_url('billing/test_bill_payment'), array('class' => 'form-horizontal form-bordered')); ?>
					<input type="hidden" name="bill_id" value="<?php echo html_escape($test_bill['id']); ?>">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('paid_on'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" data-plugin-datepicker data-plugin-options='{"todayHighlight" : true}' name="paid_date" value="<?php echo date('Y-m-d'); ?>" />
							<span class="error"><?php echo form_error('paid_on'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('amount'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="payment_amount" value="<?php echo set_value('payment_amount', $due_amount); ?>" placeholder="<?php echo translate('enter_payment_amount'); ?>" />
							<span class="error"><?php echo form_error('payment_amount'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('pay_via'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("pay_via", $payvia_list, set_value('pay_via'), "class='form-control' data-plugin-selectTwo data-width='100%'");
							?>
							<span class="error"><?php echo form_error('pay_via'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
						<div class="col-md-6 mb-md">
							<textarea name="remarks" rows="2" class="form-control" placeholder="<?php echo translate('write_your_remarks'); ?>">Lab Test Bill Payment</textarea>
						</div>
					</div>
					
					<input type="hidden" name= "bill_type"class="form-control"  value="1" id="bill_type" />
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-3">
								<button class="btn btn-default"><?php echo translate('payment'); ?></button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php endif; ?>
		</div>
	</div>
</section>