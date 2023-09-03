<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Leave.php
 */

class Leave extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('leave_model');
        $this->load->model('email_model');
    }

    // leave information are prepared and stored in the database here
    public function index()
    {
        // check access permission
        if (!get_permission('my_leave', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            if (!get_permission('my_leave', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('leave_type_id', 'Leave Type', 'required|callback_leave_check');
            $this->form_validation->set_rules('start_date', 'Date', 'trim|required|callback_date_check');
            if ($this->form_validation->run() !== false) {
                $staff_id = get_loggedin_user_id();
                $leave_type_id = $this->input->post('leave_type_id');
                $reason = $this->input->post('reason');
                $start_date = $this->input->post('start_date');
                $end_date = $this->input->post('end_date');
                $apply_date = date("Y-m-d H:i:s");

                $datetime1 = new DateTime($start_date);
                $datetime2 = new DateTime($end_date);
                $leave_days = $datetime2->diff($datetime1)->format("%a") + 1;

                $orig_file_name = '';
                $enc_file_name = '';
                if (isset($_FILES["attachment_file"]) && !empty($_FILES['attachment_file']['name'])) {
                    $config = array(
                        'upload_path' => './uploads/attachments/leave/',
                        'encrypt_name' => true,
                        'allowed_types' => '*',
                    );
                    $this->upload->initialize($config);
                    $this->upload->do_upload("attachment_file");
                    $orig_file_name = $this->upload->data('orig_name');
                    $enc_file_name = $this->upload->data('file_name');
                }

                $leave_array = array(
                    'staff_id' => $staff_id,
                    'category_id' => $leave_type_id,
                    'reason' => $reason,
                    'start_date' => date("Y-m-d", strtotime($start_date)),
                    'end_date' => date("Y-m-d", strtotime($end_date)),
                    'leave_days' => $leave_days,
                    'status' => 1,
                    'orig_file_name' => $orig_file_name,
                    'enc_file_name' => $enc_file_name,
                    'apply_date' => $apply_date,
                );
                $this->db->insert('leave_application', $leave_array);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('leave'));
            }
        }

        $this->data['leaves'] = $this->leave_model->get_leaves_list(array('leave_application.staff_id' => get_loggedin_user_id()));
        $this->data['leavecategory'] = $this->leave_model->get_list('leave_category');
        $this->data['title'] = translate('leaves');
        $this->data['sub_page'] = 'leave/index';
        $this->data['main_menu'] = 'leave';
        $this->load->view('layout/index', $this->data);
    }

    // manage staff leave
    public function manage_all()
    {
        if (!get_permission('leave_manage', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            if (!get_permission('leave_manage', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('staff_role', 'Role', 'trim|required');
            $this->form_validation->set_rules('staff_id', 'Staff Name', 'trim|required');
            $this->form_validation->set_rules('leave_type_id', 'Leave Type', 'required|callback_leave_check');
            $this->form_validation->set_rules('start_date', 'Date', 'trim|required|callback_date_check');
            if ($this->form_validation->run() !== false) {
                $staff_id = $this->input->post('staff_id');
                $leave_type_id = $this->input->post('leave_type_id');
                $start_date = $this->input->post('start_date');
                $end_date = $this->input->post('end_date');
                $reason = $this->input->post('reason');
                $comments = $this->input->post('comments');
                $apply_date = date("Y-m-d H:i:s");

                $datetime1 = new DateTime($start_date);
                $datetime2 = new DateTime($end_date);
                $leave_days = $datetime2->diff($datetime1)->format("%a") + 1;

                $orig_file_name = '';
                $enc_file_name = '';
                // upload attachment file
                if (isset($_FILES["attachment_file"]) && !empty($_FILES['attachment_file']['name'])) {
                    $config['upload_path'] = './uploads/attachments/leave/';
                    $config['allowed_types'] = "gif|jpg|png|pdf|docx|csv";
                    $config['max_size'] = '1024';
                    $config['encrypt_name'] = true;
                    $this->upload->initialize($config);
                    $this->upload->do_upload("attachment_file");
                    $orig_file_name = $this->upload->data('orig_name');
                    $enc_file_name = $this->upload->data('file_name');
                }

                $arrayData = array(
                    'staff_id' => $staff_id,
                    'category_id' => $leave_type_id,
                    'reason' => $reason,
                    'start_date' => date("Y-m-d", strtotime($start_date)),
                    'end_date' => date("Y-m-d", strtotime($end_date)),
                    'leave_days' => $leave_days,
                    'status' => 2,
                    'orig_file_name' => $orig_file_name,
                    'enc_file_name' => $enc_file_name,
                    'apply_date' => $apply_date,
                    'approved_by' => get_loggedin_user_id(),
                    'comments' => $comments,
                );
                $this->db->insert('leave_application', $arrayData);

                // send email for leave approval
                $eTemplate = $this->app_lib->get_table('email_templates', 7, true);
                if ($eTemplate['notified'] == 1) {
                    $getStaff = $this->db->select('name,email')->where('id', $staff_id)->get('staff')->row_array();
                    $getStaff = $this->db->select('name,email')->where('id', $arrayData['staff_id'])->get('staff')->row_array();
                    $message = $eTemplate['template_body'];
                    $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
                    $message = str_replace("{name}", $getStaff['name'], $message);
                    $message = str_replace("{admin_comments}", $arrayData['comments'], $message);
                    $message = str_replace("{start_date}", _d($start_date), $message);
                    $message = str_replace("{end_date}", _d($end_date), $message);
                    $msgData['recipient'] = $getStaff['email'];
                    $msgData['subject'] = $eTemplate['subject'];
                    $msgData['message'] = $message;
                    $this->email_model->send_mail($msgData);
                }

                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('leave/manage_all'));
            }
        }

        $this->data['leaves'] = $this->leave_model->get_leaves_list();
        $this->data['leavecategory'] = $this->leave_model->get_list('leave_category');
        $this->data['title'] = translate('leaves');
        $this->data['sub_page'] = 'leave/manage_all';
        $this->data['main_menu'] = 'leave';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        if (!get_permission('leave_manage', 'is_delete')) {
            access_denied();
        }
        $getName = $this->db->select('enc_file_name')->where(array('id' => $id))->get('leave_application')->row_array();
        $filepath = FCPATH . 'uploads/attachments/leave/' . $getName['enc_file_name'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        $this->db->where('id', $id);
        $this->db->delete('leave_application');
    }

    public function date_check($start_date)
    {
        $today = date('Y-m-d');
        if ($today == $start_date) {
            $this->form_validation->set_message('date_check', "You can not leave the current day.");
            return false;
        }
        if ($this->input->post('staff_id')) {
            $staff_id = $this->input->post('staff_id');
        } else {
            $staff_id = get_loggedin_user_id();
        }
        $end_date = $this->input->post('end_date');
        $getUserLeaves = $this->db->get_where('leave_application', array('staff_id' => $staff_id))->result();
        if (!empty($getUserLeaves)) {
            foreach ($getUserLeaves as $user_leave) {
                $get_dates = $this->user_leave_days($user_leave->start_date, $user_leave->end_date);
                $result_start = in_array($start_date, $get_dates);
                $result_end = in_array($end_date, $get_dates);
                if (!empty($result_start) || !empty($result_end)) {
                    $this->form_validation->set_message('date_check', 'Already have leave in the selected time.');
                    return false;
                }
            }
        }
        return true;
    }

    public function leave_check($type_id)
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        if ($this->input->post('staff_id')) {
            $staff_id = $this->input->post('staff_id');
        } else {
            $staff_id = get_loggedin_user_id();
        }
        if (!empty($start_date) && !empty($end_date)) {
            $leave_total = get_type_name_by_id('leave_category', $type_id, 'days');
            $total_spent = $this->db->select('IFNULL(SUM(leave_days), 0) as total_days')
                ->where(array('staff_id' => $staff_id, 'category_id' => $type_id, 'status' => '2'))
                ->get('leave_application')->row()->total_days;

            $datetime1 = new DateTime($start_date);
            $datetime2 = new DateTime($end_date);
            $leave_days = $datetime2->diff($datetime1)->format("%a") + 1;
            $left_leave = ($leave_total - $total_spent);
            if ($left_leave < $leave_days) {
                $this->form_validation->set_message('leave_check', "Applyed for $leave_days days, get maximum $left_leave Days days.");
                return false;
            } else {
                return true;
            }
        } else {
            $this->form_validation->set_message('leave_check', "Select all required field.");
            return false;
        }
    }

    public function download($id = '', $file = '')
    {
        if (!empty($id) && !empty($file)) {
            $leave = $this->db->select('orig_file_name,enc_file_name')->where('id', $id)->get('leave_application')->row();
            if ($file != $leave->enc_file_name) {
                access_denied();
            }
            $this->load->helper('download');
            $fileData = file_get_contents('./uploads/attachments/leave/' . $leave->enc_file_name);
            force_download($leave->orig_file_name, $fileData);
        }
    }

    public function request_delete($id = '')
    {
        if (!get_permission('my_leave', 'is_delete')) {
            access_denied();
        }
        $staff_id = get_loggedin_user_id();
        $getName = $this->db->select('enc_file_name')->where(array('id' => $id, 'staff_id' => $staff_id, 'status' => 1))->get('leave_application')->row_array();
        $filepath = FCPATH . 'uploads/attachments/leave/' . $getName['enc_file_name'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        $this->db->where('staff_id', $staff_id);
        $this->db->where('status', 1);
        $this->db->where('id', $id);
        $this->db->delete('leave_application');
    }

    // staff leave request approval
    public function request_approval($id = '')
    {
        if (!get_permission('leave_manage', 'is_add')) {
            access_denied();
        }
        if ($this->input->post('save')) {
            $arrayData = array(
                'status' => $this->input->post('status'),
                'approved_by' => get_loggedin_user_id(),
                'comments' => $this->input->post('comments'),
            );
            $this->db->where('id', $id);
            $this->db->update('leave_application', $arrayData);

            $email_alert = false;
            if ($arrayData['status'] == 2) {
                $eTemplate = $this->app_lib->get_table('email_templates', 7, true);
                if ($eTemplate['notified'] == 1) {
                    $email_alert = true;
                }
            } elseif ($arrayData['status'] == 3) {
                $eTemplate = $this->app_lib->get_table('email_templates', 8, true);
                if ($eTemplate['notified'] == 1) {
                    $email_alert = true;
                }
            }

            // send email for leave approval/reject alert
            if ($email_alert == true) {
                $getApplication = $this->leave_model->get_list('leave_application', array('id' => $id), true, 'staff_id,start_date,end_date');
                $getStaff = $this->db->select('name,email')->where('id', $getApplication['staff_id'])->get('staff')->row_array();
                $message = $eTemplate['template_body'];
                $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
                $message = str_replace("{name}", $getStaff['name'], $message);
                $message = str_replace("{admin_comments}", $arrayData['comments'], $message);
                $message = str_replace("{start_date}", _d($getApplication['start_date']), $message);
                $message = str_replace("{end_date}", _d($getApplication['end_date']), $message);
                $msgData['recipient'] = $getStaff['email'];
                $msgData['subject'] = $eTemplate['subject'];
                $msgData['message'] = $message;
                $this->email_model->send_mail($msgData);
            }
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('leave/manage_all'));
        }
    }

    // leave category information are prepared and stored in the database here
    public function category()
    {
        if (isset($_POST['save'])) {
            if (!get_permission('leave_category', 'is_add')) {
                access_denied();
            }

            $this->form_validation->set_rules('leave_category', 'Leave Category', 'trim|required|callback_unique_category');
            $this->form_validation->set_rules('leave_days', 'Leave Days', 'required');
            if ($this->form_validation->run() !== false) {
                $arrayData = array(
                    'name' => $this->input->post('leave_category'),
                    'days' => $this->input->post('leave_days'),
                );
                $this->db->insert('leave_category', $arrayData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('leave/category'));
            }
        }
        $this->data['title'] = translate('leaves');
        $this->data['category'] = $this->leave_model->get_list('leave_category');
        $this->data['sub_page'] = 'leave/category';
        $this->data['main_menu'] = 'leave';
        $this->load->view('layout/index', $this->data);
    }

    public function category_edit()
    {
        if (!get_permission('leave_category', 'is_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('leave_category', 'Leave Category', 'trim|required|callback_unique_category');
        if ($this->form_validation->run() !== false) {
            $category_id = $this->input->post('category_id');
            $arrayData = array(
                'name' => $this->input->post('leave_category'),
                'days' => $this->input->post('leave_days'),
            );
            $this->db->where('id', $category_id);
            $this->db->update('leave_category', $arrayData);
            set_alert('success', translate('information_has_been_updated_successfully'));
        }
        redirect(base_url('leave/category'));
    }

    public function category_delete($id = '')
    {
        if (!get_permission('leave_category', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('leave_category');
    }

    // unique valid name verification is done here
    public function unique_category($name)
    {
        $category_id = $this->input->post('category_id');
        if (!empty($category_id)) {
            $this->db->where_not_in('id', $category_id);
        }
        $this->db->where('name', $name);
        $query = $this->db->get('leave_category');
        if ($query->num_rows() > 0) {
            if (!empty($category_id)) {
                set_alert('error', "The Category name are already used");
            } else {
                $this->form_validation->set_message("unique_category", "The %s name are already used.");
            }
            return false;
        } else {
            return true;
        }
    }

    public function user_leave_days($start_date, $end_date)
    {
        $dates = array();
        $current = strtotime($start_date);
        $end_date = strtotime($end_date);
        while ($current <= $end_date) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }
        return $dates;
    }
}
