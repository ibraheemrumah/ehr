<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // payslip save and update function
    public function save_payslip($data)
    {
        $staff_id = $data['staff_id'];
        $month = $data['month'];
        $year = $data['year'];
        $total_allowance = $data['total_allowance'];
        $total_deduction = $data['total_deduction'];
        $net_salary = $data['net_salary'];
        $overtime_hour = $data['overtime_total_hour'];
        $overtime_amount = $data['overtime_amount'];
        $salary_template_id = $data['salary_template_id'];
        $exist_verify = $this->db->select('id')->where(array('staff_id' => $staff_id, 'month' => $month, 'year' => $year))->get('payslip')->num_rows();
        if ($exist_verify == 0) {
            $arrayPayslip = array(
                'staff_id' => $staff_id,
                'month' => $month,
                'year' => $year,
                'basic_salary' => $data['basic_salary'],
                'total_allowance' => $total_allowance,
                'total_deduction' => $total_deduction,
                'net_salary' => $net_salary,
                'bill_no' => $this->app_lib->get_bill_no('payslip'),
                'remarks' => $data['remarks'],
                'pay_via' => $data['pay_via'],
                'hash' => app_generate_hash(),
                'paid_by' => get_loggedin_user_id(),
                'payment_date' => date('Y-m-d'),
            );
            $this->db->insert('payslip', $arrayPayslip);
            $payslip_id = $this->db->insert_id();

            $payslipData = array();
            $getTemplate = $this->get_list("salary_template_details", array('salary_template_id' => $salary_template_id));
            foreach ($getTemplate as $row) {
                if ($row['type'] == 1) {
                    $payslipData[] = array(
                        'payslip_id' => $payslip_id,
                        'name' => $row['name'],
                        'amount' => $row['amount'],
                        'type' => 1,
                    );
                } else {
                    $payslipData[] = array(
                        'payslip_id' => $payslip_id,
                        'name' => $row['name'],
                        'amount' => $row['amount'],
                        'type' => 2,
                    );
                }
            }

            if (!empty($overtime_hour) && $overtime_hour != 0) {
                $payslipData[] = array(
                    'payslip_id' => $payslip_id,
                    'name' => "Overtime Salary (" . $overtime_hour . " Hour)",
                    'amount' => $overtime_amount,
                    'type' => 1,
                );
            }
            $this->db->insert_batch('payslip_details', $payslipData);
            $payslip_url = base_url('payroll/invoice/' . $payslip_id . '/' . $arrayPayslip['hash']);

            // send salary paid email
            $eTemplate = $this->app_lib->get_table('email_templates', 5, true);
            if ($eTemplate['notified'] == 1) {
                $message = $eTemplate['template_body'];
                $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
                $message = str_replace("{name}", get_type_name_by_id('staff', $staff_id), $message);
                $message = str_replace("{month_year}", date('F', strtotime($year . '-' . $month)), $message);
                $message = str_replace("{payslip_no}", $arrayPayslip['bill_no'], $message);
                $message = str_replace("{payslip_url}", $payslip_url, $message);
                $msgData['recipient'] = get_type_name_by_id('staff', $staff_id, 'email');
                $msgData['subject'] = $eTemplate['subject'];
                $msgData['message'] = $message;
                $this->email_model->send_mail($msgData);
            }
            return ['status' => 'success', 'uri' => $payslip_url];
        } else {
            return ['status' => 'failed'];
        }
    }

    // get staff all details
    public function get_employee_list($role_id, $designation)
    {
        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id, roles.name as role');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->where('login_credential.role', $role_id);
        $this->db->where('login_credential.active', 1);
        $this->db->where('staff.designation', $designation);
        return $this->db->get()->result();
    }

    // get employee payment list
    public function get_employee_payment_list($role_id, $month, $year)
    {
        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id, roles.name as role, IFNULL(payslip.id, 0) as salary_id, payslip.hash as salary_hash,salary_template.name as template_name, salary_template.basic_salary');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->join('payslip', 'payslip.staff_id = staff.id and payslip.month = ' . $this->db->escape($month) . ' and payslip.year = ' . $this->db->escape($year), 'left');
        $this->db->join('salary_template', 'salary_template.id = staff.salary_template_id', 'left');
        $this->db->where('login_credential.role', $role_id);
        $this->db->where('login_credential.active', 1);
        $this->db->where('staff.salary_template_id !=', 0);
        return $this->db->get()->result();
    }

    // get employee payment list
    public function get_employee_payment($staff_id)
    {
        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id,roles.name as role,salary_template.name as template_name,salary_template.basic_salary,salary_template.overtime_salary');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->join('salary_template', 'salary_template.id = staff.salary_template_id', 'left');
        $this->db->where('staff.id', $staff_id);
        return $this->db->get()->row_array();
    }

    public function get_invoice($id)
    {
        $this->db->select('payslip.*,staff.name as staff_name,staff.mobileno,IFNULL(staff_designation.name, "N/A") as designation_name,IFNULL(staff_department.name, "N/A") as department_name');
        $this->db->from('payslip');
        $this->db->join('staff', 'staff.id = payslip.staff_id', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->where('payslip.id', $id);
        return $this->db->get()->row_array();
    }

    // get summary report function
    public function get_summary($month = '', $year = '')
    {
        $this->db->select('payslip.*,staff.name as staff_name,staff.mobileno,IFNULL(staff_designation.name, "N/A") as designation_name,IFNULL(staff_department.name, "N/A") as department_name,payment_type.name as payvia');
        $this->db->from('payslip');
        $this->db->join('staff', 'staff.id = payslip.staff_id', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->join('payment_type', 'payment_type.id = payslip.pay_via', 'left');
        $this->db->where('payslip.month', $month);
        $this->db->where('payslip.year', $year);
        return $this->db->get()->result_array();
    }
}
