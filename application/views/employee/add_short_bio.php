<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-user-tag"></i> <?php echo translate('add_short_bio'); ?></h4>
			</header>
			<?php echo form_open($this->uri->uri_string()); ?>
			<div class="panel-body">
				<div class="form-group">
					<label  class="control-label"><?php echo translate('add_short_bio'); ?> <span class="required">*</span></label>
					<textarea name="short_bio" id="editor"><?php echo set_value('short_bio', $bio['biography']); ?></textarea>
					<span class="error"><?php echo form_error('short_bio'); ?></span>
				</div>
			</div>
			<footer class="panel-footer mt-md">
				<div class="row">
					<div class="col-md-2 col-md-offset-10">
						<button type="submit" class="btn btn-default btn-block" name="save" value="1">
							<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
						</button>
					</div>
				</div>	
			</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>