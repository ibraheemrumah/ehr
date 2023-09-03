<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="">
				<a href="<?php echo base_url('accounts/voucher'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('transactions') . " " . translate('list'); ?></a>
			</li>

			<li class="active">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('voucher'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="create">
	            <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
	            	<input type="hidden" name="voucher_old_id" value="<?php echo html_escape($voucher['id']); ?>">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('account'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("account_id", $accounts_list, set_value('account_id', $voucher['account_id']), "class='form-control' disabled 
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"><?php echo form_error('account_id'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('voucher') . " " . translate('type'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$array_type = array(
									'' => translate('select'),
									'expense' => 'Expense',
									'income' => 'Income'
								);
								echo form_dropdown("voucher_type", $array_type, set_value('voucher_type', $voucher['type']), "class='form-control' disabled
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"><?php echo form_error('voucher_type'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('voucher_head_id')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('voucher') . " " . translate('head'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayVoucherHead = array('' => translate('select'));
								echo form_dropdown("voucher_head_id", $arrayVoucherHead, set_value('voucher_head_id', $voucher['voucher_head_id']), "class='form-control'  id='voucher_head_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"><?php echo form_error('voucher_head_id'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('ref'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="ref_no" value="<?php echo set_value('ref_no', $voucher['ref']); ?>" />
						</div>
					</div>
					<div class="form-group <?php if (form_error('amount')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('amount'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="amount" value="<?php echo set_value('amount', $voucher['amount']); ?>" disabled />
							<span class="error"><?php echo form_error('amount'); ?></span>
						</div>
						<input type="hidden" name="voucher_old_amount" value="<?php echo html_escape($voucher['amount']); ?>">
					</div>
					<div class="form-group <?php if (form_error('date')) echo 'has-error'; ?>">
						<label  class="col-md-3 control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="date" value="<?php echo set_value('date', $voucher['date']); ?>" data-plugin-datepicker 
							data-plugin-options='{ "todayHighlight" : true, "endDate": "+0d" }' readonly />
							<span class="error"><?php echo form_error('date'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('pay_via'); ?></label>
						<div class="col-md-6">
    						<?php
    							echo form_dropdown("pay_via", $payvia_list, set_value('pay_via', $voucher['pay_via']) , "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
    						?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('description'); ?></label>
						<div class="col-md-6">
							<textarea class="form-control" id="description" name="description" placeholder="" rows="3" ><?php echo html_escape($voucher['description']); ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('attachment'); ?></label>
						<div class="col-md-6 mb-md">
							<input type="file" name="attachment_file" class="dropify" data-height="70" />
						</div>
					</div>
					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" name="save" value="1">
									<i class="fas fa-edit"></i> <?php echo translate('update'); ?>
								</button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        var voucher_type = "<?php echo set_value('voucher_type', $voucher['type']) ?>";
        var voucher_head_id = "<?php echo set_value('voucher_head_id', $voucher['voucher_head_id']) ?>";
        getHeadList(voucher_type, voucher_head_id);
    });
</script>