<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Leave_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_leaves_list($where = '', $single = false)
    {
        $this->db->select('leave_application.*,leave_category.name as type_name,staff.name as staff_name,staff.staff_id as staffid,staff_department.name as department_name,login_credential.role as role_id, roles.name as role');
        $this->db->from('leave_application');
        $this->db->join('leave_category', 'leave_category.id = leave_application.category_id', 'left');
        $this->db->join('staff', 'staff.id = leave_application.staff_id', 'left');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "7"', 'left');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        if ($where != '') {
            $this->db->where($where);
        }
        if ($single == false) {
            $this->db->order_by('leave_application.id', 'DESC');
            return $this->db->get()->result_array();
        } else {
            return $this->db->get()->row_array();
        }
    }
}
