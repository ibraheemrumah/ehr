<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Appointment.php
 */

class Appointment extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('email_model');
        $this->load->model('smsmanager_model');
    }

    public function index()
    {
        // check access permission
        if (!get_permission('appointment', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['appointmentlist'] = $this->appointment_model->get_appointment_list(true, $start, $end);
        } else {
            $this->data['appointmentlist'] = $this->appointment_model->get_appointment_list(true);
        }
        $this->data['title'] = translate('appointment');
        $this->data['sub_page'] = 'appointment/index';
        $this->data['main_menu'] = 'appointment';
        $this->load->view('layout/index', $this->data);
    }

    // this function is used to add data
    public function add()
    {
        // check access permission
        if (!get_permission('appointment', 'is_add')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            // save data into table
            $data = $this->input->post();
            $appointment_id = $this->appointment_model->appointment_save($data);
            // send email for appointment alert
            $this->email_model->sentAppointment($appointment_id, $data['available_schedule'], 9);
            // send SMS for appointment alert
            $this->smsmanager_model->sentAppointment($appointment_id, $data['available_schedule'], 2);

            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('appointment/add'));
        }
        $this->data['doctorlist'] = $this->app_lib->getDoctorlList();
        $this->data['patientlist'] = $this->app_lib->getPatientList();
        $this->data['title'] = translate('appointment');
        $this->data['sub_page'] = 'appointment/add';
        $this->data['main_menu'] = 'appointment';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        // check access permission
        if (!get_permission('appointment', 'is_edit')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            $data = $this->input->post();
            $this->appointment_model->appointment_save($data);
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('appointment'));
        }
        $this->data['appointment'] = $this->appointment_model->get_list('appointment', array('id' => $id), true);
        $this->data['doctorlist'] = $this->app_lib->getDoctorlList();
        $this->data['patientlist'] = $this->app_lib->getPatientList();
        $this->data['title'] = translate('appointment');
        $this->data['sub_page'] = 'appointment/edit';
        $this->data['main_menu'] = 'appointment';
        $this->load->view('layout/index', $this->data);
    }

    // delete into appointment table by appointment id
    public function delete($id = '')
    {
        // check access permission
        if (!get_permission('appointment', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('appointment');
    }

    // appointment make closed
    public function schedule_closed($id = '')
    {
        if (!get_permission('appointment', 'is_edit')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->update('appointment', array('status' => 2));
    }

    // patient appointment requested list
    public function requested_list()
    {
        if (!get_permission('appointment_request', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['appointmentlist'] = $this->appointment_model->get_appointment_list(false, $start, $end);
        } else {
            $this->data['appointmentlist'] = $this->appointment_model->get_appointment_list(false);
        }
        $this->data['title'] = translate('appointment');
        $this->data['sub_page'] = 'appointment/requested_list';
        $this->data['main_menu'] = 'appointment';
        $this->load->view('layout/index', $this->data);
    }

    // update patient appointment status by ajax
    public function approvedRequested()
    {
        if (get_permission('appointment_request', 'is_edit')) {
            $appointment_status = $this->input->post('appointment_status');
            if ($appointment_status == 1) {
                $this->form_validation->set_rules('schedule_time', 'Request Schedule', 'trim|required|callback_check_schedule');
            }
            $this->form_validation->set_rules('appointment_status', 'Status', 'trim|required');
            if ($this->form_validation->run() == false) {
                $msg = array(
                    'schedule_error' => form_error('schedule_time'),
                    'status_error' => form_error('appointment_status'),
                );
                $array = array('status' => 'fail', 'error' => $msg);
            } else {
                $appointment_id = $this->input->post('appointment_id');
                $schedule_time = $this->input->post('schedule_time');
                $this->db->where('id', $appointment_id);
                $this->db->update('appointment', array('schedule' => $schedule_time, 'status' => $appointment_status));

                if ($appointment_status == 1) {
                    // send email for patient alert
                    $this->email_model->sentAppointment($appointment_id, $schedule_time, 9);
                    // send SMS for appointment alert
                    $this->smsmanager_model->sentAppointment($appointment_id, $schedule_time, 2);
                }elseif ($appointment_status == 3) {
                    // send email for patient alert
                    $this->email_model->sentAppointment($appointment_id, $schedule_time, 10);
                    // send SMS for appointment alert
                    $this->smsmanager_model->sentAppointment($appointment_id, $schedule_time, 3);
                }

                set_alert('success', translate('information_has_been_updated_successfully'));
                $array = array('status' => 'success', 'error' => '');
            }

            echo json_encode($array);
        }
    }

    // delete appointment request
    public function request_delete($id = '')
    {
        if (!get_permission('appointment_request', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('appointment');
    }

    // check patient schedule verification here
    public function check_schedule($schedule_id)
    {
        $doctor_id = $this->input->post('doctor_id');
        $appointment_date = $this->input->post('appointment_date');
        $this->db->where_in('status', array(1, 2));
        $this->db->where(array('doctor_id' => $doctor_id, 'appointment_date' => $appointment_date, 'schedule' => $schedule_id));
        $exist = $this->db->get('appointment')->num_rows();
        if ($exist > 0) {
            $this->form_validation->set_message("check_schedule", "The Schedule Already Allocated For The Patient.");
            return false;
        } else {
            return true;
        }
    }

    public function my_list()
    {
        if (loggedin_role_id() != 7) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            $this->form_validation->set_rules('doctor_id', 'doctor', 'trim|required|xss_clean');
            $this->form_validation->set_rules('appointment_date', 'Appointment Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('available_schedule', 'Available Schedule', 'trim|required|xss_clean');
            $this->form_validation->set_rules('remarks', 'Remarks', 'xss_clean|required');
            if ($this->form_validation->run() == false) {
                $this->data['active_request'] = 1;
            }else{
                $insertAppointment = array(
                    'appointment_id' => $this->app_lib->getAppointmentNo(),
                    'doctor_id' => $this->input->post('doctor_id'),
                    'patient_id' => get_loggedin_user_id(),
                    'schedule' => $this->input->post('available_schedule'),
                    'remarks' => $this->input->post('remarks'),
                    'appointment_date' => date("Y-m-d", strtotime($this->input->post('appointment_date'))),
                    'status' => 4,
                );
                $this->db->insert('appointment', $insertAppointment);
                // send Email for appointment alert
                $this->email_model->sentAppointmentRequested($insertAppointment);
                // send SMS for appointment alert
                $this->smsmanager_model->sentAppointmentRequested($insertAppointment);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('appointment/my_list'));
            }
        }
        $this->data['appointmentlist'] = $this->appointment_model->get_my_appointment_list();
        $this->data['doctorlist'] = $this->app_lib->getDoctorlList();
        $this->data['title'] = translate('appointment');
        $this->data['sub_page'] = 'appointment/my_list';
        $this->data['main_menu'] = 'appointment';
        $this->load->view('layout/index', $this->data);
    }

    public function my_request_delete($id = '')
    {
        if (loggedin_role_id() != 7) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->where('patient_id', get_loggedin_user_id());
        $this->db->delete('appointment');
    }
}
