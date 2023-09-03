<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#database_backup" data-toggle="tab">
				   <i class="fas fa-database"></i> 
				   <span class="hidden-xs"> <?php echo translate('database') . " " . translate('list'); ?></span>
				</a>
			</li>
<?php if (get_permission('database_restore', 'is_add')){ ?>
			<li>
				<a href="#restore_database" data-toggle="tab">
				   <i class="fas fa-upload"></i>
				   <span class="hidden-xs"> <?php echo translate('restore') . " " . translate('database') ?></span>
				</a>
			</li>
<?php } ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane box active" id="database_backup">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-right mb-md">
						    <?php echo form_open($this->uri->uri_string()); ?>
						    	<button class="btn btn-default btn-sm" type="submit" name="backup" value="1"><i class="fas fa-paste"></i> <?php echo translate('create_backup') ?></button>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed">
						<thead>
							<tr>
								<th width="60"><?php echo translate('sl'); ?></th>
								<th><?php echo translate('file') . " " . translate('name'); ?></th>
								<th><?php echo translate('backup_size'); ?></th>
								<th><?php echo translate('date'); ?></th>
								<th><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							$files = get_filenames(FCPATH.'/uploads/db_backup/');
							if(count($files)){
								foreach ($files as $file){
								$sqlpath = './uploads/db_backup/' . $file;
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo ($file); ?></td>
								<td><?php echo bytesToSize($sqlpath); ?></td>
								<td><?php echo date('Y-m-d h:i a', filectime($sqlpath)); ?></td>
								<td>
									<?php if (get_permission('database_backup', 'is_add')) { ?>
										<a href="<?php echo base_url('backup/download?file=' . $file) ?>" class="btn btn-circle icon btn-default"><i class="fas fa-download"></i></a>
									<?php } if (get_permission('database_backup', 'is_delete')) { ?>
										<?php echo btn_delete('backup/delete_file/' . $file); ?>
									<?php } ?>
								</td>
							</tr>
							<?php } }else{ 
								echo '<tr><td colspan="5"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
							
							}?>
						</tbody>
					</table>
				</div>
			</div>
<?php if (get_permission('database_restore', 'is_add')){ ?>
			<div class="tab-pane box" id="restore_database">
				<?php echo form_open_multipart(base_url('backup/restore_file'), array('class' => 'form-horizontal validate')); ?>
					<div class="form-group mb-lg">
						<label class="col-md-3 control-label"><?php echo translate('file_upload'); ?> <span class="required" aria-required="true">*</span></label>
						<div class="col-md-7">
							<input type="file" name="uploaded_file" class="dropify" data-height="140" data-allowed-file-extensions="zip" required />
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-2 col-sm-offset-3">
							 <button type="submit" class="btn btn-default btn-block"><i class="fas fa-cloud-upload-alt"></i> <?php echo translate('restore'); ?></button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php } ?>
		</div>
	</div>
</section>