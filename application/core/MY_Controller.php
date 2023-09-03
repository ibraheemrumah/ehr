<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

	function __construct() {
		parent::__construct();
		
		if ($this->config->item('installed') == FALSE) {
            redirect(site_url('install'));
		}

		$get_config = $this->db->get_where('global_settings', array('id'=>1))->row_array();
		$this->data['global_config'] = $get_config;
		$this->data['main_menu'] = '';
		$this->data['theme_config'] = $this->db->get_where('theme_settings',array('id'=>1))->row_array();
		date_default_timezone_set($get_config['timezone']);
	}
}

class Authentication_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('authentication_model');
    }
}

class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_loggedin()) {
            $this->session->set_userdata('redirect_url', current_url());
            redirect(base_url('authentication'), 'refresh');
        }
    }
}

