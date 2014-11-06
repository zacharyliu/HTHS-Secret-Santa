<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Group
 */
class Group extends CI_Controller
{

    /**
     * class constructor
     */
    public function __construct() {
        parent::__construct();

        $this->load->model('datamod');

        // TODO: move login enforcement into helper
        if ($this->session->userdata('auth') != 'true') {
            redirect(base_url("login/timeout"));
        }
    }

    /**
     * retrieve modal containing list of group members
     * @param $code
     * @param null $year
     */
    public function membersModal($code, $year = null) {
        $groupName = $this->datamod->getGroupName($code, $year);
        $members = $this->datamod->getMemberNames($code, $year);
        if ($members == false) $members = array();
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        </head>
        <body>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Members of <?=$groupName?></h4>
                </div>
                <div class="modal-body">
                    <ul>
                        <?php foreach ($members as $member) : ?>
                            <li><?=$member?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
        </body>
        </html>
<?php
    }

}