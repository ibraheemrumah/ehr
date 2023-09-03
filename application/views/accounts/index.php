<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('account') . " " . translate('list'); ?></a>
			</li>
<?php if (get_permission('account', 'is_add')){ ?>
			<li class="<?php echo (isset($validation_error) ? 'active' : ''); ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('create') . " " . translate('account'); ?></a>
			</li>
<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<div class="mb-md">
					<div class="export_title"><?php echo translate('test') . " " . translate('list'); ?></div>
					<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
						<thead>
							<tr>
								<th width="50"><?php echo translate('sl'); ?></th>
								<th><?php echo translate('account') . " " . translate('name'); ?></th>
								<th><?php echo translate('description'); ?></th>
								<th><?php echo translate('date'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php $count = 1; if (!empty($accountslist)){ foreach ($accountslist as $row): ?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo html_escape($row['name']); ?></td>
								<td><?php echo html_escape($row['description']); ?></td>
								<td><?php echo html_escape(_d($row['created_at'])); ?></td>
								<td>
									<?php if (get_permission('account', 'is_edit')): ?>
										<a href="<?php echo base_url('accounts/edit/' . $row['id']); ?>" class="btn btn-circle icon btn-default" data-placement="top" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
											<i class="fas fa-pen-nib"></i>
										</a>
									<?php endif; if (get_permission('account', 'is_delete')): ?>
										<?php echo btn_delete('accounts/delete/' . $row['id']); ?>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; }?>
						</tbody>
					</table>
				</div>
			</div>
<?php if (get_permission('account', 'is_add')){ ?>
			<div class="tab-pane <?php echo (isset($validation_error) ? 'active' : ''); ?>" id="create">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered')); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('account') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="account_name" value="<?php echo set_value('account_name'); ?>" />
							<span class="error"><?php echo form_error('account_name'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('description'); ?></label>
						<div class="col-md-6">
							<textarea class="form-control" id="description" name="description" placeholder="" rows="3"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('opening_balance'); ?></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="opening_balance" value="<?php echo set_value('opening_balance', 0); ?>" />
							<span class="error"><?php echo form_error('opening_balance'); ?></span>
						</div>
					</div>
					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" name="save" value="1">
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