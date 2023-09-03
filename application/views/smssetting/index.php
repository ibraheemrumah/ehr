<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#smsConfig" data-toggle="tab" ><i class="far fa-envelope"></i> <?php echo translate('sms_config')?></a>
			</li>
			<li >
				<a href="<?php echo base_url('smssettings/template'); ?>"><i class="fas fa-sitemap"></i> <?php echo translate('sms_template')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="smsConfig">
				<div class="panel-group" id="accordion">
					<div class="panel panel-accordion">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#sms_service"><?php echo translate('select_a_sms_service'); ?></a>
							</h4>
						</div>
						<div id="sms_service" class="accordion-body collapse <?php echo ($this->session->flashdata('active_box') == 1 ? 'in' : ''); ?>">
							<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal validate')); ?>
								<div class="panel-body">
									<div class="form-group mt-md">
									  <label class="col-md-3 control-label"><?php echo translate('select_a_sms_service'); ?> <span class="required">*</span></label>
										<div class="col-md-6 mb-md">
											<?php
												$array = array(
													"disabled" => translate('disabled'),
													"clickatell" => "Clickatell",
													"twilio" => "Twilio",
												);
												echo form_dropdown("sms_gateway", $array, $sms_api->active_gateway, "class='form-control' data-plugin-selectTwo data-width='100%'
												data-minimum-results-for-search='Infinity'");
											?>
										</div>
									</div>
								</div>
								<div class="panel-footer">
								    <div class="row">
								        <div class="col-md-offset-3 col-md-2">
								            <button type="submit" name="save" value="gateway" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
								        </div>
								    </div>
								</div>
							<?php echo form_close(); ?>
						</div>
					</div>
					<div class="panel panel-accordion">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#clickatellGateway">Clickatell Gateway</a>
							</h4>
						</div>
						<div id="clickatellGateway" class="accordion-body collapse <?php echo ($this->session->flashdata('active_box') == 2 ? 'in' : ''); ?>">
							<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal validate')); ?>
								<div class="panel-body">
									<div class="form-group mt-md">
									  <label class="col-md-3 control-label"><?php echo translate('clickatell_username'); ?> <span class="required">*</span></label>
										<div class="col-md-6">
											<input type="text" required class="form-control" name="clickatell_username" value="<?php echo $sms_api->clickatell_username; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"><?php echo translate('clickatell_password'); ?> <span class="required">*</span></label>
										<div class="col-md-6">
											<input type="text" required class="form-control" name="clickatell_password" value="<?php echo $sms_api->clickatell_password; ?>">
										</div>
									</div>
									<div class="form-group">
									  <label class="col-md-3 control-label"><?php echo translate('clickatell_api_key'); ?> <span class="required">*</span></label>
										<div class="col-md-6">
											<input type="text" required class="form-control" name="clickatell_api_key" value="<?php echo $sms_api->clickatell_api_key; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"><?php echo translate('registered_number'); ?> <span class="required">*</span></label>
										<div class="col-md-6 mb-md">
											<input type="text" required class="form-control" name="registered_number" value="<?php echo $sms_api->clickatell_number; ?>">
										</div>
									</div>
								</div>
								<div class="panel-footer">
								    <div class="row">
								        <div class="col-md-offset-3 col-md-2">
								            <button type="submit" name="save" value="clickatell" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
								        </div>
								    </div>
								</div>
							<?php echo form_close(); ?>
						</div>
					</div>
					<div class="panel panel-accordion">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#twilioGateway">Twilio Gateway</a>
							</h4>
						</div>
						<div id="twilioGateway" class="accordion-body collapse <?php echo ($this->session->flashdata('active_box') == 3 ? 'in' : ''); ?>">
							<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal validate')); ?>
								<div class="panel-body">
									<div class="form-group mt-md">
									  <label class="col-md-3 control-label"><?php echo translate('account_sid'); ?> <span class="required">*</span></label>
										<div class="col-md-6">
											<input type="text" required class="form-control" name="account_sid" value="<?php echo $sms_api->twilio_account_sid; ?>">
										</div>
									</div>
									<div class="form-group">
									  <label class="col-md-3 control-label"><?php echo translate('auth_token'); ?> <span class="required">*</span></label>
										<div class="col-md-6">
											<input type="text" required class="form-control" name="auth_token" value="<?php echo $sms_api->twilio_auth_token; ?>">
										</div>
									</div>
									<div class="form-group">
									  <label class="col-md-3 control-label"><?php echo translate('registered_number'); ?> <span class="required">*</span></label>
										<div class="col-md-6 mb-md">
											<input type="text" required class="form-control" name="registered_number" value="<?php echo $sms_api->twilio_number; ?>">
										</div>
									</div>
								</div>
								<div class="panel-footer">
								    <div class="row">
								        <div class="col-md-offset-3 col-md-2">
								            <button type="submit" name="save" value="twilio" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
								        </div>
								    </div>
								</div>
							<?php echo form_close(); ?>
						</div>
					</div>				
				</div>
			</div>
		</div>
	</div>
</section>