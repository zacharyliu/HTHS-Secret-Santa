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
     * index page for discover page
     */
    public function index()
    {
        $trending = $this->datamod->listTrendingGroups();
        render("discover", array("trending" => $trending));
    }

    public function joinGroup()
    {
        $group = $this->uri->segment(3);
        if ($this->numGroups() && $this->checkgroup($group) && $this->inGroup($group)) {
                $this->datamod->addGroup($this->session->userdata('id'), $group);
                $this->session->set_flashdata('result', message('You have successfully joined the group <strong>' . $this->datamod->getGroupName($group) . '</strong>!', 1)); //groupCode
        }
        redirect(base_url('discover'));
    }


    /* Validation helper functions*/

    private function numGroups()
    { //verifies that user is in less than threshold groups
        $num = $this->datamod->countPersonGroups($this->session->userdata('id'));
        if ($num < $this->config->item('max_groups'))
            return true;
        else {
            $this->session->set_flashdata('result', 'You are already in <strong>' . $num . '</strong> groups.  Leave a group and try again.');
            return false;
        }
    }

    private function checkGroup($str)
    { //checks entered group code to make sure it exists
        if ($this->datamod->checkGroup($str) == true) { //if exists
            return true;
        } else {
            $this->session->set_flashdata('result', message('The group you requested to join is invalid.', 3)); //groupCode
            return false;
        }
    }

    private function inGroup($str)
    { //checks if user is in inputted group
        if ($this->datamod->inGroup($this->session->userdata('id'), $str) == true) { //if ingroup
            $this->session->set_flashdata('result', message('You are already in the group <strong>' . $this->datamod->getGroupName($str) . '</strong>.',3));
            return false;
        } else return true;
    }
}