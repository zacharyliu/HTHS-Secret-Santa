<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('admin')) {
            header('HTTP/1.1 403 Forbidden');
            exit();
        }
        $this->load->model('datamod'); //load the data model
        $this->load->model('adminmod'); //load the admin model
        $this->load->helper('message'); //load the bootstrap message helper
    }

    public function index()
    {
        $groups = $this->datamod->listGroups();
        foreach ($groups as &$group) {
            $group->memberCount = $this->datamod->countMembers($group->code);
            $group->paired = $this->datamod->paired($group->code);
        }
        $data = array('groups' => $groups);
        render('admin/index', $data);
    }

    public function addHTHS()
    {
        $this->adminmod->addGroupHTHS();
    }

    public function pairCustom()
    {
        if (isset($_POST['code']) && $_POST['code'] != '') {
            $result = $this->adminmod->pairCustom($_POST['code']);
            if ($result) {
                $this->session->set_flashdata('admin', message('<strong>Success!</strong> Successfully ran pairing on code ' . $_POST['code'] . ' with ' . $result . ' members',1));
                redirect('admin');
            } else {
                $this->session->set_flashdata('admin', message('<strong>Error!</strong> Pairing failed. Invalid code, group does not meet requirements, or pairing was already run.',3));
                redirect('admin');
            }
        } else {
            $this->session->set_flashdata('admin', message('<strong>Error!</strong> No code specified',3));
            redirect('admin');
        }
    }
}