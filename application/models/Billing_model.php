<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Billing_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // patient lab test bill data save function
    public function save_test_bill($data)
    {
        $status = 1;
        $bill_id = $this->app_lib->get_bill_no('labtest_bill');
        $payment_amount = (empty($data['payment_amount']) ? 0 : $data['payment_amount']);
        $total_discount = (empty($data['total_discount']) ? 0 : $data['total_discount']);
        $tax_amount = (empty($data['tax_amount']) ? 0 : $data['tax_amount']);
        $sub_total_amount = (empty($data['sub_total_amount']) ? 0 : $data['sub_total_amount']);
        $net_amount = (empty($data['net_amount']) ? 0 : $data['net_amount']);
        $bill_type = $data['bill_type'];
        if ($payment_amount > 0) {
            $status = 2;

        }
        if ($payment_amount == $net_amount) {
            $status = 3;
        }
        $array_bill = array(
            'bill_no' => $bill_id,
            'patient_id' => $data['patient_id'],
            'referral_id' => $data['referral_id'],
            'total' => $sub_total_amount,
            'discount' => $total_discount,
            'tax_amount' => $tax_amount,
            'paid' => $payment_amount,
            'due' => floatval($net_amount - $payment_amount),
            'status' => $status,
            'date' => date("Y-m-d", strtotime($data['bill_date'])),
            'hash' => app_generate_hash(),
            'prepared_by' => get_loggedin_user_id(),
            'bill_type' => $bill_type
        );
        $this->db->insert('labtest_bill', $array_bill);
        $bill_id = $this->db->insert_id();

        // record payment history save in db
        if ($payment_amount > 1) {
            $array_payment = array(
                'labtest_bill_id' => $bill_id,
                'collect_by' => get_loggedin_user_id(),
                'amount' => $payment_amount,
                'method_id' => $data['pay_via'],
                'remarks' => $data['payment_remarks'],
                'paid_on' => date("Y-m-d", strtotime($data['bill_date'])),
            );
            $this->db->insert('labtest_payment_history', $array_payment);
        }

        $items = $data['items'];
        $bill_details = array();
        $total_commission = 0;
        
        foreach ($items as $key => $value) {
            // inventory reagent here
            $assignedResult = $this->db->get_where('chemical_assigned', array('test_id' => $value['lab_test']))->result_array();
            foreach ($assignedResult as $assign) {
                $this->db->where('id', $assign['chemical_id']);
                $this->db->set('available_stock', 'available_stock - 1', false);
                $this->db->update('chemical');
            }

            // referral commission calculation here
            $query = $this->db->select('percentage')->where(array('staff_id' => $data['referral_id'], 'test_id' => $value['lab_test']))->get('referral_commission');
            if ($query->num_rows() > 0) {
                $afterDiscount = $value['total_price'];
                // if you want removed production cost
                $production_cost = get_type_name_by_id('lab_test', $value['lab_test'], 'production_cost');
                $sub_total_amount = $afterDiscount - $production_cost;
                $comm_percentage = $query->row()->percentage;
                $commission = ($comm_percentage / 100) * $sub_total_amount;
            } else {
                $commission = 0;
            }

            $array_bill_details = array(
                'labtest_bill_id' => $bill_id,
                'category_id' => $value['category'],
                'test_id' => $value['lab_test'],
                'price' => $value['unit_price'],
                'discount' => $value['dis_amount'],
                'commission_amount' => $commission,
            );
            $total_commission += $commission;
            $bill_details[] = $array_bill_details;
        }
        $this->db->insert_batch('labtest_bill_details', $bill_details);

        // referral commission amount add to user account
        $this->db->where('staff_id', $data['referral_id']);
        $this->db->set('amount', 'amount + ' . $total_commission, false);
        $this->db->update('staff_balance');

        // update total commission amount
        $this->db->where('id', $bill_id);
        $this->db->update('labtest_bill', array('commission' => $total_commission));

        // investigation report record
       
        $array_investigation = array(
            'labtest_bill_id' => $bill_id,
            'report_remarks' => $data['report_remarks'],
            'delivery_date' => date("Y-m-d", strtotime($data['delivery_date'])),
            'delivery_time' => date("H:i:s", strtotime($data['delivery_time'])),
            'status' => 1,
        );
        $this->db->insert('labtest_report', $array_investigation);
        if ($bill_type == '1'){
        $url = base_url('billing/test_bill_invoice/' . $bill_id . '/' . $array_bill['hash']);
        return $url;
        }
        if ($bill_type == '2'){
           
            $url = base_url('billing/treament_bill_invoice/' . $bill_id . '/' . $array_bill['hash']);
            return $url;
            }
           
    }

    // patient lab test bill payment data save function
    public function save_billpayment($data)
    {
        $status = 1;
        $bill_id = $data['bill_id'];
        $payment_amount = $data['payment_amount'];
        $array_history = array(
            'labtest_bill_id' => $bill_id,
            'collect_by' => get_loggedin_user_id(),
            'amount' => $payment_amount,
            'method_id' => $data['pay_via'],
            'remarks' => $data['remarks'],
            'paid_on' => date("Y-m-d", strtotime($data['paid_date'])),
            'bill_type' => $data['bill_type'],
        );
        $this->db->insert('labtest_payment_history', $array_history);
        if ($data['getbill']['due'] <= $payment_amount) {
            $status = 3;
        } else {
            $status = 2;
        }
        $sql = "UPDATE labtest_bill SET status = " . $status . ", paid = paid + " . $payment_amount . ", due = due - " . $payment_amount . " WHERE id = " . $this->db->escape($bill_id);
        $this->db->query($sql);


    }

    // get patient lab test bill information
    public function get_labtest_bill($id)
    {
        $this->db->select('labtest_bill.*,SUM(total-discount+tax_amount) as net_amount,patient.name as p_name,patient.patient_id,patient.mobileno as p_mobileno,patient.address as p_address,IFNULL(patient.age, "0") as p_age,patient.sex as p_sex,patient_category.name as p_category,staff.staff_id,staff.name as referral_name');
        $this->db->from('labtest_bill');
        $this->db->join('patient', 'patient.id = labtest_bill.patient_id', 'left');
        $this->db->join('patient_category', 'patient_category.id = patient.category_id', 'left');
        $this->db->join('staff', 'staff.id = labtest_bill.referral_id', 'left');
        $this->db->where('labtest_bill.id', $id);
        return $this->db->get()->row_array();
    }

    // get patient lab test bill payment history
    public function get_test_paymenthistory($id)
    {
        $this->db->select('labtest_payment_history.*,payment_type.name as pay_via,staff.name as collect_by');
        $this->db->from('labtest_payment_history');
        $this->db->join('payment_type', 'payment_type.id = labtest_payment_history.method_id', 'left');
        $this->db->join('staff', 'staff.id = labtest_payment_history.collect_by', 'left');
        $this->db->where('labtest_payment_history.labtest_bill_id', $id);
        $this->db->order_by('labtest_payment_history.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_labtest_bill_details($id)
    {
        $this->db->select('labtest_bill_details.*,lab_test.name as test_name,lab_test_category.name as test_category');
        $this->db->from('labtest_bill_details');
        $this->db->join('lab_test', 'lab_test.id = labtest_bill_details.test_id', 'left');
        $this->db->join('lab_test_category', 'lab_test_category.id = labtest_bill_details.category_id', 'left');
        $this->db->where('labtest_bill_details.labtest_bill_id', $id);
        $this->db->order_by('labtest_bill_details.id', 'ASC');
        return $this->db->get()->result_array();
    }

    // drug_bill_details

    public function drug_bill_details($id)
    {
        $this->db->select('labtest_bill_details.*,chemical.name as test_name,lab_test_category.name as test_category');
        $this->db->from('labtest_bill_details');
        $this->db->join('chemical', 'chemical.id = labtest_bill_details.test_id', 'left');
        $this->db->join('lab_test_category', 'lab_test_category.id = labtest_bill_details.category_id', 'left');
        $this->db->where('labtest_bill_details.labtest_bill_id', $id);
        $this->db->order_by('labtest_bill_details.id', 'ASC');
        return $this->db->get()->result_array();
    }

    // get patient lab test bill by date range
    public function get_bill_list($start = '', $end = '')
    {
        $this->db->select('labtest_bill.*,SUM(total-discount+tax_amount) as net_amount,patient.name as patient_name,patient.patient_id,staff.name as referral_name,labtest_report.delivery_date,labtest_report.delivery_time,labtest_report.status as delivery_status');
        $this->db->from('labtest_bill');
        $this->db->where('bill_type', 1);
        $this->db->join('labtest_report', 'labtest_report.labtest_bill_id = labtest_bill.id', 'inner');
        $this->db->join('patient', 'patient.id = labtest_bill.patient_id', 'left');
        $this->db->join('staff', 'staff.id = labtest_bill.referral_id', 'left');
        if (!empty($start) && !empty($end)) {
            $this->db->where('labtest_bill.date >=', $start);
            $this->db->where('labtest_bill.date <=', $end);
        }
        if (loggedin_role_id() == 7) {
            $this->db->where('labtest_bill.patient_id', get_loggedin_user_id());
        }
        $this->db->group_by('labtest_bill.bill_no');
        $this->db->order_by('labtest_bill.id', 'ASC');
        return $this->db->get()->result_array();
    }

    // get patient drug  bill by date range
    public function get_drug_bill_list($start = '', $end = '')
    {
        $this->db->select('labtest_bill.*,SUM(total-discount+tax_amount) as net_amount,patient.name as patient_name,patient.patient_id,staff.name as referral_name,labtest_report.delivery_date,labtest_report.delivery_time,labtest_report.status as delivery_status');
        $this->db->from('labtest_bill');
        $this->db->where('bill_type', 2);
        $this->db->join('labtest_report', 'labtest_report.labtest_bill_id = labtest_bill.id', 'inner');
        $this->db->join('patient', 'patient.id = labtest_bill.patient_id', 'left');
        $this->db->join('staff', 'staff.id = labtest_bill.referral_id', 'left');
        if (!empty($start) && !empty($end)) {
            $this->db->where('labtest_bill.date >=', $start);
            $this->db->where('labtest_bill.date <=', $end);
        }
        if (loggedin_role_id() == 7) {
            $this->db->where('labtest_bill.patient_id', get_loggedin_user_id());
        }
        $this->db->group_by('labtest_bill.bill_no');
        $this->db->order_by('labtest_bill.id', 'ASC');
        return $this->db->get()->result_array();
    }

    // get lab test bill due collect report
    public function get_due_collect_report($start = '', $end = '')
    {
        $sql = "SELECT labtest_payment_history.*,labtest_bill.bill_no,labtest_bill.id as bill_id,labtest_bill.date as bill_date,labtest_bill.hash,labtest_bill.status,
		labtest_bill.paid,labtest_bill.due,patient.name as patient_name,patient.patient_id, staff.name as collect_by,payment_type.name as pay_via FROM labtest_payment_history
		LEFT JOIN labtest_bill ON labtest_bill.id = labtest_payment_history.labtest_bill_id LEFT JOIN patient ON patient.id = labtest_bill.patient_id LEFT JOIN staff ON
		staff.id = labtest_payment_history.collect_by LEFT JOIN payment_type ON payment_type.id = labtest_payment_history.method_id WHERE
        labtest_payment_history.coll_type = '1' AND labtest_payment_history.paid_on >= " . $this->db->escape($start) . " AND labtest_payment_history.paid_on <= " .
        $this->db->escape($end) . " ORDER BY labtest_payment_history.id ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // get lab test bill paid/due report
    public function get_bill_duepaid_list($start = '', $end = '', $mode = '')
    {
        $this->db->select('labtest_bill.*,SUM(total-discount+tax_amount) as net_amount,patient.name as patient_name,staff.name as referral_name');
        $this->db->from('labtest_bill');
        $this->db->join('patient', 'patient.id = labtest_bill.patient_id', 'left');
        $this->db->join('staff', 'staff.id = labtest_bill.referral_id', 'left');
        if ($mode == 'due') {
            $this->db->where('labtest_bill.status !=', 3);
        } elseif ($mode == 'paid') {
            $this->db->where('labtest_bill.status', 3);
        }
        $this->db->where('labtest_bill.date >=', $start);
        $this->db->where('labtest_bill.date <=', $end);
        $this->db->group_by('labtest_bill.bill_no');
        $this->db->order_by('labtest_bill.id', 'ASC');
        return $this->db->get()->result_array();
    }
}
