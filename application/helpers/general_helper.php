<?php
defined('BASEPATH') or exit('No direct script access allowed');

// return translation
function translate($word = '')
{
    $ci = &get_instance();
    $set_lang = $ci->session->userdata('set_lang');
    if (empty($set_lang)) {
        $set_lang = get_global_setting('translation');
    }
    $query = $ci->db->get_where('languages', array('word' => $word));
    if ($query->num_rows() > 0) {
        if (isset($query->row()->$set_lang) && $query->row()->$set_lang != '') {
            return $query->row()->$set_lang;
        } else {
            return $query->row()->english;
        }
    } else {
        $arrayData = array(
            'word' => $word,
            'english' => ucwords(str_replace('_', ' ', $word)),
        );
        $ci->db->insert('languages', $arrayData);
        return ucwords(str_replace('_', ' ', $word));
    }
}

// return translation
function is_secure($url)
{
    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) {
        $val = 'https://' . $url;
    } else {
        $val = 'http://' . $url;
    }
    return $val;
}

function sanitizeString($var = '')
{
    if (empty($var))
    {
        return $var;
    }
    if (is_array($var))
    {
        foreach (array_keys($var) as $key)
        {
            $var[$key] = sanitizeString($var[$key], true);
        }

        return $var;
    }

    $var = trim($var);
    if (get_magic_quotes_gpc()) {
        $var = stripslashes($var);
    }
    $var = strtr($var, array_flip(get_html_translation_table(HTML_ENTITIES)));
    $var = strip_tags($var);
    $var = htmlspecialchars($var, ENT_QUOTES, config_item('charset'), true);
    return $var;
}

function get_global_setting($name = '')
{
    $ci = &get_instance();
    $name = trim($name);
    $ci->db->where('id', 1);
    $ci->db->select($name);
    $query = $ci->db->get('global_settings');

    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row->$name;
    }
}

// getting user access permission
function get_permission($permission, $can = '')
{
    $ci = &get_instance();
    $role_id = $ci->session->userdata('loggedin_role_id');
    if ($role_id == 1) {
        return true;
    }
    $permissions = get_staff_permissions($role_id);
    foreach ($permissions as $permObject) {
        if ($permObject->permission_prefix == $permission && $permObject->$can == '1') {
            return true;
        }
    }
    return false;
}

function get_staff_permissions($id)
{
    $ci = &get_instance();
    $sql = "SELECT `staff_privileges`.*, `permission`.`id` as `permission_id`, `permission`.`prefix` as `permission_prefix` FROM `staff_privileges` JOIN `permission` ON `permission`.`id`=`staff_privileges`.`permission_id` WHERE `staff_privileges`.`role_id` = " . $ci->db->escape($id);
    $result = $ci->db->query($sql)->result();
    return $result;
}

// get session loggedin
function is_loggedin()
{
    $ci = &get_instance();
    if ($ci->session->has_userdata('loggedin')) {
        return true;
    }
    return false;
}

// website menu list
function web_menu_list($publish = '', $default = '')
{
    $CI = &get_instance();
    $CI->db->select('*');
    if ($publish != '') {
        $CI->db->where('publish', $publish);
    }
    if ($default != '') {
        $CI->db->where('system', $default);
    }
    $CI->db->order_by('ordering', 'asc');
    $result = $CI->db->get('front_cms_menu')->result_array();
    return $result;
}

// get loggedin role name
function loggedin_role_name()
{
    $ci = &get_instance();
    $role = $ci->session->userdata('loggedin_role_id');
    return $ci->db->select('name')->where('id', $role)->get('roles')->row()->name;
}

function loggedin_role_id()
{
    $ci = &get_instance();
    return $ci->session->userdata('loggedin_role_id');
}

// get logged in user id
function get_loggedin_id()
{
    $ci = &get_instance();
    return $ci->session->userdata('loggedin_id');
}

// get staff db id
function get_loggedin_user_id()
{
    $ci = &get_instance();
    return $ci->session->userdata('loggedin_userid');
}

// get table name by type and id
function get_type_name_by_id($type, $type_id = '', $field = 'name')
{
    $ci = &get_instance();
    $result = $ci->db->select($field)->from($type)->where('id', $type_id)->limit(1)->get()->row_array();
    return $result[$field];
}

// set session alert / flashdata
function set_alert($type, $message)
{
    $ci = &get_instance();
    $ci->session->set_flashdata('alert-message-' . $type, $message);
}

// generate md5 hash
function app_generate_hash()
{
    return md5(rand() . microtime() . time() . uniqid());
}

// generate encryption key
function generate_encryption_key()
{
    $ci = &get_instance();
    // In case accessed from my_functions_helper.php
    $ci->load->library('encryption');
    $key = bin2hex($ci->encryption->create_key(16));
    return $key;
}

// get date by config format
function _d($date)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00') {
        return '';
    }
    $ci = &get_instance();
    $format = $ci->session->userdata('date_format');
    return strftime("$format", strtotime($date));
}

// delete url
function btn_delete($uri)
{
    return "<button class='btn btn-danger btn-circle icon' onclick=confirm_modal('" . site_url($uri) . "') ><i class='fas fa-trash-alt'></i></button>";
}

// csrf jquery token
function csrf_jquery_token()
{
    $csrf = [get_instance()->security->get_csrf_token_name() => get_instance()->security->get_csrf_hash()];
    return $csrf;
}

function check_hash_restrictions($table, $id, $hash)
{
    $ci = &get_instance();
    if (!$table || !$id || !$hash) {
        show_404();
    }
    $query = $ci->db->select('hash')->where('id', $id)->get($table);
    if ($query->num_rows() > 0) {
        $get_hash = $query->row()->hash;
    } else {
        $get_hash = '';
    }
    if (empty($hash) || ($get_hash != $hash)) {
        show_404();
    }
}

function get_nicetime($date)
{
    $get_format = get_global_setting('date_format');
    if (empty($date)) {
        return "Unknown";
    }
    // current time as mysql datetime value
    $csqltime = date('Y-m-d H:i:s');
    // current time as unix timestamp
    $ptime = strtotime($date);
    $ctime = strtotime($csqltime);

    //now calc the difference between the two
    $timeDiff = floor(abs($ctime - $ptime) / 60);

    //now we need find out whether or not the time difference needs to be in
    //minutes, hours, or days
    if ($timeDiff < 2) {
        $timeDiff = "Just now";
    } elseif ($timeDiff > 2 && $timeDiff < 60) {
        $timeDiff = floor(abs($timeDiff)) . " minutes ago";
    } elseif ($timeDiff > 60 && $timeDiff < 120) {
        $timeDiff = floor(abs($timeDiff / 60)) . " hour ago";
    } elseif ($timeDiff < 1440) {
        $timeDiff = floor(abs($timeDiff / 60)) . " hours ago";
    } elseif ($timeDiff > 1440 && $timeDiff < 2880) {
        $timeDiff = floor(abs($timeDiff / 1440)) . " day ago";
    } elseif ($timeDiff > 2880) {
        $timeDiff = date($get_format, $ptime);
    }
    return $timeDiff;
}

function bytesToSize($path, $filesize = '')
{
    if (!is_numeric($filesize)) {
        $bytes = sprintf('%u', filesize($path));
    } else {
        $bytes = $filesize;
    }
    if ($bytes > 0) {
        $unit = intval(log($bytes, 1024));
        $units = [
            'B',
            'KB',
            'MB',
            'GB',
        ];
        if (array_key_exists($unit, $units) === true) {
            return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
        }
    }
    return $bytes;
}

function array_to_object($array)
{
    if (!is_array($array) && !is_object($array)) {
        return new stdClass();
    }
    return json_decode(json_encode((object) $array));
}

function access_denied()
{
    set_alert('error', translate('access_denied'));
    redirect(site_url('dashboard'));
}
