<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package             : Ramom school management system
 * @version            : 1.0
 * @developed by     : Sublimecode
 * @support             : support@sublimecodes.com
 * @author url        : http://codecanyon.net/user/sublimecode
 * @filename            : Accounting.php
 * @copyright        : Reserved Sublimecodes Team
 */

class Smssettings extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        if (!get_permission('sms_setting', 'is_view')) {
            access_denied();
        }
        if ($this->input->post()) {
            if (!get_permission('sms_setting', 'is_add')) {
                access_denied();
            }
        }

        if ($this->input->post('save') == 'gateway') {
            $arrayGateway = array(
                'active_gateway' => $this->input->post('sms_gateway'),
            );

            $this->db->where('id', 1);
            $this->db->update('sms_config', $arrayGateway);
            $this->session->set_flashdata('active_box', 1);
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('smssettings'));
        }

        if ($this->input->post('save') == 'clickatell') {
            $arrayClickatell = array(
                'clickatell_username' => $this->input->post('clickatell_username'),
                'clickatell_password' => $this->input->post('clickatell_password'),
                'clickatell_api_key' => $this->input->post('clickatell_api_key'),
                'clickatell_number' => $this->input->post('registered_number'),
            );

            $this->db->where('id', 1);
            $this->db->update('sms_config', $arrayClickatell);
            $this->session->set_flashdata('active_box', 2);
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('smssettings'));
        }

        if ($this->input->post('save') == 'twilio') {
            $arrayTwilio = array(
                'twilio_account_sid' => $this->input->post('account_sid'),
                'twilio_auth_token' => $this->input->post('auth_token'),
                'twilio_number' => $this->input->post('registered_number'),
            );

            $this->db->where('id', 1);
            $this->db->update('sms_config', $arrayTwilio);
            $this->session->set_flashdata('active_box', 3);
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('smssettings'));
        }

        $this->data['title'] = translate('sms') . ' ' . translate('settings');
        $this->data['sms_api'] = $this->db->get_where('sms_config', array('id' => 1))->row();
        $this->data['sub_page'] = 'smssetting/index';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function template($id = '')
    {
        if (isset($_POST['save'])) {
            if (!get_permission('sms_setting', 'is_add')) {
                access_denied();
            }
            $notified = (isset($_POST['notify_enable']) ? 1 : 0);
            $template_id = $this->input->post('template_id');
            $array_template = array(
                'subject' => $this->input->post('subject', true),
                'template_body' => $this->input->post('template_body'),
                'notified' => $notified,
            );
            $this->db->where('id', $template_id);
            $this->db->update('sms_templates', $array_template);
            $this->session->set_flashdata('active_template', $template_id);
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('smssettings/template'));
        }
        $this->data['title'] = translate('settings');
        $this->data['template'] = $this->app_lib->get_table('sms_templates');
        $this->data['sub_page'] = 'smssetting/template';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }
}
