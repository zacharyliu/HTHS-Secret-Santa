<?php

class Messages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('auth') != 'true') {
            redirect(base_url("login/timeout"));
        }
        $this->load->model('messagesmod');
    }

    public function index() {

    }

}
