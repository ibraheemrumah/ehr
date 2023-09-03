<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Dashboard.php
 */

class Dashboard extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
    }

    public function index()
    {
        $this->data['patient_fees_summary'] = $this->dashboard_model->get_monthly_patient_fees();
        $this->data['yearly_income_expense'] = $this->dashboard_model->get_income_vs_expense();
        $this->data['get_today_invoice_qty'] = $this->dashboard_model->get_today_invoice_qty();
        $this->data['get_today_commission'] = $this->dashboard_model->get_today_commission();
        $this->data['get_today_incomeandexpense'] = $this->dashboard_model->get_today_incomeandexpense();
        $this->data['get_total_patient'] = $this->dashboard_model->get_total_patient();
        $this->data['get_total_doctor'] = $this->dashboard_model->get_total_doctor();
        $this->data['get_total_staff'] = $this->dashboard_model->get_total_staff();
        $this->data['get_total_appointment'] = $this->dashboard_model->get_total_appointment();
        $this->data['get_monthly_appointment'] = $this->dashboard_model->get_monthly_appointment();
        $this->data['headerelements'] = array(
            'js' => array(
                'vendor/chartjs/chart.min.js',
                'vendor/chartjs/utils.js',
            ),
        );
        $this->data['title'] = translate('dashboard');
        $this->data['sub_page'] = 'dashboard/index';
        $this->data['main_menu'] = 'dashboard';
        $this->load->view('layout/index', $this->data);
    }
}
