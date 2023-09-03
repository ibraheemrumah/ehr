<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{

	function __construct() {
		parent::__construct();
	}

    function get_list($table_name, $where_array = NULL, $single = FALSE, $columns = NULL)
    {
        if ($columns)
            $this->db->select($columns);

        if (!empty($where_array))
            $this->db->where($where_array);

		if($single == TRUE) {
			$method = 'row_array';
		}else{
			$method = 'result_array';
			$this->db->order_by('id', 'ASC');
		}
		$result = $this->db->get($table_name)->$method();
        return $result;
    }
}
