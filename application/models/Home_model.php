<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_doctor_profile($id)
    {
        $this->db->select('staff.*,front_cms_doctor_bio.biography,staff_designation.name as designation_name,staff_department.name as department_name');
        $this->db->from('staff');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->join('front_cms_doctor_bio', 'front_cms_doctor_bio.doctor_id = staff.id', 'left');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != 7', 'inner');
        $this->db->where('login_credential.role', 3);
        $this->db->where('login_credential.user_id', $id);
        return $this->db->get()->row_array();
    }

    public function get_doctor_list($start = '')
    {
        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != 7', 'inner');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->where('login_credential.role', 3);
        $this->db->where('login_credential.active', 1);
        $this->db->order_by('staff.id', 'asc');
        if ($start != '') {
            $this->db->limit(4, $start);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_doctor_departments()
    {
        $this->db->select('staff_department.id as department_id,staff_department.name as department_name');
        $this->db->from('staff_department');
        $this->db->join('staff', 'staff.department = staff_department.id', 'left');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != 7', 'inner');
        $this->db->where('login_credential.role', 3);
        $this->db->where('login_credential.active', 1);
        $this->db->group_by('staff_department.id');
        $this->db->order_by('staff.id', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    // appointment data save and update function
    public function appointment_save_new($data)
    {
        $birthday = new DateTime(date('Y-m-d', strtotime($data['birthday'])));
        $today = new DateTime('today');
        $age = $birthday->diff($today)->y;
        $inser_data1 = array(
            'name' => $data["new_patient_name"],
            'sex' => $data["new_patient_gender"],
            'email' => $data["new_patient_email"],
            'mobileno' => $data["new_patient_phone"],
            'patient_id' => substr(app_generate_hash(), 3, 7),
            'birthday' => date('Y-m-d', strtotime($data['birthday'])),
            'age' => $age,
            'source' => 2,
        );
        // save patient information in the database
        $this->db->insert('patient', $inser_data1);
        $patient_id = $this->db->insert_id();
        // save patient login credential information in the database
        $inser_data2['username'] = $data["new_username"];
        $inser_data2['role'] = 7;
        $inser_data2['active'] = 1;
        $inser_data2['user_id'] = $patient_id;
        $inser_data2['password'] = $this->app_lib->pass_hashed($data["new_password"]);
        $this->db->insert('login_credential', $inser_data2);

        $insert_appointment = array(
            'appointment_id' => $this->app_lib->getAppointmentNo(),
            'doctor_id' => $data['new_doctor_id'],
            'patient_id' => $patient_id,
            'schedule' => $data['new_time_slots'],
            'remarks' => $data['new_message'],
            'appointment_date' => date("Y-m-d", strtotime($data['new_appointment_date'])),
            'status' => 4,
        );
        $this->db->insert('appointment', $insert_appointment);
        $insertID =  $this->db->insert_id();
        // send Email for appointment alert
        $this->email_model->sentAppointmentRequested($insert_appointment);
        // send SMS for appointment alert
        $this->smsmanager_model->sentAppointmentRequested($insert_appointment);
        return $insertID;
    }

    // appointment data save function
    public function appointment_save_old($data)
    {
        $patient_id = $this->db->select('id')->where('patient_id', $data['patient_id'])->get('patient')->row()->id;
        $insert_appointment = array(
            'appointment_id' => $this->app_lib->getAppointmentNo(),
            'doctor_id' => $data['old_doctor_id'],
            'patient_id' => $patient_id,
            'schedule' => $data['old_time_slots'],
            'remarks' => $data['old_message'],
            'appointment_date' => date("Y-m-d", strtotime($data['old_appointment_date'])),
            'status' => 4,
        );
        $this->db->insert('appointment', $insert_appointment);
        $insertID = $this->db->insert_id();
        // send Email for appointment alert
        $this->email_model->sentAppointmentRequested($insert_appointment);
        // send SMS for appointment alert
        $this->smsmanager_model->sentAppointmentRequested($insert_appointment);
        return $insertID;
    }
}
