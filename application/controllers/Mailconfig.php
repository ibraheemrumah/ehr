<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Mailconfig.php
 */

class Mailconfig extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    // email congig settings update
    public function index()
    {
        // check access permission
        if (!get_permission('email_setting', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            if (!get_permission('email_setting', 'is_edit')) {
                access_denied();
            }

            $data = array();
            foreach ($this->input->post() as $key => $value) {
                if ($key == 'save') {
                    continue;
                }

                $data[$key] = $value;
            }
            $this->db->where('id', 1);
            $this->db->update('email_config', $data);
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('mailconfig'));
        }

        $this->data['config'] = $this->app_lib->get_table('email_config', 1, true);
        $this->data['title'] = translate('settings');
        $this->data['sub_page'] = 'mailconfig/index';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    // email template update in db
    public function template($id = '')
    {
        if (!get_permission('email_setting', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            if (!get_permission('email_setting', 'is_edit')) {
                access_denied();
            }
            $notified = (isset($_POST['notify_enable']) ? 1 : 0);
            $template_id = $this->input->post('template_id');
            $array_template = array(
                'subject' => $this->input->post('subject'),
                'template_body' => $this->input->post('template_body'),
                'notified' => $notified,
            );
            $this->db->where('id', $template_id);
            $this->db->update('email_templates', $array_template);
            $this->session->set_flashdata('emailt_active', $template_id);
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('mailconfig/template'));
        }

        $this->data['headerelements'] = array(
            'js' => array(
                'vendor/ckeditor/ckeditor.js',
                'vendor/ckeditor/adapters/jquery.js',
            ),
        );
        $this->data['title'] = translate('settings');
        $this->data['template'] = $this->app_lib->get_table('email_templates');
        $this->data['sub_page'] = 'mailconfig/template';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }
}
