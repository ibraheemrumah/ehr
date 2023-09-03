<div class="row">
	<div class="col-md-12 mb-lg">
		<div class="profile-head">
			<div class="col-md-12 col-lg-4 col-xl-3">
				<div class="image-content-center user-pro">
					<div class="preview">
						<img src="<?php echo $this->app_lib->get_image_url('patient/' . $patient['photo'], TRUE); ?>">
					</div>
				</div>
			</div>
			<div class="col-md-12 col-lg-5 col-xl-5">
				<h5><?php echo html_escape($patient['name']); ?></h5>
				<p><?php echo "Patient / " . html_escape($patient['category_name']); ?></p>
				<ul>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('guardian'); ?>"><i class="fas fa-user-friends"></i></div> <?php echo html_escape(!empty($patient['guardian']) ? $patient['guardian'] : 'N/A'); ?></li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('birthday'); ?>"><i class="fas fa-birthday-cake"></i></div> <?php echo _d($patient['birthday']); ?></li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('mobile_no'); ?>"><i class="fas fa-phone"></i></div> <?php echo html_escape(!empty($patient['mobileno']) ? $patient['mobileno'] : 'N/A'); ?></li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('email'); ?>"><i class="far fa-envelope"></i></div> <?php echo html_escape($patient['email']); ?></li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('address'); ?>"><i class="fas fa-home"></i></div> <?php echo html_escape(!empty($patient['address']) ? $patient['address'] : 'N/A'); ?></li>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="col-md-12">
		<div class="panel-group" id="accordion">
			<div class="panel panel-accordion">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#profile">
							<i class="fas fa-user-edit"></i> <?php echo translate('profile'); ?>
						</a>
					</h4>
				</div>
				<div id="profile" class="accordion-body collapse in">
                    <?php echo form_open_multipart($this->uri->uri_string()); ?>
                        <div class="panel-body">
                            <!-- Basic Details -->
                            <div class="headers-line mt-md">
                                <i class="fas fa-user-check"></i> <?php echo translate('basic') . " " . translate('details'); ?>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-sm">
                                    <div class="form-group <?php if (form_error('name')) echo 'has-error'; ?>">
                                        <label class="control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="far fa-user"></i></span>
                                            <input class="form-control" name="name" type="text" value="<?php echo set_value('name', $patient['name']); ?>" />
                                        </div>
										<span class="error"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('gender'); ?></label>
										<select name="gender" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
											<option value=""><?php echo translate('select'); ?></option>
											<option value="male" <?php if(set_value('gender', $patient['sex']) == 'male') echo 'selected'; ?>><?php echo translate('male'); ?></option>
											<option value="female" <?php if(set_value('gender', $patient['sex']) == 'female') echo 'selected'; ?>><?php echo translate('female'); ?></option>
										</select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('birthday'); ?> <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
                                            <input class="form-control" name="birthday" value="<?php echo set_value('birthday', $patient['birthday']); ?>" data-plugin-datepicker data-plugin-options='{ "startView": 2 }' type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('age'); ?> <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="age" value="<?php echo html_escape($patient['age']); ?>" disabled />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('marital_status'); ?></label>
										<select name="marital_status" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
											<option value=""><?php echo translate('select'); ?></option>
											<option value="1" <?php if(set_value('marital_status', $patient['marital_status']) == '1') echo 'selected'; ?>><?php echo translate('single'); ?></option>
											<option value="2" <?php if(set_value('marital_status', $patient['marital_status']) == '2') echo 'selected'; ?>><?php echo translate('married'); ?></option>
										</select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-sm">
                                    <div class="form-group <?php if (form_error('mobile_no')) echo 'has-error'; ?>">
                                        <label class="control-label"><?php echo translate('mobile_no'); ?> <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fas fa-phone-volume"></i></span>
                                            <input class="form-control" name="mobile_no" type="text" value="<?php echo set_value('mobile_no', $patient['mobileno']); ?>" />
                                        </div>
										<span class="error"><?php echo form_error('mobile_no'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-sm">
                                    <div class="form-group <?php if (form_error('email')) echo 'has-error'; ?>">
                                        <label class="control-label"><?php echo translate('email'); ?> <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="far fa-envelope-open"></i></span>
                                            <input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email', $patient['email']); ?>" />
                                        </div>
										<span class="error"><?php echo form_error('email'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-md">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('category'); ?> <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="category_id" value="<?php echo html_escape($patient['category_name']); ?>" disabled />
                                    </div>
                                </div>
                                <div class="col-md-2 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('blood_group'); ?></label>
                                        <input type="text" class="form-control" name="blood_group" value="<?php echo html_escape($patient['blood_group']); ?>" disabled />
                                    </div>
                                </div>
                                <div class="col-md-2 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('blood_pressure'); ?></label>
                                        <input type="text" class="form-control" name="blood_pressure" value="<?php echo html_escape($patient['blood_pressure']); ?>" disabled />
                                    </div>
                                </div>
                                <div class="col-md-2 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('height'); ?></label>
                                        <input type="text" class="form-control" name="height" value="<?php echo html_escape($patient['height']); ?>" disabled />
                                    </div>
                                </div>
                                <div class="col-md-2 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('weight'); ?></label>
                                        <input type="text" class="form-control" name="weight" value="<?php echo html_escape($patient['weight']); ?>" disabled />
                                    </div>
                                </div>
                            </div>
                    
                            <div class="row">
                                <div class="col-md-12 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('address'); ?></label>
                                        <textarea class="form-control" rows="3" name="address" placeholder="<?php echo translate('address'); ?>" ><?php echo set_value('address', $patient['address']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-md">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="input-file-now"><?php echo translate('profile_picture'); ?></label>
                                        <input type="file" name="user_photo" class="dropify" data-allowed-file-extensions="jpg png" data-height="120" data-default-file="<?php echo $this->app_lib->get_image_url('patient/' . $patient['photo']); ?>" />
                                    </div>
                                     <input type="hidden" name="old_user_photo" value="<?php echo html_escape($patient['photo']); ?>" />
                                </div>
                            </div>

                            <!-- Emergency Contact -->
                            <div class="headers-line">
                                <i class="fas fa-pencil-ruler"></i> <?php echo translate('emergency_contact'); ?>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('guardian'); ?></label>
                                        <input class="form-control" name="guardian" type="text" value="<?php echo set_value('guardian', $patient['guardian']); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group>">
                                        <label class="control-label"><?php echo translate('relationship'); ?></label>
                                        <input class="form-control" name="relationship" type="text" value="<?php echo set_value('relationship', $patient['relationship']); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('mobile_no'); ?></label>
                                        <input class="form-control" name="gua_mobileno" type="text" value="<?php echo set_value('gua_mobileno', $patient['gua_mobileno']); ?>" />
                                    </div>
                                </div>

                            </div>

                            <!-- login details -->
                            <div class="headers-line">
                                <i class="fas fa-user-lock"></i> <?php echo translate('login_details'); ?>
                            </div>

                            <div class="row mb-lg">
                                <div class="col-md-12">
                                    <div class="form-group <?php if (form_error('username')) echo 'has-error'; ?>">
                                        <label class="control-label"><?php echo translate('username'); ?> <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fas fa-user-lock"></i></span>
                                            <input type="username" class="form-control" name="username" id="username" value="<?php echo set_value('username', $patient['username']); ?>" />
                                        </div>
										<span class="error"><?php echo form_error('username'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
    					<div class="panel-footer">
    						<div class="row">
    							<div class="col-md-offset-9 col-md-3">
    								<button type="submit" name="update" value="1" class="btn btn-default btn-block"><?php echo translate('update'); ?></button>
    							</div>
    						</div>
    					</div>
				    <?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Login Authentication And Account Inactive Modal -->
<div id="authentication_modal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-unlock-alt"></i> <?php echo translate('authentication'); ?></h4>
        </header>
        <div class="panel-body">
            <div class="form-group">
            <label for="password" class="control-label"><?php echo translate('password'); ?> <span class="required">*</span></label>
            <div class="input-group">
                <input type="password" class="form-control password" name="password" id="reset_password" />
                <span class="input-group-addon">
                    <a href="javascript:void(0);" id="show_password" ><i class="fa fa-eye"></i></a>
                </span>
             </div>
            <span class="control-label" id="password-msg"></span>
            </div>
            <div class="form-group mb-md">
                <div class="checkbox-replace">
                    <label class="i-checks">
                        <input type="checkbox" name="authentication" id="cb_authentication">
                        <i></i> <?php echo translate('login_authentication_deactivate'); ?>
                    </label>
                </div>
            </div>
        </div>
        <footer class="panel-footer">
            <div class="text-right">
                <button class="btn btn-default mr-xs" id="pass_update" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><?php echo translate('update'); ?></button>
                <button class="btn btn-default modal-dismiss"><?php echo translate('close'); ?></button>
            </div>
        </footer>
    </section>
</div>