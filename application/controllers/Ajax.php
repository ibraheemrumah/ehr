<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Ajax.php
 */

class Ajax extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ajax_model');
    }

    public function department_details()
    {
        if (get_permission('department', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $query = $this->db->get('staff_department');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    public function designation_details()
    {
        if (get_permission('designation', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $query = $this->db->get('staff_designation');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    public function bank_details()
    {
        if (get_permission('employee', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $query = $this->db->get('staff_bank_account');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    public function staff_document_details()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $query = $this->db->get('staff_documents');
        $result = $query->row_array();
        echo json_encode($result);
    }

    public function patient_document_details()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $query = $this->db->get('patient_documents');
        $result = $query->row_array();
        echo json_encode($result);
    }

    public function get_labtest_by_category()
    {
        $category_id = $this->input->post('category_id');
        $selected_id = $this->input->post('selected_id');
        $productlist = $this->db->select('id,name,test_code')->where('category_id', $category_id)->get('lab_test')->result_array();
        $html = "<option value=''>" . translate('select') . "</option>";
        foreach ($productlist as $product) {
            $selected = ($product['id'] == $selected_id ? 'selected' : '');
            $html .= "<option value='" . $product['id'] . "' " . $selected . ">" . $product['name'] . " (" . $product['test_code'] . ")</option>";
        }
        echo $html;
    }

    public function get_labtest_price()
    {
        $testid = $this->input->post('testid');
        $r = $this->db->select('patient_price')->where('id', $testid)->get('lab_test')->row_array();
        if (empty($r['patient_price'])) {
            echo "0.00";
        } else {
            echo $r['patient_price'];
        }
    }
	
	public function get_chm_price()
    {
        $testid = $this->input->post('testid');
        $r = $this->db->select('sales_price')->where('id', $testid)->get('chemical')->row_array();
        if (empty($r['sales_price'])) {
            echo "0.00";
        } else {
            echo $r['sales_price'];
        }
    }

    public function test_category_details()
    {
        if (get_permission('designation', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $query = $this->db->get('lab_test_category');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    public function chemical_category_details()
    {
        if (get_permission('chemical_category', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $query = $this->db->get('chemical_category');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    public function get_chemical_price()
    {
        $id = $this->input->post('id');
        $price = $this->db->select('ifnull(purchase_price,0) as purchase_price')->where('id', $id)->get('chemical')->row_array();
        echo $price['purchase_price'];
    }

    public function get_chemical_sprice()
    {
        $id = $this->input->post('id');
        $price = $this->db->select('ifnull(sales_price,0) as sales_price')->where('id', $id)->get('chemical')->row_array();
        echo $price['sales_price'];
    }

    public function get_chemical_by_category()
    {
        $category_id = $this->input->post('category_id');
        $selected_id = (isset($_POST['chemical_id']) ? $_POST['chemical_id'] : 0);
        $chemicallist = $this->ajax_model->get_list('chemical', array('category_id' => $category_id), false, 'id,name,code');
        $html = "<option value=''>" . translate('select') . "</option>";
        foreach ($chemicallist as $chemical) {
            $selected = (($chemical['id'] == $selected_id) ? 'selected' : '');
            $html .= "<option value='" . $chemical['id'] . "' " . $selected . ">" . $chemical['name'] . " (" . $chemical['code'] . ")</option>";
        }
        echo $html;
    }

    public function chemical_unit_details()
    {
        if (get_permission('chemical_unit', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $query = $this->db->get('chemical_unit');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    public function get_staff_list()
    {
        $role_id = $this->input->post('role_id');
        $selected_id = (isset($_POST['staff_id']) ? $_POST['staff_id'] : 0);
        $this->db->select('staff.id,staff.name,staff.staff_id,login_credential.role');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id AND login_credential.role != "7"', 'inner');
        $this->db->where('login_credential.role', $role_id);
        $this->db->order_by('staff.id', 'ASC');
        $result = $this->db->get()->result_array();
        $html = "<option value=''>" . translate('select') . "</option>";
        foreach ($result as $staff) {
            $selected = ($staff['id'] == $selected_id ? 'selected' : '');
            $html .= "<option value='" . $staff['id'] . "' " . $selected . ">" . $staff['name'] . " (" . $staff['staff_id'] . ")</option>";
        }
        echo $html;
    }

    public function get_payslip_details()
    {
        $id = $this->input->post('id');
        $this->data['payslip'] = $this->ajax_model->getPayslip($id);
        $this->load->view('referral/payslipPrint', $this->data);
    }

    public function get_balance()
    {
        $staff_id = $this->input->post('staff_id');
        $getAmount = $this->db->select('amount')->where('staff_id', $staff_id)->get('staff_balance')->row_array();
        echo translate('current_balance') . " : " . $this->data['global_config']['currency_symbol'] . number_format($getAmount['amount'], 2, '.', '');
    }

    public function get_salary_template_details()
    {
        if (get_permission('salary_template', 'is_view')) {
            $template_id = $this->input->post('id');
            $this->data['allowances'] = $this->ajax_model->get_list('salary_template_details', array('type' => 1, 'salary_template_id' => $template_id));
            $this->data['deductions'] = $this->ajax_model->get_list('salary_template_details', array('type' => 2, 'salary_template_id' => $template_id));
            $this->data['template'] = $this->ajax_model->get_list('salary_template', array('id' => $template_id), true);
            $this->load->view('payroll/qview_salary_templete', $this->data);
        }
    }

    public function getReportTemplate()
    {
        $id = $this->input->post('id');
        $q = $this->db->select('template')->where('id', $id)->get('lab_report_template')->row_array();
        echo $q['template'];
    }

    public function get_leave_category_details()
    {
        if (get_permission('leave_category', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $query = $this->db->get('leave_category');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    public function get_staff_leave_details()
    {
        $this->data['leave_id'] = $this->input->post('id');
        $this->load->view('leave/staff_modalView', $this->data);
    }

    public function getApprovelLeaveDetails()
    {
        if (get_permission('leave_manage', 'is_add')) {
            $this->data['leave_id'] = $this->input->post('id');
            $this->load->view('leave/approvel_modalView', $this->data);
        }
    }

    public function voucher_head_details()
    {
        if (get_permission('voucher_head', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $query = $this->db->get('voucher_head');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    public function get_voucher_head_list()
    {
        $voucher_type = $this->input->post('voucher_type');
        $voucher_head_id = $this->input->post('voucher_head_id');
        $result = $this->db->where('type', $voucher_type)->get('voucher_head')->result_array();
        $html = "<option value=''>" . translate('select') . "</option>";
        foreach ($result as $head) {
            $selected = ($head['id'] == $voucher_head_id ? 'selected' : '');
            $html .= "<option value='" . $head['id'] . "' " . $selected . ">" . $head['name'] . "</option>";
        }
        echo $html;
    }

    public function get_appointment_schedule()
    {
        $appointment_id = $this->input->post('appointment_id');
        $schedule_id = (isset($_POST['schedule_id']) ? $_POST['schedule_id'] : 0);
        $doctor_id = $this->input->post('doctor_id');
        $date = date("Y-m-d", strtotime($this->input->post('appointment_date')));
        $nameOfDay = date('l', strtotime($date));
        $query = $this->db->where(array('doctor_id' => $doctor_id, 'day' => $nameOfDay))->get('schedule');
        if ($query->num_rows() > 0) {
            $count = 1;
            $srow = $query->row_array();
            $per_time = $srow['per_patient_time'];
            $consultation_fees = $srow['consultation_fees'];
            $min = $per_time * 60;
            $start = strtotime($srow['time_start']);
            $end = strtotime($srow['time_end']) - $per_time;
            $html = "<option value=''>" . translate('select') . "</option>";
            for ($i = $start; $i <= $end; $i = $i + $min) {
                $cID = $count++;
                if ($appointment_id != "") {
                    $this->db->where_not_in('id', $appointment_id);
                }

                $this->db->where_in('status', array(1, 2));
                $this->db->where(array('doctor_id' => $doctor_id, 'appointment_date' => $date, 'schedule' => $cID));
                $exist = $this->db->get('appointment')->num_rows();
                if ($exist > 0) {
                    continue;
                }

                $sel = ($cID == $schedule_id ? 'selected' : '');
                $html .= "<option value='" . $cID . "' " . $sel . ">" . date('h:i A', $i) . ' - ' . date('h:i A', $i + $min) . "</option>";
            }
        } else {
            $consultation_fees = '0.00';
            $html = "<option value=''>" . translate('no_schedule_found') . "</option>";
        }
        echo json_encode(['schedule' => $html, 'fees' => $consultation_fees]);
    }

    public function get_appointment_request_details()
    {
        if (get_permission('appointment_request', 'is_view')) {
            $id = $this->input->post('id');
            $arraySchedule = array();
            $this->db->select('appointment.*,staff.name as staff_name');
            $this->db->from('appointment');
            $this->db->join('staff', 'staff.id = appointment.doctor_id', 'left');
            $this->db->where('appointment.id', $id);
            $appointment = $this->db->get()->row_array();
            $nameOfDay = date('l', strtotime($appointment['appointment_date']));
            $query = $this->db->where(array('doctor_id' => $appointment['doctor_id'], 'day' => $nameOfDay))->get('schedule');
            if ($query->num_rows() > 0) {
                $count = 1;
                $srow = $query->row_array();
                $per_time = $srow['per_patient_time'];
                $min = $per_time * 60;
                $start = strtotime($srow['time_start']);
                $end = strtotime($srow['time_end']) - $per_time;
                for ($i = $start; $i <= $end; $i = $i + $min) {
                    $cID = $count++;
                    $arraySchedule[] = array('id' => $cID, 'name' => date('h:i A', $i) . ' - ' . date('h:i A', $i + $min));
                }
            }
            $appointment['ava_schedule'] = $arraySchedule;
            echo json_encode($appointment);
        }
    }
}
