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
                        <div class="auth-pan">
                            <button class="btn btn-default btn-circle" id="authentication_btn">
                                <i class="fas fa-unlock-alt"></i> <?php echo translate('authentication'); ?>
                            </button>
                        </div>
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#profile">
                            <i class="fas fa-user-edit"></i> <?php echo translate('profile'); ?>
                        </a>
                    </h4>
                </div>
                <div id="profile" class="accordion-body collapse <?php echo ($this->session->flashdata('profile_tab') ? 'in' : ''); ?>">
                    <?php echo form_open_multipart($this->uri->uri_string()); ?>
                        <div class="panel-body">
                            <fieldset>
                                <input type="hidden" name="staff_id" id="staff_id" value="<?php echo html_escape($staff['id']); ?>">
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
                                                echo form_dropdown("blood_group", $bloodArray, set_value('blood_group', $staff['blood_group']), "class='form-control' data-plugin-selectTwo
                                                data-width='100%' data-minimum-results-for-search='Infinity' ");
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
                                            <input type="file" name="user_photo" class="dropify" data-allowed-file-extensions="jpg png" data-default-file="<?php echo $this->app_lib->get_image_url('staff/' . $staff['photo']); ?>" />
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
                                <div class="headers-line"><i class="fas fa-school"></i> <?php echo translate('office_details'); ?></div>

                                <div class="row">
                                    <div class="col-md-4 mb-sm">
                                        <div class="form-group <?php if (form_error('user_role')) echo 'has-error'; ?>">
                                            <label class="control-label"><?php echo translate('role'); ?> <span class="required">*</span></label>
                                            <?php
                                                $role_array = $this->app_lib->getRoles();
                                                echo form_dropdown("user_role", $role_array, set_value('user_role', $staff['role_id']), "class='form-control' data-plugin-selectTwo data-width='100%'
                                                data-minimum-results-for-search='Infinity' ");
                                            ?>
											<span class="error"><?php echo form_error('user_role'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-sm">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo translate('designation'); ?> <span class="required">*</span></label>
                                            <?php
                                                echo form_dropdown("designation_id", $designationlist, set_value('designation_id', $staff['designation']), "class='form-control' id='designation_holder'
                                                data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                                            ?>
											<span class="error"><?php echo form_error('designation_id'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-sm">
                                        <div class="form-group <?php if (form_error('department_id')) echo 'has-error'; ?>">
                                            <label class="control-label"><?php echo translate('department'); ?> <span class="required">*</span></label>
                                            <?php
                                                echo form_dropdown("department_id", $departmentlist, set_value('department_id', $staff['department']), "class='form-control' id='department_holder' 
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
												value="<?php echo set_value('joining_date', $staff['joining_date']); ?>" />
                                            </div>
											<span class="error"><?php echo form_error('joining_date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-sm">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo translate('qualification'); ?></label>
                                            <input type="text" class="form-control" name="qualification" value="<?php echo set_value('qualification', $staff['qualification']); ?>" />
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
                            </fieldset>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-offset-9 col-md-3">
                                    <button type="submit" name="submit" value="update" class="btn btn-default btn-block"><?php echo translate('update'); ?></button>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        
            <div class="panel panel-accordion">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#salary_transaction">
                            <i class="far fa-address-card"></i> <?php echo translate('salary') . " " . translate('transaction'); ?>
                        </a>
                    </h4>
                </div>
                <div id="salary_transaction" class="accordion-body collapse">
                    <div class="panel-body">
                        <div class="table-responsive mb-sm mt-xs">
                            <table class="table table-bordered table-hover table-condensed mb-md mt-sm">
                                <thead>
                                    <tr>
                                        <th><?php echo translate('sl'); ?></th>
                                        <th><?php echo translate('month_of_salary'); ?></th>
                                        <th><?php echo translate('basic_salary'); ?></th>
                                        <th><?php echo translate('allowances'); ?> (+)</th>
                                        <th><?php echo translate('deductions'); ?> (-)</th>
                                        <th><?php echo translate('paid_amount'); ?></th>
                                        <th><?php echo translate('payment_type'); ?></th>
                                        <th><?php echo translate('created_at'); ?></th>
                                        <th class="hidden-print"><?php echo translate('payslip'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    $salary_query = $this->db->get_where("payslip", array('staff_id' => $staff['id']));
                                    if ($salary_query->num_rows() > 0) {
                                        $payments = $salary_query->result();
                                        foreach ($payments as $row):
                                    ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo html_escape($this->app_lib->get_months_list($row->month) . " / " . $row->year); ?></td>
                                        <td><?php echo html_escape($currency_symbol . $row->basic_salary); ?></td>
                                        <td><?php echo html_escape($currency_symbol . $row->total_allowance); ?></td>
                                        <td><?php echo html_escape($currency_symbol . $row->total_deduction); ?></td>
                                        <td><?php echo html_escape($currency_symbol . $row->net_salary); ?></td>
                                        <td><?php echo html_escape(get_type_name_by_id('payment_type', $row->pay_via)); ?></td>
                                        <td><?php echo html_escape(_d($row->payment_date)); ?></td>
                                        <td class="hidden-print">
                                            <a href="<?php echo base_url('payroll/invoice/'. html_escape($row->id.'/'.$row->hash)); ?>" class="btn btn-default btn-circle"><i class="fas fa-eye"></i> <?php echo translate('view'); ?></a>
                                        </td>
                                    </tr>
                                    <?php 
                                        endforeach;
                                    }else{
                                        echo"<tr><td colspan='9'><h5 class='text-danger text-center'>". translate('no_information_available') ."</h5></td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="panel panel-accordion">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#bank_account">
                            <i class="fas fa-university"></i> <?php echo translate('bank_account'); ?>
                        </a>
                    </h4>
                </div>
                <div id="bank_account" class="accordion-body collapse <?php echo ($this->session->flashdata('bank_tab') == 1 ? 'in' : ''); ?>">
                    <div class="panel-body">
                        <div class="text-right mb-sm">
                            <a href="javascript:void(0);" id="addStaffBank" class="btn btn-circle btn-default mb-sm">
                                <i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('bank'); ?>
                            </a>
                        </div>
                        <div class="table-responsive mb-md">
                            <table class="table table-bordered table-hover table-condensed mb-none">
                            <thead>
                                <tr>
									<th><?php echo translate('sl'); ?></th>
                                    <th><?php echo translate('bank') . " " . translate('name'); ?></th>
                                    <th><?php echo translate('holder_name'); ?></th>
                                    <th><?php echo translate('bank') . " " . translate('branch'); ?></th>
                                    <th><?php echo translate('bank') . " " . translate('address'); ?></th>
                                    <th><?php echo translate('ifsc_code'); ?></th>
                                    <th><?php echo translate('account_no'); ?></th>
                                    <th><?php echo translate('actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                $this->db->where('staff_id', $staff['id']);
                                $bankResult = $this->db->get('staff_bank_account')->result();
                                if (count($bankResult)) {
                                    foreach($bankResult as $bank):
                                ?>
                                <tr>
                                    <td><?php echo $count++?></td>
                                    <td><?php echo html_escape($bank->bank_name); ?></td>
                                    <td><?php echo html_escape($bank->holder_name); ?></td>
                                    <td><?php echo html_escape($bank->bank_branch); ?></td>
                                    <td><?php echo html_escape($bank->bank_address); ?></td>
                                    <td><?php echo html_escape($bank->ifsc_code); ?></td>
                                    <td><?php echo html_escape($bank->account_no); ?></td>
                                    <td width="120px">
                                        <a href="javascript:void(0);" onclick="editStaffBank('<?php echo html_escape($bank->id); ?>')"  class="btn btn-circle icon btn-default">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php echo btn_delete('employee/bankaccount_delete/' . $bank->id); ?>
                                    </td>
                                </tr>
                                <?php
                                    endforeach;
                                }else{
                                    echo '<tr> <td colspan="8"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
                                }
                                ?>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-accordion">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#documents_details">
                            <i class="fas fa-folder-open"></i> <?php echo translate('document') . " " . translate('details'); ?>
                        </a>
                    </h4>
                </div>
                <div id="documents_details" class="accordion-body collapse <?php echo ($this->session->flashdata('documents_details') == 1 ? 'in' : ''); ?>">
                    <div class="panel-body">
                        <div class="text-right mb-sm">
                            <a href="javascript:void(0);" id="addStaffDocuments" class="btn btn-circle btn-default mb-sm">
                                <i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('document'); ?>
                            </a>
                        </div>
                        <div class="table-responsive mb-md">
                            <table class="table table-bordered table-hover table-condensed mb-none">
                            <thead>
                                <tr>
                                    <th><?php echo translate('sl'); ?></th>
                                    <th><?php echo translate('title'); ?></th>
                                    <th><?php echo translate('document') . " " . translate('type'); ?></th>
                                    <th><?php echo translate('file'); ?></th>
                                    <th><?php echo translate('remarks'); ?></th>
                                    <th><?php echo translate('created_at'); ?></th>
                                    <th><?php echo translate('actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                $this->db->where('staff_id', $staff['id']);
                                $documents = $this->db->get('staff_documents')->result();
                                if (count($documents)) {
                                    foreach($documents as $row):
                                ?>
                                <tr>
                                    <td><?php echo $count++?></td>
                                    <td><?php echo html_escape($row->title); ?></td>
                                    <td><?php echo html_escape($categorylist[$row->category_id]); ?></td>
                                    <td><?php echo html_escape($row->file_name); ?></td>
                                    <td><?php echo html_escape($row->remarks); ?></td>
                                    <td><?php echo html_escape(_d($row->created_at)); ?></td>
                                    <td class="min-w-c">
                                        <a href="<?php echo base_url('employee/documents_download?file=' . $row->enc_name); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('download'); ?>">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </a>
                                        <a href="javascript:void(0);" data-id="<?php echo html_escape($row->id);?>" class="btn btn-circle icon btn-default" onclick="editStaffDocument('<?php echo html_escape($row->id); ?>')">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php echo btn_delete('employee/document_delete/' . $row->id); ?>
                                    </td>
                                </tr>
                                <?php
                                    endforeach;
                                }else{
                                    echo '<tr> <td colspan="7"> <h5 class="text-danger text-center">' . translate('no_information_available') . '</h5> </td></tr>';
                                }
                                ?>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Documents Details Add Modal -->
<div id="add_documents_modal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('document'); ?></h4>
        </div>
		<?php echo form_open_multipart(base_url('employee/document_create'), array('class' => 'form-horizontal', 'id' => 'docaddfrm')); ?>
            <div class="panel-body">
                <input type="hidden" name="staff_id" value="<?php echo html_escape($staff['id']); ?>">
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="document_title" id="adocument_title" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo translate('category'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <?php
                        echo form_dropdown("document_category", $categorylist, set_value('document_category'), "class='form-control' data-plugin-selectTwo
                        data-width='100%' id='adocument_category' data-minimum-results-for-search='Infinity' ");
                        ?>
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo translate('document') . " " . translate('file'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="file" name="document_file" class="dropify" data-height="110" data-default-file="" id="adocument_file" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mb-md">
                    <label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
                    <div class="col-md-9">
                        <textarea class="form-control valid" rows="2" name="remarks"></textarea>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" id="docsavebtn" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
                        </button>
                        <button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
                    </div>
                </div>
			</footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Documents Details Edit Modal -->
<div id="editDocModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('document'); ?></h4>
        </div>
		<?php echo form_open_multipart(base_url('employee/document_update'), array('class' => 'form-horizontal', 'id' => 'doceditfrm')); ?>
            <div class="panel-body">
                <input type="hidden" name="document_id" id="edocument_id" value="">
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="document_title" id="edocument_title" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo translate('category'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <?php
                            echo form_dropdown("document_category", $categorylist, set_value('document_category'), "class='form-control' data-plugin-selectTwo id='edocument_category'
                            data-width='100%' data-minimum-results-for-search='Infinity' ");
                        ?>
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo translate('document') . " " . translate('file'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="file" name="document_file" class="dropify" data-height="120" data-default-file="">
                        <input type="hidden" name="exist_file_name" id="exist_file_name" value="">
                    </div>
                </div>
                <div class="form-group mb-md">
                    <label class="col-md-3 control-label"><?php echo translate('remarks'); ?></label>
                    <div class="col-md-9">
                        <textarea class="form-control valid" rows="2" name="remarks" id="edocuments_remarks"></textarea>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-default" id="doceditbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <?php echo translate('update'); ?>
                        </button>
                        <button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
                    </div>
                </div>
			</footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Bank Details Add Modal -->
<div id="addBankModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-plus-circle"></i> <?php echo translate('add') . " " . translate('bank'); ?></h4>
        </div>
        <?php echo form_open(base_url('employee/bank_account_create'), array('class' => 'form-horizontal', 'id' => 'bankaddfrm')); ?>
            <div class="panel-body">
                <input type="hidden" name="staff_id" value="<?php echo html_escape($staff['id']); ?>">
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('name'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_name" id="abank_name" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('holder_name'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="holder_name" id="aholder_name" />
                         <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('branch'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_branch" id="abank_branch" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('ifsc_code'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="ifsc_code" id="aifsc_code" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('account_no'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="account_no" id="aaccount_no" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mb-md">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('address'); ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_address" id="abank_address" />
                        <span class="error"></span>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-default" id="bankaddbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
                        </button>
                        <button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
                    </div>
                </div>
            </footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Bank Details Edit Modal -->
<div id="editBankModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('bank_account'); ?></h4>
        </header>
        <?php echo form_open(base_url('employee/bank_account_update'), array('class' => 'form-horizontal', 'id' => 'bankeditfrm')); ?>
            <div class="panel-body">
                <input type="hidden" name="bank_id" id="ebank_id" value="">
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('name'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_name" id="ebank_name" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('holder_name'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="holder_name" id="eholder_name" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('branch'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_branch" id="ebank_branch" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('ifsc_code'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="ifsc_code" id="eifsc_code" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mt-sm">
                    <label class="col-md-3 control-label"><?php echo translate('account_no'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="account_no" id="eaccount_no" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group mb-md">
                    <label class="col-md-3 control-label"><?php echo translate('bank') . " " . translate('address'); ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="bank_address" id="ebank_address" value="" />
                        <span class="error"></span>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-default" id="bankeditbtn" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <?php echo translate('update'); ?>
                        </button>
                        <button class="btn btn-default modal-dismiss"><?php echo translate('cancel'); ?></button>
                    </div>
                </div>
            </footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Login Authentication And Account Inactive Modal -->
<div id="authentication_modal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
    <section class="panel">
        <header class="panel-heading">
            <h4 class="panel-title">
                <i class="fas fa-unlock-alt"></i> <?php echo translate('authentication'); ?>
            </h4>
        </header>
        <div class="panel-body">
            <div class="form-group">
                <label for="password" class="control-label"><?php echo translate('password'); ?> <span class="required">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control password" name="password" id="reset_password" />
                    <span class="input-group-addon">
                        <a href="javascript:void(0);" id="showPassword" ><i class="fa fa-eye"></i></a>
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
                <button class="btn btn-default mr-xs" id="staffPassUpdate" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><?php echo translate('update'); ?></button>
                <button class="btn btn-default modal-dismiss"><?php echo translate('close'); ?></button>
            </div>
        </footer>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // user authentication modal show
        $('#authentication_btn').on('click', function() {
            var status = "<?php echo html_escape($staff['active']); ?>";
            if(status === '0'){
                $('#cb_authentication').prop('checked', true);
                $('#cb_authentication').prop('disabled', true);
                $('#reset_password').val("");
                $('#reset_password').prop('disabled', true);
            }else{
                $('#cb_authentication').prop('checked', false);
                $('#cb_authentication').prop('disabled', false);
                $('#reset_password').val("");
                $('#reset_password').prop('disabled', false);
            }
            mfp_modal('#authentication_modal');
        });
    });
</script>