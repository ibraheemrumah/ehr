<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 1.0
 * @developed by : techtune
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/techtune
 * @filename : Inventory.php
 */

class Inventory extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('inventory_model');
    }

    public function index()
    {
        $this->chemical();
    }

    // add new chemical
    public function chemical()
    {
        // check access permission
        if (!get_permission('chemical', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('chemical', 'is_add')) {
                access_denied();
            }
            // save chemical information in the database
            $post = $this->input->post();
            $this->inventory_model->save_chemical($post);
            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('inventory/chemical'));
        }

        $this->data['chemicallist'] = $this->inventory_model->get_chemical_list();
        $this->data['categorylist'] = $this->app_lib->getSelectList('chemical_category');
        $this->data['unitlist'] = $this->app_lib->getSelectList('chemical_unit');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/chemical';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    // update existing chemical
    public function chemical_edit($id)
    {
        // check access permission
        if (!get_permission('chemical', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $post = $this->input->post();
            $this->inventory_model->save_chemical($post);
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('inventory/chemical'));
        }

        $this->data['chemical'] = $this->inventory_model->get_list('chemical', array('id' => $id), true);
        $this->data['categorylist'] = $this->app_lib->getSelectList('chemical_category');
        $this->data['unitlist'] = $this->app_lib->getSelectList('chemical_unit');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/chemical_edit';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    // delete chemical from database
    public function chemical_delete($id)
    {
        // check access permission
        if (!get_permission('chemical', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('chemical');
    }

    public function category()
    {
        if (isset($_POST['category'])) {
            if (!get_permission('chemical_category', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|callback_unique_category');
            if ($this->form_validation->run() !== false) {
                $this->db->insert('chemical_category', array('name' => $this->input->post('category_name')));
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('inventory/category'));
            }
        }
        $this->data['categorylist'] = $this->inventory_model->get_list('chemical_category');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/category';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    public function category_edit()
    {
        // check access permission
        if (!get_permission('chemical_category', 'is_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('category_name', 'Category Name', 'trim|required|callback_unique_category');
        if ($this->form_validation->run() !== false) {
            $category_id = $this->input->post('category_id');
            $this->db->where('id', $category_id);
            $this->db->update('chemical_category', array('name' => $this->input->post('category_name')));
            set_alert('success', translate('information_has_been_updated_successfully'));
        }
        redirect(base_url('inventory/category'));
    }

    // delete category from database
    public function category_delete($id)
    {
        // check access permission
        if (!get_permission('chemical_category', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('chemical_category');
    }

    // duplicate category name check in db
    public function unique_category($name)
    {
        $category_id = $this->input->post('category_id');
        if (!empty($category_id)) {
            $this->db->where_not_in('id', $category_id);
        }
        $this->db->where('name', $name);
        $query = $this->db->get('chemical_category');
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

    // add new supplier member
    public function supplier()
    {
        // check access permission
        if (!get_permission('chemical_supplier', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['chemical'])) {
            if (!get_permission('chemical_supplier', 'is_add')) {
                access_denied();
            }
            $post = $this->input->post();
            $this->inventory_model->save_supplier($post);
            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('inventory/supplier'));
        }

        $this->data['supplierlist'] = $this->app_lib->get_table('supplier');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/supplier';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    // update existing supplier member
    public function supplier_edit($id)
    {
        // check access permission
        if (!get_permission('chemical_supplier', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $post = $this->input->post();
            $this->inventory_model->save_supplier($post);
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('inventory/supplier'));
        }

        $this->data['supplier'] = $this->inventory_model->get_list('supplier', array('id' => $id), true);
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/supplier_edit';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    // delete existing supplier member
    public function supplier_delete($id)
    {
        // check access permission
        if (!get_permission('chemical_supplier', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('supplier');
    }

    public function unit()
    {
        if (isset($_POST['unit'])) {
            if (!get_permission('chemical_unit', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('unit_name', 'Unit Name', 'trim|required|callback_unique_unit');
            if ($this->form_validation->run() !== false) {
                $this->db->insert('chemical_unit', array('name' => $this->input->post('unit_name')));
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('inventory/unit'));
            }
        }
        $this->data['unitlist'] = $this->inventory_model->get_list('chemical_unit');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/unit';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    public function unit_edit()
    {
        if (!get_permission('chemical_unit', 'is_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('unit_name', 'Unit Name', 'trim|required|callback_unique_unit');
        if ($this->form_validation->run() !== false) {
            $unit_id = $this->input->post('unit_id');
            $this->db->where('id', $unit_id);
            $this->db->update('chemical_unit', array('name' => $this->input->post('unit_name')));
            set_alert('success', translate('information_has_been_updated_successfully'));
        }
        redirect(base_url('inventory/unit'));
    }

    public function unit_delete($id)
    {
        if (!get_permission('chemical_unit', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('chemical_unit');
    }

    public function unitDetails()
    {
        if (get_permission('chemical_unit', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $query = $this->db->get('chemical_unit');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    public function unique_unit($name)
    {
        $unit_id = $this->input->post('unit_id');
        if (!empty($unit_id)) {
            $this->db->where_not_in('id', $unit_id);
        }
        $this->db->where('name', $name);
        $query = $this->db->get('chemical_unit');
        if ($query->num_rows() > 0) {
            if (!empty($unit_id)) {
                set_alert('error', "The Category name are already used");
            } else {
                $this->form_validation->set_message("unique_unit", "The %s name are already used.");
            }
            return false;
        } else {
            return true;
        }
    }

    // add new chemical purchase bill
    public function purchase()
    {
        if (!get_permission('chemical_purchase', 'is_view')) {
            access_denied();
        }
        
        $this->data['purchaselist'] = $this->inventory_model->get_purchase_list();
        $this->data['chemicallist'] = $this->inventory_model->get_list('chemical', '', false, 'id,code,name');
        $this->data['supplierlist'] = $this->app_lib->getSelectList('supplier');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/purchase';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    public function purchase_save() {
        if (!get_permission('chemical_purchase', 'is_add')) {
            access_denied();
        }
        if ($_POST) {
            // validate inputs
            $this->form_validation->set_rules('supplier_id', 'Supplier', 'trim|required');
            $this->form_validation->set_rules('bill_no', 'Bill No', 'trim|required');
            $this->form_validation->set_rules('purchase_status', 'Purchase Status', 'trim|required');
            $this->form_validation->set_rules('date', 'Date', 'trim|required');
            $items = $this->input->post('purchases');
            foreach ($items as $key => $value) {
                $this->form_validation->set_rules('purchases[' . $key . '][chemical]', 'Category', 'trim|required');
                $this->form_validation->set_rules('purchases[' . $key . '][quantity]', 'Test Name', 'trim|required');
            }
            if ($this->form_validation->run() == false) {
                $msg = array(
                    'supplier_id' => form_error('supplier_id'),
                    'bill_no' => form_error('bill_no'),
                    'purchase_status' => form_error('purchase_status'),
                    'date' => form_error('date'),
                    'delivery_time' => form_error('delivery_time'),
                    'payment_amount' => form_error('payment_amount'),
                );
                foreach ($items as $key => $value) {
                    $msg['chemical' . $key] = form_error('purchases[' . $key . '][chemical]');
                    $msg['quantity' . $key] = form_error('purchases[' . $key . '][quantity]');
                }
                $array = array('status' => 'fail', 'url' => '', 'error' => $msg);
            } else {
                $data = $this->input->post();
                $this->inventory_model->save_purchase($data);
                $url = base_url('inventory/purchase');
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            }
            echo json_encode($array);
        }
    }

    public function purchase_edit_save() {
        if (!get_permission('chemical_purchase', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            // validate inputs
            $this->form_validation->set_rules('supplier_id', 'Supplier', 'trim|required');
            $this->form_validation->set_rules('bill_no', 'Bill No', 'trim|required');
            $this->form_validation->set_rules('purchase_status', 'Purchase Status', 'trim|required');
            $this->form_validation->set_rules('date', 'Date', 'trim|required');
            $items = $this->input->post('purchases');
            foreach ($items as $key => $value) {
                $this->form_validation->set_rules('purchases[' . $key . '][chemical]', 'Category', 'trim|required');
                $this->form_validation->set_rules('purchases[' . $key . '][quantity]', 'Test Name', 'trim|required');
            }
            if ($this->form_validation->run() == false) {
                $msg = array(
                    'supplier_id' => form_error('supplier_id'),
                    'bill_no' => form_error('bill_no'),
                    'purchase_status' => form_error('purchase_status'),
                    'date' => form_error('date'),
                    'delivery_time' => form_error('delivery_time'),
                    'payment_amount' => form_error('payment_amount'),
                );
                foreach ($items as $key => $value) {
                    $msg['chemical' . $key] = form_error('purchases[' . $key . '][chemical]');
                    $msg['quantity' . $key] = form_error('purchases[' . $key . '][quantity]');
                }
                $array = array('status' => 'fail', 'url' => '', 'error' => $msg);
            } else {

                $purchase_bill_id = $this->input->post('purchase_bill_id');
                $supplier_id = $this->input->post('supplier_id');
                $bill_no = $this->input->post('bill_no');
                $purchase_status = $this->input->post('purchase_status');
                $grand_total = $this->input->post('grand_total');
                $discount = $this->input->post('total_discount');
                $purchase_paid = $this->input->post('purchase_paid');
                $net_total = $this->input->post('net_grand_total');
                $date = $this->input->post('date');
                $remarks = $this->input->post('remarks');
                if ($net_total <= $purchase_paid) {
                    $payment_status = 3;
                } else {
                    $payment_status = 2;
                }
                $array_invoice = array(
                    'supplier_id' => $supplier_id,
                    'bill_no' => $bill_no,
                    'remarks' => $remarks,
                    'total' => $grand_total,
                    'discount' => $discount,
                    'due' => ($net_total - $purchase_paid),
                    'purchase_status' => $purchase_status,
                    'payment_status' => $payment_status,
                    'hash' => app_generate_hash(),
                    'date' => date('Y-m-d', strtotime($date)),
                    'modifier_id' => get_loggedin_user_id(),
                );
                $this->db->where('id', $purchase_bill_id);
                $this->db->update('purchase_bill', $array_invoice);

                $purchases = $this->input->post('purchases');
                foreach ($purchases as $key => $value) {
                    $array_chemical = array(
                        'purchase_bill_id' => $purchase_bill_id,
                        'chemical_id' => $value['chemical'],
                        'unit_price' => $value['unit_price'],
                        'discount' => $value['discount'],
                        'quantity' => $value['quantity'],
                        'sub_total' => $value['sub_total'],
                    );

                    if (isset($value['old_bill_details_id'])) {
                        $this->db->where('id', $value['old_bill_details_id']);
                        $this->db->update('purchase_bill_details', $array_chemical);
                    } else {
                        $this->db->insert('purchase_bill_details', $array_chemical);
                    }
                }
                $url = base_url('inventory/purchase');
                set_alert('success', translate('information_has_been_updated_successfully'));
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            }
            echo json_encode($array);
        }
    } 

    // update existing chemical purchase bill
    public function purchase_edit($id)
    {
        if (!get_permission('chemical_purchase', 'is_edit')) {
            access_denied();
        }
        $this->data['purchaselist'] = $this->inventory_model->get_list('purchase_bill', array('id' => $id), true);
        $this->data['chemicallist'] = $this->inventory_model->get_list('chemical', "", false, 'id,code,name');
        $this->data['supplierlist'] = $this->app_lib->getSelectList('supplier');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/purchase_edit';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    // delete chemical purchase bill from database
    public function purchase_delete($id)
    {
        if (!get_permission('chemical_purchase', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->delete('purchase_bill');
        $this->db->where('purchase_bill_id', $id);
        $this->db->delete('purchase_bill_details');
        $this->db->where('purchase_bill_id', $id);
        $this->db->delete('purchase_payment_history');
    }

    public function purchase_bill($id = '', $hash = '')
    {
        if (!get_permission('chemical_purchase', 'is_view')) {
            access_denied();
        }
        check_hash_restrictions('purchase_bill', $id, $hash);
        $this->data['payvia_list'] = $this->app_lib->getSelectList('payment_type');
        $this->data['billdata'] = $this->inventory_model->get_invoice($id);
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/purchase_bill';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    // purchase partially payment add
    public function add_payment()
    {
        if (!get_permission('purchase_payment', 'is_add')) {
            access_denied();
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['getbill'] = $this->db->select('id,due,hash')->where('id', $data['purchase_bill_id'])->get('purchase_bill')->row_array();
            $this->form_validation->set_rules('paid_date', 'Paid Date', 'trim|required');
            $this->form_validation->set_rules('payment_amount', 'Payment Amount', 'trim|required|numeric|greater_than[1]|callback_payment_validation');
            $this->form_validation->set_rules('pay_via', 'Pay Via', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $res = $this->inventory_model->save_payment($data);
                if ($res['status'] == true) {
                    set_alert('success', translate('payment_successfull'));
                    if (get_permission('purchase_payment', 'is_view')) {
                        $this->session->set_flashdata('active_tab', 2);
                    }

                } else {
                    set_alert('error', $res['msg']);
                    $this->session->set_flashdata('active_tab', 1);
                }
                redirect(base_url('inventory/purchase_bill/' . $data['purchase_bill_id'] . '/' . $data['getbill']['hash']));
            } else {
                $this->session->set_flashdata('active_tab', 3);
                $this->purchase_bill($data['purchase_bill_id'], $data['getbill']['hash']);
            }
        }
    }

    // payment amount validation
    public function payment_validation($amount)
    {
        $bill_id = $this->input->post('purchase_bill_id');
        $due_amount = $this->db->select('due')->where('id', $bill_id)->get('purchase_bill')->row()->due;
        if ($amount <= $due_amount) {
            return true;
        } else {
            $this->form_validation->set_message("payment_validation", "Payment Amount Is More Than The Due Amount.");
            return false;
        }
    }

    public function chemical_stock()
    {
        if (!get_permission('chemical_stock', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['stock'])) {
            if (!get_permission('chemical_stock', 'is_add')) {
                access_denied();
            }
            $post = $this->input->post();
            $this->inventory_model->save_stock($post);
            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('inventory/chemical_stock'));
        }
        $this->data['stocktlist'] = $this->inventory_model->get_stockt_list();
        $this->data['categorylist'] = $this->app_lib->getSelectList('chemical_category');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/stock';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    public function chemical_stock_edit($id)
    {
        if (!get_permission('chemical_stock', 'is_edit')) {
            access_denied();
        }
        if (isset($_POST['update'])) {
            $post = $this->input->post();
            $this->inventory_model->save_stock($post);
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('inventory/chemical_stock'));
        }

        $this->data['stock'] = $this->inventory_model->get_list('chemical_stock', array('id' => $id), true);
        $this->data['categorylist'] = $this->app_lib->getSelectList('chemical_category');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/stock_edit';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    public function chemical_stock_delete($id)
    {
        if (!get_permission('chemical_stock', 'is_delete')) {
            access_denied();
        }
        $getStock = $this->db->get_where('chemical_stock', array('id' => $id))->row_array();
        $this->inventory_model->stock_upgrade($getStock['stock_quantity'], $getStock['chemical_id'], false);
        $this->db->where('id', $id);
        $this->db->delete('chemical_stock');
    }

    public function reagent_assigned()
    {
        if (!get_permission('reagent_assigned', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            if (!get_permission('reagent_assigned', 'is_add')) {
                access_denied();
            }
            // validate inputs
            $this->form_validation->set_rules('lab_test_id', 'For Lab Test', 'trim|required|callback_unique_assigned_test');
            $this->form_validation->set_rules('chemicals[]', 'chemical Name', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $lab_test_id = $this->input->post('lab_test_id');
                $chemicals = $this->input->post('chemicals');
                $array = array();
                foreach ($chemicals as $key => $value) {
                    $insert_data1 = array(
                        'test_id' => $lab_test_id,
                        'chemical_id' => $value,
                    );
                    $array[] = $insert_data1;
                }
                $this->db->insert_batch('chemical_assigned', $array);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('inventory/reagent_assigned'));
            } else {
                $this->data['valid_error'] = 1;
            }
        }
        $this->data['assigntlist'] = $this->inventory_model->get_assignt_list();
        $this->data['testlist'] = $this->app_lib->getSelectList('lab_test');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/reagent_assigned';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    public function reagent_assigned_edit($id)
    {
        if (!get_permission('reagent_assigned', 'is_edit')) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            $this->form_validation->set_rules('lab_test_id', 'For Lab Test', 'trim|required|callback_unique_assigned_test');
            $this->form_validation->set_rules('chemicals[]', 'chemical Name', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $old_test_id = $this->input->post('old_lab_test_id');
                $old_chemicals = $this->input->post('old_chemicals');
                $lab_test_id = $this->input->post('lab_test_id');
                $chemicals = $this->input->post('chemicals');
                $_add = array_diff($chemicals, $old_chemicals);
                $_delete = array_diff($old_chemicals, $chemicals);
                if ($old_test_id != $lab_test_id) {
                    $this->db->where('test_id', $old_test_id);
                    $this->db->delete('chemical_assigned');
                    $chemical_array = array();
                    foreach ($chemicals as $value) {
                        $insert_data1 = array(
                            'test_id' => $lab_test_id,
                            'chemical_id' => $value,
                        );
                        $chemical_array[] = $insert_data1;
                    }
                    $this->db->insert_batch('chemical_assigned', $chemical_array);
                } else {
                    if (!empty($_add)) {
                        $chemical_array = array();
                        foreach ($_add as $value) {
                            $insert_data1 = array(
                                'test_id' => $old_test_id,
                                'chemical_id' => $value,
                            );
                            $chemical_array[] = $insert_data1;
                        }
                        $this->db->insert_batch('chemical_assigned', $chemical_array);
                    }
                    if (!empty($_delete)) {
                        $delete_array = array();
                        foreach ($_delete as $value) {
                            $delete_array[] = $value;
                        }
                        $this->db->where('test_id', $old_test_id);
                        $this->db->where_in('chemical_id', $delete_array);
                        $this->db->delete('chemical_assigned');
                    }
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('inventory/reagent_assigned'));
            }
        }
        $this->data['assigntlist'] = $this->inventory_model->get_assignt_list($id);
        $this->data['testlist'] = $this->app_lib->getSelectList('lab_test');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/reagent_assigned_edit';
        $this->data['main_menu'] = 'inventory';
        $this->load->view('layout/index', $this->data);
    }

    public function reagent_assigned_delete($test_id)
    {
        if (!get_permission('reagent_assigned', 'is_delete')) {
            access_denied();
        }
        $this->db->where('test_id', $test_id);
        $this->db->delete('chemical_assigned');
    }

    // duplicate value check in db
    public function unique_assigned_test($test_id)
    {
        if ($this->input->post('old_lab_test_id')) {
            $old_lab_test_id = $this->input->post('old_lab_test_id');
            if ($test_id == $old_lab_test_id) {
                return true;
            }
        }
        $this->db->where('test_id', $test_id);
        $query = $this->db->get('chemical_assigned');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('unique_assigned_test', 'This Test Already Assigned.');
            return false;
        } else {
            return true;
        }
    }

    public function stockreport_chemical_wise()
    {
        if (!get_permission('inventory_report', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $category_id = $this->input->post('category_id');
            $unit_id = $this->input->post('unit_id');
            $this->data['results'] = $this->inventory_model->get_stock_chemical_wisereport($category_id, $unit_id);
        }
        $this->data['title'] = translate('inventory');
        $this->data['categorylist'] = $this->app_lib->getSelectList('chemical_category', 'all');
        $this->data['unitlist'] = $this->app_lib->getSelectList('chemical_unit', 'all');
        $this->data['sub_page'] = 'inventory/stockreport_chemical_wise';
        $this->data['main_menu'] = 'inventory_report';
        $this->load->view('layout/index', $this->data);
    }

    public function purchase_report()
    {
        if (!get_permission('inventory_report', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $supplier_id = $this->input->post('supplier_id');
            $payment_status = $this->input->post('payment_status');
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->inventory_model->get_purchase_report($supplier_id, $payment_status, $start, $end);
        }
        $this->data['title'] = translate('inventory');
        $this->data['supplierlist'] = $this->app_lib->getSelectList('supplier', 'all');
        $this->data['sub_page'] = 'inventory/purchase_report';
        $this->data['main_menu'] = 'inventory_report';
        $this->load->view('layout/index', $this->data);
    }

    public function purchase_payment_report()
    {
        if (!get_permission('inventory_report', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['search'])) {
            $supplier_id = $this->input->post('supplier_id');
            $payment_status = $this->input->post('payment_status');
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['daterange'] = $daterange;
            $this->data['results'] = $this->inventory_model->get_purchase_payment_report($supplier_id, $start, $end);
        }
        $this->data['supplierlist'] = $this->app_lib->getSelectList('supplier', 'all');
        $this->data['title'] = translate('inventory');
        $this->data['sub_page'] = 'inventory/purchase_payment_report';
        $this->data['main_menu'] = 'inventory_report';
        $this->load->view('layout/index', $this->data);
    }
}
