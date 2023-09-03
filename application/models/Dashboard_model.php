<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // get income vs expense chart function
    public function get_income_vs_expense()
    {
        $year = date('Y');
        $cr = array();
        $dr = array();
        for ($month = 1; $month <= 12; $month++) {
            $date = $year . '-' . $month . '-1';
            $month_start = date('Y-m-d', strtotime($date));
            $month_end = date("Y-m-t", strtotime($date));
            $query = 'SELECT IFNULL(SUM(dr),0) as dr, IFNULL(SUM(cr),0) as cr FROM transactions where date between ' . $this->db->escape($month_start) . ' AND ' . $this->db->escape($month_end);
            $query = $this->db->query($query);
            $result = $query->row_array();
            $cr[] = (float) $result['cr'];
            $dr[] = (float) $result['dr'];
        }
        return array('income' => $cr, 'expense' => $dr);
    }

    // get monthly patient fees chart function
    public function get_monthly_patient_fees()
    {
        $days = array();
        $total_bill = array();
        $total_paid = array();
        $total_due = array();
        $startdate = date('Y-m-01');
        $enddate = date('Y-m-t');
        $start = strtotime($startdate);
        $end = strtotime($enddate);
        while ($start <= $end) {
            $today = date('Y-m-d', $start);
            $query = 'SELECT IFNULL(SUM(total-discount+tax_amount),0) as net_bill,IFNULL(SUM(paid),0) as paid,IFNULL(SUM(due),0) as due FROM labtest_bill WHERE date = ' . $this->db->escape($today);
            $query = $this->db->query($query);
            $result = $query->row_array();
            $days[] = date('d', $start);
            $total_bill[] = (float) $result['net_bill'];
            $total_paid[] = (float) $result['paid'];
            $total_due[] = (float) $result['due'];
            $start = strtotime('+1 day', $start);
        }
        return array('total_bill' => $total_bill, 'total_paid' => $total_paid, 'total_due' => $total_due, 'days' => $days);
    }

    // get today invoice quantity function
    public function get_today_invoice_qty()
    {
        $query = $this->db->select('count(id) as total_invoice')
            ->where('date', date('Y-m-d'))
            ->get('labtest_bill')->row_array();
        return $query['total_invoice'];
    }

    // get today commission
    public function get_today_commission()
    {
        $query = $this->db->select('IFNULL(SUM(commission),0) as total_commission')
            ->where('date', date('Y-m-d'))
            ->get('labtest_bill')->row_array();
        return $query['total_commission'];
    }

    // get today income and expense amount
    public function get_today_incomeandexpense()
    {
        $query = $this->db->select('IFNULL(SUM(dr),0) as total_expense,IFNULL(SUM(cr),0) as total_income')
            ->where('date', date('Y-m-d'))
            ->get('transactions')->row_array();
        return $query;
    }

    // get total patient
    public function get_total_patient()
    {
        $query = $this->db->select('count(id) as total_patient')
            ->get('patient')->row_array();
        return $query['total_patient'];
    }

    // get total doctor
    public function get_total_doctor()
    {
        $query = $this->db->select('count(id) as total_doctor')
            ->where('role', 3)
            ->get('login_credential')->row_array();
        return $query['total_doctor'];
    }

    // get total staff
    public function get_total_staff()
    {
        $query = $this->db->select('count(id) as total_staff')
            ->where_not_in('role', array(1, 3, 7))
            ->get('login_credential')->row_array();
        return $query['total_staff'];
    }

    // get total appointment no
    public function get_total_appointment()
    {
        $query = $this->db->select('count(id) as total_appointment')
            ->where('status', 4)
            ->get('appointment')->row_array();
        return $query['total_appointment'];
    }

    // get total appointment no
    public function get_monthly_appointment()
    {
        $confirmed = 0;
        $canceled = 0;
        $pending = 0;
        $this->db->select('id,status');
        $this->db->from('appointment');
        $this->db->where('MONTH(appointment_date)', date('m'));
        $this->db->where('YEAR(appointment_date)', date('Y'));
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $value) {
            if ($value['status'] == 1 || $value['status'] == 2) {
                $confirmed++;
            }
            if ($value['status'] == 3) {
                $canceled++;
            }
            if ($value['status'] == 4) {
                $pending++;
            }
        }
        $array = array($confirmed, $pending, $canceled);
        return $array;

    }
}
