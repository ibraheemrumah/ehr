<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-pen-nib"></i> <?php echo translate('edit_word'); ?></h4>
			</header>
		    <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
			    <input type="hidden" name="select_lang" value="<?php echo html_escape($select_language); ?>">
				<div class="panel-body">
					<table class="table table-bordered table-hover table-condensed">
						<thead>
							<tr>
								<th><?php echo translate('sl'); ?></th>
								<th><?php echo translate('word'); ?></th>
								<th><?php echo translate('translations'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							foreach($langresult as $row) {
								?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo ucwords(str_replace('_', ' ',  $row['word'])); ?></td>
								<td>
									<div class="input-group">
										<span class="input-group-addon"><span class="icon"><i class="far fa-comment-alt"></i></span></span>
										<input type="text" class="form-control" placeholder="Set Word Translation" name="word[<?php echo html_escape($row['id']); ?>][field]" value="<?php echo isset($row[$select_language]) ? $row[$select_language] : ''; ?>" />
									</div>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-10 col-md-2">
						<button class="btn btn btn-default btn-block" name="update" value="1">
							<i class="fas fa-edit"></i> <?php echo translate('update'); ?>
						</button>
					</div>
				</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>
