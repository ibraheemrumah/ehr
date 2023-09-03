<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('inventory/reagent_assigned'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('assigned') . ' ' . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . ' ' . translate('assigned'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="create" class="tab-pane active">
	            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
		            <input type="hidden" name="old_lab_test_id" value="<?php echo html_escape($assigntlist['test_id']); ?>">
					<?php
					$chemicals_sel = array();
					foreach ($assigntlist['chemicals'] as $row) {
						$chemicals_sel[] = $row->chemical_id;
					?>
					<input type="hidden" name="old_chemicals[]" value="<?php echo html_escape($row->chemical_id); ?>">
					<?php } ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('for') . " " . translate('lab_test'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								echo form_dropdown("lab_test_id", $testlist, set_value("lab_test_id", $assigntlist['test_id']), "class='form-control' data-plugin-selectTwo
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
							foreach($result as $row ) {
								$arrayData[$row->id] = $row->name;
							}
							echo form_dropdown("chemicals[]", $arrayData, set_value("chemicals", $chemicals_sel), "class='form-control' id='chemical_holder' multiple='multiple'");
							?>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" name="save" value="1" class="btn btn-default btn-block"><i class="fas fa-edit"></i> <?php echo translate('update'); ?></button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
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