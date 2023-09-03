<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Schedule_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // schedule save and update function
    public function schedule_save($data)
    {
        $insert_schedule = array(
            'day' => $data['week_day'],
            'doctor_id' => $data['doctor_id'],
            'time_start' => date("H:i", strtotime($data['time_start'])),
            'time_end' => date("H:i", strtotime($data['time_end'])),
            'per_patient_time' => $data['per_patient'],
            'active' => 1,
            'consultation_fees' => $data['consultation_fees'],
        );
        if (isset($data['schedule_id']) && !empty($data['schedule_id'])) {
            $this->db->where('id', $data['schedule_id']);
            $this->db->update('schedule', $insert_schedule);
        } else {
            $this->db->insert('schedule', $insert_schedule);
        }
    }

    // get schedule list function
    public function get_schedule_list()
    {
        $this->db->select('schedule.*,staff.name as doctor_name');
        $this->db->from('schedule');
        $this->db->join('staff', 'staff.id = schedule.doctor_id', 'inner');
        $this->db->order_by('schedule.id', 'ASC');
        return $this->db->get()->result_array();
    }

}
