<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Language.php
 */

class Language extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (isset($_POST['save'])) {
            // check access permission
            if (!get_permission('language', 'is_add')) {
                access_denied();
            }
            $language = $this->input->post('name');
            $this->db->insert('language_list', array('name' => ucfirst($language)));
            $id = $this->db->insert_id();

            // upload image
            if (!empty($_FILES["flag"]["name"])) {
                move_uploaded_file($_FILES['flag']['tmp_name'], 'uploads/language_flags/flag_' . $id . '.png');
                $this->create_thumb('uploads/language_flags/flag_' . $id . '.png');
            }
            $language = 'lang_' . $id;
            $this->db->where('id', $id);
            $this->db->update('language_list', array(
                'lang_field' => $language,
            ));

            $this->load->dbforge();
            $fields = array(
                $language => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'collation' => 'utf8_unicode_ci',
                    'null' => true,
                    'default' => '',
                ),
            );
            $this->dbforge->add_column('languages', $fields);
            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('language'));
        }
        $this->data['title'] = translate('settings');
        $this->data['languages'] = $this->db->get('language_list')->result_array();
        $this->data['sub_page'] = 'language/index';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    // language name edit
    public function edit($id = '')
    {
        if (!get_permission('language', 'is_edit')) {
            access_denied();
        }
        if (isset($_POST['update'])) {
            $language = $this->input->post('name');
            $this->db->where('id', $id);
            $this->db->update('language_list', array('name' => $language));

            if (!empty($_FILES["flag"]["name"])) {
                move_uploaded_file($_FILES['flag']['tmp_name'], 'uploads/language_flags/flag_' . $id . '.png');
                $this->create_thumb('uploads/language_flags/flag_' . $id . '.png');
            }

            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('language'));
        }

        $this->data['title'] = translate('settings');
        $this->data['languages'] = $this->db->select('name')->where('id', $id)->get('language_list')->row_array();
        $this->data['sub_page'] = 'language/edit';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    // word update function
    public function word_update($lang = '')
    {
        if (!get_permission('language', 'is_edit')) {
            access_denied();
        }
        if (isset($_POST['update'])) {
            $update_data = array();
            $select_lang = $this->input->post('select_lang');
            $word_array = $this->input->post('word');
            foreach ($word_array as $key => $value) {
                $update_data[] = array(
                    'id' => $key,
                    $select_lang => $value['field'],
                );
            }
            // word update in DB
            $this->db->update_batch('languages', $update_data, 'id');
            $this->db->where('lang_field', $select_lang);
            $this->db->update('language_list', array(
                'updated_at' => date('Y-m-d H:i:s'),
            ));

            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('language'));
        }
        $this->data['title'] = translate('settings');
        $this->data['select_language'] = $lang;
        $this->data['langresult'] = $this->db->query("SELECT * FROM languages")->result_array();
        $this->data['sub_page'] = 'language/word_update';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        if (!get_permission('language', 'is_delete')) {
            access_denied();
        }
        $lang = $this->db->select('lang_field')->where('id', $id)->get('language_list')->row()->lang_field;
        $this->load->dbforge();
        $this->dbforge->drop_column('languages', $lang);
        $this->db->where('id', $id);
        $this->db->delete('language_list');
        if (file_exists('uploads/language_flags/flag_' . $id . '.png')) {
            unlink('uploads/language_flags/flag_' . $id . '.png');
            unlink('uploads/language_flags/flag_' . $id . '_thumb.png');
        }
    }

    // language Ramom image create thumb
    public function create_thumb($source)
    {
        ini_set('memory_limit', '-1');
        $config['image_library'] = 'gd2';
        $config['create_thumb'] = true;
        $config['maintain_ratio'] = true;
        $config['width'] = 16;
        $config['height'] = 12;
        $config['source_image'] = $source;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
        $this->image_lib->clear();
    }

    // session set language
    public function set_language($action = '')
    {
        $this->session->set_userdata('set_lang', $action);
        if (!empty($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(base_url('dashboard'), 'refresh');
        }
    }

    public function status()
    {
        $id = $this->input->post('lang_id');
        $status = $this->input->post('status');
        if ($status == 'true') {
            $array_data['status'] = 1;
            $message = translate('language_published');
        } else {
            $array_data['status'] = 0;
            $message = translate('language_unpublished');
        }
        $this->db->where('id', $id);
        $this->db->update('language_list', $array_data);
        echo $message;
    }
}
