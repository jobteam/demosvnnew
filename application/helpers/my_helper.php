<?php

/*
  Function: template_url
  Return: url to html, css and js file
 */
if (!function_exists('template_url')) {

    function template_url($uri = '') {
        $CI = & get_instance();
        return $CI->config->item('template_url');
    }

    //get value from sigle recore return by PROCEDURE OR FUNCTION
    function get_record_value($object, $type = 'int') {
        $return = 0;
        $datauser = $object->result_array();
        //check for exist record
        $datauser = isset($datauser[0]) ? $datauser[0] : NULL;

        if ($datauser) {
            foreach ($datauser as $useremail) {
                $return = $useremail;
            }
        }

        switch ($type) {
            case 'int':
                $return = intval($return);
                break;
        }
        return $return;
    }

// Generate Password 
    function rand_string($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

}
/*
  function: upload tenmplate
  Input: Array $datafile
  $datafile['upload_path']: path to upload, from html folder
  return: boolean
 */

function upload_template($datafile = array(), $field = 'userfile') {
    $return = array();

    //thiet lap duong dan
    $config['upload_path'] = 'html/' . (isset($datafile['path']) ? $datafile['path'] . '/' : '');

    //tao thu muc truoc khi upload
    if (!@is_dir($config['upload_path'])) {
        mkdir($config['upload_path'], 0777);
    }
    // chi chap nhan cac loai file sau
    $config['allowed_types'] = 'gif|jpg|png|zip|html|css|js|php';
    //kich thuoc size toi da
    $config['max_size'] = '10000';
    //mang dung der kiem tra file up len co phai la cho web hay khong
    $arr_ext = array(".jpg", ".gif", ".png", ".css", ".html", ".php", ".js");
    //lay di tuong CI
    $CI = & get_instance();
    $CI->load->library('upload', $config);

    //upload file
    if (!$CI->upload->do_upload($field)) { // fail
        $return = array('error' => $CI->upload->display_errors());
        //var_dump($return['error']);
        return $return;
    } else { //upload thanh cong
        //lay du lieu da upload
        $data = array('upload_data' => $CI->upload->data());
        //doi tuong file nen
        $zip = new ZipArchive;
        //ten file bao gom full duong dan
        $file = $data['upload_data']['full_path'];
        //cap quyen cho file do
        chmod($file, 0777);
        //giai nen
        if ($zip->open($file) === TRUE) { // giai nen thanh cong
            $zip->extractTo($config['upload_path']);
            $zip->close();
            //delete zip file
            if (file_exists($file))
                unlink($file);

            return true;
        }
        // neu giai ne khong thanh cong
        else {
            return false;
        }
    }
    return false;
}

function get_image_properties(&$CI, $path = '', $return = FALSE) {
    // For now we require GD but we should
    // find a way to determine this using IM or NetPBM

    if ($path == '')
        $path = $CI->full_src_path;

    if (!file_exists($path)) {
        $CI->set_error('imglib_invalid_path');
        return FALSE;
    }

    $vals = @getimagesize($path);

    $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

    $mime = (isset($types[$vals['2']])) ? 'image/' . $types[$vals['2']] : 'image/jpg';

    if ($return == TRUE) {
        $v['width'] = $vals['0'];
        $v['height'] = $vals['1'];
        $v['image_type'] = $vals['2'];
        $v['size_str'] = $vals['3'];
        $v['mime_type'] = $mime;

        return $v;
    }

    $CI->orig_width = $vals['0'];
    $CI->orig_height = $vals['1'];
    $CI->image_type = $vals['2'];
    $CI->size_str = $vals['3'];
    $CI->mime_type = $mime;

    return TRUE;
}

/*
  function: upload screen
  Input: Array $datafile
  $datafile['upload_path']: path to upload, from html folder
  return: boolean
 */

function upload_screen($datafile = array(), $field = 'userfile') {
    $return = array();

    //thiet lap duong dan
    $config['upload_path'] = (isset($datafile['path']) ? $datafile['path'] . '/' : '');

    //tao thu muc truoc khi upload

    if (!@is_dir($config['upload_path'])) {
        mkdir($config['upload_path'], 0777);
    }

    // chi chap nhan cac loai file sau
    $config['allowed_types'] = 'gif|jpg|png';
    //kich thuoc size toi da
    $config['max_size'] = '10000';
    //mang dung der kiem tra file up len co phai la cho web hay khong
    $arr_ext = array(".jpg", ".gif", ".png");
    //ghi de len file da co
    $config['overwrite'] = true;
    //lay di tuong CI
    $CI = & get_instance();
    $CI->load->library('upload', $config);
    $image = $_FILES[$field]['name'];

    //upload file
    if (!$CI->upload->do_upload($field)) { // fail
        $return = array('error' => $CI->upload->display_errors());
        //var_dump($return['error']);
        return $return;
    } else { //upload thanh cong
        //lay du lieu da upload
        $data = array('upload_data' => $CI->upload->data());
        $imgInfo = get_image_properties($CI, $config['upload_path'] . $image, true);
        $CI->thumb_height = 125;
        $CI->thumb_width = intval(($CI->thumb_height * $imgInfo['width']) / $imgInfo['height']);

        //thiet lap thong so cho thumbs
        $config_upload = array(
            'image_library' => 'gd2',
            'source_image' => $config['upload_path'] . $image, //get original image
            'new_image' => $config['upload_path'] . 'thumbs/' . $image,
            'maintain_ratio' => true,
            'height' => $CI->thumb_height,
            'width' => $CI->thumb_width
        );
        $CI->load->library('image_lib'); //load library
        $CI->image_lib->initialize($config_upload);
        //tao file thumbs
        return $CI->image_lib->resize();
    }
    return false;
}

function convert_vi_to_en($str) {
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|Ä|ä)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ö|ö)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", "d", $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|Ü|ü)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
    return $str;
}

function ConvertToFileName($filename) {
    $filename = convert_vi_to_en($filename);
    $array = explode(" ", $filename);
    $name = "";
    //seperate all the values based on delimter
    if (count($array) > 0) {
        //filename is 1 word
        //convert first character to uppercase
        $name = strtolower($array{0});
    }

    for ($i = 1; $i < count($array); $i++) {
        //filename is more than 1 word
        //convert first character to uppercase
        $name = $name . "_" . strtolower($array{$i});
    }
    return $name;
}

function ger2en($day) {
    $arrayWeek = array('Montag' => 'Monday',
        'Dienstag' => 'Tuesday',
        'Mittwoch' => 'Wednesday',
        'Donnerstag' => 'Thursday',
        'Freitag' => 'Friday',
        'Samstag' => 'Saturday',
        'Sonntag' => 'Sunday');

    return $arrayWeek[$day];
}

function ngayhientai($day, $hour, $munite) {
    $english = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday');
    $german = array(1 => 'Montag', 2 => 'Dienstag', 3 => 'Mittwoch', 4 => 'Donnerstag', 5 => 'Freitag', 6 => 'Samstag', 7 => 'Sonntag');
    $nowName = date("l");
    $dayID = 0;
    $nowID = 0;

    foreach ($german as $key => $val)
        if ($val == $day)
            $dayID = $key;

    foreach ($english as $key => $val)
        if ($val == $nowName)
            $nowID = $key;

    $int = $dayID - $nowID;

    if ($int > 0)
        $time = $int;
    else
        $time = $int + 7;

    if ($int == 0 && intval($hour) > intval(date("H")))
        $time = $int;

    if ($int == 0 && intval($hour) == intval(date("H")) && intval($munite) > intval(date("i")))
        $time = $int;

    return date("d.m.Y", strtotime(' +' . $time . ' day'));
}

function get_file_extension($filename) {
    return end(explode(".", $filename));
}

function curPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function pageURL($page) {
    $curURL = $_SERVER["REQUEST_URI"];

    $temp = split('page=', $curURL);
    if (isset($temp[1])) {
        $page_num = intval($temp[1]);
        $curURL = str_replace('page=' . $page_num, 'page=' . $page, $curURL);
    } else {
        if (!strpos($curURL, '?'))
            $curURL = $curURL . "?page=" . $page;
        else
            $curURL = $curURL . "&page=" . $page;
    }
    return $curURL;
}

function readNamelist($lang) {
    $file = $lang == 'de' ? 'namelist_de.csv' : 'namelist_en.csv';
    $return = array();
    $root = dirname(dirname(dirname(__FILE__))) . '/';
    if (($handle = fopen($root . "html/" . $file, "r")) !== FALSE) {
        # Set the parent multidimensional array key to 0.
        $nn = 0;
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            $return[] = utf8_encode($data[0] . ' ' . $data[1]);
        }
        # Close the File.
        fclose($handle);
    }
    return $return;
}

/*
  kiem tra xem file nay co tren amazon hay khong
 */

function S3_is_exit($S3_obj, $bucket, $file_url) {
    return $S3_obj->getObjectInfo($bucket, $file_url);
}

/*
  thay chuoi ky tu cuoi cung trong 1 chuoi
 */

function str_lreplace($search, $replace, $subject) {
    $pos = strrpos($subject, $search);

    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}

/*

  ham thay the sprintf

 */

function sprintf_replace($strMain, $arrData) {
    $return = '';
    // eplode chuoi thanh cac mang chia cat boi %s
    $arrTemp = explode('%s', $strMain);
    //if(is_array($arrTemp) and count($arrTemp) <= count($arrData))
    $limit = count($arrTemp) > count($arrData) ? count($arrData) : count($arrTemp);
    for ($i = 0; $i < $limit; $i++) {
        $return .= $arrTemp[$i] . $arrData[$i];
    }
    $return .= $arrTemp[$i];
    return $return;
}

/*

 */

function timezone2UPUM($num) {
    $arrInfo = array(
        "UM12" => -12,
        "UM11" => -11,
        "UM10" => -10,
        "UM9" => -9,
        "UM8" => -8,
        "UM7" => -7,
        "UM6" => -6,
        "UM5" => -5,
        "UM4" => -4,
        "UM25" => -3.5,
        "UM3" => -3,
        "UM2" => -2,
        "UM1" => -1,
        "UTC" => 0,
        "UP1" => 1,
        "UP2" => 2,
        "UP3" => 3,
        "UP25" => 3.5,
        "UP4" => 4,
        "UP35" => 4.5,
        "UP5" => 5,
        "UP45" => 5.5,
        "UP6" => 6,
        "UP7" => 7,
        "UP8" => 8,
        "UP9" => 9,
        "UP85" => 9.5,
        "UP10" => 10,
        "UP11" => 11,
        "UP12" => 12
    );
    foreach ($arrInfo as $key => $val) {
        if ($val == $num)
            return $key;
    }
}

function get_language($CIObj, $lang) {
    //codeignigter khogn chap nhan load ngon ngu de len nhau
    //do do, phai remove casi cu roi load cai moi vao
    $CIObj->lang->is_loaded = array();
    //tim vao thu muc
    $map = directory_map('application/language/');
    //have to browser all language files
    foreach ($map[$lang] as $key => $filename) {
        //and include them if they have the extension is "php"
        //have some file in language directory as index.html, so have to excluded it
        if (get_file_extension($filename) == 'php') {
            //get the file with correct format
            $def = current(explode('_lang', $filename));
            //and include it				
            $CIObj->lang->load($def, $lang);
        }//end if
    }//end foreach
}

function unicode_str_filter($str) {
    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D' => 'Đ',
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        'ae' => 'ä|Ä',
        'ue' => 'ü|Ü',
        'oe' => 'ö|Ö',
        'ss' => 'ß'
    );

    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    return $str;
}

?>