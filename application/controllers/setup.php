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

        if (ENVIRONMENT != 'development') {
            die('Setup script must be run in development environment.');
        }

    }

    /**
     * index page for setup page
     */
    public function index()
    {
        if ( ! $this->migration->version((1)))
        {
            show_error($this->migration->error_string());
        }
    }
}