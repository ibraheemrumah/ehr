<?php $currency_symbol = $global_config['currency_symbol']; ?>
<div class="row">
    <div class="col-md-12 mb-lg">
        <div class="profile-head social">
            <div class="col-md-12 col-lg-4 col-xl-3">
                <div class="image-content-center user-pro">
                    <div class="preview">
                        <ul class="social-icon-one">
                            <li><a href="<?php echo (empty($staff['facebook_url']) ? '#' : $staff['facebook_url']); ?>"><span class="fab fa-facebook-f"></span></a></li>
                            <li><a href="<?php echo (empty($staff['twitter_url']) ? '#' : $staff['twitter_url']); ?>"><span class="fab fa-twitter"></span></a></li>
                            <li><a href="<?php echo (empty($staff['linkedin_url']) ? '#' : $staff['linkedin_url']); ?>"><span class="fab fa-linkedin-in"></span></a></li>
                        </ul>
                        <img src="<?php echo $this->app_lib->get_image_url('staff/' . $staff['photo']); ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-5 col-xl-5">
                <h5><?php echo html_escape($staff['name']); ?></h5>
                <p><?php echo ucfirst($staff['role']); ?> / <?php echo html_escape($staff['designation_name']); ?></p>
                <ul>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('department'); ?>"><i class="fas fa-user-tie"></i></div> <?php echo html_escape(!empty($staff['department_name']) ? $staff['department_name'] : 'N/A'); ?></li>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('birthday'); ?>"><i class="fas fa-birthday-cake"></i></div> <?php echo html_escape(_d($staff['birthday'])); ?></li>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('joining_date'); ?>"><i class="far fa-calendar-alt"></i></div> <?php echo html_escape(_d($staff['joining_date'])); ?></li>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('mobile_no'); ?>"><i class="fas fa-phone"></i></div> <?php echo html_escape(!empty($staff['mobileno']) ? $staff['mobileno'] : 'N/A'); ?></li>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('email'); ?>"><i class="far fa-envelope"></i></div> <?php echo html_escape($staff['email']); ?></li>
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('address'); ?>"><i class="fas fa-home"></i></div> <?php echo html_escape(!empty($staff['address']) ? $staff['address'] : 'N/A'); ?></li>
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
                            <!-- Employee Details -->
                            <div class="headers-line mt-md"><i class="fas fa-user-check"></i> <?php echo translate('basic_details'); ?></div>

                            <div class="row">
                                <div class="col-md-6 mb-sm">
                                    <div class="form-group <?php if (form_error('name')) echo 'has-error'; ?>">
                                        <label class="control-label"><?php echo translate('name'); ?> <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="far fa-user"></i></span>
                                            <input type="text" class="form-control" name="name" value="<?php echo set_value('name', $staff['name']); ?>" />
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
                                                "female" => translate('female'));
                                            echo form_dropdown("gender", $gender_array, set_value('gender', $staff['gender']), "class='form-control' data-plugin-selectTwo data-width='100%'
											data-minimum-results-for-search='Infinity'");
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('religion'); ?></label>
                                        <input type="text" class="form-control" name="religion" value="<?php echo set_value('religion', $staff['religion']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('blood_group'); ?></label>
                                        <?php
                                            $bloodArray = $this->app_lib->get_blood_group();
                                            echo form_dropdown("blood_group", $bloodArray, set_value('blood_group', $staff['blood_group']), "class='form-control'
											data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('birthday'); ?></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
                                            <input type="text" class="form-control" name="birthday" value="<?php echo set_value('birthday', $staff['birthday']); ?>" data-plugin-datepicker data-plugin-options='{ "startView": 2 }'>
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
                                            <input class="form-control" name="mobile_no" type="text" value="<?php echo set_value('mobile_no', $staff['mobileno']); ?>">
                                        </div>
										<span class="error"><?php echo form_error('mobile_no'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                   <div class="form-group <?php if (form_error('email')) echo 'has-error'; ?>">
                                        <label class="control-label"><?php echo translate('email'); ?> <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="far fa-envelope-open"></i></span>
                                            <input type="text" class="form-control" name="email" id="email" value="<?php echo set_value('email', $staff['email']); ?>" />
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
                                            echo form_dropdown("marital_status", $marital_array, set_value("marital_status", $staff['marital_status']), "class='form-control'
											data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('address'); ?></label>
                                        <textarea class="form-control" rows="2" name="address" placeholder="<?php echo translate('address'); ?>" ><?php echo set_value('address', $staff['address']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-md">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="input-file-now"><?php echo translate('profile_picture'); ?></label>
                                        <input type="file" name="user_photo" class="dropify" data-allowed-file-extensions="jpg png" data-height="120" data-default-file="<?php echo $this->app_lib->get_image_url('staff/' . $staff['photo']); ?>" />
                                    </div>
                                </div>
                                <input type="hidden" name="old_user_photo" value="<?php echo html_escape($staff['photo']); ?>" />
                            </div>

                            <!-- Login Details -->
                            <div class="headers-line"><i class="fas fa-user-lock"></i> <?php echo translate('login_details'); ?></div>

                            <div class="row mb-lg">
                                <div class="col-md-12 mb-sm">
                                    <div class="form-group <?php if (form_error('username')) echo 'has-error'; ?>">
                                        <label class="control-label"><?php echo translate('username'); ?> <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fas fa-user-lock"></i></span>
                                            <input type="text" class="form-control" name="username" id="username" value="<?php echo set_value('username', $staff['username']); ?>" />
                                        </div>
										<span class="error"><?php echo form_error('username'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Office Details -->
                            <div class="headers-line">
                                <i class="fas fa-school"></i> <?php echo translate('office_details'); ?>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('role'); ?> <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="role" value="<?php echo html_escape($staff['role']); ?>" disabled />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('designation'); ?> <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="designation" value="<?php echo html_escape($staff['designation_name']); ?>" disabled />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('department'); ?> <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="department" value="<?php echo html_escape($staff['department_name']); ?>" disabled />
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-lg">
                                <div class="col-md-6 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('joining_date'); ?> <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="far fa-calendar-plus"></i></span>
                                            <input type="text" class="form-control" name="joining_date" disabled value="<?php echo set_value('joining_date', html_escape($staff['joining_date'])); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label"><?php echo translate('qualification'); ?></label>
                                        <input type="text" class="form-control" name="qualification" disabled value="<?php echo html_escape($staff['qualification']); ?>" />
                                    </div>
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div class="headers-line"><i class="fas fa-globe"></i> <?php echo translate('social_links'); ?></div>

                            <div class="row mb-md">
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label">Facebook</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fab fa-facebook-f"></i></span>
                                            <input type="text" class="form-control" name="facebook" value="<?php echo set_value('facebook', $staff['facebook_url']); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label">Twitter</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fab fa-twitter"></i></span>
                                            <input type="text" class="form-control" name="twitter" value="<?php echo set_value('twitter', $staff['twitter_url']); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-sm">
                                    <div class="form-group">
                                        <label class="control-label">Linkedin</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fab fa-linkedin-in"></i></span>
                                            <input type="text" class="form-control" name="linkedin" value="<?php echo set_value('linkedin', $staff['linkedin_url']); ?>" />
                                        </div>
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