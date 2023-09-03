<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('inventory/supplier'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('supplier') . ' ' . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . ' ' . translate('supplier'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="edit" class="tab-pane active">
	            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
	            	<input type="hidden" name="supplier_id" value="<?php echo html_escape($supplier['id']); ?>">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('supplier') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="supplier_name" value="<?php echo html_escape($supplier['name']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('email'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="email_address" value="<?php echo html_escape($supplier['email']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('contact_number'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="contact_number" value="<?php echo html_escape($supplier['mobileno']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('company_name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="company_name" value="<?php echo html_escape($supplier['company_name']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('product') . " " . translate('list'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="product_list" value="<?php echo html_escape($supplier['product_list']); ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('address'); ?></label>
						<div class="col-md-6 mb-md">
							<textarea class="form-control" rows="3" name="address" placeholder="<?php echo translate('address'); ?>" ><?php echo html_escape($supplier['address']); ?></textarea>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" name="chemical" value="1" class="btn btn-default btn-block">
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