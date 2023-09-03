<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css');?>">
<script src="<?php echo base_url('assets/vendor/bootstrap-timepicker/bootstrap-timepicker.js');?>"></script>
<?php $currency_symbol = $global_config['currency_symbol']; ?>
 
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="far fa-credit-card"></i> <?php echo translate('Treatment_bill') . " " . translate('details'); ?></h4>
	</header>
  
	<?php echo form_open('billing/bill_save', array('id' => 'frmSubmit')); ?>
		<div class="panel-body">
			<!-- patient details -->
			<div class="headers-line">
				<i class="fas fa-user-tag"></i> <?php echo translate('bill') . " " . translate('details'); ?>
			</div>
			<div class="row mb-lg">
				<div class="col-md-6 mt-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('patient') ?> <span class="required">*</span></label>
						<?php echo form_dropdown("patient_id", $patientlist, set_value('patient_id'), "class='form-control' id='patient_id' data-plugin-selectTwo data-width='100%'"); ?>
						<span class="error"></span>
					</div>
				</div>
				<input type="hidden" name= "referral_id"class="form-control"  value="0" id="referral_id" />
				<input type="hidden" name= "delivery_date"class="form-control"   value="<?php echo date("Y-m-d"); ?>" id="delivery_date" />
				<input type="hidden" name= "delivery_time"class="form-control"   value="<?php echo date("Y-m-d"); ?>" id="delivery_time" />
				<input type="hidden" name= "bill_type"class="form-control"  value="2" id="bill_type" />
				<div class="col-md-6 mt-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('bill') . " " . translate('date'); ?> <span class="required">*</span></label>
						<input type="text" class="form-control" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true}' 
						name="bill_date"  value="<?php echo date("Y-m-d"); ?>" id="bill_date" />
						<span class="error"></span>
					</div>
				</div>
				<div class="col-md-12 mt-sm">
					<div class="form-group">
						<label class="control-label"><?php echo translate('remarks'); ?> <span class="required">*</span></label>
						<textarea class="form-control" rows="2" name="report_remarks"><?php echo set_value('remarks'); ?></textarea>
					</div>
				</div>
			</div>


			<!-- test delivery -->
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fa fa-procedures"></i> <?php echo translate('Select ') . " " . translate('Treatment'); ?></h4>
	</header>		

			<div class="row mt-md mb-md">
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="tableID">
							<thead>
								<th><?php echo translate('category'); ?> <span class="required">*</span></th>
								<th><?php echo translate('test') . " " . translate('name'); ?></th>
								<th><?php echo translate('test') . " " . translate('Price'); ?></th>
								<th><?php echo  translate('Qty'); ?></th>
								<th><?php echo translate('discount'); ?> (%)</th>
								<th><?php echo translate('total') . " " . translate('price'); ?></th>
							</thead>
							<tbody>
								<tr id="row_0">
									<td class="min-w-md">
										<div class="form-group">
											<?php
											echo form_dropdown("items[0][category]", $categorylist, "", "class='form-control' data-plugin-selectTwo onchange='getLabTest(this.value, 0)' id='test_category0' data-width='100%' ");
											?>
											<span class="error"></span>
										</div>
									</td>
									<td class="min-w-md">
										<div class="form-group">
											<select name="items[0][lab_test]" class="form-control" data-plugin-selectTwo data-width="100%" onchange="get_labtest_price(this.value, 0)" id="lab_test0">
												<option value=""><?php echo translate('select'); ?></option>
											</select>
											<span class="error"></span>
										</div>
									</td>
									<td class="min-w-sm">
										<div class="form-group">
											<input type="text" class="form-control unit_price" id="unit_price0" name="items[0][unit_price]" readonly value="0.00" />
										</div>
									</td>
									<td class="min-w-sm">
										<div class="form-group">
											<input type="text" class="form-control unit_price" id="item_qty0" name="items[0][item_qty]"  value="1" />
										</div>
									</td>
									<td width="320">
										<div class="form-group">
											<div class="discount-group">
												<div class="input-group">
													<span class="input-group-addon">%</span>
													<input type="number" class="form-control" name="items[0][dis_percent]" id="dis_percent0" value="0" onchange="discount_update(0)" onkeyup="discount_update(0)" />
												</div>
												<div class="amount-div">
													<input type="text" class="form-control items_discount" name="items[0][dis_amount]" id="dis_amount0" value="0" readonly />
												</div>
											</div>
										</div>
									</td>
									<td class="min-w-lg">
										<div class="form-group">
											<input type="text" class="form-control total_price" name="items[0][total_price]" value="0.00" id="total_price0" readonly />
											<input type="hidden" class="cont_gra_total" name="items[0][hidden_total_price]" id="hidden_total_price0" value="0">
										</div>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="5"><button type="button" class="btn btn-default" onclick="addRows()"> <i class="fas fa-plus-circle"></i> <?php echo translate('add_rows'); ?></button></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-offset-6 col-md-6">
					<section class="panel panel-custom">
						<header class="panel-heading panel-heading-custom">
							<h4 class="panel-title"><?php echo translate('bill') . " " . translate('summary'); ?></h4>
						</header>
						<div class="panel-body panel-body-custom">
							<table class="table b-less mb-none text-dark">
								<tbody>
									<tr>
										<td colspan="2"><?php echo translate('sub_total'); ?></td>
										<td>
											<div class="input-group">
												<span class="input-group-addon"><?php echo html_escape($currency_symbol); ?></span>
												<input type="text" class="form-control" name="sub_total_amount" id="sub_total_amount" value="0.00" required readonly />
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="2"><?php echo translate('tax'); ?> ( + )</td>
										<td>
											<div class="discount-group">
												<div class="input-group">
													<span class="input-group-addon">%</span>
													<input type="number" class="form-control" name="tax_percent" id="tax_percent" value="0" onkeyup="tax_update()" onchange="tax_update()" />
												</div>
												<div class="amount-div">
													<input type="text" class="form-control" name="tax_amount" id="tax_amount" value="0.00" readonly />
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="2"><?php echo translate('discount'); ?> ( - )</td>
										<td>
											<div class="input-group">
												<span class="input-group-addon"><?php echo html_escape($currency_symbol); ?></span>
												<input type="number" class="form-control" name="total_discount" id="total_discount" value="0.00" readonly />
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="2"><?php echo translate('net') . ' ' . translate('payable'); ?></td>
										<td>
											<div class="input-group">
												<span class="input-group-addon"><?php echo html_escape($currency_symbol); ?></span>
												<input type="text" class="form-control" name="net_amount" id="net_amount" value="0.00" readonly />
											</div>
										</td>
									</tr>

									<tr>
										<td colspan="2"><?php echo translate('received') . ' ' . translate('amount'); ?></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" name="payment_amount" id="payment_amount" placeholder="<?php echo translate('enter_payment_amount'); ?>" />
												<span class="error"></span>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="2"><?php echo translate('pay_via'); ?></td>
										<td>
											<div class="form-group">
												<?php
												echo form_dropdown("pay_via", $payvia_list, set_value('pay_via'), "class='form-control'
												id='pay_via' data-plugin-selectTwo data-width='100%'");
												?>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="2"><?php echo translate('remarks'); ?></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" name="payment_remarks" placeholder="<?php echo translate('write_your_remarks'); ?>" />
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</section>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-3 col-md-offset-9">
					<button class="btn btn-default btn-block" type="submit" id="savebtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-plus-circle"></i> <?php echo translate('create') . " " . translate('bill'); ?>
					</button>
				</div>
			</div>	
		</footer>
	<?php echo form_close(); ?>
</section>

<script type="text/javascript">
	var count = 1;
	function addRows(){
		var tbody = $('#tableID').children('tbody');
		tbody.append(getDynamicInput(count));
		$(".select2_in").select2({
		    theme: "bootstrap",
		    width: "100%"
		});
		count++;
	}

    function deleteRow(id) {
        $("#row_" + id).remove();
        grandTotalCalculateBill();
        tax_update();
    }

	function getDynamicInput(value) {
		var html_row = "";
		html_row += '<tr id="row_' + value + '">';
		html_row += '<td><div class="form-group">';
		html_row += '<select id="test_category' + value + '" name="items[' + value + '][category]" class="form-control select2_in"  onchange="getLabTest(this.value, ' + value + ')" >';
		html_row += '<option value=""><?php echo translate('select'); ?></option>';
<?php
$categorylist = $this->app_lib->get_table('lab_test_category');
foreach($categorylist as $category):
?>
		html_row += '<option value="<?php echo html_escape($category['id']) ?>"><?php echo html_escape($category['name']); ?></option>';
<?php endforeach; ?>
		html_row += '</select>';
		html_row += '<span class="error"></span></div></td>';
		html_row += '<td><div class="form-group">';
		html_row += '<select id="lab_test' + value + '" name="items[' + value + '][lab_test]" class="form-control select2_in"  onchange="get_labtest_price(this.value, ' + value + ')">';
		html_row += '<option value=""><?php echo translate('select'); ?></option>';
		html_row += '</select>';
		html_row += '<span class="error"></span></div></td>';
		html_row += '<td><div class="form-group">';
		html_row += '<input type="text" name="items[' + value + '][unit_price]" id="unit_price' + value + '" class="form-control items_unit_price" readonly value="0.00" />';
		html_row += '</div></td>';
		html_row += '<td><div class="form-group">';
		html_row += '<input type="text" name="items[' + value + '][item_qty]" id="item_qty' + value + '" class="form-control items_unit_price"  value="1" />';
		html_row += '</div></td>';
		html_row += '<td><div class="form-group">';
		html_row += '<div class="discount-group"><div class="input-group"><span class="input-group-addon">%</span>';
		html_row += '<input type="number" name="items[' + value + '][dis_percent]" id="dis_percent' + value + '" class="form-control" onchange="discount_update(' + value + ')" onkeyup="discount_update(' + value + ')" value="0" />';
		html_row += '</div><div class="amount-div"><input type="text" class="form-control items_discount" name="items[' + value + '][dis_amount]" id="dis_amount' + value + '" value="0" readonly />';
		html_row += '</div></div></div></td>';
		html_row += '<td>';
		html_row += '<input type="text" class="form-control total_price" name="items[' + value + '][total_price]" id="total_price' + value + '" value="0.00" readonly style="float: left; width: 70%;" />';
		html_row += '<input type="hidden" class="cont_gra_total" name="items[' + value + '][hidden_total_price]" id="hidden_total_price' + value + '" value="0" />';
		html_row += '<button type="button" class="btn btn-danger" onclick="deleteRow(' + value + ')" style="float: right; max-width: 30%"><i class="fas fa-times"></i> </button>';
		html_row += '</td>';
		html_row += '</tr>';
		return html_row;
	}

	function getLabTest(categoryid, rowid) {
	    var test_id = 0;
	    $("#lab_test" + rowid).html("<option value=''><?php echo translate('exploring'); ?>...</option>");
		$("#unit_price" + rowid).val(0);
		$("#total_price" + rowid).val(0);
		$("#hidden_total_price" + rowid).val(0);
		$("#dis_percent" + rowid).val(0);
		$("#dis_amount" + rowid).val(0);
	    $.ajax({
	        type: "POST",
	        url: "<?php echo base_url('ajax/get_labtest_by_category'); ?>",
	        data: {"selected_id": test_id, "category_id": categoryid},
	        dataType: "html",
	        success: function(data) {
	           $("#lab_test" + rowid).html(data);
	        }
	    });	
	}

	function get_labtest_price(testid, rowid) {
		$("#unit_price" + rowid).val(0);
		$("#total_price" + rowid).val(0);
		$("#hidden_total_price" + rowid).val(0);
		$("#dis_percent" + rowid).val(0);
		$("#dis_amount" + rowid).val(0);
	    $.ajax({
	        type: "POST",
	        url: "<?php echo base_url('ajax/get_labtest_price'); ?>",
	        data: {"testid": testid},
	        dataType: "html",
	        success: function(amount) {
	           $("#unit_price" + rowid).val(amount);
	           $("#total_price" + rowid).val(amount);
	           $("#hidden_total_price" + rowid).val(amount);
	           grandTotalCalculateBill();
	           tax_update();
	        }
	    });	
	}
</script>