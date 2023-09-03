<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('inventory/chemical'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('chemical') . " " . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('chemical'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="edit" class="tab-pane active">
	            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
		            <input type="hidden" name="chemical_id" value="<?php echo html_escape($chemical['id']); ?>">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="chemical_name" value="<?php echo html_escape($chemical['name']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('code'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="chemical_code" value="<?php echo html_escape($chemical['code']); ?>" required />
						</div>
					</div>
					
					
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('category'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("chemical_category", $categorylist, set_value('chemical_category', $chemical['category_id']), "class='form-control' data-plugin-selectTwo
								data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('purchase_unit'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("purchase_unit", $unitlist, set_value('purchase_unit', $chemical['purchase_unit_id']), "class='form-control' data-plugin-selectTwo
								data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('sale_unit'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("sales_unit", $unitlist, set_value('sales_unit', $chemical['sales_unit_id']), "class='form-control' data-plugin-selectTwo
								data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('unit_ratio'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="unit_ratio" id="unit_ratio" value="<?php echo html_escape($chemical['unit_ratio']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('purchase_price'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="purchase_price" id="purchase_price" value="<?php echo html_escape($chemical['purchase_price']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('sales_price'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="sales_price" id="sales_price" value="<?php echo html_escape($chemical['sales_price']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
						<div class="col-md-6 mb-lg">
							<input type="text" class="form-control" name="remarks" id="remarks" value="<?php echo html_escape($chemical['remarks']); ?>" />
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block"><i class="fas fa-edit"></i> <?php echo translate('update'); ?></button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>