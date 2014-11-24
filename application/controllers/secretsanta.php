<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Secretsanta
 * contains the main page
 */
class Secretsanta extends CI_Controller
{

    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('datamod');

        //redirect to setup if app not setup
        if ($this->datamod->getGlobalVar('setup') == false) {
            redirect(base_url('setup'));
        }

    }

    /**
     * home page
     */
    public function index()
    {
        $this->load->library('countdown');
        $gift_date = $this->datamod->getGlobalVar("evt_gift_date");
        $vars['timer'] = $this->countdown->generate(array('day' => $gift_date[1], 'month' => $gift_date[0], 'year' => date("Y"), 'hour' => 7, 'minute' => 40, 'second' => 0), 'light'); //target date, light or dark
        $vars['is_logged_in'] = ($this->session->userdata('auth') == 'true');
        render('index', $vars);
    }

    /**
     * about page
     */
    public function about()
    {
        render('about');
    }


    /**
     * 404 landing page
     */
    public function notfound() {
        render("landing",array("icon"=>"&#xf071;","header"=>"404 Page not Found","subheader"=>"The page you requested does not exist."));
    }
}


/* End of file secretsanta.php */
/* Location: ./application/controllers/welcome.php */
