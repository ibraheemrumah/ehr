<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_lib
{
	protected $CI;
	
	function __construct()
	{
		$this->CI = & get_instance();
	}

    function pass_hashed($password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        return $hashed;
    }

    function verify_password($password, $encrypt_password) {
        $hashed = password_verify($password, $encrypt_password);
        return $hashed;
    }

	function getStafflList($all='')
	{
		$this->CI->db->select('staff.id,staff.name,staff.staff_id,login_credential.role');
		$this->CI->db->from('staff');
		$this->CI->db->join('login_credential','login_credential.user_id = staff.id AND login_credential.role != 7','inner');
		$this->CI->db->where('login_credential.role !=', 1);
		$this->CI->db->order_by('staff.id', 'ASC');
		$result = $this->CI->db->get()->result();
		$data = array('' => translate('select'));
		if ($all == 'all') {
			$data['all'] = translate('all_select');
		}
		foreach ($result as $row) {
			$data[$row->id] = $row->name . " (" . $row->staff_id . ")";
		}
		return $data;
	}

    function get_bill_no($table)
    {
        $result = $this->CI->db->select("max(bill_no) as id")->get($table)->row_array();
        $id = $result["id"];
        if (!empty($id)) {
            $bill = $id + 1;
        } else {
            $bill = 1;
        }
        return str_pad($bill, 4, '0', STR_PAD_LEFT);
    }
	
    // get last serial appointment number
    public function getAppointmentNo()
    {
        $result = $this->CI->db->select("max(appointment_id) as id")->get('appointment')->row_array();
        $id = $result["id"];
        if (!empty($id)) {
            $serial = $id + 1;
        } else {
            $serial = 1;
        }
        return str_pad($serial, 4, '0', STR_PAD_LEFT);
    }

	public function get_doctor_list()
	{
		$this->CI->db->select('staff.id,staff.name,staff.staff_id,login_credential.role');
		$this->CI->db->from('staff');
		$this->CI->db->join('login_credential','login_credential.user_id = staff.id AND login_credential.role != 7','inner');
		$this->CI->db->where('login_credential.role', 3);
		$this->CI->db->order_by('staff.id', 'ASC');
		$result = $this->CI->db->get()->result();
		return $result;
	}

	public function getDoctorlList()
	{
		$this->CI->db->select('staff.id,staff.name,staff.staff_id,login_credential.role');
		$this->CI->db->from('staff');
		$this->CI->db->join('login_credential','login_credential.user_id = staff.id AND login_credential.role != 7','inner');
		$this->CI->db->where('login_credential.role', 3);
		$this->CI->db->order_by('staff.id', 'ASC');
		$result = $this->CI->db->get()->result();
		$data = array('' => translate('select'));
		foreach ($result as $row) {
			$data[$row->id] = $row->name . " (" . $row->staff_id . ")";
		}
		return $data;
	}

	public function getDoctorlListFront()
	{
		$this->CI->db->select('staff.id,staff.name,staff.staff_id,login_credential.role');
		$this->CI->db->from('staff');
		$this->CI->db->join('login_credential','login_credential.user_id = staff.id AND login_credential.role != 7','inner');
		$this->CI->db->where('login_credential.role', 3);
		$this->CI->db->order_by('staff.id', 'ASC');
		$result = $this->CI->db->get()->result();
		$data = array('' => translate('select'));
		foreach ($result as $row) {
			$data[$row->id] = $row->name;
		}
		return $data;
	}

	function getPatientList()
	{
		$this->CI->db->select('id,name,patient_id');
		$this->CI->db->from('patient');
		$this->CI->db->order_by('id', 'ASC');
		$result = $this->CI->db->get()->result();
		$data = array('' => translate('select'));
		foreach ($result as $row) {
			$data[$row->id] = $row->name . " (" . $row->patient_id . ")";
		}
		return $data;
	}

    function get_table($table, $id = NULL, $single = FALSE)
    {
        if ($single == TRUE) {
            $method = 'row_array';
        } else {
        	$this->CI->db->order_by('id', 'ASC');
            $method = 'result_array';
        }
        if ($id != NULL) {
        	$this->CI->db->where('id', $id);
        }
        $query = $this->CI->db->get($table);
		return $query->$method();
    }
	
	function upload_image($role)
	{
		$return_photo = 'defualt.png';
		$old_user_photo = $this->CI->input->post('old_user_photo');
		if (isset($_FILES["user_photo"]) && !empty($_FILES['user_photo']['name'])) {
			$config['upload_path'] = './uploads/images/' . $role . '/';
			$config['allowed_types'] = 'jpg|png';
			$config['overwrite'] = FALSE;
			$config['encrypt_name'] = TRUE;
			$this->CI->upload->initialize($config);
			if ($this->CI->upload->do_upload("user_photo")) {
	            // need to unlink previous photo
	            if (!empty($old_user_photo)) {
	            	$unlink_path = 'uploads/images/' . $role . '/';
	                if (file_exists($unlink_path . $old_user_photo)) {
	                    @unlink($unlink_path . $old_user_photo);
	                }
	            }
				$return_photo = $this->CI->upload->data('file_name');
			}
		}else{
			if (!empty($old_user_photo)){
				$return_photo = $old_user_photo;
			}
		}
		return $return_photo;
	}

    public function get_image_url($file_path = '')
    {
        $path = 'uploads/images/' . $file_path;
        if ($file_path == 'patient/defualt.png' || $file_path == 'staff/defualt.png' || empty($file_path) || !file_exists($path)) {
            $image_url = base_url('uploads/app_image/defualt.png');
        } else {
            $image_url = base_url($path);
        }
        return $image_url;
    }

	function get_lang_image_url($id = '', $thumb = TRUE)
	{
		$file_path = 'uploads/language_flags/flag_' . $id .'_thumb.png';
		if (file_exists($file_path)){
			if ($thumb == TRUE) {
				$image_url = base_url($file_path);
			}else{
				$image_url = base_url('uploads/language_flags/flag_' . $id .'.png');
			}
		}else{
			if ($thumb == TRUE) {
				$image_url = base_url('uploads/language_flags/defualt_thumb.png');
			}else{
				$image_url = base_url('uploads/language_flags/defualt.png');
			}
		}
		return $image_url;
	}

	function generate_csrf()
	{
		return '<input type="hidden" name="'. $this->CI->security->get_csrf_token_name().'" value="'. $this->CI->security->get_csrf_hash().'" />';
	}

	function getRoles($arra_id = [1,7])
	{
		$this->CI->db->where_not_in('id', $arra_id);
		$rolelist = $this->CI->db->get('roles')->result();
		$role_array = array('' => translate('select'));
		foreach($rolelist as $role ){
			$role_array[$role->id] = $role->name;
		}
		return $role_array;
	}

	function get_select_list($table) {
		$result = $this->CI->db->get($table)->result();
		return $result;
	}

	function getSelectList($table, $all='') {
		$arrayData = array("" => translate('select'));
		if ($all == 'all') {
			$arrayData['all'] = translate('all_select');
		}
		$result = $this->CI->db->get($table)->result();
		foreach($result as $row ){
			$arrayData[$row->id] = $row->name;
		}
		return $arrayData;
	}

	function get_credential_id($user_id, $staff = TRUE)
	{
		$this->CI->db->select('id');
		if ($staff == TRUE) {
			$this->CI->db->where_not_in('role', 7);
		}elseif($staff == FALSE){
			$this->CI->db->where('role', 7);
		}
		$this->CI->db->where('user_id', $user_id);
		$result = $this->CI->db->get('login_credential')->row_array();
		return $result['id'];
	}
	
	function get_document_category()
	{
		$category = array(
			''  => translate('select'),
			'1' => "Resume File",
			'2' => "Offer Letter",
			'3' => "Joining Letter",
			'4'	=> "Experience Certificate",
			'5' => "Resignation Letter",
			'6' => "Other Documents"
		);
		return $category;
	}
	
	function get_animations_list()
	{
		$animations = array(
			'fadeIn' 			=> "fadeIn",
			'fadeInUp' 			=> "fadeInUp",
			'fadeInDown' 		=> "fadeInDown",
			'fadeInLeft' 		=> "fadeInLeft",
			'fadeInRight' 		=> "fadeInRight",
			'bounceIn' 			=> "bounceIn",
			'rotateInUpLeft' 	=> "rotateInUpLeft",
			'rotateInDownLeft' 	=> "rotateInDownLeft",
			'rotateInUpRight' 	=> "rotateInUpRight",
			'rotateInDownRight' => "rotateInDownRight"
		);
		return $animations;
	}

	function get_months_list($m)
	{
		$months = array(
		    '01' => 'January',
		    '02' => 'February',
		    '03' => 'March',
		    '04' => 'April',
		    '05' => 'May',
		    '06' => 'June',
		    '07' => 'July ',
		    '08' => 'August',
		    '09' => 'September',
		    '10' => 'October',
		    '11' => 'November',
		    '12' => 'December',
		);
		return $months[$m];
	}

	function get_date_format()
	{
		$date = array(
			"%Y-%m-%d" => "yyyy-mm-dd",
			"%Y/%m/%d" => "yyyy/mm/dd",
			"%Y.%m.%d" => "yyyy.mm.dd",
			"%d-%b-%Y" => "dd-mmm-yyyy",
			"%d/%b/%Y" => "dd/mmm/yyyy",
			"%d.%b.%Y" => "dd.mmm.yyyy",
			"%d-%m-%Y" => "dd-mm-yyyy",
			"%d/%m/%Y" => "dd/mm/yyyy",
			"%d.%m.%Y" => "dd.mm.yyyy",
			"%m-%d-%Y" => "mm-dd-yyyy",
			"%m/%d/%Y" => "mm/dd/yyyy",
			"%m.%d.%Y" => "mm.dd.yyyy"
		);
		return $date;
	}
	
	function get_blood_group()
	{
		$blood_group = array(
			''  	=> translate('select'),
			'A+' 	=> 'A+',
			'A-' 	=> 'A-',
			'B+' 	=> 'B+',
			'B-' 	=> 'B-',
			'O+' 	=> 'O+',
			'O-' 	=> 'O-',
			'AB+' 	=> 'AB+',
			'AB-' 	=> 'AB-'
		);
		return $blood_group;
	}
	
	function timezone_list()
	{
		static $timezones = null;
		if ($timezones === null) {
			$timezones = [];
			$offsets = [];
			$now = new DateTime('now', new DateTimeZone('UTC'));
				foreach (DateTimeZone::listIdentifiers() as $timezone) {
				$now->setTimezone(new DateTimeZone($timezone));
				$offsets[] = $offset = $now->getOffset();
				$timezones[$timezone] = '(' . $this->format_GMT_offset($offset) . ') ' . $this->format_timezone_name($timezone);
			}
			array_multisort($offsets, $timezones);
		}
		return $timezones;
	}

	function format_GMT_offset($offset)
	{
	    $hours = intval($offset / 3600);
	    $minutes = abs(intval($offset % 3600 / 60));
	    return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
	}

	function format_timezone_name($name)
	{
	    $name = str_replace('/', ', ', $name);
	    $name = str_replace('_', ' ', $name);
	    $name = str_replace('St ', 'St. ', $name);
	    return $name;
	}
}
