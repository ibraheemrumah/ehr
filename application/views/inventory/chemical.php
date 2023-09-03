<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#chemicallist" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('chemical') . ' ' . translate('list'); ?></a>
			</li>
<?php if (get_permission('chemical', 'is_add')){ ?>
			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('create') . ' ' . translate('chemical'); ?></a>
			</li>
<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="chemicallist" class="tab-pane active mb-md">
				<div class="export_title"><?php echo translate('chemical') . " " . translate('list'); ?></div>
				<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
					<thead>
						<tr>
							<th><?php echo translate('sl'); ?></th>
							<th><?php echo translate('name'); ?></th>
							<th><?php echo translate('code'); ?></th>
							<th><?php echo translate('category'); ?></th>
							<th><?php echo translate('purchase_unit'); ?></th>
							<th><?php echo translate('sale_unit'); ?></th>
							<th><?php echo translate('unit_ratio'); ?></th>
							<th><?php echo translate('purchase_price'); ?></th>
							<th><?php echo translate('sales_price'); ?></th>
							<th><?php echo translate('remarks'); ?></th>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						if (!empty($chemicallist)){
							foreach ($chemicallist as $row):
							?>	
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo html_escape($row['name']); ?></td>
							<td><?php echo html_escape($row['code']); ?></td>
							<td><?php echo html_escape($row['category_name']); ?></td>
							<td><?php echo html_escape($row['p_unit_name']); ?></td>
							<td><?php echo html_escape($row['s_unit_name']); ?></td>
							<td><?php echo html_escape($row['unit_ratio']); ?></td>
							<td><?php echo html_escape($global_config['currency_symbol'] . $row['purchase_price']); ?></td>
							<td><?php echo html_escape($global_config['currency_symbol'] . $row['sales_price']); ?></td>
							<td><?php echo html_escape($row['remarks']); ?></td>
							<td class="min-w-xs">
								<?php if (get_permission('chemical', 'is_edit')): ?>
									<a href="<?php echo base_url('inventory/chemical_edit/' . $row['id']); ?>" class="btn btn-circle icon btn-default" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('chemical', 'is_delete')): ?>
									<?php echo btn_delete('inventory/chemical_delete/' . $row['id']); ?>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; }?>
					</tbody>
				</table>
			</div>
		<?php if (get_permission('chemical', 'is_add')){ ?>
			<div id="create" class="tab-pane">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="chemical_name" value="" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('code'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="chemical_code" value="" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('category'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("chemical_category", $categorylist, set_value("chemical_category"), "class='form-control' data-plugin-selectTwo required
								data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('purchase_unit'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("purchase_unit", $unitlist, set_value("purchase_unit"), "class='form-control' data-plugin-selectTwo required
								data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('sale_unit'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("sales_unit", $unitlist, set_value("sales_unit"), "class='form-control' data-plugin-selectTwo required
								data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('unit_ratio'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="unit_ratio" id="unit_ratio" value="" placeholder="Eg. Purchase Unit : KG & Sales Unit : Gram = Ratio : 1000" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('purchase_price'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="purchase_price" id="purchase_price" value="" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('sales_price'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="sales_price" id="sales_price" value="" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
						<div class="col-md-6 mb-lg">
							<input type="text" class="form-control" name="remarks" id="remarks" value="" />
						</div>
					</div>
					
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
			<?php } ?>
		</div>
	</div>
</section>