<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authentication_model extends MY_Model
{
    // checking login credential
    public function login_credential($username, $password)
    {
        $this->db->select('*');
        $this->db->from('login_credential');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $verify_password = $this->app_lib->verify_password($password, $query->row()->password);
            if ($verify_password) {
                return $query->row();
            }
        }
        return false;
    }

    // password forgotten
    public function lose_password($username)
    {
        if (!empty($username)) {
            $this->db->select('*');
            $this->db->from('login_credential');
            $this->db->where('username', $username);
            $this->db->limit(1);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $login_credential = $query->row();
                if ($login_credential->role == 7) {
                    $getUser = $this->get_list('patient', array('id' => $login_credential->user_id), true, 'name,email');
                } else {
                    $getUser = $this->get_list('staff', array('id' => $login_credential->user_id), true, 'name,email');
                }

                $key = hash('sha512', $login_credential->role . $login_credential->username . app_generate_hash());
                $query = $this->db->get_where('reset_password', array('login_credential_id' => $login_credential->id));
                if ($query->num_rows() > 0) {
                    $this->db->where('login_credential_id', $login_credential->id);
                    $this->db->delete('reset_password');
                }

                $arrayReset = array(
                    'key' => $key,
                    'login_credential_id' => $login_credential->id,
                    'username' => $login_credential->username,
                );
                $this->db->insert('reset_password', $arrayReset);

                // send email for forgot password
                $this->load->model('email_model');
                $eTemplate = $this->app_lib->get_table('email_templates', 2, true);
                if ($eTemplate['notified'] == 1) {
                    $message = $eTemplate['template_body'];
                    $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
                    $message = str_replace("{username}", $login_credential->username, $message);
                    $message = str_replace("{name}", $getUser['name'], $message);
                    $message = str_replace("{reset_url}", base_url('authentication/pwreset?key=' . $key), $message);
                    $msgData['recipient'] = $getUser['email'];
                    $msgData['subject'] = $eTemplate['subject'];
                    $msgData['message'] = $message;
                    $this->email_model->send_mail($msgData);
                }
                return true;
            }
        }
        return false;
    }
}
