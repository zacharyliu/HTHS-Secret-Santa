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
        //$this->load->model('migrations');//load the migrations model
        $this->load->helper('message'); //load the bootstrap message helper
    }

    public function index()
    {
        $groups = $this->datamod->listAllGroups();
        foreach ($groups as &$group) {//get list of groups for pairing
            $year = $group->year;//get the year of the group
            $group->memberCount = $this->datamod->countMembers($group->code,$year);
            $group->paired = $this->datamod->paired($group->code,$year);
        }
        $data = array('groups' => $groups, 'templates'=>$this->adminmod->listTemplateGroups(), 'first_year'=>$this->adminmod->getFirstYear(),'current_year'=>intval(date('Y')));
        render('admin/index', $data);
    }

    public function pairCustom()
    {
        if ($this->input->post('code') != '') {
            $result = $this->adminmod->pairCustom($this->input->post('code'));
            if ($result) {
                $this->session->set_flashdata('admin', message('<strong>Success!</strong> Successfully ran pairing on code ' . $this->input->post('code') . ' with ' . $result . ' members',1));
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

    /**
     * ajax function for adding a new template group to the groups_template table
     */
    public function newTemplateGroup() {
        $name = $this->input->post("n"); //name of group
        $code = $this->input->post("c");//descrip of group
        $description = $this->input->post("d");//descrip of group
        $privacy = $this->input->post("p");//descrip of group
        $group_code =$this->adminmod->newTemplateGroup($code,$name,$description,$privacy);//($user_id,$task_name,$date,$estimated)
        echo $group_code;
    }

    public function deleteTemplateGroup() {
        $code = $this->input->post('c');
        $this->adminmod->deleteTemplateGroup($code);
        echo true;
    }

    public function loadAllTemplateGroups() {
        echo json_encode($this->adminmod->loadAllTemplateGroups());
    }

    public function editTemplateGroup() {
        $code = $this->input->post("c");//code of group
        $name = $this->input->post("n");//name of group
        $description = $this->input->post("d");//descrip of group
        $privacy = $this->input->post("p");//privacy
        echo $this->adminmod->editTemplateGroup($code,$name,$description,$privacy);
    }

    public function createTemplateGroup() {
        $code = $this->input->post("c");
        echo $this->adminmod->createTemplateGroup($code);
    }
}