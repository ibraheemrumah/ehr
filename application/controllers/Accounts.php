<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Accounts.php
 */

class Accounts extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('accounts_model');
        $this->load->model('email_model');
    }

    // add new account for office accounting
    public function index()
    {
        // check access permission
        if (!get_permission('account', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('account', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('account_name', 'Account Name', array('trim','required',array('unique_account_name', array($this->accounts_model, 'unique_account_name'))));
            if ($this->form_validation->run() !== false) {
                $data = $this->input->post();
                $this->accounts_model->save_accounts($data);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->data['validation_error'] = 1;
            }
        }
        $this->data['accountslist'] = $this->accounts_model->get_list('accounts');
        $this->data['sub_page'] = 'accounts/index';
        $this->data['main_menu'] = 'accounts';
        $this->data['title'] = translate('office_accounting');
        $this->load->view('layout/index', $this->data);
    }

    // update existing account if passed id
    public function edit($id = '')
    {
        if (!get_permission('account', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->form_validation->set_rules('account_name', 'Account Name', array('trim', 'required', 'xss_clean', array('unique_account_name', array($this->accounts_model, 'unique_account_name'))));
            if ($this->form_validation->run() !== false) {
                $data = $this->input->post();
                $this->accounts_model->save_accounts($data);
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('accounts'));
            }
        }
        $this->data['account'] = $this->accounts_model->get_list('accounts', array('id' => $id), true);
        $this->data['sub_page'] = 'accounts/edit';
        $this->data['main_menu'] = 'accounts';
        $this->data['title'] = translate('office_accounting');
        $this->load->view('layout/index', $this->data);
    }

    // delete account from database
    public function delete($id = '')
    {
        if (!get_permission('account', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('accounts');
        $this->db->where('account_id', $id);
        $this->db->delete('transactions');
    }

    // add new voucher head for voucher
    public function voucher_head()
    {
        if ($_POST) {
            if (!get_permission('voucher_head', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('voucher_head', 'Voucher Head', array('trim', 'required', 'xss_clean', array('unique_voucher_head', array($this->accounts_model, 'unique_voucher_head'))));
            $this->form_validation->set_rules('type', 'Type', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $arrayHead = array(
                    'name' => $this->input->post('voucher_head'),
                    'type' => $this->input->post('type'),
                );
                $this->db->insert('voucher_head', $arrayHead);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $this->data['productlist'] = $this->accounts_model->get_list('voucher_head');
        $this->data['title'] = translate('office_accounting');
        $this->data['sub_page'] = 'accounts/voucher_head';
        $this->data['main_menu'] = 'accounts';
        $this->load->view('layout/index', $this->data);
    }

    // update existing voucher head if passed id
    public function voucher_head_edit()
    {
        if (!get_permission('voucher_head', 'is_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('voucher_head', 'Voucher Head', array('trim', 'required', 'xss_clean', array('unique_voucher_head', array($this->accounts_model, 'unique_voucher_head'))));
        if ($this->form_validation->run() !== false) {
            $voucher_head_id = $this->input->post('voucher_head_id');
            $arrayHead = array(
                'name' => $this->input->post('voucher_head'),
            );
            $this->db->where('id', $voucher_head_id);
            $this->db->update('voucher_head', $arrayHead);
            set_alert('success', translate('information_has_been_updated_successfully'));
        }
        redirect(base_url('accounts/voucher_head'));
    }

    // delete voucher head from database
    public function voucher_head_delete($id)
    {
        if (!get_permission('voucher_head', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('voucher_head');
    }

    // this function is used to add voucher data
    public function voucher()
    {
        if (!get_permission('voucher', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('voucher', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('account_id', 'Account', 'trim|required');
            $this->form_validation->set_rules('voucher_head_id', 'Voucher Head', 'trim|required');
            $this->form_validation->set_rules('voucher_type', 'Voucher Type', 'trim|required');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
            $this->form_validation->set_rules('date', 'Date', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                //save data into table
                $insert_id = $this->accounts_model->save_voucher($post);
                if (isset($_FILES["attachment_file"]) && !empty($_FILES['attachment_file']['name'])) {
                    $ext = pathinfo($_FILES["attachment_file"]["name"], PATHINFO_EXTENSION);
                    $file_name = $insert_id . '.' . $ext;
                    move_uploaded_file($_FILES["attachment_file"]["tmp_name"], "./uploads/attachments/voucher/" . $file_name);
                    $this->db->where('id', $insert_id);
                    $this->db->update('transactions', array('attachments' => $file_name));
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->data['validation_error'] = 1;
            }
        }
        $this->data['voucherlist'] = $this->accounts_model->get_voucher_list();
        $this->data['accounts_list'] = $this->app_lib->getSelectList('accounts');
        $this->data['payvia_list'] = $this->app_lib->getSelectList('payment_type');
        $this->data['sub_page'] = 'accounts/voucher';
        $this->data['main_menu'] = 'accounts';
        $this->data['title'] = translate('office_accounting');
        $this->load->view('layout/index', $this->data);
    }

    // this function is used to voucher data update
    public function voucher_edit($id = '')
    {
        if (!get_permission('voucher', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->form_validation->set_rules('voucher_head_id', 'Voucher Head', 'trim|required');
            $this->form_validation->set_rules('date', 'Date', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                //UPDATE DATA INTO TABLE
                $insert_id = $this->accounts_model->edit_Voucher($post);
                if (isset($_FILES["attachment_file"]) && !empty($_FILES['attachment_file']['name'])) {
                    $ext = pathinfo($_FILES["attachment_file"]["name"], PATHINFO_EXTENSION);
                    $file_name = $insert_id . '.' . $ext;
                    move_uploaded_file($_FILES["attachment_file"]["tmp_name"], "./uploads/attachments/voucher/" . $file_name);
                    $this->db->where('id', $insert_id);
                    $this->db->update('transactions', array('attachments' => $file_name));
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect('accounts/voucher');
            } else {
                $this->data['validation_error'] = 1;
            }
        }
        $this->data['voucher'] = $this->accounts_model->get_list('transactions', array('id' => $id), true);
        $this->data['accounts_list'] = $this->app_lib->getSelectList('accounts');
        $this->data['payvia_list'] = $this->app_lib->getSelectList('payment_type');
        $this->data['sub_page'] = 'accounts/voucher_edit';
        $this->data['main_menu'] = 'accounts';
        $this->data['title'] = translate('office_accounting');
        $this->load->view('layout/index', $this->data);
    }

    // delete into voucher table by voucher id
    public function voucher_delete($id)
    {
        if (!get_permission('voucher', 'is_delete')) {
            access_denied();
        }
        $q = $this->db->where('id', $id)->get('transactions')->row_array();
        if ($q['type'] == 'expense') {
            $sql = "UPDATE accounts SET balance = balance + " . $q['amount'] . " WHERE id = " . $this->db->escape($q['account_id']);
            $this->db->query($sql);
        } elseif ($q['type'] == 'income') {
            $sql = "UPDATE accounts SET balance = balance - " . $q['amount'] . " WHERE id = " . $this->db->escape($q['account_id']);
            $this->db->query($sql);
        }
        $filepath = FCPATH . 'uploads/attachments/voucher/' . $q['attachments'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        $this->db->where('id', $id);
        $this->db->delete('transactions');
    }

    // account statement by date to date
    public function account_statement()
    {
        if (!get_permission('accounting_reports', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $account_id = $this->input->post('account_id');
            $type = $this->input->post('type');
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->accounts_model->get_statement_report($account_id, $type, $start, $end);
        }
        $this->data['title'] = translate('office_accounting');
        $this->data['accountlist'] = $this->app_lib->getSelectList('accounts');
        $this->data['sub_page'] = 'accounts/account_statement';
        $this->data['main_menu'] = 'accounts_repots';
        $this->load->view('layout/index', $this->data);
    }

    // income repots by date to date
    public function income_repots()
    {
        if (!get_permission('accounting_reports', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->accounts_model->get_income_expense_repots($start, $end, 'income');
        }
        $this->data['title'] = translate('office_accounting');
        $this->data['sub_page'] = 'accounts/income_repots';
        $this->data['main_menu'] = 'accounts_repots';
        $this->load->view('layout/index', $this->data);
    }

    public function expense_repots()
    {
        if (!get_permission('accounting_reports', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->accounts_model->get_income_expense_repots($start, $end, 'expense');
        }
        $this->data['title'] = translate('office_accounting');
        $this->data['sub_page'] = 'accounts/expense_repots';
        $this->data['main_menu'] = 'accounts_repots';
        $this->load->view('layout/index', $this->data);
    }

    // account balance sheet
    public function balance_sheet()
    {
        if (!get_permission('accounting_reports', 'is_view')) {
            access_denied();
        }
        $this->data['results'] = $this->accounts_model->get_balance_sheet();
        $this->data['title'] = translate('office_accounting');
        $this->data['sub_page'] = 'accounts/balance_sheet';
        $this->data['main_menu'] = 'accounts_repots';
        $this->load->view('layout/index', $this->data);
    }

    // income vs expense repots by date to date
    public function incomevsexpense()
    {
        if (!get_permission('accounting_reports', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->accounts_model->get_incomevsexpense($start, $end);
        }
        $this->data['title'] = translate('office_accounting');
        $this->data['sub_page'] = 'accounts/income_vs_expense';
        $this->data['main_menu'] = 'accounts_repots';
        $this->load->view('layout/index', $this->data);
    }

    public function transitions_repots()
    {
        if (!get_permission('accounting_reports', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->accounts_model->get_transitions_repots($start, $end);
        }
        $this->data['title'] = translate('office_accounting');
        $this->data['sub_page'] = 'accounts/transitions_repots';
        $this->data['main_menu'] = 'accounts_repots';
        $this->load->view('layout/index', $this->data);
    }
}
