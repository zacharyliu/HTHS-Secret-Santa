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
     * @deprecated new user survey
     */
    public function survey()
    {
        $this->load->model('datamod');

        //if ($this->datamod->getPrivKey($this->session->userdata('id')) != false) //only allow access if keys not set
        redirect('profile');

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('<div style="color:red;margin-top:10px;font-size:12px;text-indent:5px">', '</div>');

        $this->form_validation->set_rules('pin', 'Pin', 'trim|required|min_length[4]|max_length[4]|numeric');
        $this->form_validation->set_rules('pinconf', 'Pin Confirmation', 'trim|required|min_length[4]|max_length[4]|numeric|matches[pin]');
        //$this->form_validation->set_rules('group', 'Group Code', 'trim|min_length[4]|max_length[4]|alpha_numeric');

        if ($this->form_validation->run() == FALSE) {
            render('survey');
        } else {
            //$this->load->library('crypt');
            //$keys = $this->crypt->create_key(md5($this->session->userdata('email') . set_value('pin'))); //[private, public]
            //var_dump($keys);

            $id = $this->session->userdata('id');

            //$this->datamod->storeKeyPair($id, $keys);
            if (!$this->datamod->inGroup($this->session->userdata('name'), 'hths')) //prevent duplicate additions to hths group
            $this->datamod->addgroup($this->session->userdata('name'), 'hths');
            render('survey_success');
        }
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
