<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="">
				<a href="<?php echo base_url('test'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('test') . " " . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('test'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="create">
	            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered')); ?>
		            <input type="hidden" name="test_id" value="<?php echo html_escape($test['id']); ?>">
					<div class="form-group <?php if (form_error('category_id')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('test_category'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$array_categories = $this->app_lib->getSelectList('lab_test_category');
								echo form_dropdown("category_id", $array_categories, set_value('category_id', $test['category_id']), "class='form-control' id='category_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"><?php echo form_error('category_id'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('test_name')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('test_name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="test_name" value="<?php echo set_value('test_name', $test['name']); ?>" />
							<span class="error"><?php echo form_error('test_name'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('test_code')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('test_code'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="test_code" value="<?php echo set_value('test_code', $test['test_code']); ?>" />
							<span class="error"><?php echo form_error('test_code'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('production_cost')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('production_cost'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="test" class="form-control" name="production_cost" value="<?php echo set_value('production_cost', $test['production_cost']); ?>" />
							<span class="error"><?php echo form_error('production_cost'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('patient_price')) echo 'has-error'; ?>">
						<label class="col-md-3 control-label"><?php echo translate('patient_price'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="test" class="form-control" name="patient_price" value="<?php echo set_value('patient_price', $test['patient_price']); ?>" />
							<span class="error"><?php echo form_error('patient_price'); ?></span>
						</div>
					</div>
					<div class="form-group <?php if (form_error('date')) echo 'has-error'; ?>">
						<label  class="col-md-3 control-label"><?php echo translate('date'); ?> <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
							<input type="text" class="form-control" name="date" value="<?php echo set_value('date', $test['date']); ?>" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' />
							<span class="error"><?php echo form_error('date'); ?></span>
						</div>
					</div>
					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" name="update" value="1">
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