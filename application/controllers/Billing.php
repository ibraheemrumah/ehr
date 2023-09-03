<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Billing.php
 */

class Billing extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('billing_model');
    }

    // patient lab test bill data save into table
    public function test_bill_add()
    {
        // check access permission
        if (!get_permission('lab_test_bill', 'is_add')) {
            access_denied();
        }

        $this->data['title'] = translate('billing');
        $this->data['patientlist'] = $this->app_lib->getPatientList();
         $this->data['referrallist'] = $this->app_lib->getStafflList();
        $this->data['payvia_list'] = $this->app_lib->getSelectList('payment_type');
        $this->data['categorylist'] = $this->app_lib->getSelectList('lab_test_category');
        $this->data['sub_page'] = 'billing/test_bill_add';
        $this->data['main_menu'] = 'billing';
        $this->load->view('layout/index', $this->data);
    }
	
	public function treat_add()
    {
        // check access permission
        if (!get_permission('lab_test_bill', 'is_add')) {
            access_denied();
        }

        $this->data['title'] = translate('Treatment_bill');
        $this->data['patientlist'] = $this->app_lib->getPatientList();
		 $this->data['referrallist'] = $this->app_lib->getStafflList();
        $this->data['payvia_list'] = $this->app_lib->getSelectList('payment_type');
        $this->data['categorylist'] = $this->app_lib->getSelectList('lab_test_category');
        $this->data['sub_page'] = 'billing/treat_add';
        $this->data['main_menu'] = 'billing';
        $this->load->view('layout/index', $this->data);
    }

    public function bill_save()
    {
        if (!get_permission('lab_test_bill', 'is_add')) {
            access_denied();
        }
        if ($_POST) {
            // validate inputs
            $this->form_validation->set_rules('patient_id', 'Patient', 'trim|required');
            
            $this->form_validation->set_rules('bill_date', 'Bill Date', 'trim|required');
            $this->form_validation->set_rules('delivery_date', 'Delivery Date', 'trim|required');
            $this->form_validation->set_rules('delivery_time', 'Delivery Time', 'trim|required');
            $this->form_validation->set_rules('payment_amount', 'Payment Amount', 'numeric|callback_check_payment_amount');
            $items = $this->input->post('items');
            foreach ($items as $key => $value) {
                $this->form_validation->set_rules('items[' . $key . '][category]', 'Category', 'trim|required');
                $this->form_validation->set_rules('items[' . $key . '][lab_test]', 'Test Name', 'trim|required');
            }
            if ($this->form_validation->run() == false) {
                $msg = array(
                    'patient_id' => form_error('patient_id'),
                   
                    'bill_date' => form_error('bill_date'),
                    'delivery_date' => form_error('delivery_date'),
                    'delivery_time' => form_error('delivery_time'),
                    'payment_amount' => form_error('payment_amount'),
                );
                foreach ($items as $key => $value) {
                    $msg['test_category' . $key] = form_error('items[' . $key . '][category]');
                    $msg['lab_test' . $key] = form_error('items[' . $key . '][lab_test]');
                }
                $array = array('status' => 'fail', 'url' => '', 'error' => $msg);
            } else {
                $post = $this->input->post();
                $url = $this->billing_model->save_test_bill($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            }
            echo json_encode($array);
        }
    }

    public function check_payment_amount($amount)
    {
        $net_amount = $this->input->post('net_amount');
        if ($amount <= $net_amount) {
            return true;
        } else {
            $this->form_validation->set_message('check_payment_amount', 'Invaild payment amount.');
            return false;
        }
    }

    // getting test bill list by date range
    public function test_bill_list()
    {
        // check access permission
        if (!get_permission('lab_test_bill', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['billlist'] = $this->billing_model->get_bill_list($start, $end);
        } else {
            $this->data['billlist'] = $this->billing_model->get_bill_list();
        }
        $this->data['title'] = translate('billing');
        $this->data['sub_page'] = 'billing/labtest_bill_list';
        $this->data['main_menu'] = 'billing';
        $this->load->view('layout/index', $this->data);
    }

    // getting test drug list by date range
    public function treatment_bill_list()
    {
        // check access permission
        if (!get_permission('lab_test_bill', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['billlist'] = $this->billing_model->get_drug_bill_list($start, $end);
        } else {
            $this->data['billlist'] = $this->billing_model->get_drug_bill_list();
        }
        $this->data['title'] = translate('billing');
        $this->data['sub_page'] = 'billing/treatment_bill_list';
        $this->data['main_menu'] = 'billing';
        $this->load->view('layout/index', $this->data);
    }

    // getting test bill invoice by date range
    public function test_bill_invoice($id = '', $hash = '')
    {
        // check access permission
        if (!get_permission('lab_test_bill', 'is_view')) {
            access_denied();
        }
        check_hash_restrictions('labtest_bill', $id, $hash);
        $this->data['title'] = translate('billing');
        $this->data['payvia_list'] = $this->app_lib->getSelectList('payment_type');
        $this->data['test_bill'] = $this->billing_model->get_labtest_bill($id);
        $this->data['bill_details'] = $this->billing_model->get_labtest_bill_details($id);
        $this->data['paymentHistory'] = $this->billing_model->get_test_paymenthistory($id);
        $this->data['sub_page'] = 'billing/labtest_bill_invoice';
        $this->data['main_menu'] = 'billing';
        $this->load->view('layout/index', $this->data);
    }

    // getting test drug bill invoice by date range
    public function treatment_bill_invoice($id = '', $hash = '')
    {
        // check access permission
        if (!get_permission('lab_test_bill', 'is_view')) {
            access_denied();
        }
        check_hash_restrictions('labtest_bill', $id, $hash);
        $this->data['title'] = translate('billing');
        $this->data['payvia_list'] = $this->app_lib->getSelectList('payment_type');
        $this->data['test_bill'] = $this->billing_model->get_labtest_bill($id);
        $this->data['bill_details'] = $this->billing_model->get_labtest_bill_details($id);
        $this->data['paymentHistory'] = $this->billing_model->get_test_paymenthistory($id);
        $this->data['sub_page'] = 'billing/treatment_bill_invoice';
        $this->data['main_menu'] = 'billing';
        $this->load->view('layout/index', $this->data);
    }

    // test bill delete then restore product stock and referral balance and invoice related all payment
    public function test_bill_delete($id = '')
    {
        if (!get_permission('lab_test_bill', 'is_delete')) {
            access_denied();
        }
       
        $test_result = $this->db->get_where('labtest_bill_details', array('labtest_bill_id' => $id))->result_array();
        foreach ($test_result as $row) {
            $assignedResult = $this->db->get_where('chemical_assigned', array('test_id' => $row['test_id']))->result_array();
            foreach ($assignedResult as $assign) {
                $this->db->where('id', $assign['chemical_id']);
                $this->db->set('available_stock', 'available_stock + 1', false);
                $this->db->update('chemical');
            }
        }

       

        $this->db->where('id', $id);
        $this->db->delete('labtest_bill');

        $this->db->where('labtest_bill_id', $id);
        $this->db->delete('labtest_bill_details');

        $this->db->where('labtest_bill_id', $id);
        $this->db->delete('labtest_payment_history');

        $this->db->where('labtest_bill_id', $id);
        $this->db->delete('labtest_report');
    }

    // record new inoice payment view
    public function test_bill_payment()
    {
        if (!get_permission('test_bill_payment', 'is_add')) {
            access_denied();
        }
        if ($_POST) {
            $data = $this->input->post();
            $data['getbill'] = $this->db->select('due,hash')->where('id', $data['bill_id'])->get('labtest_bill')->row_array();
            $this->form_validation->set_rules('paid_date', 'Paid Date', 'trim|required');
            $this->form_validation->set_rules('payment_amount', 'Payment Amount', 'trim|required|numeric|greater_than[1]|callback_payment_validation');
            $this->form_validation->set_rules('pay_via', 'Pay Via', 'trim|required');
            if ($this->form_validation->run() !== false) {
                // save data into table
                $this->billing_model->save_billpayment($data);
                set_alert('success', translate('payment_successfull'));
                if (get_permission('test_bill_payment', 'is_view')) {
                    $this->session->set_flashdata('active_tab', 2);
                }
                redirect(base_url('billing/test_bill_invoice/' . $data['bill_id'] . '/' . $data['getbill']['hash']));
            } else {
                $this->session->set_flashdata('active_tab', 3);
                $this->test_bill_invoice($data['bill_id'], $data['getbill']['hash']);
            }
        }
    }

     // record new drug invoice payment view
     public function drug_bill_payment()
     {
         if (!get_permission('test_bill_payment', 'is_add')) {
             access_denied();
         }
         if ($_POST) {
             $data = $this->input->post();
             $data['getbill'] = $this->db->select('due,hash')->where('id', $data['bill_id'])->get('labtest_bill')->row_array();
             $this->form_validation->set_rules('paid_date', 'Paid Date', 'trim|required');
             $this->form_validation->set_rules('payment_amount', 'Payment Amount', 'trim|required|numeric|greater_than[1]|callback_payment_validation');
             $this->form_validation->set_rules('pay_via', 'Pay Via', 'trim|required');
             if ($this->form_validation->run() !== false) {
                 // save data into table
                 $this->billing_model->save_billpayment($data);
                 set_alert('success', translate('payment_successfull'));
                 if (get_permission('test_bill_payment', 'is_view')) {
                     $this->session->set_flashdata('active_tab', 2);
                 }
                 redirect(base_url('billing/treatment_bill_invoice/' . $data['bill_id'] . '/' . $data['getbill']['hash']));
             } else {
                 $this->session->set_flashdata('active_tab', 3);
                 $this->test_bill_invoice($data['bill_id'], $data['getbill']['hash']);
             }
         }
     }

    // payment amount verified here
    public function payment_validation($amount)
    {
        $bill_id = $this->input->post('bill_id');
        $due_amount = $this->db->select('due')->where('id', $bill_id)->get('labtest_bill')->row()->due;
        if ($amount <= $due_amount) {
            return true;
        } else {
            $this->form_validation->set_message("payment_validation", "Payment Amount Is More Than The Due Amount.");
            return false;
        }
    }

    // getting test bill due collection report by date range
    public function due_collection_report()
    {
        if (!get_permission('test_bill_report', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->billing_model->get_due_collect_report($start, $end);
        }
        $this->data['title'] = translate('billing');
        $this->data['sub_page'] = 'billing/due_collection_report';
        $this->data['main_menu'] = 'billing_report';
        $this->load->view('layout/index', $this->data);
    }

    // getting test bill due report by date range
    public function due_bill_report()
    {
        if (!get_permission('test_bill_report', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->billing_model->get_bill_duepaid_list($start, $end, 'due');
        }
        $this->data['title'] = translate('billing');
        $this->data['sub_page'] = 'billing/due_bill_report';
        $this->data['main_menu'] = 'billing_report';
        $this->load->view('layout/index', $this->data);
    }

    // getting test bill paid report by date range
    public function paid_bill_report()
    {
        if (!get_permission('test_bill_report', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->billing_model->get_bill_duepaid_list($start, $end, 'paid');
        }
        $this->data['title'] = translate('billing');
        $this->data['sub_page'] = 'billing/paid_bill_report';
        $this->data['main_menu'] = 'billing_report';
        $this->load->view('layout/index', $this->data);
    }
}
