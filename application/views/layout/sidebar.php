<aside id="sidebar-left" class="sidebar-left">
	<div class="sidebar-header">
		<div class="sidebar-title">Shortcut</div>
	</div>
	<div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <!-- Dashboard -->
                    <li class="<?php if ($main_menu == 'dashboard') echo 'nav-active'; ?>">
                        <a href="<?php echo base_url('dashboard'); ?>"><i class="fab fa-dashcube"></i><span><?php echo translate('dashboard'); ?></span></a>
                    </li>

                   
                    <?php
                    if(get_permission('patient', 'is_add') ||
                    get_permission('patient', 'is_view') ||
                    get_permission('patient_category', 'is_add') ||
                    get_permission('patient_category', 'is_view') ||
                    get_permission('patient_disable_authentication', 'is_view')){
                    ?>
                    <!-- Patient Details -->
                    <li class="nav-parent <?php if ($main_menu == 'patient') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fas fa-wheelchair"></i><span><?php echo translate('patient') . " " . translate('details'); ?></span></a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('patient', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'patient/add') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('patient/create'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('create') . " " . translate('patient'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('patient', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'patient/view' || $sub_page == 'patient/profile') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('patient/view'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('patient') . " " . translate('list'); ?></span>
                                </a>
                            </li>
                        <?php }  if(get_permission('patient_category', 'is_add') || get_permission('patient_category', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'patient/category') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('patient/category'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('category'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('patient_disable_authentication', 'is_view')){ ?>
                            
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('chemical', 'is_view') ||
                    get_permission('chemical_category', 'is_view') ||
                    get_permission('chemical_supplier', 'is_view') ||
                    get_permission('chemical_unit', 'is_view') ||
                    get_permission('chemical_purchase', 'is_view') ||
                    get_permission('chemical_stock', 'is_view') ||
                    get_permission('reagent_assigned', 'is_view') ||
                    get_permission('inventory_report', 'is_view')){
                    ?>
                    <!-- Inventory -->
                    <li class="nav-parent <?php if ($main_menu == 'inventory' || $main_menu == 'inventory_report') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fas fa-dolly"></i><span><?php echo translate('Pharmacy'); ?></span></a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('chemical', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'inventory/chemical' || $sub_page == 'inventory/chemical_edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('inventory/chemical'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('chemical'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('chemical_category', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'inventory/category') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('inventory/category'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('category'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('chemical_supplier', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'inventory/supplier' || $sub_page == 'inventory/supplier_edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('inventory/supplier'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('supplier'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('chemical_unit', 'is_view')){  ?>
                            <li class="<?php if ($sub_page == 'inventory/unit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('inventory/unit'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('unit'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('chemical_purchase', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'inventory/purchase' || $sub_page == 'inventory/purchase_edit' || $sub_page == 'inventory/purchase_bill') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('inventory/purchase'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('purchase'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('chemical_stock', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'inventory/stock' || $sub_page == 'inventory/stock_edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('inventory/chemical_stock'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('stock'); ?></span>
                                </a>
                            </li>
                        <?php }  if(get_permission('reagent_assigned', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'inventory/reagent_assigned' || $sub_page == 'inventory/reagent_assigned_edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('inventory/reagent_assigned'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('reagent') . " " . translate('assigned'); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if(get_permission('inventory_report', 'is_view')){  ?>
                            <!-- Reports -->
                            <li class="nav-parent <?php if ($main_menu == 'inventory_report') echo 'nav-expanded'; ?>">
                                <a><i class="fas fa-print" aria-hidden="true"></i><?php echo translate('reports'); ?></a>
                                <ul class="nav nav-children">
                                    <li class="<?php if ($sub_page == 'inventory/stockreport_chemical_wise') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('inventory/stockreport_chemical_wise'); ?>">
                                            <?php echo translate('stock') . " " . translate('report'); ?>
                                        </a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'inventory/purchase_report') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('inventory/purchase_report'); ?>">
                                            <?php echo translate('purchase') . " " . translate('report'); ?>
                                        </a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'inventory/purchase_payment_report') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('inventory/purchase_payment_report'); ?>">
                                            <?php echo translate('payment') . " " . translate('report'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(get_permission('schedule', 'is_view')){ ?>
                    <!-- Schedule -->
                    <li class="<?php if ($main_menu == 'schedule') echo 'nav-active'; ?>">
                        <a href="<?php echo base_url('schedule'); ?>"><i class="fas fa-dna"></i><span><?php echo translate('schedule'); ?></span></a>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('appointment', 'is_view') ||
                    get_permission('appointment', 'is_add') ||
                    (loggedin_role_id() == 7) ||
                    get_permission('appointment_request', 'is_view')){
                    ?>
                    <!-- Appointment -->
                    <li class="nav-parent <?php if ($main_menu == 'appointment') echo 'nav-expanded nav-active'; ?>">
                        <a>
                            <i class="fas fa-notes-medical"></i><span><?php echo translate('appointment'); ?></span>
                        </a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('appointment', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'appointment/index' || $sub_page == 'appointment/edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('appointment'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('appointment') . " " . translate('list'); ?></span>
                                </a>
                            </li>
                        <?php }  if(get_permission('appointment', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'appointment/add') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('appointment/add'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('add') . " " . translate('appointment'); ?></span>
                                </a>
                            </li>
                        <?php }  if(loggedin_role_id() == 7){ ?>
                            <li class="<?php if ($sub_page == 'appointment/my_list') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('appointment/my_list'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('my_appointment'); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
					<?php
                    if(get_permission('employee', 'is_view') ||
                    get_permission('employee', 'is_add') ||
                    get_permission('designation', 'is_view') ||
                    get_permission('designation', 'is_add') ||
                    get_permission('department', 'is_view') ||
                    get_permission('employee_disable_authentication', 'is_view')){
					?>
                    <!-- Employees -->
                    <li class="nav-parent <?php if ($main_menu == 'employee') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fas fa-users"></i><span><?php echo translate('employee'); ?></span></a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('employee', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'employee/view' ||
                                                    $sub_page == 'employee/profile' ||
                                                        $sub_page == 'employee/add_short_bio') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('employee/view'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('employee') . " " . translate('list'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('department', 'is_view') || get_permission('department', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'employee/department') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('employee/department'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('add') . " " . translate('department'); ?></span>
                                </a>
                            </li>
                        <?php }  if(get_permission('designation', 'is_view') || get_permission('designation', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'employee/designation') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('employee/designation'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('add') . " " . translate('designation'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('employee', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'employee/add') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('employee/create'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('add') . " " . translate('employee'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('employee_disable_authentication', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'employee/disable_authentication') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('employee/disable_authentication'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('login_deactivate'); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
					<?php } ?>
                    <?php
                    if(get_permission('salary_template', 'is_view') ||
                    get_permission('salary_payment', 'is_view') ||
                    get_permission('salary_assign', 'is_view') ||
                    get_permission('salary_summary_report', 'is_view') ||
                    get_permission('leave_category', 'is_view') ||
                    get_permission('leave_category', 'is_add') ||
                    get_permission('my_leave', 'is_view') ||
                    get_permission('leave_manage', 'is_view') ||
                    get_permission('staff_attendance', 'is_view') ||
                    get_permission('staff_attendance', 'is_add') ||
                    get_permission('staff_attendance', 'is_view')){
                    ?>
                    <!-- Human Resource -->
                    <li class="nav-parent <?php if ($main_menu == 'payroll' || $main_menu == 'leave' || $main_menu == 'attendance') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fab fa-hubspot"></i><span><?php echo translate('hrm'); ?></span></a>
                        <ul class="nav nav-children">
                            <?php
                            if(get_permission('salary_template', 'is_view') ||
                            get_permission('salary_payment', 'is_view') ||
                            get_permission('salary_assign', 'is_view') ||
                            get_permission('salary_summary_report', 'is_view')){
                            ?>
                            <!-- Payroll -->
                            <li class="nav-parent <?php if ($main_menu == 'payroll') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="far fa-address-card" aria-hidden="true"></i><span><?php echo translate('payroll'); ?></span></a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('salary_template', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'payroll/salary_templete' || $sub_page == 'payroll/salary_templete_edit') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('payroll/salary_template'); ?>">
                                            <span><?php echo translate('salary') . " " . translate('template'); ?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('salary_assign', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'payroll/salary_assign') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('payroll/salary_assign'); ?>">
                                            <span><?php echo translate('salary') . " " . translate('assign'); ?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('salary_payment', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'payroll/salary_payment' || $sub_page == 'payroll/invoice' || $sub_page == 'payroll/create') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('payroll'); ?>">
                                            <span><?php echo translate('salary') . " " . translate('payment'); ?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('salary_summary_report', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'payroll/salary_summary') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('payroll/salary_summary'); ?>">
                                            <span><?php echo translate('salary') . " " . translate('summary'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php
                            if(get_permission('leave_category', 'is_view') ||
                            get_permission('leave_category', 'is_add') ||
                            get_permission('my_leave', 'is_view') ||
                            get_permission('leave_manage', 'is_view')){
                            ?>
                            <!-- Leave Managements -->
                            <li class="nav-parent <?php if ($main_menu == 'leave') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="fas fa-umbrella-beach" aria-hidden="true"></i><span><?php echo translate('leaves'); ?></span></a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('leave_category', 'is_view') || get_permission('leave_category', 'is_add')){ ?>
                                    <li class="<?php if ($sub_page == 'leave/category') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('leave/category'); ?>"><span><?php echo translate('category'); ?></span></a>
                                    </li>
                                    <?php } if(get_permission('my_leave', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'leave/index') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('leave'); ?>"><span><?php echo translate('my_leave'); ?></span></a>
                                    </li>
                                    <?php } if(get_permission('leave_manage', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'leave/manage_all') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('leave/manage_all'); ?>"><span><?php echo translate('leave_manage'); ?></span></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php if(get_permission('staff_attendance', 'is_add') || get_permission('staff_attendance', 'is_view')){ ?>
                            <!-- Staff Attendance -->
                            <li class="nav-parent <?php if ($main_menu == 'attendance') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="far fa-chart-bar" aria-hidden="true"></i><span><?php echo translate('attendance'); ?></span></a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('staff_attendance', 'is_add')){ ?>
                                    <li class="<?php if ($sub_page == 'attendance/add') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('attendance/add'); ?>"><span><?php echo translate('set'); ?></span></a>
                                    </li>
                                    <?php } if(get_permission('staff_attendance', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'attendance/index') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('attendance'); ?>"><span><?php echo translate('report'); ?></span></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('lab_test', 'is_view') ||
                    get_permission('test_category', 'is_view')) {
                    ?>
                
                    
                     <!-- Lab Test Manager -->
                    <li class="nav-parent <?php if ($main_menu == 'test') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fas fa-microscope" aria-hidden="true"></i> <span><?php echo translate('Services'); ?></span></a>
                        <ul class="nav nav-children">
                            <?php if(get_permission('lab_test', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'test/test' || $sub_page == 'test/test_edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('service'); ?>"><i class="fas fa-caret-right"></i><span><?php echo translate('Create') . " " . translate('service'); ?></span></a>
                            </li>
                            <?php } if(get_permission('test_category', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'test/category') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('service/category'); ?>"><i class="fas fa-caret-right"></i><span><?php echo translate('category'); ?></span></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('referral_assign', 'is_view') ||
                    get_permission('referral_assign', 'is_add') ||
                    get_permission('commission_withdrawal', 'is_view') ||
                    get_permission('my_commission', 'is_view') ||
                    get_permission('referral_reports', 'is_view')){
                    ?>
                    <!-- Refer Manager -->
                    <li class="nav-parent <?php if ($main_menu == 'referral' || $main_menu == 'referral_report') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fas fa-hands-helping" aria-hidden="true"></i><span><?php echo translate('refer_manager'); ?></span></a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('referral_assign', 'is_add')) { ?>
                            <!-- Referral Assign -->
                            <li class="<?php if ($sub_page == 'referral/manage_assign') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('referral/manage_assign'); ?>"><i class="fab fa-creative-commons-share"></i><span><?php echo translate('set') . " " . translate('referral'); ?></span></a>
                            </li>
                        <?php } if(get_permission('referral_assign', 'is_view')) { ?>
                            <!-- Referral List -->
                            <li class="<?php if ($sub_page == 'referral/assign_list') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('referral/assign_list'); ?>"><i class="fas fa-users"></i><span><?php echo translate('referral') . " " . translate('list'); ?></span></a>
                            </li>
                        <?php } if(get_permission('commission_withdrawal', 'is_view')) { ?>
                            <!-- Commission Withdrawal -->
                            <li class="<?php if ($sub_page == 'referral/withdrawal') echo 'nav-active'; ?>">
                                 <a href="<?php echo base_url('referral/withdrawal'); ?>">
                                    <i class="far fa-credit-card"></i><span><?php echo translate('withdrawal'); ?></span>
                                 </a>
                            </li>
                        <?php } 
                        if(loggedin_role_id() != 1){
                        if(get_permission('my_commission', 'is_view')) { ?>
                            <!-- Commission Withdrawal -->
                            <li class="<?php if ($sub_page == 'referral/my_commission_summary') echo 'nav-active'; ?>">
                                 <a href="<?php echo base_url('referral/my_commission_summary'); ?>">
                                    <i class="fas fa-project-diagram"></i><span><?php echo translate('my_commission'); ?></span>
                                 </a>
                            </li>
                        <?php } } ?>
                        <?php if(get_permission('referral_reports', 'is_view')) { ?>
                            <!-- Report -->
                            <li class="nav-parent <?php if ($main_menu == 'referral_report') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="fas fa-print"></i><span><?php echo translate('reports'); ?></span></a>
                                <ul class="nav nav-children">
                                    <li class="<?php if ($sub_page == 'referral/referral_statement') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('referral/referral_statement'); ?>"> <?php echo translate('referral') . " " . translate('statement'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'referral/commission_history') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('referral/commission_history'); ?>"><?php echo translate('commission') . " " . translate('report'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'referral/commission_summary') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('referral/commission_summary'); ?>"><?php echo translate('commission') . " " . translate('summary'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'referral/payout_report') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('referral/payout_report'); ?>"><?php echo translate('payout') . " " . translate('report'); ?></a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('account', 'is_view') ||
                    get_permission('voucher_head', 'is_view') ||
                    get_permission('voucher_head', 'is_add') ||
                    get_permission('voucher', 'is_view') ||
                    get_permission('accounting_reports', 'is_view')){
                    ?>
                    <!-- Office Accounting -->
                    <li class="nav-parent <?php if ($main_menu == 'accounts' || $main_menu == 'accounts_repots') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fas fa-calculator"></i><span><?php echo translate('office_accounting'); ?></span></a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('account', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'accounts/index' || $sub_page == 'accounts/edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('accounts'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('account'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('voucher', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'accounts/voucher' || $sub_page == 'accounts/voucher_edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('accounts/voucher'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('voucher'); ?></span>
                                </a>
                            </li>
                        <?php }  if(get_permission('voucher_head', 'is_view') || get_permission('voucher_head', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'accounts/voucher_head') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('accounts/voucher_head'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('voucher') . " " . translate('head'); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if(get_permission('accounting_reports', 'is_view')){ ?>
                            <!-- Report -->
                            <li class="nav-parent <?php if ($main_menu == 'accounts_repots') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="fas fa-print"></i><span><?php echo translate('reports'); ?></span></a>
                                <ul class="nav nav-children">
                                    <li class="<?php if ($sub_page == 'accounts/account_statement') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounts/account_statement'); ?>"><?php echo translate('account') . " " . translate('statement'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'accounts/income_repots') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounts/income_repots'); ?>"><?php echo translate('income') . " " . translate('repots'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'accounts/expense_repots') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounts/expense_repots'); ?>"> <?php echo translate('expense') . " " . translate('repots'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'accounts/transitions_repots') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounts/transitions_repots'); ?>"> <?php echo translate('transitions') . " " . translate('repots'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'accounts/balance_sheet') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounts/balance_sheet'); ?>"><?php echo translate('balance') . " " . translate('sheet'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'accounts/income_vs_expense') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounts/incomevsexpense'); ?>"> <?php echo translate('income_vs_expense'); ?></a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php 
                    if(get_permission('lab_test_bill', 'is_add') ||
                    get_permission('lab_test_bill', 'is_view') ||
                    get_permission('test_bill_report', 'is_view')){
                    ?>
                    <!-- Billing -->
                    <li class="nav-parent <?php if ($sub_page == 'billing/labtest_bill_list' || $sub_page == 'billing/labtest_bill_invoice') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fas fa-money-check"></i><span><?php echo translate('pathology') . " " .  translate('billing'); ?></span></a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('lab_test_bill', 'is_add')) { ?>
                            <li class="<?php if ($sub_page == 'billing/test_bill_add') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('billing/test_bill_add'); ?>">
                                    <i class="fas fa-caret-right"></i><span><?php echo translate('create') . " " . translate('test') . " " . translate('bill'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('lab_test_bill', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'billing/labtest_bill_list' || $sub_page == 'billing/labtest_bill_invoice') echo 'nav-active'; ?>">
                                 <a href="<?php echo base_url('billing/test_bill_list'); ?>">
                                    <i class="fas fa-caret-right"></i><span><?php echo translate('test') . " " . translate('bill') . " " . translate('list'); ?></span>
                                 </a>
                            </li>
                        <?php } ?>
                        <?php if(get_permission('test_bill_report', 'is_view')) { ?>
                            <!-- Report -->
                            <li class="nav-parent <?php if ($main_menu == 'billing_report') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="fas fa-print"></i><span><?php echo translate('reports'); ?></span></a>
                                <ul class="nav nav-children">
                                   <li class="<?php if ($sub_page == 'billing/due_bill_report') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('billing/due_bill_report'); ?>">
                                            <?php echo translate('due') . " " . translate('bill') . " " . translate('report'); ?>
                                        </a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'billing/paid_bill_report') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('billing/paid_bill_report'); ?>">
                                            <?php echo translate('paid') . " " . translate('bill') . " " . translate('report'); ?>
                                        </a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'billing/due_collection_report') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('billing/due_collection_report'); ?>">
                                            <?php echo translate('due') . " " . translate('collect') . " " . translate('report'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </li> 
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php
                    if(get_permission('test_report', 'is_view') ||
                    get_permission('test_report_template', 'is_view')) {
                    ?>
                    <!-- Treatment billing -->
                    
                    <li class="nav-parent <?php if ($sub_page == 'billing/treat_add' || $sub_page == 'billing/treatment_bill_list' || $sub_page == 'billing/drug_bill_invoice') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fas fa-money-check"></i><span><?php echo translate('Treatment') . " " .  translate('billing'); ?></span></a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('lab_test_bill', 'is_add')) { ?>
                            <li class="<?php if ($sub_page == 'billing/treat_add') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('billing/treat_add'); ?>">
                                    <i class="fas fa-caret-right"></i><span><?php echo translate('create') . " " . translate('Drug') . " " . translate('bill'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('lab_test_bill', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'billing/treatment_bill_list' || $sub_page == 'billing/drug_bill_invoice') echo 'nav-active'; ?>">
                                 <a href="<?php echo base_url('billing/treatment_bill_list'); ?>">
                                    <i class="fas fa-caret-right"></i><span><?php echo translate('Treatment') . " " . translate('bill') . " " . translate('list'); ?></span>
                                 </a>
                            </li>
                        <?php } ?>
                        <?php if(get_permission('test_bill_report', 'is_view')) { ?>
                            <!-- Report -->
                            <li class="nav-parent <?php if ($main_menu == 'billing_report') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="fas fa-print"></i><span><?php echo translate('reports'); ?></span></a>
                                <ul class="nav nav-children">
                                   <li class="<?php if ($sub_page == 'billing/due_bill_report') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('billing/due_bill_report'); ?>">
                                            <?php echo translate('due') . " " . translate('bill') . " " . translate('report'); ?>
                                        </a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'billing/paid_bill_report') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('billing/paid_bill_report'); ?>">
                                            <?php echo translate('paid') . " " . translate('bill') . " " . translate('report'); ?>
                                        </a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'billing/due_collection_report') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('billing/due_collection_report'); ?>">
                                            <?php echo translate('due') . " " . translate('collect') . " " . translate('report'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </li> 
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php
                    if(get_permission('test_report', 'is_view') ||
                    get_permission('test_report_template', 'is_view')) {
                    ?>

                    
                    <!-- Investigation report -->
                    <li class="nav-parent <?php if ($main_menu == 'test_report') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="far fa-clipboard" aria-hidden="true"></i> <span><?php echo translate('investigation') . " " . translate('report'); ?></span></a>
                        <ul class="nav nav-children">
                            <?php if(get_permission('test_report', 'is_add')) { ?>
                            <li class="<?php if ($sub_page == 'test/pending_report' || $sub_page == 'test/investigation_create') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('test/pending_report'); ?>"><i class="fas fa-caret-right"></i><span><?php echo translate('create') . " " . translate('report'); ?></span></a>
                            </li>
                            <?php } if(get_permission('test_report', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'test/report_list' ||
                                                    $sub_page == 'test/investigation_print'  ||
                                                        $sub_page == 'test/investigation_edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('test/report_list'); ?>"><i class="fas fa-caret-right"></i><span><?php echo translate('report') . " " . translate('list'); ?></span></a>
                            </li>
                            <?php } if(get_permission('test_report_template', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'test/report_template' || $sub_page == 'test/report_template_edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('test/report_template'); ?>"><i class="fas fa-caret-right"></i><span><?php echo translate('template'); ?></span></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php
                    if(get_permission('global_setting', 'is_view') ||
                    get_permission('email_setting', 'is_view') ||
                    get_permission('database_backup', 'is_view') ||
                    get_permission('language', 'is_view') ||
                    get_permission('database_backup', 'is_view') ||
                    get_permission('database_restore', 'is_add')){
                    ?>
                    <!-- Settings -->
                    <li class="nav-parent <?php if ($main_menu == 'settings') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fas fa-cogs"></i><span><?php echo translate('settings'); ?></span></a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('global_setting', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'setting/index') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('settings'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('global') . " " . translate('setting'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('sms_setting', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'smssetting/index' || $sub_page == 'smssetting/template') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('smssettings'); ?>">
                                    <span><i class="fas fa-caret-right"></i><?php echo translate('sms') . " " . translate('settings'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('email_setting', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'mailconfig/index' || $sub_page == 'mailconfig/template') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('mailconfig'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('email') . " " . translate('setting'); ?></span>
                                </a>
                            </li>
                        <?php } if(loggedin_role_id() == 1) { ?>
                            <li class="<?php if ($sub_page == 'role/index' || $sub_page == 'role/permission') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('role'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('role') . " " . translate('permission'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('language', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'language/index' || $sub_page == 'language/word_update' || $sub_page == 'language/edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('language'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('language') . " " . translate('setting'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('database_backup', 'is_view') || get_permission('database_restore', 'is_add')) { ?>
                            <li class="<?php if ($sub_page == 'backup/index') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('backup'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('database') . " " . translate('backup'); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
	</div>
</aside>
<!-- end sidebar -->