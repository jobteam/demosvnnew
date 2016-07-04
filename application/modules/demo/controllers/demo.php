<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once(APPPATH . 'controllers/MYController.php');

class Demo extends MYController {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function index() {
        
       echo 'vao demo index';
    }
    
    public function demo(){
        $data = array();
         $this->loadViewTemplate('demo_view', $data);
    }

    private function loadViewTemplate($page, $data) {
        $this->load->view('header');
        $this->load->view($page, $data);
        $this->load->view('footer');
    }

}
