<?php $currency_symbol = $global_config['currency_symbol']; ?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#chemicallist" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('purchase') . ' ' . translate('list'); ?></a>
			</li>
<?php if (get_permission('chemical_purchase', 'is_add')): ?>
			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('add') . ' ' . translate('purchase'); ?></a>
			</li>
<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div id="chemicallist" class="tab-pane active mb-md">
				<div class="export_title"><?php echo translate('purchase') . " " . translate('report'); ?></div>
				<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
					<thead>
						<tr>
							<th><?php echo translate('bill_no'); ?></th>
							<th><?php echo translate('supplier') . " " . translate('name'); ?></th>
							<th><?php echo translate('purchase') . " " . translate('status'); ?></th>
							<th><?php echo translate('payment') . " " . translate('status'); ?></th>
							<th><?php echo translate('purchase') . " " . translate('date'); ?></th>
							<th><?php echo translate('net') . " " . translate('payable'); ?></th>
							<th><?php echo translate('paid'); ?></th>
							<th><?php echo translate('due'); ?></th>
							<th><?php echo translate('remarks'); ?></th>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($purchaselist)){ foreach ($purchaselist as $row): ?>	
						<tr>
							<td><?php echo html_escape($row['bill_no']); ?></td>
							<td><?php echo html_escape($row['supplier_name']); ?></td>
							<td>
								<?php
									$status_list = array(
										'1' => translate('ordered'),
										'2' => translate('received'),
										'3' => translate('pending')
									);
									echo $status_list[$row['purchase_status']];
								?>
							</td>
							<td>
								<?php
									$labelMode = "";
									$status = $row['payment_status'];
									if($status == 1) {
										$status = translate('unpaid');
										$labelMode = 'label-danger-custom';
									} elseif($status == 2) {
										$status = translate('partly_paid');
										$labelMode = 'label-info-custom';
									} elseif($status == 3 || $row['due'] == 0) {
										$status = translate('total_paid');
										$labelMode = 'label-success-custom';
									}
									echo "<span class='label " . $labelMode. "'>" . $status . "</span>";
								?>
							</td>
							<td><?php echo _d($row['date']); ?></td>
							<td><?php echo html_escape($currency_symbol . number_format($row['total'] - $row['discount'], 2, '.', '')); ?></td>
							<td><?php echo html_escape($currency_symbol . number_format($row['paid'], 2, '.', '')); ?></td>
							<td><?php echo html_escape($currency_symbol . number_format($row['due'], 2, '.', '')); ?></td>
							<td><?php echo html_escape($row['remarks']); ?></td>
							<td class="min-w-c">
								<a href="<?php echo base_url('inventory/purchase_bill/' . $row['id'] . "/" . $row['hash']); ?>" class="btn btn-circle icon btn-default" data-toggle="tooltip" data-original-title="<?php echo translate('bill_view'); ?>"> <i class="fas fa-eye"></i></a>
								
								<?php if (get_permission('chemical_purchase', 'is_edit')): ?>
									<a href="<?php echo base_url('inventory/purchase_edit/' . $row['id']); ?>" class="btn btn-circle icon btn-default" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"><i class="fas fa-pen-nib"></i></a>
								<?php endif; if (get_permission('chemical_purchase', 'is_delete')): ?>
									<?php echo btn_delete('inventory/purchase_delete/' . $row['id']); ?>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; }?>
					</tbody>
				</table>
			</div>
<?php if (get_permission('chemical_purchase', 'is_add')){ ?>
			<div id="create" class="tab-pane">
				<?php echo form_open('inventory/purchase_save', array('id' => 'frmSubmit')); ?>
					<div class="form-horizontal form-bordered">
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo translate('supplier'); ?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									echo form_dropdown("supplier_id", $supplierlist, set_value("supplier_id"), "class='form-control' data-plugin-selectTwo id='supplier_id'
									data-width='100%' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo translate('bill_no'); ?> <span class="required">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="bill_no" value="<?php echo $this->app_lib->get_bill_no('purchase_bill'); ?>" id="bill_no" />
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo translate('purchase') . " " . translate('status'); ?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									$status_list = array(
										'' => translate('select'),
										'1' => translate('ordered'),
										'2' => translate('received'),
										'3' => translate('pending')
									);
									echo form_dropdown("purchase_status", $status_list, set_value("purchase_status"), "class='form-control' data-plugin-selectTwo id='purchase_status'
									data-width='100%' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' id='date' />
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
							<div class="col-md-6 mb-lg">
								<textarea class="form-control" rows="2" name="remarks"></textarea>
							</div>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table table-bordered table-hover mt-md" id="tableID">
							<thead>
								<th><?php echo translate('chemical'); ?> <span class="required">*</span></th>
								<th><?php echo translate('unit') . " " . translate('price'); ?></th>
								<th><?php echo translate('quantity'); ?> <span class="required">*</span></th>
								<th><?php echo translate('discount'); ?></th>
								<th><?php echo translate('total') . " " . translate('price'); ?></th>
							</thead>
							<tbody>
								<tr id="row_0">
									<td class="min-w-lg">
										<div class="form-group">
											<select data-plugin-selectTwo class="form-control purchase_chemical" data-width="100%" name="purchases[0][chemical]" id="chemical0">
											<option value=""><?php echo translate('select'); ?></option>
											<?php foreach ($chemicallist as $value) { ?>
												<option value="<?php echo html_escape($value['id']); ?>"><?php echo html_escape($value['name']) . ' ('. $value['code'] . ')'?></option>
											<?php } ?>
											</select>
											<span class="error"></span>
										</div>
									</td>
									<td class="min-w-sm">
										<div class="form-group">
											<input type="text" class="form-control purchase_unit_price" name="purchases[0][unit_price]" readonly value="0.00" />
										</div>
									</td>
									<td class="min-w-sm">
										<div class="form-group">
											<input type="text" class="form-control purchase_quantity" name="purchases[0][quantity]" value="1" id="quantity0" />
											<span class="error"></span>
										</div>
									</td>
									<td class="min-w-md">
										<div class="form-group">
											<input type="number" class="form-control purchase_discount" name="purchases[0][discount]" value="0" />
										</div>
									</td>
									<td class="min-w-md">
										<div class="form-group">
											<input type="text" class="form-control net_sub_total" name="purchases[0][net_sub_total]" value="0.00" readonly />
											<input type="hidden" class="sub_total" name="purchases[0][sub_total]" value="0">
										</div>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="1"><button type="button" class="btn btn-default" onclick="addRows()"> <i class="fas fa-plus-circle"></i> <?php echo translate('add_rows'); ?></button></td>
									<td class="text-right" colspan="3"><b><?php echo translate('net_total'); ?> :</b></td>
									<td class="text-right">
										<input type="text" id="netGrandTotal" class="text-right form-control" name="net_grand_total" value="0.00" readonly />
										<input type="hidden" id="grandTotal" name="grand_total" value="0">
										<input type="hidden" id="totalDiscount" name="total_discount" value="0">
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-9 col-md-3">
								<button type="submit" name="purchase" id="savebtn" value="1" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
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

<script type="text/javascript">
	var count = 1;
	$(document).ready(function() {
		$(document).on('change', '.purchase_chemical', function() {
			var row = $(this).closest('tr');
			var id = $(this).val();
			$.ajax({
				type: "POST",
				data: {'id' : id},
				url: "<?php echo base_url('ajax/get_chemical_price'); ?>",
				success: function (result) {
					var unit_price = read_number(result);
					var quantity = read_number(row.find('.purchase_quantity').val());
					var discount = read_number(row.find('.purchase_discount').val());
					var total_price = unit_price * quantity;
					row.find('.purchase_unit_price').val(unit_price.toFixed(2));
					var after_discount = total_price - discount;
					row.find('.sub_total').val(total_price.toFixed(2));
					row.find('.net_sub_total').val(after_discount.toFixed(2));
					grandTotalCalculatePur();
				}
			});
		});

		$(document).on('change keyup', '.purchase_quantity, .purchase_discount', function() {
			var row = $(this).closest('tr');
			var quantity = read_number(row.find('.purchase_quantity').val());
			var unit_price = read_number(row.find('.purchase_unit_price').val());
			var discount = read_number(row.find('.purchase_discount').val());
			var total_price = unit_price * quantity;
			var after_discount = total_price - discount;
			row.find('.sub_total').val(total_price.toFixed(2));
			row.find('.net_sub_total').val(after_discount.toFixed(2));
			grandTotalCalculatePur();
		});
	});

	function addRows(){
		var tbody = $('#tableID').children('tbody');
		tbody.append(getDynamicInput(count));
		$("#chemical" + count).select2({
		    theme: "bootstrap",
		    width: "100%"
		});
		count++;
	}

    function deleteRow(id) {
        $("#row_" + id).remove();
        grandTotalCalculatePur();
    }

	function getDynamicInput(value) {
		var html_row = "";
		html_row += '<tr id="row_' + value + '">';
		html_row += '<td><div class="form-group">';
		html_row += '<select id="chemical' + value + '" name="purchases[' + value + '][chemical]" class="form-control purchase_chemical" >';
		html_row += '<option value=""><?php echo translate('select'); ?></option>';
<?php foreach ($chemicallist as $chemical): ?>
		html_row += '<option value="<?php echo html_escape($chemical['id']) ?>" ><?php echo html_escape($chemical['name']) . ' (' . $chemical['code'] . ')' ?></option>';
<?php endforeach; ?>
		html_row += '</select>';
		html_row += '<span class="error"></span></div></td>';
		html_row += '</div></td>';
		html_row += '<td><div class="form-group">';
		html_row += '<input type="text" name="purchases[' + value + '][unit_price]" class="form-control purchase_unit_price" readonly value="0.00" />';
		html_row += '</div></td>';
		html_row += '<td><div class="form-group">';
		html_row += '<input id="quantity' + value + '" type="number" name="purchases[' + value + '][quantity]" class="form-control purchase_quantity" value="1" />';
		html_row += '<span class="error"></span></div></td>';
		html_row += '</div></td>';
		html_row += '<td><div class="form-group">';
		html_row += '<input type="number" name="purchases[' + value + '][discount]" class="form-control purchase_discount" value="0" />';
		html_row += '</div></td>';
		html_row += '<td class="min-w-md">';
		html_row += '<input type="text" class="form-control net_sub_total" name="purchases[' + value + '][net_sub_total]" value="0.00" readonly style="float: left; width: 70%;" />';
		html_row += '<input type="hidden" class="sub_total" name="purchases[' + value + '][sub_total]" value="0" />';
		html_row += '<button type="button" class="btn btn-danger" onclick="deleteRow(' + value + ')" style="float: right; max-width: 30%"><i class="fas fa-times"></i> </button>';
		html_row += '</td>';
		html_row += '</tr>';
		return html_row;
	}
</script>