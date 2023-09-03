<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('withdrawal') . " " . translate('list'); ?></a>
			</li>
<?php if (get_permission('commission_withdrawal', 'is_add')){ ?>
			<li class="<?php echo (isset($validation_error) ? 'active' : ''); ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('withdrawal'); ?></a>
			</li>
<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<div class="mb-md">
					<div class="export_title"><?php echo translate('withdrawal') . " " . translate('list'); ?></div>
					<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
						<thead>
							<tr>
								<th><?php echo translate('sl'); ?></th>
								<th><?php echo translate('role'); ?></th>
								<th><?php echo translate('staff') . " " . translate('id'); ?></th>
								<th><?php echo translate('staff') . " " . translate('name'); ?></th>
								<th><?php echo translate('payout'); ?></th>
								<th><?php echo translate('paid_by'); ?></th>
								<th><?php echo translate('date'); ?></th>
								<th class="min-w-sm"><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($payoutlist)){ $count = 1; foreach($payoutlist as $row): ?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo html_escape($row->role); ?></td>
                                <td><?php echo html_escape($row->staffid); ?></td>
								<td><?php echo html_escape($row->staff_name); ?></td>
								<td><?php echo html_escape($currency_symbol) . number_format($row->amount, 2, '.', ''); ?></td>
								<td><?php echo get_type_name_by_id('staff', $row->paid_by); ?></td>
								<td><?php echo _d($row->created_at); ?></td>
								<td class="min-w-c">
									<a class="btn btn-default btn-circle" href="javascript:void(0);" onclick="getPayslip('<?php echo html_escape($row->id); ?>')">
										<i class="far fa-eye"></i> <?php echo translate('payslip'); ?>
									</a>

									<?php if (get_permission('commission_withdrawal', 'is_delete')): ?>
										<?php echo btn_delete('referral/withdrawal_delete/' . $row->id); ?>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; }?>
						</tbody>
					</table>
				</div>
			</div>
<?php if (get_permission('commission_withdrawal', 'is_add')){ ?>
			<div class="tab-pane <?php echo (isset($validation_error) ? 'active' : ''); ?>" id="create">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered')); ?>
					<div class="form-group <?php if (form_error('staff_role')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('role'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
                            <?php
                                $role_list = $this->app_lib->getRoles();
                                echo form_dropdown("staff_role", $role_list, set_value('staff_role'), "class='form-control' data-plugin-selectTwo data-width='100%'
								data-minimum-results-for-search='Infinity' onchange='getStaffList(this.value, 0)'");
                            ?>
							<span class="error"><?php echo form_error('staff_role'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('staff_id')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('user'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<select name="staff_id" class="form-control" id="staff_id" data-plugin-selecttwo data-width="100%" onchange="get_balance(this.value)">
								<option value=""><?php echo translate('select'); ?></option>
							</select>
							<span class="error"><?php echo form_error('staff_id'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('pay_via')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('pay_via'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("pay_via", $payvia_list, set_value('pay_via'), "class='form-control'
								data-plugin-selectTwo data-width='100%'");
							?>
							<span class="error"><?php echo form_error('pay_via'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('amount')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('amount'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="amount" value="<?php echo set_value('amount'); ?>">
							<span class="error"><?php echo form_error('amount'); ?></span>
							<div class="alert alert-custom mt-md p-sm m-none hidden-item" id="balance_div">
								<i class="fas fa-info-circle"></i> <span id="current_balance"></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
						<div class="col-md-6 mb-sm">
							<textarea class="form-control" rows="3" name="remarks"><?php echo set_value('remarks'); ?></textarea>
						</div>
					</div>
				
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" name="save" value="1">
									<i class="fas fa-plus-circle"></i> <?php echo translate('payment'); ?>
								</button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php } ?>
		</div>
	</div>
</section>

<div class="zoom-anim-dialog modal-block modal-block-lg mfp-hide" id="modal">
	<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-file-invoice"></i> <?php echo translate('payslip'); ?></h4>
	</header>
	<div class="panel-body">
		<div id="invoice_print"></div>
	</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button onClick="fn_printElem('invoice_print')" class="btn btn-default ml-sm"><i class="fas fa-print"></i> <?php echo translate('print'); ?></button>
					<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
				</div>
			</div>
		</footer>
	</section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var role_id = "<?php echo set_value('staff_role') ?>";
        var staff_id = "<?php echo set_value('staff_id') ?>";
        getStaffList(role_id, staff_id);
    });

	function get_balance(staff_id){
		$("#current_balance").html("<?php echo translate('exploring'); ?>...");
		$("#balance_div").slideDown();
	    $.ajax({
	        type: "POST",
	        url: "<?php echo base_url('ajax/get_balance'); ?>",
	        data: {"staff_id": staff_id},
	        dataType: "html",
	        success: function(data) {
	           $("#current_balance").html(data);
	        }
	    });
	}
</script>