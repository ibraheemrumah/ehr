<?php $chemical = $this->inventory_model->get_list('chemical', array('id' => $stock['chemical_id']), true, 'category_id'); ?>
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('inventory/chemical_stock'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('stock') . ' ' . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . ' ' . translate('stock'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="create" class="tab-pane active">
	            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
		            <input type="hidden" name="stock_id" value="<?php echo html_escape($stock['id']); ?>">
		            <input type="hidden" name="old_stock_quantity" value="<?php echo html_escape($stock['stock_quantity']); ?>">
		            <input type="hidden" name="old_chemical_id" value="<?php echo html_escape($stock['chemical_id']); ?>">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('inovice_no'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="inovice_no" id="inovice_no" value="<?php echo html_escape($stock['inovice_no']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('category'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("chemical_category", $categorylist, set_value('chemical_category', $chemical['category_id']), "class='form-control'
								data-plugin-selectTwo onchange='getChemicalByCategory(this.value,0)' required data-width='100%' ");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$chemical_list = array('' => translate('select'));
								echo form_dropdown("chemical_id", $chemical_list, set_value("chemical_id"), "class='form-control' data-plugin-selectTwo
								id='in_chemical_id' required data-width='100%' ");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" data-plugin-datepicker data-plugin-options='{"todayHighlight" : true}' name="date" value="<?php echo html_escape($stock['date']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('stock') . " " . translate('quantity'); ?> (<?php echo translate('sales_unit'); ?>) <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="stock_quantity" value="<?php echo html_escape($stock['stock_quantity']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
						<div class="col-md-6 mb-lg">
							<textarea class="form-control" id="remarks" name="remarks" placeholder="" rows="3"><?php echo html_escape($stock['remarks']); ?></textarea>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" name="update" value="1" class="btn btn-default btn-block"><i class="fas fa-edit"></i> <?php echo translate('update'); ?></button>
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
		var chemical_category_id = "<?php echo html_escape($chemical['category_id']); ?>";
		var chemical_id = "<?php echo html_escape($stock['chemical_id']); ?>";
		getChemicalByCategory(chemical_category_id, chemical_id);
	});
</script>