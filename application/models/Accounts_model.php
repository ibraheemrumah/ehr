<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// account save and update function
	public function save_accounts($data)
	{
		$obal = (empty($data['opening_balance']) ? 0 : $data['opening_balance']);
		$insert_account = array(
			'name' => $data['account_name'],
			'description' => $data['description'],
		);
		if (isset($data['account_id']) && !empty($data['account_id'])) {
			$this->db->where('id', $data['account_id']);
			$this->db->update('accounts', $insert_account);
		}else{
			$insert_account['balance'] = $obal;
			$this->db->insert('accounts', $insert_account);
			$insert_id = $this->db->insert_id();
			if ($obal > 0) {
				$insertTransaction = array(
					'account_id' => $insert_id,
					'voucher_head_id' => '0',
					'type' => 'income',
					'amount' => $obal,
					'dr' => 0,
					'cr' => $obal,
					'bal' => $obal,
					'date' => date('Y-m-d'),
					'description' => 'Opening Balance'
				);
				$this->db->insert('transactions', $insertTransaction);
			}
		}
	}

	// voucher save function
	public function save_voucher($data)
	{
		$account_id = $data['account_id'];
		$voucher_head_id = $data['voucher_head_id'];
		$voucher_type = $data['voucher_type'];
		$ref_no = $data['ref_no'];
		$amount = $data['amount'];
		$date = $data['date'];
		$pay_via = $data['pay_via'];
		$description = $data['description'];
		$qbal = $this->app_lib->get_table('accounts', $account_id, TRUE);
		$cbal = $qbal['balance'];
		if ($voucher_type == 'income') {
			$cr = $amount;
			$dr = 0;
			$bal = $cbal + $amount;
		}elseif ($voucher_type == 'expense') {
			$cr = 0;
			$dr = $amount;
			$bal = $cbal - $amount;
		}
		$insertTransaction = array(
			'account_id' => $account_id,
			'voucher_head_id' => $voucher_head_id,
			'type' => $voucher_type,
			'ref' => $ref_no,
			'amount' => $amount,
			'dr' => $dr,
			'cr' => $cr,
			'bal' => $bal,
			'date' => date("Y-m-d", strtotime($date)),
			'pay_via' => $pay_via,
			'description' => $description
		);

		$this->db->insert('transactions', $insertTransaction);
		$insert_id = $this->db->insert_id();
		$this->db->where('id', $account_id);
		$this->db->update('accounts', array('balance' => $bal));
		return $insert_id;
	}

	// voucher update function
	public function edit_voucher($data)
	{
		$voucher_head_id = $data['voucher_head_id'];
		$ref_no = $data['ref_no'];
		$date = $data['date'];
		$pay_via = $data['pay_via'];
		$description = $data['description'];

		$insertTransaction = array(
			'voucher_head_id' => $voucher_head_id,
			'ref' => $ref_no,
			'date' => date("Y-m-d", strtotime($date)),
			'pay_via' => $pay_via,
			'description' => $description
		);

		if (isset($data['voucher_old_id']) && !empty($data['voucher_old_id'])) {
			$this->db->where('id', $data['voucher_old_id']);
			$this->db->update('transactions', $insertTransaction);
			$insert_id = $data['voucher_old_id'];
			return $insert_id;
		}
	}

	// get voucher list function
	public function get_voucher_list()
	{
		$sql = "SELECT transactions.*, accounts.name as ac_name, voucher_head.name as v_head, payment_type.name as via_name FROM transactions LEFT JOIN accounts ON accounts.id = transactions.account_id LEFT JOIN voucher_head ON voucher_head.id = transactions.voucher_head_id LEFT JOIN payment_type ON payment_type.id = transactions.pay_via ORDER BY transactions.id ASC";
		return $this->db->query($sql)->result_array();
	}

	// get statement report function
	public function get_statement_report($account_id='', $type='', $start='', $end='')
	{
		$this->db->select('transactions.*,voucher_head.name as v_head');
		$this->db->from('transactions');
		$this->db->join('voucher_head', 'voucher_head.id = transactions.voucher_head_id', 'left');
		$this->db->where('transactions.account_id', $account_id);
		$this->db->where('transactions.date >=', $start);
		$this->db->where('transactions.date <=', $end);
		if ($type != 'all') {
			$this->db->where('transactions.type', $type);
		}
		$this->db->order_by('transactions.id', 'ASC');
		return $this->db->get()->result_array();
	}

	// get income expense report function
	public function get_income_expense_repots($start='', $end='', $type='')
	{
		$this->db->select('transactions.*,accounts.name as ac_name,voucher_head.name as v_head,payment_type.name as via_name');
		$this->db->from('transactions');
		$this->db->join('accounts', 'accounts.id = transactions.account_id', 'left');
		$this->db->join('voucher_head', 'voucher_head.id = transactions.voucher_head_id', 'left');
		$this->db->join('payment_type', 'payment_type.id = transactions.pay_via', 'left');
		if ($type != '') {
			$this->db->where('transactions.type', $type);
		}
		$this->db->where('transactions.date >=', $start);
		$this->db->where('transactions.date <=', $end);
		$this->db->order_by('transactions.id', 'ASC');
		return $this->db->get()->result_array();
	}

	// get account balance sheet report
	public function get_balance_sheet()
	{
		$this->db->select('transactions.*,IFNULL(SUM(transactions.dr), 0) as total_dr,IFNULL(SUM(transactions.cr),0) as total_cr,accounts.name as ac_name,accounts.balance as fbalance');
		$this->db->from('accounts');
		$this->db->join('transactions', 'transactions.account_id = accounts.id', 'left');
		$this->db->group_by('transactions.account_id');
		$this->db->order_by('accounts.balance', 'DESC');
		return $this->db->get()->result_array();
	}

	// get income vs expense report
	public function get_incomevsexpense($start='', $end='')
	{
		$sql = "SELECT transactions.*, voucher_head.name as v_head, IFNULL(SUM(transactions.dr), 0) as total_dr, IFNULL(SUM(transactions.cr), 0) as total_cr FROM voucher_head LEFT JOIN transactions ON transactions.voucher_head_id = voucher_head.id WHERE transactions.date >= " . $this->db->escape($start) . " AND transactions.date <= " . $this->db->escape($end) . " GROUP BY transactions.voucher_head_id ORDER BY transactions.id ASC";
		return $this->db->query($sql)->result_array();
	}

	// get transitions repots
	public function get_transitions_repots($start='', $end='')
	{
		$sql = "SELECT transactions.*, accounts.name as ac_name, voucher_head.name as v_head, payment_type.name as via_name FROM transactions LEFT JOIN accounts ON accounts.id = transactions.account_id LEFT JOIN voucher_head ON voucher_head.id = transactions.voucher_head_id LEFT JOIN payment_type ON payment_type.id = transactions.pay_via WHERE transactions.date >= " . $this->db->escape($start) . " AND transactions.date <= " . $this->db->escape($end) . " ORDER BY transactions.id ASC";
		return $this->db->query($sql)->result_array();
	}

	// duplicate voucher head check in db
	public function unique_voucher_head($name)
	{
		$voucher_head_id = $this->input->post('voucher_head_id');
		if (!empty($voucher_head_id))
			$this->db->where_not_in('id', $voucher_head_id);
		$this->db->where('name', $name);
		$query = $this->db->get('voucher_head');
		if ($query->num_rows() > 0){
			if (!empty($voucher_head_id)){
				set_alert('error', "The Voucher Head name are already used");
			}else{
				$this->form_validation->set_message("unique_voucher_head", "The %s name are already used.");
			}
			return FALSE;
		}else{
			return TRUE;
		}
	}

	// duplicate account name check in db
	public function unique_account_name($name)
	{
		$account_id = $this->input->post('account_id');
		if (!empty($account_id))
			$this->db->where_not_in('id', $account_id);
		$this->db->where('name', $name);
		$query = $this->db->get('accounts');
		if ($query->num_rows() > 0){
			$this->form_validation->set_message("unique_account_name", "The %s name are already used.");
			return FALSE;
		}else{
			return TRUE;
		}
	}
}