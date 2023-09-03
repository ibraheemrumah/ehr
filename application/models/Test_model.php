<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // test save and update function
    public function save_test($data)
    {
        $insert_test = array(
            'category_id' => $data['category_id'],
            'name' => $data['test_name'],
            'test_code' => $data['test_code'],
            'patient_price' => $data['patient_price'],
            'production_cost' => $data['production_cost'],
            'date' => date("Y-m-d", strtotime($data['date'])),
        );

        if (isset($data['test_id']) && !empty($data['test_id'])) {
            $this->db->where('id', $data['test_id']);
            $this->db->update('lab_test', $insert_test);
        } else {
            $insert_test['created_by'] = get_loggedin_user_id();
            $this->db->insert('lab_test', $insert_test);
        }
    }

    // get test list function
    public function get_test_list()
    {
        $this->db->select('lab_test.*,lab_test_category.name as category_name,staff.name as creator_name');
        $this->db->from('lab_test');
        $this->db->join('lab_test_category', 'lab_test_category.id = lab_test.category_id', 'left');
        $this->db->join('staff', 'staff.id = lab_test.created_by', 'left');
        $this->db->order_by('lab_test.id', 'ASC');
        return $this->db->get()->result_array();
    }
    public function get_patient_report_list($id)
    {
        $this->db->select('labtest_report.*,labtest_bill.id as billid,labtest_bill.bill_no,labtest_bill.date as bill_date,patient.name as patient_name');
        $this->db->from('labtest_report');
        $this->db->join('labtest_bill', 'labtest_bill.id = labtest_report.labtest_bill_id', 'inner');
        $this->db->join('patient', 'patient.id = labtest_bill.patient_id', 'left');
       
        $this->db->where('patient.id',$id);
       
        $this->db->order_by('labtest_report.id', 'ASC');
        return $this->db->get()->result_array();
    }

    // report template save and update function
    public function save_template()
    {
        $insert_data = array(
            'name' => $this->input->post('template_name'),
            'template' => $this->input->post('template', false),
        );
        if (!isset($data['id']) && empty($data['id'])) {
            $this->db->insert('lab_report_template', $insert_data);
        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('lab_report_template', $insert_data);
        }
    }

    // get templete list function
    public function get_templete_list()
    {
        $this->db->select('*');
        $this->db->from('lab_report_template');
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result_array();
    }

    // get pending report list function
    public function get_pending_report_list($report = false, $start = '', $end = '')
    {
        $this->db->select('labtest_report.*,labtest_bill.id as billid,labtest_bill.bill_no,labtest_bill.due,labtest_bill.date as bill_date,patient.name as patient_name');
        $this->db->from('labtest_report');
        $this->db->join('labtest_bill', 'labtest_bill.id = labtest_report.labtest_bill_id', 'inner');
        $this->db->join('patient', 'patient.id = labtest_bill.patient_id', 'left');
        if ($report == true) {
            $this->db->where('labtest_report.status', 2);
        } else {
            $this->db->where('labtest_report.status', 1);
        }
        if (!empty($start) && !empty($end)) {
            $this->db->where('labtest_bill.date >=', $start);
            $this->db->where('labtest_bill.date <=', $end);
        }
        $this->db->order_by('labtest_report.id', 'ASC');
        return $this->db->get()->result_array();
    }

    // get test details function
    public function get_test_details($id = '', $report = false)
    {
        $this->db->select('labtest_report.*,labtest_bill.id as billid,labtest_bill.bill_no,labtest_bill.date as bill_date,patient.name as patient_name,patient.sex,patient.age,patient.mobileno,patient_category.name as cname,staff.name as ref_name');
        $this->db->from('labtest_report');
        $this->db->join('labtest_bill', 'labtest_bill.id = labtest_report.labtest_bill_id', 'inner');
        $this->db->join('patient', 'patient.id = labtest_bill.patient_id', 'left');
        $this->db->join('patient_category', 'patient_category.id = patient.category_id', 'left');
        $this->db->join('staff', 'staff.id = labtest_bill.referral_id', 'left');
        $this->db->where('labtest_report.id', $id);
        if ($report == true) {
            $this->db->where('labtest_report.status', 2);
        } else {
            $this->db->where('labtest_report.status', 1);
        }
        return $this->db->get()->row_array();
    }

    // investigation save and update function
    public function save_investigation()
    {
        $update_data = array(
            'status' => 2,
            'report_description' => $this->input->post('report_description', false),
            'reporting_date' => date("Y-m-d", strtotime($this->input->post('reporting_date'))),
        );
        $db_id = $this->input->post('labtest_report_id');
        $this->db->where('id', $db_id);
        $this->db->update('labtest_report', $update_data);
        return $db_id;
    }
}
