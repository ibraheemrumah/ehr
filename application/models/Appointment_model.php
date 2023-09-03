<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Appointment_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // appointment data save and update function
    public function appointment_save($data)
    {
        $insert_appointment = array(
            'appointment_id' => $this->app_lib->getAppointmentNo(),
            'doctor_id' => $data['doctor_id'],
            'patient_id' => $data['patient_id'],
            'consultation_fees' => $data['consultation_fees'],
            'discount' => $data['discount'],
            'schedule' => $data['available_schedule'],
            'remarks' => $data['remarks'],
            'appointment_date' => date("Y-m-d", strtotime($data['appointment_date'])),
            'status' => 1,
        );
        if (isset($data['appointment_id']) && !empty($data['appointment_id'])) {
            $this->db->where('id', $data['appointment_id']);
            $this->db->update('appointment', $insert_appointment);
        } else {
            $this->db->insert('appointment', $insert_appointment);
            return $this->db->insert_id();
        }
    }

    // get appointment list by date range function
    public function get_appointment_list($requset = false, $start = '', $end = '')
    {
        $this->db->select('appointment.*,staff.name as doctor_name,patient.name as patient_name');
        $this->db->from('appointment');
        $this->db->join('staff', 'staff.id = appointment.doctor_id', 'left');
        $this->db->join('patient', 'patient.id = appointment.patient_id', 'left');
        if ($requset == false) {
            $this->db->where_not_in('appointment.status', array(1, 2));
        } else {
            $this->db->where_not_in('appointment.status', array(3, 4));
        }
        if (!empty($start) && !empty($end)) {
            $this->db->where('appointment.appointment_date >=', $start);
            $this->db->where('appointment.appointment_date <=', $end);
        }
        $this->db->order_by('appointment.id', 'DESC');
        return $this->db->get()->result_array();
    }

    // get schedule details function
    public function get_schedule_details($doctor_id, $date, $serial)
    {
        $nameOfDay = date('l', strtotime($date));
        $srow = $this->db->where(array('doctor_id' => $doctor_id, 'day' => $nameOfDay))->get('schedule')->row_array();
        $per_time = $srow['per_patient_time'];
        $min = $per_time * 60;
        $start = strtotime($srow['time_start']);
        $end = strtotime($srow['time_end']);
        $count = 1;
        for ($i = $start; $i <= $end; $i = $i + $min) {
            $cID = $count++;
            if ($cID == $serial) {
                return date('h:i A', $i) . ' - ' . date('h:i A', $i + $min);
            }
        }
    }

    public function get_my_appointment_list()
    {
        $this->db->select('appointment.*,staff.name as doctor_name,patient.name as patient_name');
        $this->db->from('appointment');
        $this->db->join('staff', 'staff.id = appointment.doctor_id', 'left');
        $this->db->join('patient', 'patient.id = appointment.patient_id', 'left');
        $this->db->where('appointment.patient_id', get_loggedin_user_id());
        $this->db->order_by('appointment.id', 'ASC');
        return $this->db->get()->result_array();
    }
}
