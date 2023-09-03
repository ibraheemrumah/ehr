<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referral_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // assign save and update function
    public function save_assign($id = null)
    {
        $insert_data1 = array(
            'staff_id' => $this->input->post('user_id'),
            'test_id' => $this->input->post('commission_for'),
            'percentage' => $this->input->post('percentage'),
            'assign_by' => get_loggedin_user_id(),
            'remarks' => $this->input->post('remarks'),
        );
        if (empty($id)) {
            $this->db->insert('product', $insert_data1);
        } else {
            $this->db->where('id', $id);
            $this->db->update('product', $insert_data);
        }
    }

    // save payout remittance function
    public function save_payout_remittance($data)
    {
        $getBalance = $this->db->select('amount')->where('staff_id', $data['staff_id'])->get('staff_balance')->row()->amount;
        $payslip_no = $this->app_lib->get_bill_no('payout_commission');
        $insert_data1 = array(
            'staff_id' => $data['staff_id'],
            'bill_no' => $payslip_no,
            'pay_via' => $data['pay_via'],
            'before_payout' => $getBalance,
            'amount' => $data['amount'],
            'remarks' => $data['remarks'],
            'paid_by' => get_loggedin_user_id(),
        );
        $this->db->insert('payout_commission', $insert_data1);

        // update user balance
        $this->db->where('staff_id', $data['staff_id']);
        $this->db->set('amount', 'amount - ' . $data['amount'], false);
        $this->db->update('staff_balance');
    }

    // staff balance upgrade
    public function balance_upgrade($amount, $staff_id, $add = true)
    {
        if ($add == true) {
            $this->db->set('amount', 'amount +' . $amount, false);
        } else {
            $this->db->set('amount', 'amount -' . $amount, false);
        }
        $this->db->where('staff_id', $staff_id);
        $this->db->update('staff_balance');
    }

    public function get_staff_by_role($role_id, $test_id)
    {
        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id,IFNULL(referral_commission.id, 0) as commission_id,IFNULL(referral_commission.percentage, 0) as percentage');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "7"', 'inner');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->join('referral_commission', 'referral_commission.staff_id = staff.id and referral_commission.test_id =' . $this->db->escape($test_id), 'left');
        $this->db->where('login_credential.role', $role_id);
        $this->db->where('login_credential.active', 1);
        $this->db->order_by('staff.id', 'ASC');
        return $this->db->get()->result();
    }

    // get staff commission list function
    public function get_staff_commission_list()
    {
        $sql = "SELECT referral_commission.*, staff.staff_id as staffid, staff.name as staff_name, staff_designation.name as designation_name, staff_department.name as department_name, login_credential.role as role_id, roles.name as role, lab_test.name as test_name FROM referral_commission INNER JOIN staff ON staff.id = referral_commission.staff_id INNER JOIN login_credential ON login_credential.user_id = staff.id and login_credential.role != 7 LEFT JOIN roles ON roles.id = login_credential.role LEFT JOIN staff_designation ON staff_designation.id = staff.designation LEFT JOIN staff_department ON staff_department.id = staff.department LEFT JOIN lab_test ON lab_test.id = referral_commission.test_id WHERE login_credential.active = 1 ORDER BY referral_commission.id ASC";
        return $this->db->query($sql)->result();
    }

    // get payout list function
    public function getPayoutList()
    {
        $this->db->select('payout_commission.*,staff.staff_id as staffid,staff.name as staff_name,login_credential.role as role_id, roles.name as role');
        $this->db->from('payout_commission');
        $this->db->join('staff', 'staff.id = payout_commission.staff_id', 'inner');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "7"', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->where('login_credential.active', 1);
        if (!get_permission('commission_withdrawal', 'is_add'))
            $this->db->where(' payout_commission.staff_id', get_loggedin_user_id());
        $this->db->order_by('payout_commission.id', 'DESC');
        return $this->db->get()->result();
    }

    // get payout details function
    public function get_payout($id)
    {
        $this->db->select('payout_commission.*,login_credential.role');
        $this->db->from('payout_commission');
        $this->db->join('staff', 'staff.id = payout_commission.staff_id', 'left');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "7"', 'left');
        $this->db->where('payout_commission.id', $id);
        return $this->db->get()->row_array();
    }

    // get commission history function
    public function get_commission_history($staff_id = '', $start = '', $end = '')
    {
        $this->db->select('labtest_bill.*,SUM(labtest_bill.total+labtest_bill.tax_amount-labtest_bill.discount) as net_amount,staff.name as staff_name,staff.staff_id as staffid,patient.name as patient_name,patient.patient_id as patientid');
        $this->db->from('labtest_bill');
        $this->db->join('staff', 'staff.id = labtest_bill.referral_id', 'left');
        $this->db->join('patient', 'patient.id = labtest_bill.patient_id', 'left');
        if ($staff_id != 'all') {
            $this->db->where('labtest_bill.referral_id', $staff_id);
        }
        $this->db->where('labtest_bill.date >=', $start);
        $this->db->where('labtest_bill.date <=', $end);
        $this->db->group_by('labtest_bill.bill_no');
        $this->db->order_by('labtest_bill.id', 'ASC');
        return $this->db->get()->result_array();
    }

    // get commission summary function
    public function get_commission_summary($staff_id = '', $start = '', $end = '')
    {
        $sql = "SELECT labtest_bill.*,SUM(labtest_bill.total+labtest_bill.tax_amount-labtest_bill.discount) as net_amount, staff.name as staff_name, staff.staff_id as staffid, patient.name as patient_name, patient.patient_id as patientid,payment_history.duecollect,paid_history.totalpaid FROM labtest_bill LEFT JOIN staff ON staff.id = labtest_bill.referral_id LEFT JOIN patient ON patient.id = labtest_bill.patient_id LEFT JOIN (SELECT labtest_payment_history.labtest_bill_id,labtest_payment_history.coll_type,SUM(amount) duecollect FROM labtest_payment_history WHERE labtest_payment_history.coll_type=1 group by labtest_payment_history.labtest_bill_id) as payment_history ON payment_history.labtest_bill_id=labtest_bill.id LEFT JOIN (SELECT labtest_payment_history.labtest_bill_id,labtest_payment_history.coll_type,SUM(amount) totalpaid FROM labtest_payment_history WHERE labtest_payment_history.coll_type=0 group by labtest_payment_history.labtest_bill_id) as paid_history ON paid_history.labtest_bill_id=labtest_bill.id WHERE labtest_bill.date >= " . $this->db->escape($start) . " AND labtest_bill.date <= " . $this->db->escape($end);
        if ($staff_id != 'all') {
            $sql .= " AND labtest_bill.referral_id=" . $this->db->escape($staff_id);
        }
        $sql .= " GROUP BY labtest_bill.id ORDER BY labtest_bill.id ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // get referral statement
    public function get_referral_statement($start = '', $end = '')
    {
        $this->db->select('count(labtest_bill.id) as invoice_q,SUM(labtest_bill.commission) as total_commission,SUM(labtest_bill.total) as total,SUM(labtest_bill.total+labtest_bill.tax_amount-labtest_bill.discount) as net_amount,staff.name as staff_name,staff.staff_id as staffid,IFNULL(staff_department.name,"N/A") as department_name');
        $this->db->from('labtest_bill');
        $this->db->join('staff', 'staff.id = labtest_bill.referral_id', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->where('labtest_bill.date >=', $start);
        $this->db->where('labtest_bill.date <=', $end);
        $this->db->group_by('labtest_bill.referral_id');
        $this->db->order_by('labtest_bill.id', 'ASC');
        return $this->db->get()->result_array();
    }

    // get payout report
    public function get_payout_report($staff_id = '', $start = '', $end = '')
    {
        $this->db->select('payout_commission.*,payment_type.name as payvia,staff.name as staff_name,staff.staff_id as staffid,IFNULL(staff_department.name,"N/A") as department_name');
        $this->db->from('payout_commission');
        $this->db->join('payment_type', 'payment_type.id = payout_commission.pay_via', 'left');
        $this->db->join('staff', 'staff.id = payout_commission.staff_id', 'inner');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->where('DATE(payout_commission.created_at) >=', $start);
        $this->db->where('DATE(payout_commission.created_at) <=', $end);
        if ($staff_id != 'all') {
            $this->db->where('payout_commission.staff_id', $staff_id);
        }

        $this->db->order_by('payout_commission.id', 'ASC');
        return $this->db->get()->result_array();
    }
}
