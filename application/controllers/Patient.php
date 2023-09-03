<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Patient extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('patient_model');
        $this->load->model('email_model');
        $this->load->model('test_model');
    }

    public function index()
    {
        redirect(base_url('patient/list'));
    }

    // getting all patient list
    public function view()
    {
        // check access permission
        if (!get_permission('patient', 'is_view')) {
            access_denied();
        }
        $this->data['title'] = translate('patient') . " " . translate('details');
        $this->data['patientlist'] = $this->patient_model->get_patient_list();
        $this->data['sub_page'] = 'patient/view';
        $this->data['main_menu'] = 'patient';
        $this->load->view('layout/index', $this->data);
    }

    // search patient list
    public function search()
    {
        // check access permission
        if (!get_permission('patient', 'is_view')) {
            access_denied();
        }
        $search_text = $this->input->post('search_text');
        $this->data['patientlist'] = $this->patient_model->get_search_list($search_text);
        $this->data['title'] = translate('patient') . " " . translate('details');
        $this->data['sub_page'] = 'patient/view';
        $this->data['main_menu'] = 'patient';
        $this->load->view('layout/index', $this->data);
    }

    // patient information are prepared and stored in the database here
    public function create()
    {
        // check access permission
        if (!get_permission('patient', 'is_add')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            $this->form_validation->set_rules('sname', 'Surname', 'trim|required');
            $this->form_validation->set_rules('fname', 'First name', 'trim|required');
            $this->form_validation->set_rules('age', 'Age', 'trim|required');
            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required');
            $this->form_validation->set_rules('category_id', 'Category', 'required');
            if ($this->form_validation->run() !== false) {
                $data = $this->input->post();
                // PATIENT INFORMATION SAVE IN THE DATABASE
                $user_id = $this->patient_model->save_patient($data);
                set_alert('success', translate('information_has_been_saved_successfully'));

                redirect(base_url('patient/view'));
            }
        }
        $this->data['title'] = translate('patient') . " " . translate('details');
        $this->data['categorylist'] = $this->app_lib->getSelectList('patient_category');
        $this->data['sub_page'] = 'patient/add';
        $this->data['main_menu'] = 'patient';
        $this->load->view('layout/index', $this->data);
    }

    // unique valid username verification is done here
    

    // profile preview and information are controlled here
    public function profile($id = '')
    {
        // check access permission
        if (!get_permission('patient', 'is_edit')) {
            access_denied();
        }
        if (isset($_POST['update'])) {
            $this->form_validation->set_rules('sname', 'Surname', 'trim|required');
            $this->form_validation->set_rules('fname', 'First name', 'trim|required');
            $this->form_validation->set_rules('age', 'Age', 'trim|required');
            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required');
            $this->form_validation->set_rules('category_id', 'Category', 'required');
            if ($this->form_validation->run() !== false) {
                $data = $this->input->post();
                //patient all information update in the database
                $user_id = $this->patient_model->save_patient($data);
                $this->session->set_flashdata('profile_tab', 1);
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('patient/profile/' . $data['patient_id']));
            } else {
                $this->session->set_flashdata('profile_tab', 1);
            }
        } 
        $this->data['patient'] = $this->patient_model->get_single_patient($id);
        $this->data['billlist'] = $this->patient_model->get_bill_list($id);
        $this->data['investlist'] = $this->test_model->get_patient_report_list($id);
        $this->data['categorylist'] = $this->app_lib->getSelectList('patient_category');
        $this->data['title'] = translate('patient') . " " . translate('details');
        $this->data['sub_page'] = 'patient/profile';
        $this->data['main_menu'] = 'patient';
        $this->load->view('layout/index', $this->data);
    }

    // patient information delete here
    public function patient_delete($id = '')
    {
        if (!get_permission('patient', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('staff');
        $this->db->where('user_id', $id);
        $this->db->where('role', 7);
        $this->db->delete('login_credential');
    }


    // patient document details are create here / ajax
    public function document_create()
    {
        if (get_permission('patient', 'is_edit')) {
            $this->form_validation->set_rules('document_title', 'Document Title', 'trim|required');
            $this->form_validation->set_rules('document_category', 'Document Category', 'trim|required');
            if (isset($_FILES['document_file']['name']) && empty($_FILES['document_file']['name'])) {
                $this->form_validation->set_rules('document_file', 'Document File', 'required');
            }
            if ($this->form_validation->run() !== false) {
                $insert_doc = array(
                    'patient_id' => $this->input->post('patient_id'),
                    'title' => $this->input->post('document_title'),
                    'type' => $this->input->post('document_category'),
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
                    $this->db->insert('patient_documents', $insert_doc);
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

    // patient document details are update here / ajax
    public function document_update()
    {
        if (get_permission('patient', 'is_edit')) {
            // validate inputs
            $this->form_validation->set_rules('document_title', 'Document Title', 'trim|required');
            $this->form_validation->set_rules('document_category', 'Document Category', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $document_id = $this->input->post('document_id');
                $insert_doc = array(
                    'title' => $this->input->post('document_title'),
                    'type' => $this->input->post('document_category'),
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
                $this->db->update('patient_documents', $insert_doc);
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

    // patient document details are delete here
    public function document_delete($id)
    {
        if (get_permission('patient', 'is_edit')) {
            $enc_name = $this->db->select('enc_name')->where('id', $id)->get('patient_documents')->row()->enc_name;
            $file_name = FCPATH . 'uploads/attachments/documents/' . $enc_name;
            if (file_exists($file_name)) {
                unlink($file_name);
            }
            $this->db->where('id', $id);
            $this->db->delete('patient_documents');
            $this->session->set_flashdata('documents_details', 1);
        }
    }

    // file downloader
    public function documents_download()
    {
        $encrypt_name = $this->input->get('file');
        $file_name = $this->db->select('file_name')->where('enc_name', $encrypt_name)->get('patient_documents')->row()->file_name;
        $this->load->helper('download');
        force_download($file_name, file_get_contents('./uploads/attachments/documents/' . $encrypt_name));
    }

    // patient login password change here by admin
    public function change_password()
    {
        if (!get_permission('patient', 'is_edit')) {
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
                $this->db->where('role', 7);
                $this->db->update('login_credential', array('password' => $this->app_lib->pass_hashed($password)));
            }
        } else {
            $this->db->where('user_id', $user_id);
            $this->db->where('role', 7);
            $this->db->update('login_credential', array('active' => 0));
        }
        if ($response['status'] == 'success') {
            set_alert('success', translate('information_has_been_updated_successfully'));
        }

        echo json_encode($response);
    }

    // showing disable authentication patient list
    public function disable_authentication()
    {
        if (!get_permission('patient_disable_authentication', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['auth'])) {
            if (!get_permission('patient_disable_authentication', 'is_edit')) {
                access_denied();
            }

            $patientlist = $this->input->post('views_bulk_operations');
            if (isset($patientlist)) {
                foreach ($patientlist as $id) {
                    $this->db->where('user_id', $id);
                    $this->db->where('role', 7);
                    $this->db->update('login_credential', array('active' => 1));
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('patient/disable_authentication'));
            } else {
                set_alert('error', 'Please select at least one item');
            }
        }
        $this->data['patientlist'] = $this->patient_model->get_patient_list(0);
        $this->data['title'] = translate('patient') . " " . translate('details');
        $this->data['sub_page'] = 'patient/disable_authentication';
        $this->data['main_menu'] = 'patient';
        $this->load->view('layout/index', $this->data);
    }

    // add new patient category
    public function category()
    {
        if (isset($_POST['category'])) {
            if (!get_permission('patient_category', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|callback_unique_category');
            if ($this->form_validation->run() !== false) {
                $this->db->insert('patient_category', array('name' => $this->input->post('category_name')));
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('patient/category'));
            }
        }
        $this->data['title'] = translate('patient') . " " . translate('details');
        $this->data['productlist'] = $this->patient_model->get_list('patient_category');
        $this->data['sub_page'] = 'patient/category';
        $this->data['main_menu'] = 'patient';
        $this->load->view('layout/index', $this->data);
    }

    // update existing patient category
    public function category_edit()
    {
        if (!get_permission('patient_category', 'is_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|callback_unique_category');
        if ($this->form_validation->run() !== false) {
            $category_id = $this->input->post('category_id');
            $this->db->where('id', $category_id);
            $this->db->update('patient_category', array('name' => $this->input->post('category_name')));
            set_alert('success', translate('information_has_been_updated_successfully'));
        }
        redirect(base_url('patient/category'));
    }

    // delete patient category from database
    public function category_delete($id)
    {
        if (!get_permission('patient_category', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('patient_category');
    }

    // patient category details send by ajax
    public function categoryDetails()
    {
        if (get_permission('patient_category', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $query = $this->db->get('patient_category');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    // duplicate value check in db
    public function unique_category($name)
    {
        $category_id = $this->input->post('category_id');
        if (!empty($category_id)) {
            $this->db->where_not_in('id', $category_id);
        }
        $this->db->where('name', $name);
        $query = $this->db->get('patient_category');
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
}
