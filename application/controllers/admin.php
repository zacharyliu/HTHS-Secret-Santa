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
                $this->session->set_flashdata('admin', 'Successfully ran pairing on code ' . $_POST['code'] . ' with ' . $result . ' members');
                redirect('admin');
            } else {
                $this->session->set_flashdata('admin', 'Error: pairing failed. Invalid code, group does not meet rquirements, or pairing was already run.');
                redirect('admin');
            }
        } else {
            $this->session->set_flashdata('admin', 'Error: no code specified');
        }
        //
    }
}

?>