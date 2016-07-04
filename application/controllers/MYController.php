<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MYController extends CI_Controller {

    protected $pre_defined = null;

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('language', 'form', 'url'));
        $lang = $this->session->userdata('lang');
        if (!$lang) {
            //get default lang
            $lang = $this->config->item('language');
            //set default lang to session
            $this->session->set_userdata('lang', $lang);
        }

        // find all language files
        $map = directory_map('application/language/');
        foreach ($map[$lang] as $key) {
            //and include them if they have the extension is "php"
            //have some file in language directory as index.html, so have to excluded it
            if (get_file_extension($key) == 'php') {
                //get the file with correct format
                $def = current(explode('_lang', $key));
                //and include it
                $this->lang->load($def, $lang);
            }//end if
        }//end foreach
    }

    function _checkLogin() {
        if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true) {
            return true;
        }
        return false;
    }

    function _logout($link = '') {
        //remove session login  
        $this->session->unset_userdata('logged_in', false);

        $lang = $this->session->userdata('lang');
        $this->session->sess_destroy();
        if (isset($lang)) {
            $this->session->set_userdata('lang', $lang);
        }
        // back to login page
        redirect(base_url() . $link);
    }

    // Load template + data
    public function loadTemplateVuihi($page, $data) {
        $this->load->view('askme/header', $data);
        $this->load->view($page, $data);
        $this->load->view('askme/footer');
    }

    function slugify($text) {
        try {
            // replace all non letters or digits with -
            $text = preg_replace('/\W+/', '-', $text);
            // trim and lowercase
            $text = strtolower(trim($text, '-'));
            return $text;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
        //cat chuoi get element
    public function subStringSplitElement($post, $num, $char) {
        try {

            if (strpos($post, $char) !== false) {

                $str = explode($char, $post);
                return $str[$num];
            } else {
                echo 'FORMAT---' . $char;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
