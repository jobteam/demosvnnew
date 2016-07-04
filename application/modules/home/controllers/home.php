<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once(APPPATH . 'controllers/MYController.php');

class Home extends MYController {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function index() {
        $data = array();
        $this->loadViewTemplate('home', $data);
    }

    private function loadViewTemplate($page, $data) {
        $this->load->view('header');
        $this->load->view($page, $data);
        $this->load->view('footer');
    }

}
