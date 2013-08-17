<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Profile
 *
 * controller for profile functionality
 */
class Profile extends CI_Controller
{

    /**
     * class constructor
     *
     * @see Datamod         management of users and groups
     * @see message_helper  helps in generating bootstrap alerts
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('auth') != 'true') {
            header('HTTP/1.1 403 Forbidden');
            exit();
        }
        $this->load->model('datamod'); //load the database model
        $this->load->helper('message');//helps in generating bootstrap alerts
        //$this->load->helper('form');//form helper
        $this->load->library('form_validation');//form validation helper

        if (!$this->datamod->getPrivKey($this->session->userdata('id'))) //if key is not set, set key
            redirect('secretsanta/survey');

    }

    /**
     * internal render function to inject group info for the current user in profile page
     * @param array $data          other data that needs passing
     * @private
     */
    private function _render($data = array())
    {
        $groups = $this->datamod->getPersonGroups($this->session->userdata('name')); //get all the group codes
        $groupsInfo = $this->datamod->groupInfoMultiple($groups); //get all relevant group info
        $data = array_merge(array('groups' => $groupsInfo), $data); //inject it into data array
        render('profile', $data);
    }

    /**
     * index page for profile page
     */
    public function index()
    {
        $this->_render();
    }

    public function groupcode()
    { //form helper for adding group by code

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('group', 'Group Code', 'trim|required|min_length[4]|max_length[4]|alpha_numeric|callback_checkGroup|callback_inGroup|callback_numGroups');

        if ($this->form_validation->run() == FALSE) {
            $this->_render();
        } else {
            $this->datamod->addGroup($this->session->userdata('name'), set_value('group'));
            $this->session->set_flashdata('result', message('You have successfully joined the group <strong>' . $this->datamod->getGroupName(set_value('group')) . '</strong>!',1)); //groupCode
            redirect('profile');
        }
    }

    public function addgroup()
    { //form helper for creating new group
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('group_name', 'Group Name', 'trim|required|min_length[4]|max_length[50]|callback_numGroups|callback_checkGroupName|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->_render();
        } else {
            $this->datamod->genGroup($this->session->userdata('name'), set_value('group_name'));
            $this->session->set_flashdata('result', message('You have successfully created the group <strong>' . set_value('group_name') . '</strong>! Your group code is <strong>' . $this->datamod->getGroupCode(set_value('group_name')) . '</strong>. Keep this in a safe place.',1)); //groupcreate
            redirect('profile');
        }
    }

    public function rm($code)
    { //remove membership from group
        $groupname = $this->datamod->getGroupName($code);

        if ($this->datamod->leaveable($code)) {
            if ($this->datamod->removeFromGroup($this->session->userdata('name'), $this->uri->segment(3)))
                $this->session->set_flashdata('result', message('Successfully left the group <strong>' . $groupname . '</strong>.',0));

            else $this->session->set_flashdata('result', message('Poopy. Something went wrong. :( ',3));
        } else $this->session->set_flashdata('result', message('<strong>Error!</strong> You can\'t leave this group! </div>',3));
        redirect('profile');

    }

    public function resetPin()
    { //reset pin form

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

        $this->form_validation->set_rules('pin', 'Pin', 'trim|required|min_length[4]|max_length[4]|numeric');
        $this->form_validation->set_rules('pinconf', 'Pin Confirmation', 'trim|required|min_length[4]|max_length[4]|numeric|matches[pin]');

        if ($this->form_validation->run() == FALSE) {
            render('survey', array('reset' => 1));
        } else {
            $this->load->library('crypt');//load the crypting library
            $keys = $this->crypt->create_key(md5($this->session->userdata('email') . set_value('pin'))); //key array: [private, public]

            $this->datamod->storeKeyPair($this->session->userdata('id'), $keys);
            $this->session->set_flashdata('result', message('Pin reset to <strong>' . set_value('pin') . '</strong>. Don\'t forget it again!',1));
            redirect('profile');
        }
    }

    //
    //form validation callback functions
    //
    public function checkGroup($str)
    { //checks entered group code to make sure it exists
        if ($this->datamod->checkGroup($str) == true) { //if exists
            return true;
        } else {
            $this->form_validation->set_message('checkGroup', 'The %s you entered does not exist.');
            return false;
        }
    }

    public function checkGroupName($str)
    { //checks entered group name to make sure it exists
        if ($this->datamod->checkGroupName($str) == true) { //if exists
            $this->form_validation->set_message('checkGroupName', 'The %s you entered already exists. :(');
            return false; //unusable
        } else return true;
    }

    public function inGroup($str)
    { //checks if user is in inputted group
        if ($this->datamod->inGroup($this->session->userdata('name'), $str) == true) { //if ingroup
            $this->form_validation->set_message('inGroup', 'You are already in the group <strong>' . $this->datamod->getGroupName(set_value('group')) . '</strong>.');
            return false;
        } else return true;
    }

    public function numGroups()
    { //verifies that user is in less than threshold groups
        $num = $this->datamod->countPersonGroups($this->session->userdata('name'));
        if ($num < 5)
            return true;
        else {
            $this->form_validation->set_message('numGroups', 'You are already in <strong>' . $num . '</strong> groups.  Leave a group and try again.');
            return false;
        }
    }
}