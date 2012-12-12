<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Secretsanta extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->library('countdown');
		$this->load->model('datamod');
		$vars['timer'] = $this->countdown->generate(array('day'=> 21,'month'=> 12,'year'=> 2012,'hour'=> 7,'minute'=> 40,'second'=> 0), 'light'); //target date, light or dark
		render('index', $vars);
	}
	
	public function about()
	{
		render('about');
	}
	
	public function survey()
	{
		$this->load->model('datamod');
		
		if ($this->datamod->getPrivKey($this->session->userdata('id')) != false) //only allow access if keys not set
			redirect('profile');
			
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_error_delimiters('<div style="color:red;margin-top:10px;font-size:12px;text-indent:5px">', '</div>');
		
		$this->form_validation->set_rules('pin', 'Pin', 'trim|required|min_length[4]|max_length[4]|numeric');
		$this->form_validation->set_rules('pinconf', 'Pin Confirmation', 'trim|required|min_length[4]|max_length[4]|numeric|matches[pin]');
		//$this->form_validation->set_rules('group', 'Group Code', 'trim|min_length[4]|max_length[4]|alpha_numeric');
	
		if ($this->form_validation->run() == FALSE)
		{
			render('survey');
		}
		else
		{
			$this->load->library('crypt');
			$keys = $this->crypt->create_key(md5($this->session->userdata('email').set_value('pin'))); //[private, public]
			//var_dump($keys);
			
			$id = $this->session->userdata('id');
			
			$this->datamod->storeKeyPair($id, $keys);
			if (!$this->datamod->inGroup($this->session->userdata('name'), 'hths')) //prevent duplicate additions to hths group
				$this->datamod->addgroup($this->session->userdata('name'),'hths');
			render('survey_success');
		}
	}
	
	public function logout() {
	$this->session->sess_destroy();
	render('logout_success');
	}
}


/* End of file secretsanta.php */
/* Location: ./application/controllers/welcome.php */
