<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Settings.php
 */

class Settings extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    // global settings controller
    public function index()
    {
        // check access permission
        if (!get_permission('global_setting', 'is_view')) {
            access_denied();
        }
        $config = array();
        // general setting update in database
        if ($this->input->post('app_setting')) {
            if (!get_permission('global_setting', 'is_add')) {
                access_denied();
            }
            $csrf = $this->security->get_csrf_token_name();
            foreach ($this->input->post() as $input => $value) {
                if ($input == 'app_setting' || $input == $csrf) {
                    continue;
                }

                $config[$input] = $value;
            }
            $this->db->where('id', 1);
            $this->db->update('global_settings', $config);
            $this->session->set_userdata(array('date_format' => $config['date_format'], 'set_lang' => $config['translation']));
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('settings'));
        }
        // theme setting update in database
        if ($this->input->post('theme')) {
            if (!get_permission('global_setting', 'is_add')) {
                access_denied();
            }
            $csrf = $this->security->get_csrf_token_name();
            foreach ($this->input->post() as $input => $value) {
                if ($input == 'theme' || $input == $csrf) {
                    continue;
                }

                $config[$input] = $value;
            }
            $this->db->where('id', 1);
            $this->db->update('theme_settings', $config);
            set_alert('success', translate('the_configuration_has_been_updated'));
            $this->session->set_flashdata('active', 2);
            redirect(base_url('settings'));
        }
        // logo setting update in database
        if ($this->input->post('logo')) {
            if (!get_permission('global_setting', 'is_add')) {
                access_denied();
            }
            move_uploaded_file($_FILES['logo_file']['tmp_name'], 'uploads/app_image/logo.png');
            move_uploaded_file($_FILES['text_logo']['tmp_name'], 'uploads/app_image/logo-small.png');
            move_uploaded_file($_FILES['print_file']['tmp_name'], 'uploads/app_image/printing-logo.png');
            move_uploaded_file($_FILES['slider_1']['tmp_name'], 'uploads/login_image/slider_1.jpg');
            move_uploaded_file($_FILES['slider_2']['tmp_name'], 'uploads/login_image/slider_2.jpg');
            move_uploaded_file($_FILES['slider_3']['tmp_name'], 'uploads/login_image/slider_3.jpg');
            set_alert('success', translate('the_configuration_has_been_updated'));
            $this->session->set_flashdata('active', 3);
            redirect(base_url('settings'));
        }

        $this->data['title'] = translate('settings');
        $this->data['sub_page'] = 'setting/index';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }
}
