<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('admin')) {
            $this->session->set_flashdata('notice', 'You must be logged in as an admin to view this page.');
            redirect('login');
        }
    }
    
    public function index() {
        
    }
    
}