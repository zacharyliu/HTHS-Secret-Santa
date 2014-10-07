<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('admin')) {
            redirect(base_url("login/timeout"));
        }
        $this->load->model('datamod'); //load the data model
        $this->load->model('adminmod'); //load the admin model
        //$this->load->model('migrations');//load the migrations model
        $this->load->helper('message'); //load the bootstrap message helper
        $this->load->helper('render_admin');
    }

    public function index()
    {
        redirect(base_url("admin/groups"));
    }

    public function groups() {
        $year = $this->__getGlobalVar("firstyear");//get the year of the group
        $groups = $this->datamod->listAllGroups();
        foreach ($groups as &$group) {//get list of groups for pairing

            $group->memberCount = $this->datamod->countMembers($group->code,$year);
            $group->paired = $this->datamod->paired($group->code,$year);
        }
        $data = array('groups' => $groups, 'templates'=>$this->adminmod->listTemplateGroups(), 'first_year'=>$this->__getGlobalVar('first_year'),'current_year'=>intval(date('Y')), 'allowed_emails' => $this->adminmod->getAllowedEmails());
        render_admin('admin/groups', $data);
    }

    public function general() {

    }

    public function addAllowedEmail()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('admin', message('<strong>Error!</strong> You must enter a valid email address.'));
            redirect('admin');
        }

        $email = $this->input->post('email');
        if ($this->adminmod->addAllowedEmail($email)) {
            $this->session->set_flashdata('admin', message("<strong>Success!</strong> Added $email to the list of allowed email addresses."));
        } else {
            $this->session->set_flashdata('admin', message("<strong>Error!</strong> Could not add $email."));
        }

        redirect('admin');
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

    public function sendBulkMail($code = null, $year = null)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('subject', 'trim|required');
        $this->form_validation->set_rules('message', 'trim|required');

        $sendTo = $this->datamod->getMemberEmails($code, $year);

        if ($sendTo == false || count($sendTo) == 0) {
            $this->session->set_flashdata('admin', message('No recipient users found. Are you sure the group you specified is valid?'));
        }

        if ($this->form_validation->run() == false) {
            $data['varNames'] = array('name', 'email', 'groupCount');
            $data['code'] = $code;
            $data['year'] = ($year == null) ? $this->datamod->current_year : $year;
            $data['sendTo'] = $sendTo;
            render('admin/sendBulkMail', $data);
        } else {
            foreach ($sendTo as $email) {
                $userId = $this->datamod->getUserId($email);
                $vars['name'] = $this->datamod->getUserName($userId);
                $vars['email'] = $email;
                $vars['groupCount'] = $this->datamod->countPersonGroups($userId);
                $this->adminmod->sendMail($email, $this->input->post('subject'), $this->input->post('message'), $vars);
            }

            $this->session->set_flashdata('admin', message('Sent successfully to ' . count($sendTo) . ' users.'));
            redirect(current_url());
        }
    }

    private function __getGlobalVar($var) {
        return $this->config->item($var);
    }

    private function __setGlobalVar($varName,$val) {
        $this->config->set_item($varName, $val);
    }
}