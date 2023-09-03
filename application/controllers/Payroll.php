<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Payroll.php
 */

class Payroll extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payroll_model');
        $this->load->model('email_model');
    }

    // getting staff list for pay salary
    public function index()
    {
        if (!get_permission('salary_payment', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $month_year = $this->input->post('month_year');
            $staff_role = $this->input->post('staff_role');
            $this->data['month'] = date("m", strtotime($month_year));
            $this->data['year'] = date("Y", strtotime($month_year));
            $this->data['stafflist'] = $this->payroll_model->get_employee_payment_list($staff_role, $this->data['month'], $this->data['year']);
        }
        $this->data['sub_page'] = 'payroll/salary_payment';
        $this->data['main_menu'] = 'payroll';
        $this->data['title'] = translate('payroll');
        $this->load->view('layout/index', $this->data);
    }

    // add staff salary payslip in database
    public function create($id = '', $month = '', $year = '')
    {
        if (!get_permission('salary_payment', 'is_add')) {
            access_denied();
        }
        // SAVE ALL INFORMATION RELATED TO SALARY
        if (isset($_POST['paid'])) {
            $post = $this->input->post();
            $response = $this->payroll_model->save_payslip($post);
            if ($response['status'] == 'success') {
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect($response['uri']);
            } else {
                set_alert('error', "This Month Salary Already Paid !");
                redirect(base_url('payroll'));
            }
        }
        $this->data['month'] = $month;
        $this->data['year'] = $year;
        $this->data['staff'] = $this->payroll_model->get_employee_payment($id);
        $this->data['payvia_list'] = $this->app_lib->getSelectList('payment_type');
        $this->data['sub_page'] = 'payroll/create';
        $this->data['main_menu'] = 'payroll';
        $this->data['title'] = translate('payroll');
        $this->load->view('layout/index', $this->data);
    }

    // view staff salary payslip
    public function invoice($id = '', $hash = '')
    {
        if (!get_permission('salary_payment', 'is_view')) {
            access_denied();
        }
        check_hash_restrictions('payslip', $id, $hash);
        $this->data['salary'] = $this->payroll_model->get_invoice($id);
        $this->data['sub_page'] = 'payroll/invoice';
        $this->data['main_menu'] = 'payroll';
        $this->data['title'] = translate('payroll');
        $this->load->view('layout/index', $this->data);
    }

    // add staff salary template
    public function salary_template()
    {
        if (!get_permission('salary_template', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            if (!get_permission('salary_template', 'is_add')) {
                access_denied();
            }

            $overtime_rate = $this->input->post('overtime_rate');
            $overtime_rate = (empty($overtime_rate) ? 0 : $overtime_rate);
            // save salary template info
            $insertData = array(
                'name' => $this->input->post('template_name'),
                'basic_salary' => $this->input->post('basic_salary'),
                'overtime_salary' => $overtime_rate,
            );
            $this->db->insert('salary_template', $insertData);
            $template_id = $this->db->insert_id();

            // SAVE ALL ALLOWANCE INFO
            $allowances = $this->input->post('allowance');
            foreach ($allowances as $key => $value) {
                if ($value["name"] != "" && $value["amount"] != "") {
                    $insertAllowance = array(
                        'salary_template_id' => $template_id,
                        'name' => $value["name"],
                        'amount' => $value["amount"],
                        'type' => 1,
                    );
                    $this->db->insert('salary_template_details', $insertAllowance);
                }
            }

            // save all deduction info
            $deductions = $this->input->post('deduction');
            foreach ($deductions as $key => $value) {
                if ($value["name"] != "" && $value["amount"] != "") {
                    $insertDeduction = array(
                        'salary_template_id' => $template_id,
                        'name' => $value["name"],
                        'amount' => $value["amount"],
                        'type' => 2,
                    );
                    $this->db->insert('salary_template_details', $insertDeduction);
                }
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('payroll/salary_template'));
        }

        $this->data['templatelist'] = $this->payroll_model->get_list('salary_template');
        $this->data['title'] = translate('payroll');
        $this->data['sub_page'] = 'payroll/salary_templete';
        $this->data['main_menu'] = 'payroll';
        $this->load->view('layout/index', $this->data);
    }

    // salary template update by id
    public function salary_template_edit($id)
    {
        if (!get_permission('salary_template', 'is_edit')) {
            access_denied();
        }

        if (isset($_POST['save'])) {
            $template_id = $this->input->post('salary_template_id');
            $overtime_rate = $this->input->post('overtime_rate');
            $overtime_rate = (empty($overtime_rate) ? 0 : $overtime_rate);

            // update salary template info
            $insertData = array(
                'name' => $this->input->post('template_name'),
                'basic_salary' => $this->input->post('basic_salary'),
                'overtime_salary' => $overtime_rate,
            );
            $this->db->where('id', $template_id);
            $this->db->update('salary_template', $insertData);

            // update all allowance info
            $allowances = $this->input->post('allowance');
            foreach ($allowances as $key => $value) {
                if ($value["name"] != "" && $value["amount"] != "") {
                    $insertAllowance = array(
                        'salary_template_id' => $template_id,
                        'name' => $value["name"],
                        'amount' => $value["amount"],
                        'type' => 1,
                    );

                    if (isset($value["old_allowance_id"])) {
                        $this->db->where('id', $value["old_allowance_id"]);
                        $this->db->update('salary_template_details', $insertAllowance);
                    } else {
                        $this->db->insert('salary_template_details', $insertAllowance);
                    }
                }
            }

            // update all deduction info
            $deductions = $this->input->post('deduction');
            foreach ($deductions as $key => $value) {
                if ($value["name"] != "" && $value["amount"] != "") {
                    $insertDeduction = array(
                        'salary_template_id' => $template_id,
                        'name' => $value["name"],
                        'amount' => $value["amount"],
                        'type' => 2,
                    );

                    if (isset($value["old_deduction_id"])) {
                        $this->db->where('id', $value["old_deduction_id"]);
                        $this->db->update('salary_template_details', $insertDeduction);
                    } else {
                        $this->db->insert('salary_template_details', $insertDeduction);
                    }
                }
            }
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('payroll/salary_template'));
        }

        $this->data['template_id'] = $id;
        $this->data['allowances'] = $this->payroll_model->get_list('salary_template_details', array('type' => 1, 'salary_template_id' => $id));
        $this->data['deductions'] = $this->payroll_model->get_list('salary_template_details', array('type' => 2, 'salary_template_id' => $id));
        $this->data['template'] = $this->payroll_model->get_list('salary_template', array('id' => $id), true);
        $this->data['title'] = translate('payroll');
        $this->data['sub_page'] = 'payroll/salary_templete_edit';
        $this->data['main_menu'] = 'payroll';
        $this->load->view('layout/index', $this->data);
    }

    // delete salary template from database
    public function salary_template_delete($id)
    {
        if (!get_permission('salary_template', 'is_delete')) {
            access_denied();
        }
        $this->db->where('salary_template_id', $id);
        $this->db->delete('salary_template_details');
        $this->db->where('id', $id);
        $this->db->delete('salary_template');
    }

    // staff salary allocation
    public function salary_assign()
    {
        if (!get_permission('salary_assign', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $staff_role = $this->input->post('staff_role');
            $designation_id = $this->input->post('designation_id');
            $this->data['stafflist'] = $this->payroll_model->get_employee_list($staff_role, $designation_id);
        }
        if (isset($_POST['assign'])) {
            if (!get_permission('salary_assign', 'is_add')) {
                access_denied();
            }
            $stafflist = $this->input->post('stafflist');
            if (count($stafflist)) {
                foreach ($stafflist as $key => $value) {
                    $template_id = $value['template_id'];
                    if (empty($template_id)) {
                        $template_id = 0;
                    }

                    $this->db->where('id', $value['id']);
                    $this->db->update('staff', array('salary_template_id' => $template_id));
                }
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('payroll/salary_assign'));
        }
        $this->data['title'] = translate('payroll');
        $this->data['designationlist'] = $this->app_lib->getSelectList('staff_designation');
        $this->data['templatelist'] = $this->app_lib->getSelectList('salary_template');
        $this->data['sub_page'] = 'payroll/salary_assign';
        $this->data['main_menu'] = 'payroll';
        $this->load->view('layout/index', $this->data);
    }

    // employees salary statement list
    public function salary_summary()
    {
        if (!get_permission('salary_summary_report', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $this->data['month'] = date("m", strtotime($this->input->post('month_year')));
            $this->data['year'] = date("Y", strtotime($this->input->post('month_year')));
            $this->data['payslip'] = $this->payroll_model->get_summary($this->data['month'], $this->data['year']);
        }
        $this->data['title'] = translate('payroll');
        $this->data['sub_page'] = 'payroll/salary_summary';
        $this->data['main_menu'] = 'payroll';
        $this->load->view('layout/index', $this->data);
    }
}
