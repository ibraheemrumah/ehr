<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#email_config" data-toggle="tab"><i class="far fa-envelope"></i> <?php echo translate('email_config'); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url('mailconfig/template'); ?>"><i class="fas fa-sitemap"></i> <?php echo translate('email_triggers'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="email_config" class="tab-pane active">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('system_email'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input required class="form-control" value="<?php echo html_escape($config['email']); ?>" name="email" type="email" placeholder="All Outgoing Email Will be sent from This Email Address.">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Email Protocol</label>
						<div class="col-md-6">
							<select name="email_protocol" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="email_protocol">
								<option value="mail" <?php if(set_value('email_protocol', $config['email_protocol']) == 'mail') echo 'selected'; ?>>PHP Mail Function</option>
								<option value="sendmail" <?php if(set_value('email_protocol', $config['email_protocol']) == 'sendmail') echo 'selected'; ?>>Send Mail</option>
								<option value="smtp" <?php if(set_value('email_protocol', $config['email_protocol']) == 'smtp') echo 'selected'; ?>>SMTP Mail</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">SMTP Host <span class="required">*</span></label>
						<div class="col-md-6">
							<input required class="form-control smtp" value="<?php echo html_escape($config['smtp_host']); ?>" name="smtp_host" type="text">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">SMTP Username <span class="required">*</span></label>
						<div class="col-md-6">
							<input required class="form-control smtp" value="<?php echo html_escape($config['smtp_user']); ?>" name="smtp_user" type="text">
						</div>
					</div>
					<div class="form-group">
						 <label class="col-md-3 control-label">SMTP Password <span class="required">*</span></label>
						<div class="col-md-6">
							<input name="smtp_pass" required value="<?php echo html_escape($config['smtp_pass']); ?>" class="form-control smtp" type="password">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">SMTP Port <span class="required">*</span></label>
						<div class="col-md-6">
							<input required class="form-control smtp" value="<?php echo html_escape($config['smtp_port']); ?>" name="smtp_port" type="text">
						</div>
					</div>
					<div class="form-group m">
						<label class="col-md-3 control-label">Email Encryption</label>
						<div class="col-md-6 mb-md">
							<select name="smtp_encryption" class="form-control smtp" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
								<option value="">No</option>
								<option value="tls" <?php if(set_value('smtp_encryption', $config['smtp_encryption']) == 'tls') echo 'selected'; ?>>TLS</option>
								<option value="ssl" <?php if(set_value('smtp_encryption', $config['smtp_encryption']) == 'ssl') echo 'selected'; ?>>SSL</option>
							</select>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-2 col-sm-offset-3">
								<button type="submit" class="btn btn btn-default btn-block" name="save" value="1"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
<?php if($config['email_protocol'] != 'smtp'): ?>
	$(document).ready(function () {
		$(".smtp").prop('disabled', true);
	});
<?php endif; ?>
</script>