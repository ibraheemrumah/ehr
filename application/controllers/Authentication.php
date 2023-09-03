<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Authentication.php
 */

class Authentication extends Authentication_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // check auth
        if (is_loggedin()) {
            redirect('dashboard');
        }

        if ($_POST) {
            $config = array(
                array(
                    'field' => 'username',
                    'label' => 'Username',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'trim|required',
                ),
            );
            $this->form_validation->set_rules($config);
            if ($this->form_validation->run() !== false) {
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                // username is okey lets check the password now
                $login_credential = $this->authentication_model->login_credential($username, $password);
                if ($login_credential) {
                    if ($login_credential->active) {
                        if ($login_credential->role == 7) {
                            $profile = $this->db->select('name,photo,patient_id as uniqueid')->where('id', $login_credential->user_id)->get('patient')->row();
                        } else {
                            $profile = $this->db->select('name,photo,staff_id as uniqueid')->where('id', $login_credential->user_id)->get('staff')->row();
                        }
                        $sessionData = array(
                            'name' => $profile->name,
                            'uniqueid' => $profile->uniqueid,
                            'logger_photo' => $profile->photo,
                            'loggedin_id' => $login_credential->id,
                            'loggedin_role_id' => $login_credential->role,
                            'loggedin_userid' => $login_credential->user_id,
                            'date_format' => $this->data['global_config']['date_format'],
                            'set_lang' => $this->data['global_config']['translation'],
                            'loggedin' => true,
                        );
                        $this->session->set_userdata($sessionData);
                        $this->db->update('login_credential', array('last_login' => date('Y-m-d H:i:s')), array('id' => $login_credential->id));
                        // is logged in
                        if ($this->session->has_userdata('redirect_url')) {
                            redirect($this->session->userdata('redirect_url'));
                        } else {
                            redirect(base_url('dashboard'));
                        }
                    } else {
                        set_alert('error', translate('inactive_account'));
                        redirect(base_url('authentication'));
                    }
                } else {
                    set_alert('error', translate('username_password_incorrect'));
                    redirect(base_url('authentication'));
                }
            }
        }
        $this->load->view('authentication/login', $this->data);
    }

    // forgot password
    public function forgot()
    {
        if (is_loggedin()) {
            redirect(base_url('dashboard'), 'refresh');
        }

        if ($_POST) {
            $config = array(
                array(
                    'field' => 'username',
                    'label' => 'Username',
                    'rules' => 'trim|required',
                ),
            );
            $this->form_validation->set_rules($config);
            if ($this->form_validation->run() !== false) {
                $username = $this->input->post('username');
                $res = $this->authentication_model->lose_password($username);
                if ($res == true) {
                    $this->session->set_flashdata('reset_res', 'TRUE');
                    redirect(base_url('authentication/forgot'));
                } else {
                    $this->session->set_flashdata('reset_res', 'FALSE');
                    redirect(base_url('authentication/forgot'));
                }
            }
        }
        $this->load->view('authentication/forgot', $this->data);
    }

    // password reset
    public function pwreset()
    {
        if (is_loggedin()) {
            redirect(base_url('dashboard'), 'refresh');
        }

        $key = $this->input->get('key');
        if (!empty($key)) {
            $query = $this->db->get_where('reset_password', array('key' => $key));
            if ($query->num_rows() > 0) {
                if ($this->input->post()) {
                    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|matches[c_password]');
                    $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|min_length[4]');
                    if ($this->form_validation->run() !== false) {
                        $password = $this->app_lib->pass_hashed($this->input->post('password'));
                        $this->db->where('id', $query->row()->login_credential_id);
                        $this->db->update('login_credential', array('password' => $password));
                        $this->db->where('login_credential_id', $query->row()->login_credential_id);
                        $this->db->delete('reset_password');
                        set_alert('success', 'Password Reset Successfully');
                        redirect(base_url('authentication'));
                    }
                }
                $this->load->view('authentication/pwreset', $this->data);
            } else {
                set_alert('error', 'Token Has Expired');
                redirect(base_url('authentication'));
            }
        } else {
            set_alert('error', 'Token Has Expired');
            redirect(base_url('authentication'));
        }
    }

    // session logout
    public function logout()
    {
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('logger_photo');
        $this->session->unset_userdata('loggedin_id');
        $this->session->unset_userdata('loggedin_role_id');
        $this->session->unset_userdata('loggedin_userid');
        $this->session->unset_userdata('date_format');
        $this->session->unset_userdata('set_lang');
        $this->session->unset_userdata('loggedin');
        $this->session->sess_destroy();
        redirect(base_url(), 'refresh');
    }
}
