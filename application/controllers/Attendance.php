<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Attendance.php
 */

class Attendance extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('attendance_model');
        $this->load->model('email_model');
    }

    public function index()
    {
        // check access permission
        if (!get_permission('staff_attendance', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $staff_role = $this->input->post('staff_role');
            $timestamp = $this->input->post('timestamp');
            $this->data['month'] = date('m', strtotime($timestamp));
            $this->data['year'] = date('Y', strtotime($timestamp));
            $this->data['days'] = cal_days_in_month(CAL_GREGORIAN, $this->data['month'], $this->data['year']);
            $this->data['stafflist'] = $this->attendance_model->get_staff_list($staff_role);
        }
        $this->data['title'] = translate('attendance');
        $this->data['sub_page'] = 'attendance/index';
        $this->data['main_menu'] = 'attendance';
        $this->load->view('layout/index', $this->data);
    }

    // staff attendance save
    public function add()
    {
        // check access permission
        if (!get_permission('staff_attendance', 'is_add')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            // validate inputs
            $this->form_validation->set_rules('staff_role', 'Staff Role', 'trim|required');
            $this->form_validation->set_rules('date', 'Date', 'trim|required|callback_get_valid_date');
            if ($this->form_validation->run() !== false) {
                $staff_role = $this->input->post('staff_role');
                $date = $this->input->post('date');
                $this->data['date'] = $date;
                // staff attendance save in DB
                $this->data['stafflist'] = $this->attendance_model->get_staff_attendance_list($staff_role, $date);
            }
        }

        if (isset($_POST['attensave'])) {
            // SAVE DATA INTO TABLE
            $attendance = $this->input->post('attendance');
            $date = $this->input->post('date');
            foreach ($attendance as $key => $value) {
                $arrayData = array(
                    'staff_id' => $value['staff_id'],
                    'status' => $value['status'],
                    'remark' => $value['remark'],
                    'date' => $date,
                );
                if (empty($value['old_atten_id'])) {
                    $this->db->insert('staff_attendance', $arrayData);
                } else {
                    $this->db->where('id', $value['old_atten_id']);
                    $this->db->update('staff_attendance', $arrayData);
                }

                // send email for absent notice
                if ($arrayData['status'] == 'A') {
                    $eTemplate = $this->app_lib->get_table('email_templates', 6, true);
                    if ($eTemplate['notified'] == 1) {
                        $getStaff = $this->db->select('name,email')->where('id', $arrayData['staff_id'])->get('staff')->row_array();
                        $message = $eTemplate['template_body'];
                        $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
                        $message = str_replace("{name}", $getStaff['name'], $message);
                        $message = str_replace("{date}", _d($arrayData['date']), $message);
                        $msgData['recipient'] = $getStaff['email'];
                        $msgData['subject'] = $eTemplate['subject'];
                        $msgData['message'] = $message;
                        $this->email_model->send_mail($msgData);
                    }
                }
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('attendance/add'));
        }

        $this->data['title'] = translate('attendance');
        $this->data['sub_page'] = 'attendance/add';
        $this->data['main_menu'] = 'attendance';
        $this->load->view('layout/index', $this->data);
    }

    public function get_valid_date($date)
    {
        $present_date = date('Y-m-d');
        $date = date("Y-m-d", strtotime($date));
        if ($date > $present_date) {
            $this->form_validation->set_message("get_valid_date", "Please Enter Correct Date.");
            return false;
        } else {
            return true;
        }
    }
}
