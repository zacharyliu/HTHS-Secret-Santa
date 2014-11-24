<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Discover
 *
 * controller for discover functionality
 */
class Discover extends CI_Controller
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
        $this->load->helper('message'); //helps in generating bootstrap alerts
        $this->load->library('form_validation'); //form validation helper

        if ($this->session->userdata('auth') != 'true') {
            redirect(base_url("login/timeout"));
        }

    }

    /**
     * index page for discover
     */
    public function index()
    {
        $trending = $this->datamod->listTrendingGroups();
        render("discover", array("trending" => $trending));
    }

    /**
     * join public group by url
     */
    public function joinGroup()
    {
        $group = $this->uri->segment(3);
        if ($this->__numGroups() && $this->__checkGroup($group) && $this->__inGroup($group) && $this->__checkGroupPaired($group)) {
                $this->datamod->addGroup($this->session->userdata('id'), null, null, $group);
                $this->session->set_flashdata('result', message('You have successfully joined the group <strong>' . $this->datamod->getGroupName($group) . '</strong>!', 1)); //groupCode
        }
        redirect(base_url('discover'));
    }


    /* Validation helper functions*/

    /**
     * verifies that user is in less than threshold groups
     * @return bool
     */
    private function __numGroups()
    {
        $num = $this->datamod->countPersonGroups($this->session->userdata('id'));
        if ($num < $this->datamod->getGlobalVar('max_groups'))
            return true;
        else {
            $this->session->set_flashdata('result', message('You are already in <strong>' . $num . '</strong> groups.  Leave a group and try again.',3));
            return false;
        }
    }

    /**
     * checks entered group code to make sure it exists
     * @param $str
     * @return bool
     */
    private function __checkGroup($str)
    {
        if ($this->datamod->checkGroup($str) == true) { //if exists
            return true;
        } else {
            $this->session->set_flashdata('result', message('The group you requested to join is invalid.', 3)); //groupCode
            return false;
        }
    }

    /**
     * checks if user is in inputted group
     * @param $str
     * @return bool
     */
    private function __inGroup($str)
    {
        if ($this->datamod->inGroup($this->session->userdata('id'), $str) == true) { //if ingroup
            $this->session->set_flashdata('result', message('You are already in the group <strong>' . $this->datamod->getGroupName($str) . '</strong>.',3));
            return false;
        } else return true;
    }

    /**
     * checks that group has not been paired
     * @param $code
     * @return bool
     */
    private function __checkGroupPaired($code) {
        if (!$this->datamod->paired($code)) //group has not been paired
            return true;
        else {
            $this->form_validation->set_message('result', 'Unable to join <strong>' . $this->datamod->getGroupName(set_value('group')) . '</strong> as partners are already assigned. Maybe next year?', 3);
            return false;
        }
    }
}