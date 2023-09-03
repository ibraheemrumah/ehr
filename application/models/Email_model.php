<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sentRegisteredAccount($data)
    {
        $emailTemplate = $this->app_lib->get_table('email_templates', 1, true);
        if ($emailTemplate['notified'] == 1) {
            $role_name = get_type_name_by_id('roles', $data['user_role']);
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{name}", $data['name'], $message);
            $message = str_replace("{username}", $data['username'], $message);
            $message = str_replace("{password}", $data['password'], $message);
            $message = str_replace("{user_role}", $role_name, $message);
            $message = str_replace("{login_url}", base_url(), $message);
            $this->sendMail($data['email'], $emailTemplate['subject'], $message);
        }
    }

    public function sentLeave($data, $template_id)
    {
        $emailTemplate = $this->app_lib->get_table('email_templates', $template_id, true);
        if ($emailTemplate['notified'] == 1) {
            $getStaff = $this->db->select('name,email')->where('id', $data['staff_id'])->get('staff')->row_array();
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{name}", $getStaff['name'], $message);
            $message = str_replace("{admin_comments}", $data['comments'], $message);
            $message = str_replace("{start_date}", _d($data['start_date']), $message);
            $message = str_replace("{end_date}", _d($data['end_date']), $message);
            $this->sendMail($getStaff['email'], $emailTemplate['subject'], $message);
        }
    }

    public function sentAppointment($appointment_id, $schedule_id, $template_id)
    {
        $emailTemplate = $this->app_lib->get_table('email_templates', $template_id, true);
        if ($emailTemplate['notified'] == 1) {
            $getAppointment = $this->db->select('*')->where('id', $appointment_id)->get('appointment')->row_array();
            $patient = $this->db->select('name,email')->where('id', $getAppointment['patient_id'])->get('patient')->row_array();
            $schedule_time = $this->misc_model->get_schedule_details($getAppointment['doctor_id'], $getAppointment['appointment_date'], $schedule_id);
            $message = $emailTemplate['template_body'];
            $message = str_replace('{institute_name}', get_global_setting('institute_name'), $message);
            $message = str_replace('{patient_name}', $patient['name'], $message);
            $message = str_replace('{doctor_name}', get_type_name_by_id('staff', $getAppointment['doctor_id']), $message);
            $message = str_replace('{appointment_id}', $getAppointment['appointment_id'], $message);
            $message = str_replace('{schedule_time}', $schedule_time, $message);
            $message = str_replace('{appointment_date}', _d($getAppointment['appointment_date']), $message);
            $this->sendMail($patient['email'], $emailTemplate['subject'], $message);
        }
    }

    public function sentAppointmentRequested($data)
    {
        $emailTemplate = $this->app_lib->get_table('email_templates', 11, true);
        if ($emailTemplate['notified'] == 1) {
            $patient = $this->db->select('name,email')->where('id', $data['patient_id'])->get('patient')->row_array();
            $schedule_time = $this->misc_model->get_schedule_details($data['doctor_id'], $data['appointment_date'], $data['schedule']);
            $message = $emailTemplate['template_body'];
            $message = str_replace('{institute_name}', get_global_setting('institute_name'), $message);
            $message = str_replace('{patient_name}', $patient['name'], $message);
            $message = str_replace('{doctor_name}', get_type_name_by_id('staff', $data['doctor_id']), $message);
            $message = str_replace('{appointment_id}', $data['appointment_id'], $message);
            $message = str_replace('{schedule_time}', $schedule_time, $message);
            $message = str_replace('{appointment_date}', _d($data['appointment_date']), $message);
            $this->sendMail($patient['email'], $emailTemplate['subject'], $message);
        }
    }

    public function sentAbsentNotice($data)
    {
        $emailTemplate = $this->app_lib->get_table('email_templates', 6, true);
        if ($emailTemplate['notified'] == 1) {
            $getStaff = $this->db->select('name,email')->where('id', $data['staff_id'])->get('staff')->row_array();
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{name}", $getStaff['name'], $message);
            $message = str_replace("{date}", _d($data['date']), $message);
            $this->sendMail($getStaff['email'], $emailTemplate['subject'], $message);
        }
    }

    public function sentPayslipGenerated($data)
    {
        $emailTemplate = $this->app_lib->get_table('email_templates', 5, true);
        if ($emailTemplate['notified'] == 1) {
            $getStaff = $this->db->select('name,email')->where('id', $data['staff_id'])->get('staff')->row_array();
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{name}", $getStaff['name'], $message);
            $message = str_replace("{month_year}", date('F', $data['monthyear']), $message);
            $message = str_replace("{payslip_no}", $data['bill_no'], $message);
            $message = str_replace("{payslip_url}", $data['payslip_url'], $message);
            $this->sendMail($getStaff['email'], $emailTemplate['subject'], $message);
        }
    }

    public function sendMail($recipient, $subject, $message)
    {
        $emailsetting = $this->db->get_where('email_config', array('id' => 1))->row();
        if ($emailsetting->email_protocol == 'smtp') {
            $config = array(
                'smtp_host' => $emailsetting->smtp_host,
                'smtp_port' => $emailsetting->smtp_port,
                'smtp_user' => $emailsetting->smtp_user,
                'smtp_pass' => $emailsetting->smtp_pass,
                'smtp_crypto' => $emailsetting->smtp_encryption,
            );
        }
        $config['protocol'] = $emailsetting->email_protocol;
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['charset'] = 'utf-8';
        $this->load->library('email', $config);
        $this->email->from($emailsetting->email, get_global_setting('institute_name'));
        $this->email->to($recipient);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
    }
}
