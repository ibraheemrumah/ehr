<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save_chemical($data)
    {
        $insert_chemical = array(
            'name' => $data['chemical_name'],
            'code' => $data['chemical_code'],
            'category_id' => $data['chemical_category'],
            'purchase_unit_id' => $data['purchase_unit'],
            'sales_unit_id' => $data['sales_unit'],
            'unit_ratio' => $data['unit_ratio'],
            'purchase_price' => $data['purchase_price'],
            'sales_price' => $data['sales_price'],
            'remarks' => $data['remarks'],
        );
        if (isset($data['chemical_id']) && !empty($data['chemical_id'])) {
            $this->db->where('id', $data['chemical_id']);
            $this->db->update('chemical', $insert_chemical);
        } else {
            $this->db->insert('chemical', $insert_chemical);
        }
    }

    public function save_supplier($data)
    {
        $insertSupplier = array(
            'name' => $data['supplier_name'],
            'email' => $data['email_address'],
            'mobileno' => $data['contact_number'],
            'company_name' => $data['company_name'],
            'product_list' => $data['product_list'],
            'address' => $data['address'],
        );
        if (isset($data['supplier_id']) && !empty($data['supplier_id'])) {
            $this->db->where('id', $data['supplier_id']);
            $this->db->update('supplier', $insertSupplier);
        } else {
            $this->db->insert('supplier', $insertSupplier);
        }
    }

    public function get_chemical_list()
    {
        $this->db->select('chemical.*,chemical_category.name as category_name,p_unit.name as p_unit_name,s_unit.name as s_unit_name');
        $this->db->from('chemical');
        $this->db->join('chemical_category', 'chemical_category.id = chemical.category_id', 'left');
        $this->db->join('chemical_unit as p_unit', 'p_unit.id = chemical.purchase_unit_id', 'left');
        $this->db->join('chemical_unit as s_unit', 's_unit.id = chemical.sales_unit_id', 'left');
        $this->db->order_by('chemical.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_purchase_list()
    {
        $sql = "SELECT purchase_bill.*,supplier.name as supplier_name,staff.name as biller_name FROM purchase_bill LEFT JOIN supplier ON supplier.id = purchase_bill.supplier_id LEFT JOIN staff ON staff.id = purchase_bill.prepared_by ORDER BY purchase_bill.id ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_invoice($id)
    {
        $this->db->select('purchase_bill.*,supplier.name as supplier_name,supplier.address as supplier_address,supplier.company_name as supplier_company_name,supplier.mobileno as supplier_mobileno,staff.name as biller_name');
        $this->db->from('purchase_bill');
        $this->db->join('supplier', 'supplier.id = purchase_bill.supplier_id', 'left');
        $this->db->join('staff', 'staff.id = purchase_bill.prepared_by', 'left');
        $this->db->where('purchase_bill.id', $id);
        return $this->db->get()->row_array();
    }

    public function save_purchase($data)
    {
        $arrayInvoice = array(
            'supplier_id' => $data['supplier_id'],
            'bill_no' => $data['bill_no'],
            'remarks' => $data['remarks'],
            'total' => $data['grand_total'],
            'discount' => $data['total_discount'],
            'due' => $data['net_grand_total'],
            'paid' => 0,
            'payment_status' => 1,
            'purchase_status' => $data['purchase_status'],
            'hash' => app_generate_hash(),
            'date' => date('Y-m-d', strtotime($data['date'])),
            'prepared_by' => get_loggedin_user_id(),
            'modifier_id' => get_loggedin_user_id(),
        );
        $this->db->insert('purchase_bill', $arrayInvoice);
        $purchase_bill_id = $this->db->insert_id();

        $arrayData = array();
        $purchases = $data['purchases'];
        foreach ($purchases as $key => $value) {
            $arraychemical = array(
                'purchase_bill_id' => $purchase_bill_id,
                'chemical_id' => $value['chemical'],
                'unit_price' => $value['unit_price'],
                'discount' => $value['discount'],
                'quantity' => $value['quantity'],
                'sub_total' => $value['sub_total'],
            );
            $arrayData[] = $arraychemical;
        }
        $this->db->insert_batch('purchase_bill_details', $arrayData);
    }

    // add partly of the purchase payment
    public function save_payment($data)
    {
        $status = true;
        $payment_status = 1;
        $attach_orig_name = "";
        $attach_file_name = "";
        $purchase_bill_id = $data['purchase_bill_id'];
        $payment_amount = $data['payment_amount'];
        $paid_date = $data['paid_date'];

        // uploading file using codeigniter upload library
        if (isset($_FILES['attach_document']['name']) && !empty($_FILES['attach_document']['name'])) {
            $config['upload_path'] = './uploads/attachments/inventory_payment/';
            $config['allowed_types'] = 'gif|jpg|png|pdf|docx|csv|txt';
            $config['max_size'] = '2048';
            $config['encrypt_name'] = true;
            $this->upload->initialize($config);
            if ($this->upload->do_upload("attach_document")) {
                $attach_orig_name = $this->upload->data('orig_name');
                $attach_file_name = $this->upload->data('file_name');
            } else {
                $error = $this->upload->display_errors();
                return ['status' => false, 'msg' => $error];
            }
        }

        if ($status == true) {
            $array_history = array(
                'purchase_bill_id' => $purchase_bill_id,
                'payment_by' => get_loggedin_user_id(),
                'amount' => $payment_amount,
                'pay_via' => $this->input->post('pay_via'),
                'remarks' => $this->input->post('remarks'),
                'attach_orig_name' => $attach_orig_name,
                'attach_file_name' => $attach_file_name,
                'coll_type' => 1,
                'paid_on' => date("Y-m-d", strtotime($paid_date)),
            );
            $this->db->insert('purchase_payment_history', $array_history);
            if ($data['getbill']['due'] <= $payment_amount) {
                $payment_status = 3;
            } else {
                $payment_status = 2;
            }
            $sql = "UPDATE purchase_bill SET payment_status = " . $payment_status . ", paid = paid + " . $payment_amount . ", due = due - " . $payment_amount . " WHERE id = " . $this->db->escape($purchase_bill_id);
            $this->db->query($sql);
            return ['status' => true, 'msg' => ''];
        }
    }

    public function get_stockt_list()
    {
        $this->db->select('chemical_stock.*,chemical.name as chemical_name,chemical_category.name as category_name,staff.name as staff_name,staff.staff_id');
        $this->db->from('chemical_stock');
        $this->db->join('staff', 'staff.id = chemical_stock.stock_by', 'left');
        $this->db->join('chemical', 'chemical.id = chemical_stock.chemical_id', 'left');
        $this->db->join('chemical_category', 'chemical_category.id = chemical.category_id', 'left');
        $this->db->order_by('chemical_stock.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function save_stock($data)
    {
        $insert_data = array(
            'inovice_no' => $data['inovice_no'],
            'chemical_id' => $data['chemical_id'],
            'date' => $data['date'],
            'stock_quantity' => $data['stock_quantity'],
            'remarks' => $data['remarks'],
            'stock_by' => get_loggedin_user_id(),
        );
        if (isset($data['old_chemical_id'])) {
            if ($data['old_chemical_id'] == $data['chemical_id']) {
                if (isset($data['old_stock_quantity'])) {
                    if ($data['stock_quantity'] >= $data['old_stock_quantity']) {
                        $stock = floatval($data['stock_quantity'] - $data['old_stock_quantity']);
                        $this->stock_upgrade($stock, $data['chemical_id']);
                    } else {
                        $stock = floatval($data['old_stock_quantity'] - $data['stock_quantity']);
                        $this->stock_upgrade($stock, $data['chemical_id'], false);
                    }
                }
            } else {
                $this->stock_upgrade($data['old_stock_quantity'], $data['old_chemical_id'], false);
                $this->stock_upgrade($data['stock_quantity'], $data['chemical_id']);
            }
        }
        if (!isset($data['stock_id'])) {
            $this->stock_upgrade($data['stock_quantity'], $data['chemical_id']);
            $this->db->insert('chemical_stock', $insert_data);
        } else {
            $this->db->where('id', $data['stock_id']);
            $this->db->update('chemical_stock', $insert_data);
        }
    }

    public function stock_upgrade($stock, $chemical_id, $add = true)
    {
        if ($add == true) {
            $this->db->set('available_stock', 'available_stock +' . $stock, false);
        } else {
            $this->db->set('available_stock', 'available_stock -' . $stock, false);
        }
        $this->db->where('id', $chemical_id);
        $this->db->update('chemical');
    }

    public function get_assignt_list($id = null)
    {
        $this->db->select('chemical_assigned.*,lab_test.name as test_name,lab_test.test_code');
        $this->db->from('chemical_assigned');
        $this->db->join('lab_test', 'lab_test.id = chemical_assigned.test_id');
        $this->db->group_by('chemical_assigned.test_id');
        if ($id == null) {
            $this->db->order_by('chemical_assigned.id', 'DESC');
        } else {
            $this->db->where('chemical_assigned.test_id', $id);
        }
        $query = $this->db->get();

        if ($id == null) {
            $result = $query->result_array();
            $array = array();
            foreach ($result as $key => $value) {
                $data['id'] = $value['id'];
                $data['test_id'] = $value['test_id'];
                $data['test_name'] = $value['test_name'];
                $data['test_code'] = $value['test_code'];
                $data['chemicals'] = $this->get_chemical_by_testid($value['test_id']);
                $array[] = $data;
            }
            return $array;
        } else {
            $row = $query->row_array();
            $data['id'] = $row['id'];
            $data['test_id'] = $row['test_id'];
            $data['chemicals'] = $this->get_chemical_by_testid($row['test_id']);
            return $data;
        }
    }

    public function get_chemical_by_testid($test_id)
    {
        $this->db->select('chemical_assigned.*,chemical.name as chemical_name,chemical.code as chemical_code');
        $this->db->from('chemical_assigned');
        $this->db->join('chemical', 'chemical.id = chemical_assigned.chemical_id');
        $this->db->where('chemical_assigned.test_id', $test_id);
        $this->db->order_by('chemical_assigned.id', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_stock_chemical_wisereport($category_id = '', $unit_id = '')
    {
        $this->db->select('chemical.*,chemical_unit.name as unit_name,chemical_category.name as category_name,IFNULL(SUM(chemical_stock.stock_quantity),0) as in_stock');
        $this->db->from('chemical');
        $this->db->join('chemical_category', 'chemical_category.id = chemical.category_id', 'left');
        $this->db->join('chemical_unit', 'chemical_unit.id = chemical.sales_unit_id', 'left');
        $this->db->join('chemical_stock', 'chemical_stock.chemical_id = chemical.id', 'left');
        if ($category_id != 'all') {
            $this->db->where('chemical.category_id', $category_id);
        }

        if ($unit_id != 'all') {
            $this->db->where('chemical.sales_unit_id', $unit_id);
        }

        $this->db->group_by('chemical_stock.chemical_id');
        $this->db->order_by('chemical.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_purchase_report($supplier_id = '', $payment_status = '', $start = '', $end = '')
    {
        $this->db->select('purchase_bill.*,IFNULL(SUM(purchase_bill.total - purchase_bill.discount),0) as net_payable,supplier.name as supplier_name');
        $this->db->from('purchase_bill');
        $this->db->join('supplier', 'supplier.id = purchase_bill.supplier_id', 'left');
        if ($supplier_id != 'all') {
            $this->db->where('purchase_bill.supplier_id', $supplier_id);
        }

        if ($payment_status != 'all') {
            $this->db->where('purchase_bill.payment_status', $payment_status);
        }

        $this->db->where('purchase_bill.date >=', $start);
        $this->db->where('purchase_bill.date <=', $end);
        $this->db->group_by('purchase_bill.id');
        $this->db->order_by('purchase_bill.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_purchase_payment_report($supplier_id = '', $start = '', $end = '')
    {
        $this->db->select('purchase_payment_history.*,purchase_bill.bill_no,purchase_bill.id as bill_id,purchase_bill.hash,supplier.name as supplier_name,payment_type.name as paidvia');
        $this->db->from('purchase_payment_history');
        $this->db->join('purchase_bill', 'purchase_bill.id = purchase_payment_history.purchase_bill_id', 'inner');
        $this->db->join('payment_type', 'payment_type.id = purchase_payment_history.pay_via', 'left');
        $this->db->join('supplier', 'supplier.id = purchase_bill.supplier_id', 'left');
        if ($supplier_id != 'all') {
            $this->db->where('purchase_bill.supplier_id', $supplier_id);
        }

        $this->db->where('purchase_payment_history.paid_on >=', $start);
        $this->db->where('purchase_payment_history.paid_on <=', $end);
        $this->db->order_by('purchase_payment_history.id', 'DESC');
        return $this->db->get()->result_array();
    }
}
