<div class="row">
<?php if (get_permission('designation', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('add') . " " . translate('designation'); ?></h4>
			</header>
			<?php echo form_open($this->uri->uri_string()); ?>
				<div class="panel-body">
					<div class="form-group mb-md <?php if (form_error('designation_name')) echo 'has-error'; ?>">
						<label class="control-label"><?php echo translate('designation') . " " . translate('name'); ?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="designation_name" value="<?php echo set_value('designation_name'); ?>" />
						<span class="error"><?php echo form_error('designation_name'); ?></span>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-default pull-right" type="submit"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
						</div>	
					</div>
				</div>
			<?php echo form_close(); ?>
		</section>
	</div>
<?php endif; ?>
<?php if (get_permission('designation', 'is_view')): ?>
	<div class="col-md-<?php if (get_permission('designation', 'is_add')){ echo "7"; }else{echo "12";} ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?php echo translate('designation') . " " . translate('list'); ?></h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr>
								<th><?php echo translate('sl'); ?></th>
								<th><?php echo translate('name'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$count = 1;
						$query = $this->db->get('staff_designation');
						if ($query->num_rows() > 0){
							$designations = $query->result();
							foreach ($designations as $row):
						?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo html_escape($row->name); ?></td>
								<td class="min-w-xs">
								<?php if (get_permission('designation', 'is_edit')): ?>
									<a class="btn btn-default btn-circle icon" href="javascript:void(0);" data-toggle="tooltip" data-original-title="<?php echo translate('edit');?>"
									onclick="getDesignationDetails('<?php echo html_escape($row->id); ?>')">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('designation', 'is_delete')): ?>
									<?php echo btn_delete('employee/designation_delete/' . $row->id); ?>
								<?php endif; ?>
								</td>
							</tr>
						<?php
							endforeach;
						}else{
							echo '<tr><td colspan="3"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>
<?php endif; ?>

<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('designation'); ?>
			</h4>
		</header>
		<?php echo form_open(base_url('employee/designation_edit'), array('class' => 'validate','method' => 'post')); ?>
			<div class="panel-body">
				<input type="hidden" name="designation_id" id="edesignation_id" value="">
				<div class="form-group mb-md">
					<label class="control-label"><?php echo translate('designation') . " " . translate('name'); ?> <span class="required">*</span></label>
					<input type="text" class="form-control" required  value="" name="designation_name" id="edesignation_name">
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