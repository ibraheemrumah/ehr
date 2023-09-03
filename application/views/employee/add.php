<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<?php echo form_open_multipart($this->uri->uri_string()); ?>
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="far fa-user-circle"></i> <?php echo translate('add_employee'); ?>
					</h4>
				</div>
				<div class="panel-body">
					<!-- Basic Details -->
					<div class="headers-line mt-md">
						<i class="fas fa-user-check"></i> <?php echo translate('basic_details'); ?>
					</div>
					<div class="row">
						<div class="col-md-6 mb-sm">
							<div class="form-group <?php if (form_error('name')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="far fa-user"></i></span>
									<input class="form-control" name="name" type="text" value="<?php echo set_value('name'); ?>">
								</div>
								<span class="error"><?php echo form_error('name'); ?></span>
							</div>
						</div>
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('gender'); ?></label>
								<?php
									$gender_array = array(
										"" => translate('select'),
										"male" => translate('male'),
										"female" => translate('female')
									);
									echo form_dropdown("gender", $gender_array, set_value('gender') , "class='form-control' data-plugin-selectTwo data-width='100%'
									data-minimum-results-for-search='Infinity'");
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('religion'); ?></label>
								<input type="text" class="form-control" name="religion" value="<?php echo set_value('religion'); ?>">
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('blood_group'); ?></label>
								<?php
									$bloodarray = $this->app_lib->get_blood_group();
									echo form_dropdown("blood_group", $bloodarray, set_value("blood_group"), "class='form-control' data-plugin-selectTwo
									data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('birthday'); ?> </label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
									<input type="text" class="form-control" name="birthday" value="<?php echo set_value('birthday'); ?>" data-plugin-datepicker data-plugin-options='{ "startView": 2 }'>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 mb-sm">
							<div class="form-group <?php if (form_error('mobile_no')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('mobile_no'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-phone-volume"></i></span>
									<input class="form-control" name="mobile_no" type="text" value="<?php echo set_value('mobile_no'); ?>">
								</div>
								<span class="error"><?php echo form_error('mobile_no'); ?></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group <?php if (form_error('email')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('email'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="far fa-envelope-open"></i></span>
									<input type="text" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" />
								</div>
								<span class="error"><?php echo form_error('email'); ?></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('marital_status'); ?></label>
								<?php
									$marital_array = array(
										'' => translate('select'),
										'1' => translate('single'),
										'2' => translate('married')
									);
									echo form_dropdown("marital_status", $marital_array, set_value("marital_status"), "class='form-control' data-plugin-selectTwo
									data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('address'); ?></label>
								<textarea class="form-control" rows="3" name="address" placeholder="<?php echo translate('address'); ?>" ><?php echo set_value('address'); ?></textarea>
							</div>
						</div>
					</div>
					<div class="row mb-md">
						<div class="col-md-12">
							<div class="form-group">
								<label for="input-file-now"><?php echo translate('profile_picture'); ?></label>
								<input type="file" name="user_photo" class="dropify" data-allowed-file-extensions="jpg png" data-height="120" />
							</div>
						</div>
					</div>

					<!-- Login Details -->
					<div class="headers-line">
						<i class="fas fa-user-lock"></i> <?php echo translate('login_details'); ?>
					</div>
					<div class="row mb-lg">
						<div class="col-md-6 mb-sm">
							<div class="form-group <?php if (form_error('username')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('username'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-user-lock"></i></span>
									<input type="text" class="form-control" name="username" id="username" value="<?php echo set_value('username'); ?>" />
								</div>
								<span class="error"><?php echo form_error('username'); ?></span>
							</div>
						</div>
						<div class="col-md-3 mb-sm">
							<div class="form-group <?php if (form_error('password')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('password'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-unlock-alt"></i></span>
									<input type="password" class="form-control" name="password" value="<?php echo set_value('password'); ?>" />
								</div>
								<span class="error"><?php echo form_error('password'); ?></span>
							</div>
						</div>
						<div class="col-md-3 mb-sm">
							<div class="form-group <?php if (form_error('retype_password')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('retype_password'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-unlock-alt"></i></span>
									<input type="password" class="form-control" name="retype_password"  value="<?php echo set_value('retype_password'); ?>" />
								</div>
								<span class="error"><?php echo form_error('retype_password'); ?></span>
							</div>
						</div>
					</div>

					<!-- Office Details -->
					<div class="headers-line">
						<i class="fas fa-school"></i> <?php echo translate('office_details'); ?>
					</div>
					<div class="row">
						<div class="col-md-4 mb-sm">
							<div class="form-group <?php if (form_error('user_role')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('role'); ?> <span class="required">*</span></label>
								<?php
									$role_list = $this->app_lib->getRoles();
									echo form_dropdown("user_role", $role_list, set_value('user_role'), "class='form-control'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
								<span class="error"><?php echo form_error('user_role'); ?></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group <?php if (form_error('designation_id')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('designation'); ?> <span class="required">*</span></label>
								<?php
									echo form_dropdown("designation_id", $designationlist, set_value('designation_id') , "class='form-control' 
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"><?php echo form_error('designation_id'); ?></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group <?php if (form_error('department_id')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('department'); ?> <span class="required">*</span></label>
								<?php
									echo form_dropdown("department_id", $departmentlist, set_value('department_id') , "class='form-control'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"><?php echo form_error('department_id'); ?></span>
							</div>
						</div>
					</div>
					<div class="row mb-lg">
						<div class="col-md-6 mb-sm">
							<div class="form-group <?php if (form_error('joining_date')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('joining_date'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="far fa-calendar-plus"></i></span>
									<input type="text" class="form-control" name="joining_date" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' 
									value="<?php echo set_value('joining_date'); ?>">
								</div>
								<span class="error"><?php echo form_error('joining_date'); ?></span>
							</div>
						</div>
						<div class="col-md-6 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('qualification'); ?></label>
								<input type="text" class="form-control" name="qualification"  value="<?php echo set_value('qualification'); ?>">
							</div>
						</div>
					</div>

					<!-- Social Links -->
					<div class="headers-line">
						<i class="fas fa-globe"></i> <?php echo translate('social_links'); ?>
					</div>

					<div class="row mb-lg">
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label">Facebook</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fab fa-facebook-f"></i></span>
									<input type="url" class="form-control" name="facebook" value="<?php echo set_value('facebook'); ?>" />
								</div>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label">Twitter</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fab fa-twitter"></i></span>
									<input type="url" class="form-control" name="twitter" value="<?php echo set_value('twitter'); ?>"  />
								</div>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label">Linkedin</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fab fa-linkedin-in"></i></span>
									<input type="url" class="form-control" name="linkedin" value="<?php echo set_value('linkedin'); ?>" />
								</div>
							</div>
						</div>
					</div>

					<!-- Bank Details -->
					<div class="headers-line">
						<i class="fas fa-university"></i> <?php echo translate('bank_details'); ?>
					</div>
					<div class="mb-sm checkbox-replace">
						<label class="i-checks"><input type="checkbox" name="cbbank_skip" id="cbbank_skip" value="true" <?php if(isset($cbbank_skip)) echo 'checked'; ?>>
							<i></i> <?php echo translate('skipped_bank_details'); ?>
						</label>
					</div>
					<div id="bank_details_form" <?php if(isset($cbbank_skip)) echo 'class="hidden-item"'; ?>>
						<div class="row">
							<div class="col-md-4 mb-sm">
								<div class="form-group <?php if (form_error('bank_name')) echo 'has-error'; ?>">
									<label class="control-label"><?php echo translate('bank_name') . " " . translate('name'); ?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="bank_name" value="<?php echo set_value('bank_name'); ?>" />
									<span class="error"><?php echo form_error('bank_name'); ?></span>
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group <?php if (form_error('holder_name')) echo 'has-error'; ?>">
									<label class="control-label"><?php echo translate('holder_name'); ?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="holder_name" value="<?php echo set_value('holder_name'); ?>" />
									<span class="error"><?php echo form_error('holder_name'); ?></span>
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group <?php if (form_error('bank_branch')) echo 'has-error'; ?>">
									<label class="control-label"><?php echo translate('bank') . ' ' . translate('branch'); ?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="bank_branch" value="<?php echo set_value('bank_branch'); ?>" />
									<span class="error"><?php echo form_error('bank_branch'); ?></span>
								</div>
							</div>
						</div>
						<div class="row mb-lg">
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?php echo translate('bank') . " " . translate('address'); ?></label>
									<input type="text" class="form-control" name="bank_address" value="<?php echo set_value('bank_address'); ?>" />
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group">
									<label class="control-label"><?php echo translate('ifsc_code'); ?></label>
									<input type="text" class="form-control" name="ifsc_code" value="<?php echo set_value('ifsc_code'); ?>" />
								</div>
							</div>
							<div class="col-md-4 mb-sm">
								<div class="form-group <?php if (form_error('account_no')) echo 'has-error'; ?>">
									<label class="control-label"><?php echo translate('account_no'); ?> <span class="required">*</span></label>
									<input type="text" class="form-control" name="account_no" value="<?php echo set_value('account_no'); ?>" />
									<span class="error"><?php echo form_error('account_no'); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-10 col-md-2">
							<button type="submit" name="submit" value="save" class="btn btn btn-default btn-block"> <i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>