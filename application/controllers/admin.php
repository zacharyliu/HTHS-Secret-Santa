<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('admin')) {
        header('HTTP/1.1 403 Forbidden');
        exit();
        }
		$this->load->model('datamod');//load the data model
		$this->load->model('adminmod');//load the admin model
    }
    
    public function index() {
	echo 'lol. good one';
    }
	
	public function addHTHS() {
	$this->adminmod->addGroupHTHS();
    }
	
	public function pairCustom($code) {
	$this->adminmod->pairCustom($code);
	}
}
?>