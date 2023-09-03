<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Smsmanager_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sentRegisteredAccount($data)
    {
        $smsTemplate = $this->app_lib->get_table('sms_templates', 1, true);
        if ($smsTemplate['notified'] == 1) {
            $message = $smsTemplate['template_body'];
            $message = str_replace('{institute_name}', get_global_setting('institute_name'), $message);
            $message = str_replace('{name}', $data['name'], $message);
            $message = str_replace('{username}', $data['username'], $message);
            $message = str_replace('{password}', $data['password'], $message);
            $message = str_replace('{user_role}', 'Patient', $message);
            $message = str_replace('{login_url}', base_url(), $message);
            $recipient = $data['mobile_no'];
            $this->sendSMS($recipient, $message);
        }
    }

    public function sentAppointment($appointment_id, $schedule_id, $template_id)
    {
        $smsTemplate = $this->app_lib->get_table('sms_templates', $template_id, true);
        if ($smsTemplate['notified'] == 1) {
            $getAppointment = $this->db->select('*')->where('id', $appointment_id)->get('appointment')->row_array();
            $schedule_time = $this->misc_model->get_schedule_details($getAppointment['doctor_id'], $getAppointment['appointment_date'], $schedule_id);
            $patient = $this->db->select('name,mobileno')->where('id', $getAppointment['patient_id'])->get('patient')->row();
            $message = $smsTemplate['template_body'];
            $message = str_replace('{institute_name}', get_global_setting('institute_name'), $message);
            $message = str_replace('{patient_name}', $patient->name, $message);
            $message = str_replace('{doctor_name}', get_type_name_by_id('staff', $getAppointment['doctor_id']), $message);
            $message = str_replace('{appointment_id}', $getAppointment['appointment_id'], $message);
            $message = str_replace('{schedule_time}', $schedule_time, $message);
            $message = str_replace('{appointment_date}', _d($getAppointment['appointment_date']), $message);
            $recipient = $patient->mobileno;
            $this->sendSMS($recipient, $message);
        }
    }

    public function sentAppointmentRequested($data)
    {
        $smsTemplate = $this->app_lib->get_table('sms_templates', 4, true);
        if ($smsTemplate['notified'] == 1) {
            $patient = $this->db->select('name,mobileno')->where('id', $data['patient_id'])->get('patient')->row();
            $schedule_time = $this->misc_model->get_schedule_details($data['doctor_id'], $data['appointment_date'], $data['schedule']);
            $message = $smsTemplate['template_body'];
            $message = str_replace('{institute_name}', get_global_setting('institute_name'), $message);
            $message = str_replace('{patient_name}', $patient->name, $message);
            $message = str_replace('{doctor_name}', get_type_name_by_id('staff', $data['doctor_id']), $message);
            $message = str_replace('{appointment_id}', $data['appointment_id'], $message);
            $message = str_replace('{schedule_time}', $schedule_time, $message);
            $message = str_replace('{appointment_date}', _d($data['appointment_date']), $message);
            $recipient = $patient->mobileno;
            $this->sendSMS($recipient, $message);
        }
    }

    public function sendSMS($number, $message)
    {
        $gateway = $this->db->get_where('sms_config', array('id' => 1))->row();
        if ($gateway->active_gateway == 'clickatell') {
            $this->load->library('clickatell');
            $this->clickatell->send_message($number, $message);
            return true;
        } else if ($gateway->active_gateway == 'twilio') {
            $this->load->library('twilio');
            $response = $this->twilio->sms($gateway->twilio_number, $number, $message);
            if ($response->IsError) {
                return true;
            } else {
                return true;
            }
        }
    }
}
