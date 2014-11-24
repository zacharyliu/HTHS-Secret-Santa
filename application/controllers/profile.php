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
        $this->load->helper('message'); //helps in generating bootstrap alerts
        //$this->load->helper('form');//form helper
        $this->load->library('form_validation'); //form validation helper

        if ($this->session->userdata('auth') != 'true') {
            redirect(base_url("login/timeout"));
        }

        /*if (!$this->datamod->getPrivKey($this->session->userdata('id'))) //if key is not set, set key
            redirect('secretsanta/survey');
*/
    }

    /**
     * internal render function to inject group info for the current user in profile page
     * @param array $data other data that needs passing
     * @private
     */
    private function _render($data = array())
    {
        $array_defaults = array('first_year' => $this->datamod->getJoinYear($this->session->userdata('id')), 'current_year' => intval(date('Y')));
        foreach (array_keys($array_defaults) as $key) {
            if (!isset($data[$key])) $data[$key] = $array_defaults[$key];
        }
        
        $groupsInfo = $this->datamod->groupInfoMultiple($this->session->userdata('id')); //get all relevant group info for that year, and merge it with the rest of the groups
        $data = array_merge(array('groups' => $groupsInfo), $data); //inject it into data array
        $data['max_groups'] = $this->datamod->getGlobalVar('max_groups');
        $data['interests'] = $this->datamod->getUserInterests($this->session->userdata('id'));

        render('profile', $data);
    }

    /**
     * index page for profile page
     */
    public function index()
    {
        $this->_render();
    }

    /**
     * form helper for adding group by code
     */
    public function groupcode()
    {

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('group', 'Group Code', 'trim|required|min_length[4]|max_length[4]|alpha_numeric|callback_checkGroup|callback_inGroup|callback_numGroups|callback_checkGroupPaired');

        if ($this->form_validation->run() == FALSE) {
            $this->_render();
        } else {
            $this->datamod->addGroup($this->session->userdata('id'), null, null, set_value('group'));
            $this->session->set_flashdata('result', message('You have successfully joined the group <strong>' . $this->datamod->getGroupName(set_value('group')) . '</strong>!', 1)); //groupCode
            redirect(base_url('profile'));
        }
    }

    /**
     * form helper for creating new group
     */
    public function addgroup()
    {
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('group_name', 'Group Name', 'trim|required|min_length[4]|max_length[50]|callback_numGroups|xss_clean');
        $this->form_validation->set_rules('group_description', 'Group Description', 'trim|max_length[150]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->_render();
        } else {
            $this->datamod->addGroup($this->session->userdata('id'), set_value('group_name'), set_value('group_description'));
            $this->session->set_flashdata('result', message('You have successfully created the group <strong>' . set_value('group_name') . '</strong>! Your group code is <strong>' . $this->datamod->getGroupCode(set_value('group_name')) . '</strong>.', 1)); //groupcreate
            redirect(base_url('profile'));
        }
    }

    /**
     * form helper for editing a group
     */
    public function editGroup()
    {
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('edit-grp-code', 'edited Group Code', 'trim|required|min_length[4]|max_length[4]|callback_checkGroupOwner|xss_clean');
        $this->form_validation->set_rules('edit-grp-name', 'edited Group Name', 'trim|required|min_length[4]|max_length[50]|xss_clean');
        $this->form_validation->set_rules('edit-grp-description', 'edited Group Description', 'trim|max_length[150]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->_render();
        } else {
            if ($this->datamod->editGroup(set_value('edit-grp-code'), set_value('edit-grp-name'), set_value('edit-grp-description'))) {
                $this->session->set_flashdata('result', message('Successfully updated settings for the group <strong>' . set_value('edit-grp-name') . '</strong>.', 0));
            } else $this->session->set_flashdata('result', message('Poopy. Something went wrong. :( ', 3));
            redirect(base_url('profile'));
        }
    }

    /**
     * remove membership from group
     * @param $code
     */
    public function rm($code)
    {
        $groupname = $this->datamod->getGroupName($code);

            if ($this->datamod->removeFromGroup($this->session->userdata('id'), $this->uri->segment(3)))
                $this->session->set_flashdata('result', message('Successfully left the group <strong>' . $groupname . '</strong>.', 0));
            else $this->session->set_flashdata('result', message('<strong>Error!</strong> You can\'t leave this group!', 3));
        redirect(base_url('profile'));

    }

    /**
     * edit user interests
     */
    public function editInterests() {
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('interests-textarea', 'Interests', 'trim|max_length[300]|strip_tags|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->_render();
        } else {
            $this->datamod->setUserInterests($this->session->userdata('id'),set_value('interests-textarea'));
            $this->session->set_flashdata('result', message("Successfully updated interests!"));
            redirect(base_url('profile'));
        }

    }

    public function userinterests($code) {
        $partner = $this->datamod->getPair($code,$this->session->userdata('id'),intval(date('Y'))); //[id,name]
        if ($partner == false) {
            $title = "No partner assigned yet.";
            $body = "Check back later when partners are assigned!";
        }
        else {
            $title = "$partner[1]'s Interests";
            $body = $this->datamod->getUserInterests($partner[0]);
            if ($body == "") $body = "Nothing to show here...";
        }


        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        </head>
        <body>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?=$title?></h4>
        </div>
        <div class="modal-body">
            <?=$body?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </body>
        </html>
        <?
    }


    //
    //form validation callback functions
    //

    /**
     * checks entered group code to make sure it exists
     * @param $str
     * @return bool
     */
    public function checkGroup($str)
    {
        if ($this->datamod->checkGroup($str) == true) { //if exists
            return true;
        } else {
            $this->form_validation->set_message('checkGroup', 'The %s you entered does not exist.');
            return false;
        }
    }

    /**
     * checks entered group name to make sure it exists
     * @deprecated
     * @param $str
     * @return bool
     */
    public function checkGroupName($str)
    {
        if ($this->datamod->checkGroupName($str) == true) { //if exists
            $this->form_validation->set_message('checkGroupName', 'The %s you entered already exists. :(');
            return false; //unusable
        } else return true;
    }

    /**
     * checks if user is in inputted group
     * @param $str
     * @return bool
     */
    public function inGroup($str)
    {
        if ($this->datamod->inGroup($this->session->userdata('id'), $str) == true) { //if ingroup
            $this->form_validation->set_message('inGroup', 'You are already in the group <strong>' . $this->datamod->getGroupName(set_value('group')) . '</strong>.');
            return false;
        } else return true;
    }

    /**
     * verifies that user is in less than threshold groups
     * @return bool
     */
    public function numGroups()
    {
        $num = $this->datamod->countPersonGroups($this->session->userdata('id'));
        if ($num < $this->datamod->getGlobalVar('max_groups'))
            return true;
        else {
            $this->form_validation->set_message('numGroups', 'You are already in <strong>' . $num . '</strong> groups.  Leave a group and try again.');
            return false;
        }
    }

    /**
     * checks that current user is owner of the group
     * @param $str
     * @return bool
     */
    public function checkGroupOwner($str)
    {
        if ($this->datamod->getGroupOwner($str) == $this->session->userdata('id'))
            return true;
        else {
            $this->form_validation->set_message('checkGroupOwner', 'You do not have permissions to edit the group <strong>' . $this->datamod->getGroupName(set_value('edit-grp-code')) . '</strong>.');
            return false;
        }
    }

    /**
     * group has not been paired
     * @param $code
     * @return bool
     */
    public function checkGroupPaired($code) {
        if (!$this->datamod->paired($code))
                return true;
        else {
            $this->form_validation->set_message('checkGroupPaired', 'Unable to join <strong>' . $this->datamod->getGroupName(set_value('group')) . '</strong> as partners are already assigned. Maybe next year?');
            return false;
        }
    }
}