<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (!isset($valid_error) ? 'active' : '') ?>">
				<a href="#chemicallist" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('reagent') . ' ' . translate('list'); ?></a>
			</li>
<?php if (get_permission('reagent_assigned', 'is_add')){ ?>
			<li class="<?php echo (isset($valid_error) ? 'active' : '') ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('set') . ' ' . translate('reagent'); ?></a>
			</li>
<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="chemicallist" class="tab-pane <?php echo !isset($valid_error) ? 'active' : ''; ?> mb-md">
				<table class="table table-bordered table-hover table-condensed tbr-top table_default">
					<thead>
						<tr>
							<th><?php echo translate('sl'); ?></th>
							<th><?php echo translate('test') . " " . translate('name'); ?></th>
							<th><?php echo translate('test') . " " . translate('code'); ?></th>
							<th><?php echo translate('chemical'); ?></th>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$count = 1;
						if(!empty($assigntlist)) {
							foreach($assigntlist as $row):
								?>	
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo html_escape($row['test_name']); ?></td>
							<td><?php echo html_escape($row['test_code']); ?></td>
							<td>
								<?php
									$chemicals = $row['chemicals'];
									foreach ($chemicals as $value) {
										echo html_escape($value->chemical_name) . " (" . html_escape($value->chemical_code) . ") <br>";
									}
								?>
							</td>
							<td>
								<?php if (get_permission('reagent_assigned', 'is_edit')): ?>
									<a href="<?php echo base_url('inventory/reagent_assigned_edit/' . $row['test_id']); ?>" class="btn btn-circle icon btn-default" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('reagent_assigned', 'is_delete')): ?>
									<?php echo btn_delete('inventory/reagent_assigned_delete/' . $row['test_id']); ?>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; }?>
					</tbody>
				</table>
			</div>
<?php if (get_permission('reagent_assigned', 'is_add')){ ?>
			<div id="create" class="tab-pane <?php echo (isset($valid_error) ? 'active' : '') ?>">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'validate form-horizontal form-bordered')); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('for') . " " . translate('lab_test'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("lab_test_id", $testlist, set_value("lab_test_id"), "class='form-control' data-plugin-selectTwo
								data-width='100%'");
							?>
							<span class="error"><?php echo form_error('lab_test_id'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('chemical') . " " . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6 mb-md">
							<?php
							$arrayData = array(); 
							$result = $this->db->get('chemical')->result();
							foreach($result as $row ){
								$arrayData[$row->id] = $row->name;
							}
							echo form_dropdown("chemicals[]", $arrayData, set_value("chemicals"), "class='form-control' id='chemical_holder' multiple='multiple'"); 
							?>
							<span class="error"><?php echo form_error('chemicals[]'); ?></span>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" name="save" value="1" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php } ?>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
		$("#chemical_holder").select2({
		    theme: "bootstrap",
		    width: "100%",
		    placeholder: "<?php echo translate('select_multiple_chemical'); ?>",
			allowClear: true
		});
		$('.select2-search__field').css('width', '300px');
	});
</script>