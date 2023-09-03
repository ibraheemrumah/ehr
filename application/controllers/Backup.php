<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* @package : Ramom Diagnostic Management System
* @version : 1.0
* @developed by : techtune
* @support : ramomcoder@yahoo.com
* @author url : http://codecanyon.net/user/techtune
* @filename : Backup.php
*/

class Backup extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->helpers('download');
	}

	public function index()
	{
		// check access permission
		if (!get_permission('database_backup', 'is_view')) {
			access_denied();
		}
		if (isset($_POST['backup'])) {
			if (!get_permission('database_backup', 'is_add')) {
				access_denied();
			}

			$path = './uploads/db_backup/';
			$file_name = 'db-backup_' . date('Y-m-d-H-i-s');
	        if (!is_really_writable($path)) {
	            set_alert('error','Backups folder is not writable. You need to change the permissions to 755');
	            redirect(base_url('backup'));
	        }

			$this->load->dbutil();
			$config = array(
				'ignore' => array(),
				'format' => 'zip', // gzip, zip, txt
				'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
				'add_insert' => TRUE, // Whether to add INSERT data to backup file
				'filename' => $file_name . '.sql',
				'newline' => "\n"
			);
			$backup = $this->dbutil->backup($config);
			write_file($path . $file_name . '.zip', $backup);
			set_alert('success', "Database Backup Completed");
			redirect(base_url('backup'));
		}
		$this->data['title'] = translate('settings');
		$this->data['sub_page'] = 'backup/index';
		$this->data['main_menu'] = 'settings';
		$this->load->view('layout/index', $this->data);
	}
	
    // backup zip file download function
	public function download()
    {
		if (!get_permission('database_backup', 'is_add')) {
			access_denied();
		}
		$file = $this->input->get('file');
		$this->data = file_get_contents('./uploads/db_backup/'.$file);
		force_download($file, $this->data);
		redirect(base_url('backup'));
    }
	
    // backup file delete function
	public function delete_file($file)
    {
		if (!get_permission('database_backup', 'is_delete')) {
			access_denied();
		}
		unlink('./uploads/db_backup/' . $file);
    }
	
    // backup restore file function
	public function restore_file()
    {
		if (!get_permission('database_restore', 'is_add')) {
			access_denied();
		}
		$this->load->helper('unzip');
		$config['upload_path'] = './uploads/db_temp/';
		$config['allowed_types'] = 'zip';
		$config['overwrite'] = TRUE;
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('uploaded_file')) {
			$error = $this->upload->display_errors('', ' ');
			set_alert('error', $error);
			redirect(base_url('backup'));
		} else {
			$data 	= array('upload_data' => $this->upload->data());
			$backup = "uploads/db_temp/" . $data['upload_data']['file_name'];
		}
		if (!unzip($backup, "uploads/db_temp/", TRUE)) {
			set_alert('error', "Backup Restore Error");
			redirect(base_url('backup'));
		} else {
			$backup = str_replace('.zip', '', $backup);
		    $templine = '';
		    // Read in entire file
		    $lines = file($backup . ".sql");
		    // Loop through each line
		    foreach ($lines as $line) {
		      if (substr($line, 0, 2) == '--' || $line == '')
		        continue;
		      $templine .= $line;
		      // If it has a semicolon at the end, it's the end of the query so can process this templine
		      if (substr(trim($line), -1, 1) == ';') {
		        // Perform the query
		        $this->db->query($templine);
		        // Reset temp variable to empty
		        $templine = '';
		      }
		    }
			set_alert('success', "Backup Restore Successfully");
		}

		unlink($backup . '.sql');
		unlink($backup . '.zip');
		redirect(base_url('backup'));
    }
}