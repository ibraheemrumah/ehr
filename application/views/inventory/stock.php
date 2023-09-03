<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#chemicallist" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('stock') . ' ' . translate('list'); ?></a>
			</li>
<?php if (get_permission('chemical_stock', 'is_add')){ ?>
			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('add') . ' ' . translate('stock'); ?></a>
			</li>
<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="chemicallist" class="tab-pane active mb-md">
				<div class="export_title"><?php echo translate('stock') . " " . translate('report'); ?></div>
				<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
					<thead>
						<tr>
							<th><?php echo translate('sl'); ?></th>
							<th><?php echo translate('bill_no'); ?></th>
							<th><?php echo translate('stock_by'); ?></th>
							<th><?php echo translate('chemical') . " " . translate('category'); ?></th>
							<th><?php echo translate('chemical') . " " . translate('name'); ?></th>
							<th><?php echo translate('date'); ?></th>
							<th><?php echo translate('stock') . " " . translate('quantity'); ?></th>
							<th><?php echo translate('remarks'); ?></th>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						if(!empty($stocktlist)) {
							foreach ($stocktlist as $row):
								?>	
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo html_escape($row['inovice_no']); ?></td>
							<td><?php echo html_escape($row['staff_name']) . " (". $row['staff_id'] . ")"; ?></td>
							<td><?php echo html_escape($row['category_name']); ?></td>
							<td><?php echo html_escape($row['chemical_name']); ?></td>
							<td><?php echo html_escape(_d($row['date'])); ?></td>
							<td><?php echo html_escape($row['stock_quantity']); ?></td>
							<td><?php echo html_escape($row['remarks']); ?></td>
							<td>
								<?php if (get_permission('chemical_stock', 'is_edit')): ?>
									<a href="<?php echo base_url('inventory/chemical_stock_edit/' . html_escape($row['id'])); ?>" class="btn btn-circle icon btn-default" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('chemical_stock', 'is_delete')): ?>
									<?php echo btn_delete('inventory/chemical_stock_delete/' . html_escape($row['id'])); ?>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; }?>
					</tbody>
				</table>
			</div>
<?php if (get_permission('chemical_stock', 'is_add')){ ?>
			<div id="create" class="tab-pane">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('bill_no'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="inovice_no" id="inovice_no" value="" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('category'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("chemical_category", $categorylist, set_value("chemical_category"), "class='form-control' data-plugin-selectTwo
								onchange='getChemicalByCategory(this.value,0)' required data-width='100%' ");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<select name="chemical_id" class="form-control" data-plugin-selectTwo data-width="100%" id="in_chemical_id" required>
								<option value=""><?php echo translate('select'); ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" data-plugin-datepicker data-plugin-options='{"todayHighlight" : true}' name="date" value="<?php echo date('Y-m-d'); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('stock') . " " . translate('quantity'); ?> ( <?php echo translate('sale_unit'); ?> ) <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="stock_quantity" value="0" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
						<div class="col-md-6 mb-lg">
							<textarea class="form-control" id="remarks" name="remarks" placeholder="" rows="3"></textarea>
						</div>
					</div>
					
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" name="stock" value="1" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php } ?>
		</div>
	</div>
</section>