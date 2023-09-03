<div class="row">
	<div class="col-md-12">
		<section class="panel">
            <?php echo form_open_multipart($this->uri->uri_string()); ?>
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fas fa-wheelchair"></i> <?php echo translate('create') . " " . translate('patient'); ?></h4>
				</div>
				<div class="panel-body"> 
					<!-- basic details -->
					<div class="headers-line mt-md">
						<i class="fas fa-user-check"></i> <?php echo translate('basic') . " " . translate('details'); ?>
					</div>
					<div class="row">
						<div class="col-md-6 mb-sm">
							<div class="form-group <?php if (form_error('name')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('surname'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="far fa-user"></i></span>
									<input class="form-control" name="sname" type="text" value="<?php echo set_value('name'); ?>">
								</div>
								<span class="error"><?php echo form_error('name'); ?></span>
							</div>
						</div>
						<div class="col-md-6 mb-sm">
							<div class="form-group <?php if (form_error('name')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('First name'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="far fa-user"></i></span>
									<input class="form-control" name="fname" type="text" value="<?php echo set_value('name'); ?>">
								</div>
								<span class="error"><?php echo form_error('name'); ?></span>
							</div>
						</div>
						</div>
						<div class="row">
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('gender'); ?></label>
								<?php
									$array = array(
										"" => translate('select'),
										"male" => translate('male'),
										"female" => translate('female')
									);
									echo form_dropdown("gender", $array, set_value('gender'), "class='form-control' data-plugin-selectTwo data-width='100%'
									data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"><?php echo form_error('gender'); ?></span>
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('birthday'); ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
									<input class="form-control" name="birthday" id="patient_birthday" value="<?php echo set_value('birthday'); ?>" data-plugin-datepicker
									data-plugin-options='{ "startView": 2 }' type="text">
								</div>
							</div>
						</div>

						<div class="col-md-4 mb-sm">
							<div class="form-group <?php if (form_error('age')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('age'); ?> <span class="required">*</span></label>
								<input type="text" class="form-control" name="age" id="age" value="<?php echo set_value('age'); ?>">
								<span class="error"><?php echo form_error('age'); ?></span>
							</div>
						</div>
					</div>

					<div class="row">
						
						
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('marital_status'); ?></label>
								<?php
									$maritalArray = array(
										''  => translate('select'),
										'1' => translate('single'),
										'2' => translate('married')
									);
									echo form_dropdown("marital_status", $maritalArray, set_value("marital_status"), "class='form-control' data-plugin-selectTwo
									data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
							</div>
						</div>
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
									<input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" />
								</div>
								<span class="error"><?php echo form_error('email'); ?></span>
							</div>
						</div>
					</div>

					<div class="row">
						
						
					</div>

					<div class="row">
						<div class="col-md-4 mb-md">
							<div class="form-group <?php if (form_error('category_id')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('category'); ?> <span class="required">*</span></label>
								<?php
									echo form_dropdown("category_id", $categorylist, set_value('category_id') , "class='form-control' id='category_id' 
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"><?php echo form_error('category_id'); ?></span>
							</div>
						</div>
						<div class="col-md-2 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('blood_group'); ?></label>
								<?php
									$bloodlist = $this->app_lib->get_blood_group();
									echo form_dropdown("blood_group", $bloodlist, set_value("blood_group"), "class='form-control' data-plugin-selectTwo
									data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
							</div>
						</div>
						<div class="col-md-2 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('blood_pressure'); ?></label>
								<input type="text" class="form-control" name="blood_pressure" value="<?php echo set_value('blood_pressure'); ?>">
							</div>
						</div>
						<div class="col-md-2 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('height'); ?></label>
								<input type="text" class="form-control" name="height" value="<?php echo set_value('height'); ?>">
							</div>
						</div>
						<div class="col-md-2 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('weight'); ?></label>
								<input type="text" class="form-control" name="weight" value="<?php echo set_value('weight'); ?>">
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

					<!-- emergency contact -->
					<div class="headers-line">
						<i class="fas fa-pencil-ruler"></i> <?php echo translate('emergency_contact'); ?>
					</div>

					<div class="row">
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('guardian'); ?></label>
								<input class="form-control" name="guardian" type="text" value="<?php echo set_value('guardian'); ?>">
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group>">
								<label class="control-label"><?php echo translate('relationship'); ?></label>
								<input class="form-control" name="relationship" type="text" value="<?php echo set_value('relationship'); ?>">
							</div>
						</div>
						<div class="col-md-4 mb-sm">
							<div class="form-group">
								<label class="control-label"><?php echo translate('mobile_no'); ?></label>
								<input class="form-control" name="gua_mobileno" type="text" value="<?php echo set_value('gua_mobileno'); ?>">
							</div>
						</div>

					</div>

					
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-offset-10 col-md-2">
							<button type="submit" name="save" value="1" class="btn btn btn-default btn-block"><i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>