<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Setup
 *
 * controller to setup app
 */
class Setup extends CI_Controller
{

    /**
     * class constructor
     *
     * @see message_helper  helps in generating bootstrap alerts
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('message'); //helps in generating bootstrap alerts
        $this->load->library('form_validation'); //form validation helper
        $this->load->library('migration');
        $this->load->model('datamod');

        if (ENVIRONMENT != 'development') {
            die('Setup script must be run in development environment.');
        }

    }

    /**
     * index page for setup page
     */
    public function index()
    {
        /*if ($this->datamod->getGlobalVar('setup') == TRUE) {
            die('Setup script has already been run.');
        }*/
        $this->load->library('form_validation');
        //$this->load->helper('email'); //email validation
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('admin-email', 'Admin Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('admin-email-confirm', 'Confirm Admin Email', 'trim|required|matches[admin-email]|xss_clean');
        $this->form_validation->set_rules('site-name', 'Site Name', 'trim|required|max_length[40]|xss_clean');
        $this->form_validation->set_rules('domain-restriction', 'Domain Restriction', 'trim|callback_validRegex|xss_clean');

        if ($this->form_validation->run() == false) {
            render('setup/setup');
        }
        else {
            if (!$this->migration->version((1))) {
                show_error($this->migration->error_string());
                exit('Setup fauled. Aborting...');
            }

            $this->adminmod->setGlobalVar('admin_users', array(set_value('admin-email')));
            $this->adminmod->setGlobalVar('site_name', set_value('site-name'));
            $this->adminmod->setGlobalVar('domain_restriction', set_value('domain-restriction'));

            render("setup/success");
        }
    }

    public function setup() {

    }


    public function update() {

    }

    public function runUpdate() {

    }
}