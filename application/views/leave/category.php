<div class="row">
<?php if (get_permission('leave_category', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('add') . " " . translate('leave_category'); ?></h4>
			</header>
            <?php echo form_open($this->uri->uri_string()); ?>
				<div class="panel-body">
					<div class="form-group <?php if (form_error('leave_category')) echo 'has-error'; ?>">
						<label class="control-label"><?php echo translate('leave_category') . " " . translate('name'); ?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="leave_category" value="<?php echo set_value('leave_category'); ?>" />
						<span class="error"><?php echo form_error('leave_category'); ?></span>
					</div>
					<div class="form-group <?php if (form_error('leave_days')) echo 'has-error'; ?> mb-md">
						<label class="control-label"><?php echo translate('days'); ?> <span class="required">*</span></label>
						<input type="number" class="form-control" name="leave_days" value="<?php echo set_value('leave_days'); ?>" placeholder="Decide The Day" />
						<span class="error"><?php echo form_error('leave_days'); ?></span>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-default pull-right" type="submit" name="save" value="1"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
						</div>	
					</div>
				</div>
			<?php echo form_close(); ?>
		</section>
	</div>
<?php endif; ?>
<?php if (get_permission('leave_category', 'is_view')): ?>
	<div class="col-md-<?php if (get_permission('leave_category', 'is_add')){ echo "7"; }else{echo "12";} ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?php echo translate('leave_category') . " " . translate('list'); ?></h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr>
								<th><?php echo translate('sl'); ?></th>
								<th><?php echo translate('name'); ?></th>
								<th><?php echo translate('days'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						if (count($category)){
							foreach ($category as $row):
								?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td><?php echo html_escape($row['name']); ?></td>
								<td><?php echo html_escape($row['days']); ?></td>
								<td class="min-w-xs">
								<?php if (get_permission('leave_category', 'is_edit')): ?>
									<a class="btn btn-default btn-circle icon" href="javascript:void(0);" data-toggle="tooltip" data-original-title="<?php echo translate('edit');?>"
										onclick="getLeaveCategory('<?php echo html_escape($row['id']); ?>')">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php  endif; if (get_permission('leave_category', 'is_delete')): ?>
									<?php echo btn_delete('leave/category_delete/' . $row['id']); ?>
								<?php endif; ?>
								</td>
							</tr>
							<?php
								endforeach;
							}else{
								echo '<tr><td colspan="4"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
<?php endif; ?>
</div>

<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="far fa-edit"></i> <?php echo translate('edit') . " " .translate('leave_category'); ?>
			</h4>
		</header>
		<?php echo form_open(base_url('leave/category_edit'), array('class' => 'validate', 'method' => 'post')); ?>
			<div class="panel-body">
				<input type="hidden" name="category_id" id="ecategory_id" value="">
				<div class="form-group">
					<label class="control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
					<input type="text" class="form-control" required name="leave_category" id="eleave_category" value="" />
				</div>
				<div class="form-group mb-md">
					<label class="control-label"><?php echo translate('days'); ?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="leave_days" id="eleave_days" required value="" />
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-default"><?php echo translate('update'); ?></button>
						<button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
					</div>
				</div>
			</footer>
		<?php echo form_close(); ?>
	</section>
</div>