<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Profile.php
 */

class Profile extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('employee_model');
        $this->load->model('patient_model');
    }

    // when user edit his profile
    public function index()
    {
        $userID = get_loggedin_user_id();
        $loggedinID = get_loggedin_id();
        if (loggedin_role_id() == 7) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
                $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
                $this->form_validation->set_rules('username', 'Username', 'trim|required|callback_unique_username');
                if ($this->form_validation->run() !== false) {
                    $data = $this->input->post();
                    $update_data1 = array(
                        'name' => $data["name"],
                        'birthday' => date("Y-m-d", strtotime($data["birthday"])),
                        'sex' => $data["gender"],
                        'marital_status' => $data["marital_status"],
                        'mobileno' => $data["mobile_no"],
                        'email' => $data["email"],
                        'photo' => $this->app_lib->upload_image('patient'),
                        'guardian' => $data["guardian"],
                        'address' => $data["address"],
                        'relationship' => $data["relationship"],
                        'gua_mobileno' => $data["gua_mobileno"],
                    );
                    $update_data2 = array('username' => $data["username"]);

                    // UPDATE ALL INFORMATION IN THE DATABASE
                    $this->db->where('id', $userID);
                    $this->db->update('patient', $update_data1);

                    // UPDATE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
                    $this->db->where('id', $loggedinID);
                    $this->db->update('login_credential', $update_data2);
                    $this->session->set_userdata('logger_photo', $update_data1['photo']);

                    set_alert('success', translate('information_has_been_updated_successfully'));
                    redirect(base_url('profile'));
                }
            }
            $this->data['patient'] = $this->patient_model->get_single_patient($userID);
            $this->data['sub_page'] = 'profile/patient';
        } else {
            if ($this->input->post()) {
                $this->form_validation->set_rules('name', 'Name', 'trim|required');
                $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
                $this->form_validation->set_rules('username', 'Username', 'trim|required|callback_unique_username');
                if ($this->form_validation->run() !== false) {
                    // UPDATE EMPLOYEE ALL INFORMATION IN THE DATABASE
                    $data = $this->input->post();
                    $update_data1 = array(
                        'name' => $data["name"],
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
                    $update_data2 = array('username' => $data["username"]);

                    // update all information in the database by id
                    $this->db->where('id', $userID);
                    $this->db->update('staff', $update_data1);

                    // update login credential information in the database
                    $this->db->where('id', $loggedinID);
                    $this->db->update('login_credential', $update_data2);
                    $this->session->set_userdata('logger_photo', $update_data1['photo']);

                    set_alert('success', translate('information_has_been_updated_successfully'));
                    redirect(base_url('profile'));
                }
            }
            $this->data['staff'] = $this->employee_model->get_single_employee($userID);
            $this->data['sub_page'] = 'profile/employee';
        }
        $this->data['title'] = translate('profile') . " " . translate('edit');
        $this->data['main_menu'] = 'profile';
        $this->load->view('layout/index', $this->data);
    }

    // unique valid username verification is done here
    public function unique_username($username)
    {
        $loggedinID = get_loggedin_id();
        $this->db->where_not_in('id', $loggedinID);
        $this->db->where('username', $username);
        $query = $this->db->get('login_credential');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_username", translate('username_has_already_been_used'));
            return false;
        } else {
            return true;
        }
    }

    // when user change his password
    public function password()
    {
        if ($this->input->post('save')) {
            $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|min_length[4]|callback_check_validate_password');
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[4]|matches[new_password]');
            if ($this->form_validation->run() !== false) {
                $new_password = $this->input->post('new_password');
                $this->db->where('id', get_loggedin_id());
                $this->db->update('login_credential', array('password' => $this->app_lib->pass_hashed($new_password)));
                set_alert('success', translate('password_has_been_changed'));
                redirect(base_url('profile/password'));
            }
        }

        $this->data['sub_page'] = 'profile/password_change';
        $this->data['main_menu'] = 'profile';
        $this->data['title'] = translate('profile');
        $this->load->view('layout/index', $this->data);
    }

    // current password verification is done here
    public function check_validate_password($password)
    {
        $getPassword = $this->db->select('password')
            ->where('id', get_loggedin_id())
            ->get('login_credential')->row()->password;
        $getVerify = $this->app_lib->verify_password($password, $getPassword);
        if ($getVerify) {
            return true;
        } else {
            $this->form_validation->set_message("check_validate_password", translate('current_password_is_invalid'));
            return false;
        }
    }
}
