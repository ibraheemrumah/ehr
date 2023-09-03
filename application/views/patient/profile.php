<?php $currency_symbol = $global_config['currency_symbol']; ?>
<div class="row">
	<div class="col-md-12 mb-lg">
		<div class="profile-head">
			<div class="col-md-12 col-lg-4 col-xl-3">
				<div class="image-content-center user-pro">
					<div class="preview">
						<img src="<?php echo $this->app_lib->get_image_url('patient/' . $patient['photo']); ?>">
					</div>
				</div>
			</div>
			<div class="col-md-12 col-lg-5 col-xl-5">
				<h5><?php echo html_escape($patient['name']); ?><?php echo "  | EHR NO: ";?><?php echo html_escape(!empty($patient['guardian']) ? $patient['patient_id'] : 'N/A'); ?></h5>
                <p><?php echo "Surname: ";?><?php echo html_escape(!empty($patient['guardian']) ? $patient['sname'] : 'N/A'); ?></p>
               
				<p><?php echo "Patient / " . html_escape($patient['category_name']); ?></p>
				<ul>
                
                    <li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('guardian'); ?>"><i class="fas fa-user-friends"></i></div><?php echo html_escape(!empty($patient['guardian']) ? $patient['guardian'] : 'N/A'); ?></li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="<?php echo translate('birthday'); ?>"><i class="fas fa-birthday-cake"></i></div> <?php echo html_escape(_d($patient['birthday'])); ?></li>
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
				<div id="profile" class="accordion-body collapse <?php echo ($this->session->flashdata('profile_tab') ? 'in' : ''); ?>">
                    <?php echo form_open_multipart($this->uri->uri_string()); ?>
                    <input type="hidden" name="patient_id" value="<?php echo html_escape($patient['id']); ?>" id="patient_id">
                    <div class="panel-body">
                        <!-- Basic Details -->
                        <div class="headers-line mt-md"> <i class="fas fa-user-check"></i> <?php echo translate('basic') . " " . translate('details'); ?></div>
                        
                        <div class="row">
						<div class="col-md-6 mb-sm">
							<div class="form-group <?php if (form_error('name')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('surname'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="far fa-user"></i></span>
									<input class="form-control" name="sname" type="text" value="<?php echo set_value('sname', $patient['sname']); ?>">
								</div>
								<span class="error"><?php echo form_error('name'); ?></span>
							</div>
						</div>
						<div class="col-md-6 mb-sm">
							<div class="form-group <?php if (form_error('name')) echo 'has-error'; ?>">
								<label class="control-label"><?php echo translate('First name'); ?> <span class="required">*</span></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="far fa-user"></i></span>
									<input class="form-control" name="fname" type="text" value="<?php echo set_value('fname', $patient['fname']); ?>">
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
                                        $arrayGender = array(
                                            "" => translate('select'),
                                            "male" => translate('male'),
                                            "female" => translate('female')
                                        );
                                        echo form_dropdown("gender", $arrayGender, set_value('gender', $patient['sex']) , "class='form-control' data-plugin-selectTwo data-width='100%'
                                        data-minimum-results-for-search='Infinity'");
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?php echo translate('birthday'); ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-birthday-cake"></i></span>
                                        <input class="form-control" name="birthday" id="patient_birthday" value="<?php echo set_value('birthday', $patient['birthday']); ?>"
                                        data-plugin-datepicker data-plugin-options='{ "startView": 2 }' type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group <?php if (form_error('age')) echo 'has-error'; ?>">
                                    <label class="control-label"><?php echo translate('age'); ?> <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="age" id="age" value="<?php echo set_value('age', $patient['age']); ?>" />
                                    <span class="error"><?php echo form_error('age'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            
                            <div class="col-md-4 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?php echo translate('marital_status'); ?></label>
                                    <?php
                                        $bloodArray = array(
                                            '' => translate('select'),
                                            '1' => translate('single'),
                                            '2' => translate('married')
                                        );
                                        echo form_dropdown("marital_status", $bloodArray, set_value("marital_status", $patient['marital_status']), "class='form-control' data-plugin-selectTwo
                                        data-width='100%' data-minimum-results-for-search='Infinity' ");
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                                <div class="form-group <?php if (form_error('mobile_no')) echo 'has-error'; ?>">
                                    <label class="control-label"><?php echo translate('mobile_no'); ?> <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-phone-volume"></i></span>
                                        <input class="form-control" name="mobile_no" type="text" value="<?php echo set_value('mobile_no', $patient['mobileno']); ?>" />
                                    </div>
                                    <span class="error"><?php echo form_error('mobile_no'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-sm">
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
                            
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-md">
                                <div class="form-group <?php if (form_error('category_id')) echo 'has-error'; ?>">
                                    <label class="control-label"><?php echo translate('category'); ?> <span class="required">*</span></label>
                                    <?php
                                        echo form_dropdown("category_id", $categorylist, set_value('category_id', $patient['category_id']) , "class='form-control' id='category_id' 
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
                                        echo form_dropdown("blood_group", $bloodlist, set_value("blood_group", $patient['blood_group']), "class='form-control' data-plugin-selectTwo
                                        data-width='100%' data-minimum-results-for-search='Infinity' ");
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-2 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?php echo translate('blood_pressure'); ?></label>
                                    <input type="text" class="form-control" name="blood_pressure" value="<?php echo set_value('blood_pressure', $patient['blood_pressure']); ?>" />
                                </div>
                            </div>
                            <div class="col-md-2 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?php echo translate('height'); ?></label>
                                    <input type="text" class="form-control" name="height" value="<?php echo set_value('height', $patient['height']); ?>" />
                                </div>
                            </div>
                            <div class="col-md-2 mb-sm">
                                <div class="form-group">
                                    <label class="control-label"><?php echo translate('weight'); ?></label>
                                    <input type="text" class="form-control" name="weight" value="<?php echo set_value('weight', $patient['weight']); ?>" />
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
                        <div class="headers-line"><i class="fas fa-pencil-ruler"></i> <?php echo translate('emergency_contact'); ?></div>

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
<?php if (get_permission('lab_test_bill', 'is_view')) { ?>
            <div class="panel panel-accordion">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#diagnostic"><i class="fas fa-money-check"></i> <?php echo translate('bill') . " " . translate('history'); ?></a>
                    </h4>
                </div>
                <div id="diagnostic" class="accordion-body collapse">
                    <div class="panel-body">
                        <div class="export_title"><?php echo html_escape($patient['name']); ?> - Bill History</div>
                        <table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
                            <thead>
                                <tr>
                                    <th><?php echo translate('sl'); ?></th>
                                    <th><?php echo translate('bill_no'); ?></th>
                                    <th><?php echo translate('delivery') . " " . translate('date'); ?></th>
                                    <th><?php echo translate('delivery') . " " . translate('status'); ?></th>
                                    <th><?php echo translate('payment') . " " . translate('status'); ?></th>
                                    <th><?php echo translate('net') . " " . translate('payable'); ?></th>
                                    <th><?php echo translate('paid'); ?></th>
                                    <th><?php echo translate('due'); ?></th>
                                    <th><?php echo translate('date'); ?></th>
                                    <th width="130"><?php echo translate('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
								$count = 1;
								if (!empty($billlist)){
									foreach($billlist as $row): 
									?>   
                                <tr>
                                    <td><?php echo $count++ ; ?></td>
                                    <td><?php echo html_escape($row['bill_no']); ?></td>
                                    <td><?php echo html_escape(_d($row['delivery_date'])) . " - " . date("h:i A", strtotime($row['delivery_time'])); ?></td>
                                    <td>
                                    <?php
                                    if ($row['bill_type'] == 1){
                                    if ($row['delivery_status'] == 2 ) {
                                        echo "<span class='label label-success-custom'>" . translate('completed') . "</span>";
                                    }else{
                                        echo "<span class='label label-danger-custom'>" . translate('undelivered'). "</span>";
                                    }
                                    }else {
                                        if ($row['bill_type'] == 2){
                                            echo "<span class='label label-success-custom'>" . translate('drug') . "</span>";

                                        }
                                    } 
                                        if ($row['bill_type'] == 3){
                                            echo "<span class='label label-success-custom'>" . translate('Treatment') . "</span>";

                                        }

                                    
                                    ?>
                                    </td>
                                    <td>
                                        <?php
                                            $labelMode = "";
                                           
                                            $status = $row['status'];
                                            if($status == 1) {
                                                $status = translate('unpaid');
                                                $labelMode = 'label-danger-custom';
                                            } elseif($status == 2) {
                                                $status = translate('partly_paid');
                                                $labelMode = 'label-info-custom';
                                            } elseif($status == 3 || $row['total_due'] == 0) {
                                                $status = translate('total_paid');
                                                $labelMode = 'label-success-custom';
                                            }
                                            echo "<span class='label " . $labelMode. "'>" . $status . "</span>";
                                        ?>
                                    </td>
                                    <td><?php echo html_escape($currency_symbol . number_format($row['net_amount'], 2, '.', '')); ?></td>
                                    <td><?php echo html_escape($currency_symbol . number_format($row['paid'], 2, '.', '')); ?></td>
                                    <td><?php echo html_escape($currency_symbol . number_format($row['due'], 2, '.', '')); ?></td>
                                    <td><?php echo html_escape(_d($row['date'])); ?></td>
                                    <td class="min-w-c">
                                        <?php
                                          if ($row['bill_type'] == 1){
                                            ?>
                                            
                                            <a href="<?php echo base_url('billing/test_bill_invoice/' . html_escape($row['id']) . "/" . html_escape($row['hash'])); ?>" class="btn btn-circle btn-default"> <i class="fas fa-eye"></i>
                                            <?php echo translate('invoice'); ?>
                                        </a>
                                        <?php if (get_permission('lab_test_bill', 'is_delete')): ?>
                                            <?php echo btn_delete('billing/test_bill_delete/' . $row['id']); ?>
                                        <?php endif; ?>
                                        <?php }
                                        
                                        else {
                                       

                                        if ($row['bill_type'] == 2){?>
                                            <a href="<?php echo base_url('billing/drug_bill_invoice/' . html_escape($row['id']) . "/" . html_escape($row['hash'])); ?>" class="btn btn-circle btn-default"> <i class="fas fa-eye"></i>
                                            <?php echo translate('invoice'); ?>
                                            </a>
                                            <?php if (get_permission('lab_test_bill', 'is_delete')): ?>
                                            <?php echo btn_delete('billing/test_bill_delete/' . $row['id']); ?>
                                        <?php endif; ?>

                                      <?php  }

                                      else { if ($row['bill_type'] == 3){?>
                                        <a href="<?php echo base_url('billing/test_bill_invoice/' . html_escape($row['id']) . "/" . html_escape($row['hash'])); ?>" class="btn btn-circle btn-default"> <i class="fas fa-eye"></i>
                                        <?php echo translate('invoice'); ?>
                                        </a>
                                        <?php if (get_permission('lab_test_bill', 'is_delete')): ?>
                                            <?php echo btn_delete('billing/test_bill_delete/' . $row['id']); ?>
                                        <?php endif; ?>
                                     <?php }}} ?>

                                       
                                    </td>
                                </tr>
                               
                                <?php endforeach; }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<?php } ?>
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
                                $this->db->where('patient_id', $patient['id']);
                                $documents = $this->db->get('patient_documents')->result();
                                if (count($documents)) {
                                    foreach($documents as $row):
                                ?>
                                <tr>
                                    <td><?php echo $count++?></td>
                                    <td><?php echo html_escape($row->title); ?></td>
                                    <td><?php echo html_escape($row->type); ?></td>
                                    <td><?php echo html_escape($row->file_name); ?></td>
                                    <td><?php echo html_escape($row->remarks); ?></td>
                                    <td><?php echo html_escape(_d($row->created_at)); ?></td>
                                    <td class="min-w-c">
                                        <a href="<?php echo base_url('patient/documents_download?file=' . $row->enc_name); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('download'); ?>">
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </a>
                                        <a href="javascript:void(0);" data-id="<?php echo html_escape($row->id);?>" class="btn btn-circle icon btn-default" onclick="editPatientDocument('<?php echo html_escape($row->id); ?>')">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php echo btn_delete('patient/document_delete/' . $row->id); ?>
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

            
            <div class="panel panel-accordion">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#investigation_details">
                            <i class="fas fa-folder-open"></i> <?php echo translate('Investigation') . " " . translate('details'); ?>
                        </a>
                    </h4>
                </div>
                <div id="investigation_details" class="accordion-body collapse <?php echo ($this->session->flashdata('investigation_details') == 1 ? 'in' : ''); ?>">
                    <div class="panel-body">
                    <section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-list"></i> <?php echo translate('investigation') . " " . translate('list'); ?></h4>
	</header>
	<div class="panel-body">
		<div class="export_title">Lab Test Report List : <?php echo _d($daterange[0]); ?> To <?php echo _d($daterange[1]); ?></div>
		<table class="table table-bordered table-hover table-condensed" cellspacing="0" width="100%" id="table-export">
			<thead>
				<tr>
					<th><?php echo translate('sl'); ?></th>
					<th><?php echo translate('bill_no'); ?></th>
					<th><?php echo translate('patient') . " " . translate('name'); ?></th>
					<th><?php echo translate('bill') . " " . translate('date'); ?></th>
					<th><?php echo translate('delivery') . " " . translate('date'); ?></th>
					<th><?php echo translate('reporting') . " " . translate('date'); ?></th>
					<th><?php echo translate('action'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($investlist)){ $count = 1; foreach($investlist as $row): ?>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo html_escape($row['bill_no']); ?></td>
					<td><?php echo html_escape($row['patient_name']); ?></td>
					<td><?php echo _d($row['bill_date']); ?></td>
					<td><?php echo _d($row['delivery_date']) . " - " . date("h:i A", strtotime($row['delivery_time'])); ?></td>
					<td><?php echo _d($row['reporting_date']) . " - " . date("h:i A", strtotime($row['reporting_date'])); ?></td>
					<td>
						<?php if (get_permission('patient', 'is_edit')): ?>
							<a href="<?php echo base_url('test/investigation_edit/' . $row['id']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip"
							data-original-title="<?php echo translate('edit'); ?>">
								<i class="fas fa-pen-nib"></i>
							</a>
						<?php endif;?>
						<a href="<?php echo base_url('test/investigation_print/'.$row['id']); ?>" class="btn btn-circle icon btn-default" data-toggle="tooltip"
						data-original-title="<?php echo translate('view'); ?>">
							<i class="fas fa-eye"></i>
						</a>
					</td>
				</tr>
				<?php endforeach; }?>
			</tbody>
		</table>
	</div>
</section>

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
        <?php echo form_open_multipart(base_url('patient/document_create'), array('class' => 'form-horizontal', 'id' => 'patientDocAdd')); ?>
            <div class="panel-body">
                <input type="hidden" name="patient_id" value="<?php echo html_escape($patient['id']); ?>">
                <div class="form-group mt-md">
                    <label class="col-md-3 control-label"><?php echo translate('title'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="document_title" id="adocument_title" value="" />
                        <span class="error"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo translate('document') . " " . translate('type'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="document_category" id="adocument_category" value="" />
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
        <?php echo form_open_multipart(base_url('patient/document_update'), array('class' => 'form-horizontal', 'id' => 'patientDocEdit')); ?>
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
                    <label class="col-md-3 control-label"><?php echo translate('document') . " " . translate('type'); ?> <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="document_category" id="edocument_category" value="" />
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

