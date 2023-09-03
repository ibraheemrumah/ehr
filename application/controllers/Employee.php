<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Employee.php
 */

class Employee extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('employee_model');
        $this->load->model('email_model');
    }

    public function index()
    {
        redirect(base_url('dashboard'));
    }

    // getting all employee list
    public function view($role = 2)
    {
        if (!get_permission('employee', 'is_view') || ($role == 1 || $role == 7)) {
            access_denied();
        }
        $this->data['act_role'] = $role;
        $this->data['title'] = translate('employee');
        $this->data['sub_page'] = 'employee/view';
        $this->data['main_menu'] = 'employee';
        $this->data['stafflist'] = $this->employee_model->get_employee_list($role);
        $this->load->view('layout/index', $this->data);
    }

    // employees information are prepared and stored in the database here
    public function create()
    {
        if (!get_permission('employee', 'is_add')) {
            access_denied();
        }
        if ($_POST) {
            $this->data['cbbank_skip'] = $this->input->post('cbbank_skip');
            $this->form_validation->set_rules('name', 'name', 'trim|required');
            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('designation_id', 'Designation', 'trim|required');
            $this->form_validation->set_rules('department_id', 'Department', 'trim|required');
            $this->form_validation->set_rules('joining_date', 'Joining Date', 'trim|required');
            $this->form_validation->set_rules('user_role', 'Role', 'trim|required');
            $this->form_validation->set_rules('username', 'Username', 'trim|required|callback_unique_username');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
            $this->form_validation->set_rules('retype_password', 'Retype Password', 'trim|required|matches[password]');
            if (!isset($_POST['cbbank_skip'])) {
                $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
                $this->form_validation->set_rules('holder_name', 'Account Name', 'trim|required');
                $this->form_validation->set_rules('bank_branch', 'Bank Branch', 'trim|required');
                $this->form_validation->set_rules('account_no', 'Account No', 'trim|required');
            }
            if ($this->form_validation->run() !== false) {
                $data = $this->input->post();
                $role_name = get_type_name_by_id('roles', $data['user_role']);

                // save employee information in the database
                $user_id = $this->employee_model->save($data);
                set_alert('success', translate('information_has_been_saved_successfully'));

                // SEND EMAIL FOR ACCOUNT OPENED
                $eTemplate = $this->app_lib->get_table('email_templates', 1, true);
                if ($eTemplate['notified'] == 1) {
                    $message = $eTemplate['template_body'];
                    $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
                    $message = str_replace("{name}", $data['name'], $message);
                    $message = str_replace("{username}", $data['username'], $message);
                    $message = str_replace("{password}", $data['password'], $message);
                    $message = str_replace("{user_role}", $role_name, $message);
                    $message = str_replace("{login_url}", base_url(), $message);
                    $msgData['recipient'] = $data['email'];
                    $msgData['subject'] = $eTemplate['subject'];
                    $msgData['message'] = $message;
                    $this->email_model->send_mail($msgData);
                }
                redirect(base_url('employee/create'));
            }
        }

        $this->data['title'] = translate('employee');
        $this->data['designationlist'] = $this->app_lib->getSelectList('staff_designation');
        $this->data['departmentlist'] = $this->app_lib->getSelectList('staff_department');
        $this->data['sub_page'] = 'employee/add';
        $this->data['main_menu'] = 'employee';
        $this->load->view('layout/index', $this->data);
    }

    // employee information delete here
    public function delete($id = '')
    {
        if (!get_permission('employee', 'is_delete')) {
            access_denied();
        }
        $this->db->delete('staff', array('id' => $id));
        $this->db->delete('staff_balance', array('staff_id' => $id));
        $this->db->delete('login_credential', array('user_id' => $id, 'role != ' => 7));
    }

    // profile preview and information are update here
    public function profile($id = '')
    {
        if (!get_permission('employee', 'is_edit')) {
            access_denied();
        }
        if ($this->input->post('submit') == 'update') {
            // validate inputs
            $this->form_validation->set_rules('name', 'name', 'trim|required');
            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('designation_id', 'Designation', 'trim|required');
            $this->form_validation->set_rules('department_id', 'Department', 'trim|required');
            $this->form_validation->set_rules('joining_date', 'Joining Date', 'trim|required');
            $this->form_validation->set_rules('user_role', 'Role', 'trim|required');
            $this->form_validation->set_rules('username', 'Username', 'trim|required|callback_unique_username');
            if ($this->form_validation->run() !== false) {
                //update employee all information in the database
                $data = $this->input->post();
                $this->employee_model->save($data);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $this->session->set_flashdata('profile_tab', 1);
                redirect(base_url('employee/profile/' . $data['staff_id']));
            } else {
                $this->session->set_flashdata('profile_tab', 1);
            }
        }
        $this->data['staff'] = $this->employee_model->get_single_employee($id);
        $this->data['designationlist'] = $this->app_lib->getSelectList('staff_designation');
        $this->data['departmentlist'] = $this->app_lib->getSelectList('staff_department');
        $this->data['categorylist'] = $this->app_lib->get_document_category();
        $this->data['title'] = translate('employee') . " " . translate('profile');
        $this->data['sub_page'] = 'employee/profile';
        $this->data['main_menu'] = 'employee';
        $this->load->view('layout/index', $this->data);
    }

    // unique valid username verification is done here
    public function unique_username($username)
    {
        if ($this->input->post('staff_id')) {
            $staff_id = $this->input->post('staff_id');
            $login_id = $this->app_lib->get_credential_id($staff_id);
            $this->db->where_not_in('id', $login_id);
        }
        $this->db->where('username', $username);
        $query = $this->db->get('login_credential');

        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_username", translate('username_has_already_been_used'));
            return false;
        } else {
            return true;
        }
    }

    // employee login password change here by admin
    public function change_password()
    {
        if (!get_permission('employee', 'is_edit')) {
            access_denied();
        }
        $user_id = $this->input->post('user_id');
        $password = $this->input->post('password');
        $authentication = $this->input->post('authentication');
        $response['status'] = 'success';
        if (empty($authentication)) {
            if (empty($password) || strlen($password) < 4) {
                $response['status'] = 'fail';
                $response['msg'] = (empty($password) ? "The Password field is required." : "The Password field must be at least 4 characters in length");
            } else {
                $this->db->where('user_id', $user_id);
                $this->db->where('role !=', 7);
                $this->db->update('login_credential', array('password' => $this->app_lib->pass_hashed($password)));
            }
        } else {
            $this->db->where('user_id', $user_id);
            $this->db->where('role !=', 7);
            $this->db->update('login_credential', array('active' => 0));
        }
        if ($response['status'] == 'success') {
            set_alert('success', translate('information_has_been_updated_successfully'));
        }

        echo json_encode($response);
    }

    // employee bank details are create here / ajax
    public function bank_account_create()
    {
        if (get_permission('employee', 'is_edit')) {
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
            $this->form_validation->set_rules('holder_name', 'Holder Name', 'trim|required');
            $this->form_validation->set_rules('bank_branch', 'Bank Branch', 'trim|required');
            $this->form_validation->set_rules('ifsc_code', 'IFSC Code', 'trim|required');
            $this->form_validation->set_rules('account_no', 'Account No', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $array_bank = array(
                    'staff_id' => $this->input->post('staff_id'),
                    'bank_name' => $this->input->post('bank_name'),
                    'holder_name' => $this->input->post('holder_name'),
                    'bank_branch' => $this->input->post('bank_branch'),
                    'bank_address' => $this->input->post('bank_address'),
                    'ifsc_code' => $this->input->post('ifsc_code'),
                    'account_no' => $this->input->post('account_no'),
                );

                $this->db->insert('staff_bank_account', $array_bank);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $this->session->set_flashdata('bank_tab', 1);
                echo json_encode(array('status' => 'success', 'message' => ''));
            } else {
                $array_error = array(
                    'bank_name' => form_error('bank_name'),
                    'holder_name' => form_error('holder_name'),
                    'bank_branch' => form_error('bank_branch'),
                    'ifsc_code' => form_error('ifsc_code'),
                    'account_no' => form_error('account_no'),
                );
                echo json_encode(array('status' => 'fail', 'error' => $array_error));
            }
        }
    }

    // employee bank details are update here / ajax
    public function bank_account_update()
    {
        if (get_permission('employee', 'is_edit')) {
            // validate inputs
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
            $this->form_validation->set_rules('holder_name', 'Holder Name', 'trim|required');
            $this->form_validation->set_rules('bank_branch', 'Bank Branch', 'trim|required');
            $this->form_validation->set_rules('ifsc_code', 'IFSC Code', 'trim|required');
            $this->form_validation->set_rules('account_no', 'Account No', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $edit_bank_id = $this->input->post('bank_id');
                $array_bank = array(
                    'bank_name' => $this->input->post('bank_name'),
                    'holder_name' => $this->input->post('holder_name'),
                    'bank_branch' => $this->input->post('bank_branch'),
                    'bank_address' => $this->input->post('bank_address'),
                    'ifsc_code' => $this->input->post('ifsc_code'),
                    'account_no' => $this->input->post('account_no'),
                );
                $this->db->where('id', $edit_bank_id);
                $this->db->update('staff_bank_account', $array_bank);
                $this->session->set_flashdata('bank_tab', 1);
                set_alert('success', translate('information_has_been_updated_successfully'));
                echo json_encode(array('status' => 'success'));
            } else {
                $array_error = array(
                    'bank_name' => form_error('bank_name'),
                    'holder_name' => form_error('holder_name'),
                    'bank_branch' => form_error('bank_branch'),
                    'ifsc_code' => form_error('ifsc_code'),
                    'account_no' => form_error('account_no'),
                );
                echo json_encode(array('status' => 'fail', 'error' => $array_error));
            }
        }
    }

    // employee bank details are delete here
    public function bankaccount_delete($id)
    {
        if (get_permission('employee', 'is_edit')) {
            $this->db->where('id', $id);
            $this->db->delete('staff_bank_account');
            $this->session->set_flashdata('bank_tab', 1);
        }
    }

    // employee document details are create here / ajax
    public function document_create()
    {
        if (get_permission('employee', 'is_edit')) {
            $this->form_validation->set_rules('document_title', 'Document Title', 'trim|required');
            $this->form_validation->set_rules('document_category', 'Document Category', 'trim|required');
            if (isset($_FILES['document_file']['name']) && empty($_FILES['document_file']['name'])) {
                $this->form_validation->set_rules('document_file', 'Document File', 'required');
            }
            if ($this->form_validation->run() !== false) {
                $insert_doc = array(
                    'staff_id' => $this->input->post('staff_id'),
                    'title' => $this->input->post('document_title'),
                    'category_id' => $this->input->post('document_category'),
                    'remarks' => $this->input->post('remarks'),
                );

                // uploading file using codeigniter upload library
                $config['upload_path'] = './uploads/attachments/documents/';
                $config['allowed_types'] = 'gif|jpg|png|pdf|docx|csv|txt';
                $config['max_size'] = '2048';
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if ($this->upload->do_upload("document_file")) {
                    $insert_doc['file_name'] = $this->upload->data('orig_name');
                    $insert_doc['enc_name'] = $this->upload->data('file_name');
                    $this->db->insert('staff_documents', $insert_doc);
                    set_alert('success', translate('information_has_been_saved_successfully'));
                } else {
                    set_alert('error', strip_tags($this->upload->display_errors()));
                }
                $this->session->set_flashdata('documents_details', 1);
                echo json_encode(array('status' => 'success', 'message' => ''));
            } else {
                $array_error = array(
                    'document_title' => form_error('document_title'),
                    'document_category' => form_error('document_category'),
                    'document_file' => form_error('document_file'),
                );
                echo json_encode(array('status' => 'fail', 'error' => $array_error));
            }
        }
    }

    // employee document details are update here / ajax
    public function document_update()
    {
        if (get_permission('employee', 'is_edit')) {
            // validate inputs
            $this->form_validation->set_rules('document_title', 'Document Title', 'trim|required');
            $this->form_validation->set_rules('document_category', 'Document Category', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $document_id = $this->input->post('document_id');
                $insert_doc = array(
                    'title' => $this->input->post('document_title'),
                    'category_id' => $this->input->post('document_category'),
                    'remarks' => $this->input->post('remarks'),
                );
                if (isset($_FILES["document_file"]) && !empty($_FILES['document_file']['name'])) {
                    $config['upload_path'] = './uploads/attachments/documents/';
                    $config['allowed_types'] = 'gif|jpg|png|pdf|docx|csv|txt';
                    $config['max_size'] = '2048';
                    $config['encrypt_name'] = true;
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload("document_file")) {
                        $exist_file_name = $this->input->post('exist_file_name');
                        $exist_file_path = FCPATH . 'uploads/attachments/documents/' . $exist_file_name;
                        if (file_exists($exist_file_path)) {
                            unlink($exist_file_path);
                        }
                        $insert_doc['file_name'] = $this->upload->data('orig_name');
                        $insert_doc['enc_name'] = $this->upload->data('file_name');
                        set_alert('success', translate('information_has_been_updated_successfully'));
                    } else {
                        set_alert('error', strip_tags($this->upload->display_errors()));
                    }
                }
                $this->db->where('id', $document_id);
                $this->db->update('staff_documents', $insert_doc);
                echo json_encode(array('status' => 'success', 'message' => ''));
                $this->session->set_flashdata('documents_details', 1);
            } else {
                $arrayerror = array(
                    'document_title' => form_error('document_title'),
                    'document_category' => form_error('document_category'),
                );
                echo json_encode(array('status' => 'fail', 'error' => $arrayerror));
            }
        }
    }

    // employee document details are delete here
    public function document_delete($id)
    {
        if (get_permission('employee', 'is_edit')) {
            $enc_name = $this->db->select('enc_name')->where('id', $id)->get('staff_documents')->row()->enc_name;
            $file_name = FCPATH . 'uploads/attachments/documents/' . $enc_name;
            if (file_exists($file_name)) {
                unlink($file_name);
            }
            $this->db->where('id', $id);
            $this->db->delete('staff_documents');
            $this->session->set_flashdata('documents_details', 1);
        }
    }

    // file downloader
    public function documents_download()
    {
        $encrypt_name = $this->input->get('file');
        $file_name = $this->db->select('file_name')->where('enc_name', $encrypt_name)->get('staff_documents')->row()->file_name;
        $this->load->helper('download');
        force_download($file_name, file_get_contents('./uploads/attachments/documents/' . $encrypt_name));
    }

    // frontend doctor  short bio here
    public function add_short_bio($id = '')
    {
        // check access permission
        if (!get_permission('doctor_short_bio', 'is_add')) {
            access_denied();
        }
        $query = $this->db->get_where('front_cms_doctor_bio', array('doctor_id' => $id));
        if ($_POST) {
            $this->form_validation->set_rules('short_bio', 'Add Short Bio', 'trim|required|xss_clean');
            if ($this->form_validation->run() !== false) {
                $shortBio = array(
                    'doctor_id' => $id,
                    'biography' => $this->input->post('short_bio'),
                );
                if ($query->num_rows() > 0) {
                    $this->db->where('id', $query->row()->id);
                    $this->db->update('front_cms_doctor_bio', $shortBio);
                } else {
                    $this->db->insert('front_cms_doctor_bio', $shortBio);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('employee/view/3'));
            }
        }
        $this->data['headerelements'] = array(
            'js' => array(
                'vendor/ckeditor/ckeditor.js',
            ),
        );
        $this->data['bio'] = $query->row_array();
        $this->data['title'] = translate('employee');
        $this->data['sub_page'] = 'employee/add_short_bio';
        $this->data['main_menu'] = 'employee';
        $this->load->view('layout/index', $this->data);
    }

    // employee designation user interface and information are controlled here
    public function designation()
    {
        if ($_POST) {
            if (!get_permission('designation', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('designation_name', 'Designation Name', 'trim|required|callback_unique_designation');
            if ($this->form_validation->run() !== false) {
                $this->db->insert('staff_designation', array('name' => $this->input->post('designation_name')));
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('employee/designation'));
            }
        }
        $this->data['title'] = translate('employee');
        $this->data['sub_page'] = 'employee/designation';
        $this->data['main_menu'] = 'employee';
        $this->load->view('layout/index', $this->data);
    }

    public function designation_edit()
    {
        if (!get_permission('designation', 'is_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('designation_name', 'Designation Name', 'trim|required|callback_unique_designation');
        if ($this->form_validation->run() !== false) {
            $designation_id = $this->input->post('designation_id');
            $this->db->where('id', $designation_id);
            $this->db->update('staff_designation', array('name' => $this->input->post('designation_name')));
            set_alert('success', translate('information_has_been_updated_successfully'));
        }
        redirect(base_url('employee/designation'));
    }

    public function designation_delete($id)
    {
        if (!get_permission('designation', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('staff_designation');
    }

    // unique valid designation name verification is done here
    public function unique_designation($name)
    {
        $designation_id = $this->input->post('designation_id');
        if (!empty($designation_id)) {
            $this->db->where_not_in('id', $designation_id);
        }

        $this->db->where('name', $name);
        $q = $this->db->get('staff_designation');
        if ($q->num_rows() > 0) {
            if (!empty($designation_id)) {
                set_alert('error', "The Designation name are already used");
            } else {
                $this->form_validation->set_message("unique_designation", "The %s name are already used.");
            }
            return false;
        } else {
            return true;
        }
    }

    // employee department user interface and information are controlled here
    public function department()
    {
        if ($_POST) {
            if (!get_permission('department', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('department_name', 'Department Name', 'trim|required|callback_unique_department');
            if ($this->form_validation->run() !== false) {
                $this->db->insert('staff_department', array('name' => $this->input->post('department_name')));
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('employee/department'));
            }
        }
        $this->data['title'] = translate('employee');
        $this->data['sub_page'] = 'employee/department';
        $this->data['main_menu'] = 'employee';
        $this->load->view('layout/index', $this->data);
    }

    public function department_edit()
    {
        if (!get_permission('department', 'is_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('department_name', 'Department Name', 'trim|required|callback_unique_department');
        if ($this->form_validation->run() !== false) {
            $department_id = $this->input->post('department_id');
            $this->db->where('id', $department_id);
            $this->db->update('staff_department', array('name' => $this->input->post('department_name')));
            set_alert('success', translate('information_has_been_updated_successfully'));
        }
        redirect(base_url('employee/department'));
    }

    public function department_delete($id)
    {
        if (!get_permission('department', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('staff_department');
    }

    // unique valid department name verification is done here
    public function unique_department($name)
    {
        $department_id = $this->input->post('department_id');
        if (!empty($department_id)) {
            $this->db->where_not_in('id', $department_id);
        }

        $this->db->where('name', $name);
        $q = $this->db->get('staff_department');
        if ($q->num_rows() > 0) {
            if (!empty($department_id)) {
                set_alert('error', "The Department name are already used");
            } else {
                $this->form_validation->set_message("unique_department", "The %s name are already used.");
            }
            return false;
        } else {
            return true;
        }
    }

    // disable authentication staff list
    public function disable_authentication()
    {
        if (!get_permission('employee_disable_authentication', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $role = $this->input->post('staff_role');
            $this->data['stafflist'] = $this->employee_model->get_employee_list($role, 0);
        }
        if (isset($_POST['auth'])) {
            $stafflist = $this->input->post('views_bulk_operations');
            if (isset($stafflist)) {
                foreach ($stafflist as $id) {
                    $this->db->where('user_id', $id);
                    $this->db->where('role !=', 7);
                    $this->db->update('login_credential', array('active' => 1));
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('employee/disable_authentication'));
            } else {
                set_alert('error', 'Please select at least one item');
            }
        }
        $this->data['title'] = translate('deactivate_account');
        $this->data['sub_page'] = 'employee/disable_authentication';
        $this->data['main_menu'] = 'employee';
        $this->load->view('layout/index', $this->data);
    }
}
