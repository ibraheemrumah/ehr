<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Referral.php
 */

class Referral extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('referral_model');
    }

    public function index()
    {
        $this->assign_list();
    }

    // referral commission percent assign set here
    public function manage_assign()
    {
        // check access permission
        if (!get_permission('referral_assign', 'is_add')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $staff_role = $this->input->post('staff_role');
            $commission_for = $this->input->post('commission_for');
            $this->data['test_id'] = $commission_for;
            $this->data['stafflist'] = $this->referral_model->get_staff_by_role($staff_role, $commission_for);
        }
        if (isset($_POST['save'])) {
            $test_id = $this->input->post('test_id');
            $assign = $this->input->post('assign');
            foreach ($assign as $key => $value) {
                if (isset($value['cb_select_staff'])) {
                    $insert_data = array(
                        'staff_id' => $value['staff_id'],
                        'test_id' => $test_id,
                        'percentage' => $value['percentage'],
                        'assign_by' => get_loggedin_user_id(),
                    );
                    if ($value['cb_select_staff'] == 0) {
                        $insert_data['date'] = date("Y-m-d");
                        $this->db->insert('referral_commission', $insert_data);
                    } else {
                        $this->db->where('id', $value['cb_select_staff']);
                        $this->db->update('referral_commission', $insert_data);
                    }
                } else {
                    $this->db->where('staff_id', $value['staff_id']);
                    $this->db->where('test_id', $test_id);
                    $this->db->delete('referral_commission');
                }
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('referral/manage_assign'));
        }
        $this->data['title'] = translate('refer_manager');
        $this->data['sub_page'] = 'referral/manage_assign';
        $this->data['main_menu'] = 'referral';
        $this->load->view('layout/index', $this->data);
    }

    // assign referral list
    public function assign_list()
    {
        if (!get_permission('referral_assign', 'is_view')) {
            access_denied();
        }
        $this->data['stafflist'] = $this->referral_model->get_staff_commission_list();
        $this->data['title'] = translate('refer_manager');
        $this->data['sub_page'] = 'referral/assign_list';
        $this->data['main_menu'] = 'referral';
        $this->load->view('layout/index', $this->data);
    }

    // withdrawal referral commission
    public function withdrawal()
    {
        if (!get_permission('commission_withdrawal', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            if (!get_permission('commission_withdrawal', 'is_add')) {
                access_denied();
            }
            $rules = array(
                array(
                    'field' => 'staff_role',
                    'label' => 'Role',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'staff_id',
                    'label' => 'User',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'pay_via',
                    'label' => 'Payment Method',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'amount',
                    'label' => 'Amount',
                    'rules' => 'required|numeric|greater_than_equal_to[50]|callback_check_balance',
                ),
            );

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['validation_error'] = true;
            } else {
                // save withdrawal information in the database
                $data = $this->input->post();
                $this->referral_model->save_payout_remittance($data);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('referral/withdrawal'));
            }
        }
        $this->data['payoutlist'] = $this->referral_model->getPayoutList();
        $this->data['payvia_list'] = $this->app_lib->getSelectList('payment_type');
        $this->data['title'] = translate('refer_manager');
        $this->data['sub_page'] = 'referral/withdrawal';
        $this->data['main_menu'] = 'referral';
        $this->load->view('layout/index', $this->data);
    }

    // withdrawal history delete then refund user account balance
    public function withdrawal_delete($id)
    {
        if (!get_permission('commission_withdrawal', 'is_delete')) {
            access_denied();
        }
        $getRow = $this->db->get_where('payout_commission', array('id' => $id))->row_array();
        // UPDATE USER BALANCE
        $this->db->where('staff_id', $getRow['staff_id']);
        $this->db->set('amount', 'amount + ' . $getRow['amount'], false);
        $this->db->update('staff_balance');

        $this->db->where('id', $id);
        $this->db->delete('payout_commission');
    }

    // withdrawal amount check in user balance
    public function check_balance($amount)
    {
        $staff_id = $this->input->post('staff_id');
        if (!empty($staff_id)) {
            $sql = "SELECT amount FROM staff_balance WHERE staff_id =" . $this->db->escape($staff_id);
            $q = $this->db->query($sql);
            if ($q->num_rows() > 0) {
                if ($amount > $q->row()->amount) {
                    $this->form_validation->set_message('check_balance', 'The amount of payment is not user balance.');
                    return false;
                }
            } else {
                $this->form_validation->set_message('check_balance', 'User account not found.');
                return false;
            }
        }
        return true;
    }

    // getting referral commission history
    public function commission_history()
    {
        if (!get_permission('referral_reports', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $staff_id = $this->input->post('staff_id');
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->referral_model->get_commission_history($staff_id, $start, $end);
        }
        $this->data['stafflist'] = $this->app_lib->getStafflList('all');
        $this->data['title'] = translate('refer_manager');
        $this->data['sub_page'] = 'referral/commission_history';
        $this->data['main_menu'] = 'referral_report';
        $this->load->view('layout/index', $this->data);
    }

    // getting referral statement
    public function referral_statement()
    {
        if (!get_permission('referral_reports', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->referral_model->get_referral_statement($start, $end);
        }
        $this->data['title'] = translate('refer_manager');
        $this->data['sub_page'] = 'referral/referral_statement';
        $this->data['main_menu'] = 'referral_report';
        $this->load->view('layout/index', $this->data);
    }

    // commission payout report
    public function payout_report()
    {
        if (!get_permission('referral_reports', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $staff_id = $this->input->post('staff_id');
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->referral_model->get_payout_report($staff_id, $start, $end);
        }
        $this->data['title'] = translate('refer_manager');
        $this->data['sub_page'] = 'referral/payout_report';
        $this->data['stafflist'] = $this->app_lib->getStafflList('all');
        $this->data['main_menu'] = 'referral_report';
        $this->load->view('layout/index', $this->data);
    }

    // referral commission summary
    public function commission_summary()
    {
        if (!get_permission('referral_reports', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $staff_id = $this->input->post('staff_id');
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->referral_model->get_commission_summary($staff_id, $start, $end);
        }
        $this->data['stafflist'] = $this->app_lib->getStafflList('all');
        $this->data['title'] = translate('refer_manager');
        $this->data['sub_page'] = 'referral/commission_summary';
        $this->data['main_menu'] = 'referral_report';
        $this->load->view('layout/index', $this->data);
    }

    public function my_commission_summary()
    {
        if (!get_permission('my_commission', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $staff_id = get_loggedin_user_id();
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->referral_model->get_commission_summary($staff_id, $start, $end);
        }
        $this->data['title'] = translate('refer_manager');
        $this->data['sub_page'] = 'referral/my_commission_summary';
        $this->data['main_menu'] = 'referral';
        $this->load->view('layout/index', $this->data);
    }
}
