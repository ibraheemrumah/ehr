<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pharmarcy_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // pharmarcy save and update function
    public function save_pharmarcy($data)
    {
        $insert_pharmarcy = array(
            'category_id' => $data['category_id'],
            'name' => $data['pharmarcy_name'],
            'pharmarcy_code' => $data['pharmarcy_code'],
            'patient_price' => $data['patient_price'],
            'production_cost' => $data['production_cost'],
            'date' => date("Y-m-d", strtotime($data['date'])),
        );

        if (isset($data['pharmarcy_id']) && !empty($data['pharmarcy_id'])) {
            $this->db->where('id', $data['pharmarcy_id']);
            $this->db->update('pharmarcy', $insert_pharmarcy);
        } else {
            $insert_pharmarcy['created_by'] = get_loggedin_user_id();
            $this->db->insert('pharmarcy', $insert_pharmarcy);
        }
    }

    // get pharmarcy list function
    public function get_pharmarcy_list()
    {
        $this->db->select('pharmarcy.*,pharmarcy_category.name as category_name,staff.name as creator_name');
        $this->db->from('pharmarcy');
        $this->db->join('pharmarcy_category', 'pharmarcy_category.id = pharmarcy.category_id', 'left');
        $this->db->join('staff', 'staff.id = pharmarcy.created_by', 'left');
        $this->db->order_by('pharmarcy.id', 'ASC');
        return $this->db->get()->result_array();
    }
    public function get_patient_report_list($id)
    {
        $this->db->select('pharmarcy_report.*,pharmarcy_bill.id as billid,pharmarcy_bill.bill_no,pharmarcy_bill.date as bill_date,patient.name as patient_name');
        $this->db->from('pharmarcy_report');
        $this->db->join('pharmarcy_bill', 'pharmarcy_bill.id = pharmarcy_report.pharmarcy_bill_id', 'inner');
        $this->db->join('patient', 'patient.id = pharmarcy_bill.patient_id', 'left');
       
        $this->db->where('patient.id',$id);
       
        $this->db->order_by('pharmarcy_report.id', 'ASC');
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
        $this->db->select('pharmarcy_report.*,pharmarcy_bill.id as billid,pharmarcy_bill.bill_no,pharmarcy_bill.date as bill_date,patient.name as patient_name');
        $this->db->from('pharmarcy_report');
        $this->db->join('pharmarcy_bill', 'pharmarcy_bill.id = pharmarcy_report.pharmarcy_bill_id', 'inner');
        $this->db->join('patient', 'patient.id = pharmarcy_bill.patient_id', 'left');
        if ($report == true) {
            $this->db->where('pharmarcy_report.status', 2);
        } else {
            $this->db->where('pharmarcy_report.status', 1);
        }
        if (!empty($start) && !empty($end)) {
            $this->db->where('pharmarcy_bill.date >=', $start);
            $this->db->where('pharmarcy_bill.date <=', $end);
        }
        $this->db->order_by('pharmarcy_report.id', 'ASC');
        return $this->db->get()->result_array();
    }

    // get pharmarcy details function
    public function get_pharmarcy_details($id = '', $report = false)
    {
        $this->db->select('pharmarcy_report.*,pharmarcy_bill.id as billid,pharmarcy_bill.bill_no,pharmarcy_bill.date as bill_date,patient.name as patient_name,patient.sex,patient.age,patient.mobileno,patient_category.name as cname,staff.name as ref_name');
        $this->db->from('pharmarcy_report');
        $this->db->join('pharmarcy_bill', 'pharmarcy_bill.id = pharmarcy_report.pharmarcy_bill_id', 'inner');
        $this->db->join('patient', 'patient.id = pharmarcy_bill.patient_id', 'left');
        $this->db->join('patient_category', 'patient_category.id = patient.category_id', 'left');
        $this->db->join('staff', 'staff.id = pharmarcy_bill.referral_id', 'left');
        $this->db->where('pharmarcy_report.id', $id);
        if ($report == true) {
            $this->db->where('pharmarcy_report.status', 2);
        } else {
            $this->db->where('pharmarcy_report.status', 1);
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
        $db_id = $this->input->post('pharmarcy_report_id');
        $this->db->where('id', $db_id);
        $this->db->update('pharmarcy_report', $update_data);
        return $db_id;
    }
}
