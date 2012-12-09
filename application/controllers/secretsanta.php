

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
		
		$vars['timer'] = $this->countdown->generate(array('day'=> 21,'month'=> 12,'year'=> 2012,'hour'=> 7,'minute'=> 40,'second'=> 0), 'light'); //target date, light or dark
		render('index', $vars);
	}
}



/* End of file secretsanta.php */
/* Location: ./application/controllers/welcome.php */
