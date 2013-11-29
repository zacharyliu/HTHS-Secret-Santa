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
        $this->load->model('datamod'); //load the database model
        $this->load->helper('message');//helps in generating bootstrap alerts
        //$this->load->helper('form');//form helper
        $this->load->library('form_validation');//form validation helper

        if ($this->session->userdata('auth') != 'true') {
            redirect(base_url("login/timeout"));
        }

        /*if (!$this->datamod->getPrivKey($this->session->userdata('id'))) //if key is not set, set key
            redirect('secretsanta/survey');
*/
    }

    /**
     * internal render function to inject group info for the current user in profile page
     * @param array $data          other data that needs passing
     * @private
     */
    private function _render($data = array())
    {
        $join_year = $this->datamod->getJoinYear($this->session->userdata('id'));//get the year the user joined
        $current_year = intval(date('Y'));//get the current year
        $groupsInfo = array();
        while ($join_year<=$current_year){
            $groups = $this->datamod->getPersonGroups($this->session->userdata('id'),$join_year); //get all the group codes for a certain year
            //a better way of doing this would be to get all of the groups that a user is part of, and run a query once on the groups table. But I'm lazy.
            $groupsInfo=array_merge($groupsInfo,$this->datamod->groupInfoMultiple($groups,$join_year)); //get all relevant group info for that year, and merge it with the rest of the groups
            $join_year+=1;
        }
        $data = array_merge(array('groups' => $groupsInfo), $data); //inject it into data array

        render('profile', $data);
    }

    /**
     * index page for profile page
     */
    public function index()
    {
        $this->_render(array('first_year'=>$this->datamod->getJoinYear($this->session->userdata('id')),'current_year'=>intval(date('Y'))));
    }

    public function groupcode()
    { //form helper for adding group by code

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('group', 'Group Code', 'trim|required|min_length[4]|max_length[4]|alpha_numeric|callback_checkGroup|callback_inGroup|callback_numGroups');

        if ($this->form_validation->run() == FALSE) {
            $this->_render(array('first_year'=>$this->datamod->getJoinYear($this->session->userdata('id')),'current_year'=>intval(date('Y'))));
        } else {
            $this->datamod->addGroup($this->session->userdata('id'), set_value('group'));
            $this->session->set_flashdata('result', message('You have successfully joined the group <strong>' . $this->datamod->getGroupName(set_value('group')) . '</strong>!',1)); //groupCode
            redirect(base_url('profile'));
        }
    }

    public function addgroup()
    { //form helper for creating new group
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('group_name', 'Group Name', 'trim|required|min_length[4]|max_length[50]|callback_numGroups|callback_checkGroupName|xss_clean');
        $this->form_validation->set_rules('group_description', 'Group Description', 'trim|max_length[150]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->_render(array('first_year'=>$this->datamod->getJoinYear($this->session->userdata('id')),'current_year'=>intval(date('Y'))));
        } else {
            $this->datamod->addGroup($this->session->userdata('id'), set_value('group_name'), set_value('group_description'));
            $this->session->set_flashdata('result', message('You have successfully created the group <strong>' . set_value('group_name') . '</strong>! Your group code is <strong>' . $this->datamod->getGroupCode(set_value('group_name')) . '</strong>. Keep this in a safe place.',1)); //groupcreate
            redirect(base_url('profile'));
        }
    }

    public function rm($code)
    { //remove membership from group
        $groupname = $this->datamod->getGroupName($code);

        if ($this->datamod->leaveable($code)) {
            if ($this->datamod->removeFromGroup($this->session->userdata('id'), $this->uri->segment(3)))
                $this->session->set_flashdata('result', message('Successfully left the group <strong>' . $groupname . '</strong>.',0));

            else $this->session->set_flashdata('result', message('Poopy. Something went wrong. :( ',3));
        } else $this->session->set_flashdata('result', message('<strong>Error!</strong> You can\'t leave this group! </div>',3));
        redirect(base_url('profile'));

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
            redirect(base_url('profile'));
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
        if ($this->datamod->inGroup($this->session->userdata('id'), $str) == true) { //if ingroup
            $this->form_validation->set_message('inGroup', 'You are already in the group <strong>' . $this->datamod->getGroupName(set_value('group')) . '</strong>.');
            return false;
        } else return true;
    }

    public function numGroups()
    { //verifies that user is in less than threshold groups
        $num = $this->datamod->countPersonGroups($this->session->userdata('id'));
        if ($num < $this->config->item('max_groups'))
            return true;
        else {
            $this->form_validation->set_message('numGroups', 'You are already in <strong>' . $num . '</strong> groups.  Leave a group and try again.');
            return false;
        }
    }
}