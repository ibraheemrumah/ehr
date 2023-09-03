<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // get staff attendance report by role and date
    public function get_staff_attendance_list($role_id = '', $date = '')
    {
        $sql = "SELECT staff.id, staff.name as staff_name, staff.staff_id as staffid, staff_attendance.id as atten_id, staff_attendance.date, IFNULL(staff_attendance.status, 0) as att_status, staff_attendance.status, staff_attendance.remark FROM staff LEFT JOIN staff_attendance ON staff_attendance.staff_id = staff.id and staff_attendance.date =" . $this->db->escape($date) . " LEFT JOIN login_credential ON login_credential.user_id = staff.id and login_credential.role != 7 WHERE login_credential.role = " . $this->db->escape($role_id) . " AND login_credential.active = 1 ORDER BY staff.id ASC";
        return $this->db->query($sql)->result_array();
    }

    // get staff list by role
    public function get_staff_list($role_id = '')
    {
        $this->db->select('staff.*,login_credential.role as role_id');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != 7', 'inner');
        $this->db->where('login_credential.role', $role_id);
        $this->db->where('login_credential.active', 1);
        $this->db->order_by('staff.id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    // check attendance by staff id and date
    public function get_attendance_by_date($staff_id = '', $date = '')
    {
        $this->db->select('staff_attendance.*');
        $this->db->from('staff_attendance');
        $this->db->where('staff_id', $staff_id);
        $this->db->where('date', $date);
        $result = $this->db->get()->row_array();
        return $result;
    }
}
