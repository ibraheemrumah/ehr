<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employee_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // MODERATOR EMPLOYEE ALL INFORMATION
    public function save($data)
    {
        $inser_data1 = array(
            'name' => $data["name"],
            'department' => $data["department_id"],
            'qualification' => $data["qualification"],
            'designation' => $data["designation_id"],
            'joining_date' => date("Y-m-d", strtotime($data["joining_date"])),
            'birthday' => date("Y-m-d", strtotime($data["birthday"])),
            'gender' => $data["gender"],
            'religion' => $data["religion"],
            'blood_group' => $data["blood_group"],
            'marital_status' => $data["marital_status"],
            'address' => $data["address"],
            'mobileno' => $data["mobile_no"],
            'email' => $data["email"],
            'photo' => $this->app_lib->upload_image('staff'),
            'facebook_url' => $data["facebook"],
            'linkedin_url' => $data["twitter"],
            'twitter_url' => $data["linkedin"],
        );

        $inser_data2 = array(
            'username' => $data["username"],
            'role' => $data["user_role"],
        );

        if (!isset($data['staff_id']) && empty($data['staff_id'])) {
            $inser_data1['staff_id'] = substr(app_generate_hash(), 3, 7);
            // SAVE EMPLOYEE INFORMATION IN THE DATABASE
            $this->db->insert('staff', $inser_data1);
            $staff_id = $this->db->insert_id();

            // SAVE EMPLOYEE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
            $inser_data2['active'] = 1;
            $inser_data2['user_id'] = $staff_id;
            $inser_data2['password'] = $this->app_lib->pass_hashed($data["password"]);
            $this->db->insert('login_credential', $inser_data2);

            $inser_data3 = array(
                'staff_id' => $staff_id,
                'amount' => 0,
            );
            $this->db->insert('staff_balance', $inser_data3);

            // save user bank information in the database
            if (!isset($data["cbbank_skip"])) {
                $inser_data4 = array(
                    'staff_id' => $staff_id,
                    'bank_name' => $data["bank_name"],
                    'holder_name' => $data["holder_name"],
                    'bank_branch' => $data["bank_branch"],
                    'bank_address' => $data["bank_address"],
                    'ifsc_code' => $data["ifsc_code"],
                    'account_no' => $data["account_no"],
                );
                $this->db->insert('staff_bank_account', $inser_data4);
            }
            return $staff_id;
        } else {
            // UPDATE ALL INFORMATION IN THE DATABASE
            $this->db->where('id', $data['staff_id']);
            $this->db->update('staff', $inser_data1);

            // UPDATE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
            $this->db->where('user_id', $data['staff_id']);
            $this->db->where('role !=', 7);
            $this->db->update('login_credential', $inser_data2);
        }
    }

    // GET STAFF ALL DETAILS
    public function get_employee_list($role_id, $active = 1)
    {
        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id, roles.name as role');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "7"', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->where('login_credential.role', $role_id);
        $this->db->where('login_credential.active', $active);
        $this->db->order_by('staff.id', 'ASC');
        return $this->db->get()->result();
    }

    // GET SINGLE EMPLOYEE DETAILS
    public function get_single_employee($id = null)
    {
        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id,login_credential.active,login_credential.username, roles.name as role');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "7"', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->where('staff.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
}
