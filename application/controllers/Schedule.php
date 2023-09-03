<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Schedule.php
 */

class Schedule extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('schedule_model');
        $this->load->model('email_model');
    }

    // add new doctor schedule
    public function index()
    {
        // check access permission
        if (!get_permission('schedule', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            if (!get_permission('schedule', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('doctor_id', 'doctor', 'trim|required');
            $this->form_validation->set_rules('consultation_fees', 'Consultation Fees', 'trim|required|numeric');
            $this->form_validation->set_rules('week_day', 'Week Day', 'trim|required|callback_unique_weekday');
            $this->form_validation->set_rules('per_patient', 'Per Patient Duration', 'trim|required|numeric');
            if ($this->form_validation->run() !== false) {
                // SAVE INFORMATION IN THE DATABASE
                $data = $this->input->post();
                $this->schedule_model->schedule_save($data);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('schedule'));
            } else {
                $this->data['validation_error'] = 1;
            }
        }
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
            ),
            'js' => array(
                'vendor/bootstrap-timepicker/bootstrap-timepicker.js',
            ),
        );
        $this->data['schedulelist'] = $this->schedule_model->get_schedule_list();
        $this->data['doctorlist'] = $this->app_lib->getDoctorlList();
        $this->data['title'] = translate('schedule');
        $this->data['sub_page'] = 'schedule/index';
        $this->data['main_menu'] = 'schedule';
        $this->load->view('layout/index', $this->data);
    }

    // update existing schedule if passed id
    public function edit($id = '')
    {
        if (!get_permission('schedule', 'is_edit')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            $this->form_validation->set_rules('doctor_id', 'doctor', 'trim|required');
            $this->form_validation->set_rules('consultation_fees', 'Consultation Fees', 'trim|required|numeric');
            $this->form_validation->set_rules('week_day', 'Week Day', 'trim|required|callback_unique_weekday');
            $this->form_validation->set_rules('per_patient', 'Per Patient Duration', 'trim|required|numeric');
            if ($this->form_validation->run() !== false) {
                $data = $this->input->post();
                $this->schedule_model->schedule_save($data);
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('schedule'));
            }
        }
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
            ),
            'js' => array(
                'vendor/bootstrap-timepicker/bootstrap-timepicker.js',
            ),
        );
        $this->data['schedule'] = $this->schedule_model->get_list('schedule', array('id' => $id), true);
        $this->data['doctorlist'] = $this->app_lib->getDoctorlList();
        $this->data['title'] = translate('schedule');
        $this->data['sub_page'] = 'schedule/edit';
        $this->data['main_menu'] = 'schedule';
        $this->load->view('layout/index', $this->data);
    }

    // delete schedule from database
    public function delete($id = '')
    {
        if (!get_permission('schedule', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('schedule');
    }

    // duplicate value check in database
    public function unique_weekday($day)
    {
        $doctor_id = $this->input->post('doctor_id');
        if ($this->input->post('schedule_id')) {
            $schedule_id = $this->input->post('schedule_id');
            $this->db->where_not_in('id', $schedule_id);
        }
        $this->db->where('day', $day);
        $this->db->where('doctor_id', $doctor_id);
        $query = $this->db->get('schedule');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_weekday", "This week day schedule already exists.");
            return false;
        } else {
            return true;
        }
    }
}
