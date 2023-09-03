<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('inventory/purchase'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('purchase') . ' ' . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . ' ' . translate('purchase'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="edit" class="tab-pane active">
				<?php echo form_open('inventory/purchase_edit_save', array('id' => 'frmSubmit')); ?>
					<input type="hidden" name="purchase_bill_id" value="<?php echo html_escape($purchaselist['id']); ?>">
					<div class="form-horizontal form-bordered">
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo translate('supplier'); ?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									echo form_dropdown("supplier_id", $supplierlist, $purchaselist['supplier_id'], "class='form-control' data-plugin-selectTwo id='supplier_id'
									data-width='100%' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo translate('bill_no'); ?> <span class="required">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="bill_no" id='bill_no' value="<?php echo html_escape($purchaselist['bill_no']); ?>" />
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
									echo form_dropdown("purchase_status", $status_list, $purchaselist['purchase_status'], "class='form-control' id='purchase_status'
									data-plugin-selectTwo data-width='100%' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="date" id='date' value="<?php echo html_escape($purchaselist['date']); ?>" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' />
								<span class="error"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
							<div class="col-md-6 mb-lg">
								<textarea class="form-control" rows="2" name="remarks"><?php echo html_escape($purchaselist['remarks']); ?></textarea>
							</div>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table table-bordered mt-md" id="tableID">
							<thead>
								<th><?php echo translate('chemical'); ?> <span class="required">*</span></th>
								<th><?php echo translate('unit') . " " . translate('price'); ?></th>
								<th><?php echo translate('quantity'); ?> <span class="required">*</span></th>
								<th><?php echo translate('discount'); ?></th>
								<th><?php echo translate('total') . " " . translate('price'); ?></th>
							</thead>
							<tbody>
								<?php
								$count = 1;
								$this->db->order_by('id', 'ASC');
								$bill_details = $this->db->get_where('purchase_bill_details', array('purchase_bill_id' => $purchaselist['id']))->result();
								if(count($bill_details)){
								foreach ($bill_details as $key => $chemical):
								?>
								<tr>
									<td class="min-w-sm">
										<div class="form-group">
											<input type="hidden" name="purchases[<?php echo $key; ?>][old_bill_details_id]" value="<?php echo html_escape($chemical->id); ?>">
											<select data-plugin-selectTwo class="form-control purchase_chemical" data-width="100%" name="purchases[<?php echo $key; ?>][chemical]" id="chemical<?php echo $key; ?>">
											<option value=""><?php echo translate('select'); ?></option>
											<?php foreach ($chemicallist as $value) { ?>
												<option value="<?php echo html_escape($value['id']); ?>" <?php echo ($value['id'] == $chemical->chemical_id ? 'selected' : ''); ?>><?php echo html_escape($value['name']) . ' ('. html_escape($value['code']) . ')'; ?></option>
											<?php } ?>
											</select>
											<span class="error"></span>
										</div>
									</td>
									<td class="min-w-sm">
										<div class="form-group">
											<input type="text" class="form-control purchase_unit_price" name="purchases[<?php echo $key; ?>][unit_price]" readonly
											value="<?php echo html_escape($chemical->unit_price); ?>" />
										</div>
									</td>
									<td class="min-w-xs">
										<div class="form-group">
											<input type="text" class="form-control purchase_quantity" name="purchases[<?php echo $key; ?>][quantity]" id="quantity<?php echo $key; ?>"
											value="<?php echo html_escape($chemical->quantity); ?>" />
											<span class="error"></span>
										</div>
									</td>
									<td class="min-w-md">
										<div class="form-group">
											<input type="number" class="form-control purchase_discount" name="purchases[<?php echo $key; ?>][discount]" value="<?php echo html_escape($chemical->discount); ?>" />
										</div>
									</td>
									<td class="min-w-md">
										<div class="form-group">
											<input type="text" class="form-control net_sub_total" name="purchases[<?php echo $key; ?>][net_sub_total]" value="<?php echo ($chemical->sub_total - $chemical->discount); ?>" readonly />
											<input type="hidden" class="sub_total" name="purchases[<?php echo $key; ?>][sub_total]" value="<?php echo html_escape($chemical->sub_total); ?>">
										</div>
									</td>
								</tr>
								<?php endforeach; } ?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="1"><button type="button" class="btn btn-default" onclick="addRows()"> <i class="fas fa-plus-circle"></i> <?php echo translate('add_rows'); ?></button></td>
									<td class="text-right" colspan="3"><b><?php echo translate('net_total'); ?> :</b></td>
									<td class="text-right">
										<input type="text" id="netGrandTotal" class="text-right form-control" name="net_grand_total" value="<?php echo html_escape($purchaselist['total'] - $purchaselist['discount']); ?>" readonly />
										<input type="hidden" id="grandTotal" name="grand_total" value="<?php echo html_escape($purchaselist['total']); ?>">
										<input type="hidden" id="totalDiscount" name="total_discount" value="<?php echo html_escape($purchaselist['discount']); ?>">
										<input type="hidden" name="purchase_paid" value="<?php echo html_escape($purchaselist['paid']); ?>">
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-9 col-md-3">
								<button type="submit" name="update" id="savebtn" value="1" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?php echo translate('update'); ?>
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
	var count = <?php echo count($bill_details); ?>;
	$(document).ready(function() {
		$(document).on('change', '.purchase_chemical', function() {
			var row = $(this).closest('tr');
			var id = $(this).val();
			$.ajax({
				type: "POST",
				data: {'id' : id},
				url: "<?php echo base_url('ajax/get_chemical_price'); ?>",
				success: function (result) {
					var unit_price = isNaN(result) ? 0 : parseFloat(result);
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
		html_row += '<select id="chemical' + value + '" name="purchases[' + value + '][chemical]" class="form-control purchase_chemical">';
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
		html_row += '<input id="quantity' + value + '"type="number" name="purchases[' + value + '][quantity]" class="form-control purchase_quantity" value="1" />';
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