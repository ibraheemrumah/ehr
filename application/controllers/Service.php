<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Service extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('test_model');
    }

    // add new test in database
    public function index()
    {
        // check access permission
        if (!get_permission('lab_test', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            if (!get_permission('lab_test', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('category_id', 'Test Category', 'trim|required');
            $this->form_validation->set_rules('test_name', 'Test Name', 'trim|required|callback_unique_testname');
            $this->form_validation->set_rules('test_code', 'Test Code', 'trim|required');
            $this->form_validation->set_rules('production_cost', 'Production Cost', 'trim|required|numeric');
            $this->form_validation->set_rules('patient_price', 'Patient Price', 'trim|required|numeric');
            $this->form_validation->set_rules('date', 'Date', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $this->test_model->save_test($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('service'));
            } else {
                $this->data['validation_error'] = 1;
            }
        }
        $this->data['testlist'] = $this->test_model->get_test_list();
        $this->data['title'] = translate('service') . " " . translate('management');
        $this->data['sub_page'] = 'test/test';
        $this->data['main_menu'] = 'test';
        $this->load->view('layout/index', $this->data);
    }

    

    // update existing test in database
    public function edit($id)
    {
        // check access permission
        if (!get_permission('lab_test', 'is_edit')) {
            access_denied();
        }
        if (isset($_POST['update'])) {
            $this->form_validation->set_rules('category_id', 'Test Category', 'trim|required');
            $this->form_validation->set_rules('test_name', 'Test Name', 'trim|required|callback_unique_testname');
            $this->form_validation->set_rules('test_code', 'Test Code', 'trim|required');
            $this->form_validation->set_rules('production_cost', 'Production Cost', 'trim|required|numeric');
            $this->form_validation->set_rules('patient_price', 'Patient Price', 'trim|required|numeric');
            $this->form_validation->set_rules('date', 'Date', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $this->test_model->save_test($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('service'));
            } else {
                $this->data['validation_error'] = 1;
            }
        }
        $this->data['test'] = $this->test_model->get_list('lab_test', array('id' => $id), true);
        $this->data['title'] = translate('test') . " " . translate('manage');
        $this->data['sub_page'] = 'test/test_edit';
        $this->data['main_menu'] = 'test';
        $this->load->view('layout/index', $this->data);
    }

    // delete test from database
    public function delete($id)
    {
        // check access permission
        if (!get_permission('lab_test', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('lab_test');
    }

    // add new test category in db
    public function category()
    {
        if (isset($_POST['category'])) {
            if (!get_permission('test_category', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|callback_unique_category');
            if ($this->form_validation->run() !== false) {
                $this->db->insert('lab_test_category', array('name' => $this->input->post('category_name')));
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('service/category'));
            }
        }
        $this->data['productlist'] = $this->test_model->get_list('lab_test_category');
        $this->data['title'] = translate('test') . " " . translate('manage');
        $this->data['sub_page'] = 'test/category';
        $this->data['main_menu'] = 'test';
        $this->load->view('layout/index', $this->data);
    }

    // update existing test category in db
    public function category_edit()
    {
        if (!get_permission('test_category', 'is_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|callback_unique_category');
        if ($this->form_validation->run() !== false) {
            $category_id = $this->input->post('category_id');
            $this->db->where('id', $category_id);
            $this->db->update('lab_test_category', array('name' => $this->input->post('category_name')));
            set_alert('success', translate('information_has_been_updated_successfully'));
        }
        redirect(base_url('service/category'));
    }

    // delete test category from database
    public function category_delete($id)
    {
        // check access permission
        if (!get_permission('test_category', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('lab_test_category');
    }

    // duplicate test name check in database
    public function unique_testname($name)
    {
        $test_id = $this->input->post('test_id');
        if (!empty($test_id)) {
            $this->db->where_not_in('id', $test_id);
        }

        $this->db->where('name', $name);
        $query = $this->db->get('lab_test');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_testname", "The %s are already used.");
            return false;
        } else {
            return true;
        }
    }

    // duplicate value check in db
    public function unique_category($name)
    {
        $category_id = $this->input->post('category_id');
        if (!empty($category_id)) {
            $this->db->where_not_in('id', $category_id);
        }

        $this->db->where('name', $name);
        $query = $this->db->get('lab_test_category');
        if ($query->num_rows() > 0) {
            if (!empty($category_id)) {
                set_alert('error', "The Category name are already used.");
            } else {
                $this->form_validation->set_message("unique_category", "The %s name are already used.");
            }
            return false;
        } else {
            return true;
        }
    }

    // pending investigation report list by date
    public function pending_report()
    {
        if (!get_permission('test_report', 'is_add')) {
            access_denied();
        }

        if ($this->input->post()) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
        } else {
            $start = '';
            $end = '';
        }
        $this->data['result'] = $this->test_model->get_pending_report_list(false, $start, $end);
        $this->data['title'] = translate('investigation') . " " . translate('report');
        $this->data['sub_page'] = 'test/pending_report';
        $this->data['main_menu'] = 'test_report';
        $this->load->view('layout/index', $this->data);
    } 

    // investigation report list
    public function report_list()
    {
        if (!get_permission('test_report', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['result'] = $this->test_model->get_pending_report_list(true, $start, $end);
        }
        $this->data['title'] = translate('investigation') . " " . translate('report');
        $this->data['sub_page'] = 'test/report_list';
        $this->data['main_menu'] = 'test_report';
        $this->load->view('layout/index', $this->data);
    }

    // investigation report save in db
    public function investigation_create($id = '')
    {
        if (!get_permission('test_report', 'is_add') || empty($id)) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            $rules = array(
                array(
                    'field' => 'report_description',
                    'label' => 'Report Description',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'reporting_date',
                    'label' => 'Reporting Date',
                    'rules' => 'required',
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== false) {
                //save all employee information in the database
                $id = $this->test_model->save_investigation();
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('test/investigation_print/' . $id));
            }
        }

        $this->data['headerelements'] = array(
            'js' => array(
                'vendor/ckeditor/ckeditor.js',
            ),
        );
        $this->data['test'] = $this->test_model->get_test_details($id);
        $this->data['templatelist'] = $this->app_lib->getSelectList('lab_report_template');
        $this->data['title'] = translate('investigation') . " " . translate('report');
        $this->data['sub_page'] = 'test/investigation_create';
        $this->data['main_menu'] = 'test_report';
        $this->load->view('layout/index', $this->data);
    }

    // investigation report save in db
    public function investigation_edit($id = '')
    {
        if (!get_permission('test_report', 'is_edit') || empty($id)) {
            access_denied();
        }
        if (isset($_POST['update'])) {
            $rules = array(
                array(
                    'field' => 'report_description',
                    'label' => 'Report Description',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'reporting_date',
                    'label' => 'Reporting Date',
                    'rules' => 'required',
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== false) {
                //save all employee information in the database
                $id = $this->test_model->save_investigation();
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('test/investigation_print/' . $id));
            }
        }

        $this->data['headerelements'] = array(
            'js' => array(
                'vendor/ckeditor/ckeditor.js',
            ),
        );
        $this->data['test'] = $this->test_model->get_test_details($id, true);
        $this->data['templatelist'] = $this->app_lib->getSelectList('lab_report_template');
        $this->data['title'] = translate('investigation') . " " . translate('report');
        $this->data['sub_page'] = 'test/investigation_edit';
        $this->data['main_menu'] = 'test_report';
        $this->load->view('layout/index', $this->data);
    }

    public function investigation_print($id = '')
    {
        if (!get_permission('test_report', 'is_view') || empty($id)) {
            access_denied();
        }
        $this->data['test'] = $this->test_model->get_test_details($id, true);
        $this->data['title'] = translate('test') . " " . translate('report');
        $this->data['sub_page'] = 'test/investigation_print';
        $this->data['main_menu'] = 'test_report';
        $this->load->view('layout/index', $this->data);
    }

    // investigation report template save in db
    public function report_template()
    {
        if (!get_permission('test_report_template', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            if (!get_permission('test_report_template', 'is_add')) {
                access_denied();
            }
            $rules = array(
                array(
                    'field' => 'template_name',
                    'label' => 'Template Name',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'template',
                    'label' => 'Template',
                    'rules' => 'required',
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['validation_error'] = true;
            } else {
                // save information in the database
                $this->test_model->save_template();
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('test/report_template'));
            }
        }
		$this->data['headerelements'] = array(
			'js' => array(
				'vendor/ckeditor/ckeditor.js',
			),
		);
        $this->data['result'] = $this->test_model->get_templete_list();
        $this->data['title'] = translate('investigation') . " " . translate('report');
        $this->data['sub_page'] = 'test/report_template';
        $this->data['main_menu'] = 'test_report';
        $this->load->view('layout/index', $this->data);
    }

    public function report_template_edit($id = '')
    {
        if (!get_permission('test_report_template', 'is_edit')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            $rules = array(
                array(
                    'field' => 'template_name',
                    'label' => 'Template Name',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'template',
                    'label' => 'Template',
                    'rules' => 'required',
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== false) {
                // update information in the database
                $this->test_model->save_template();
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('test/report_template'));
            }
        }
        $this->data['headerelements'] = array(
            'js' => array(
                'vendor/ckeditor/ckeditor.js',
            ),
        );
        $this->data['template'] = $this->test_model->get_list('lab_report_template', array('id' => $id), true);
        $this->data['title'] = translate('test') . " " . translate('report');
        $this->data['sub_page'] = 'test/report_template_edit';
        $this->data['main_menu'] = 'test_report';
        $this->load->view('layout/index', $this->data);
    }

    // delete investigation template from database
    public function labreport_template_delete($id)
    {
        if (!get_permission('test_report_template', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('lab_report_template');
    }
}
