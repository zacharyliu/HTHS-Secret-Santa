<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends CI_Controller
{

    public function index()
    {
        require(APPPATH . 'classes/openid.php');
        //require(APPPATH . 'config/admin_settings.php'); //retrieve the list of admin users

        $openid = new LightOpenID($_SERVER['HTTP_HOST']);
        if (!$openid->mode) {
            // Didn't get login info from the OpenID provider yet / came from the login link
            $openid->identity = 'https://www.google.com/accounts/o8/id';
            $openid->required = array('namePerson/first', 'namePerson/last', 'contact/email');
            header('Location: ' . $openid->authUrl());
        } else if ($openid->mode == 'cancel') {
            // The user decided to cancel logging in, so we'll redirect to the home page instead
            redirect('/');
        } else {
            // The user has logged in and the user's info is ready
            if (!$openid->validate()) {
                // Authentication failed, try logging in again
                $this->login_failure('Authentication failed, try logging in again.');
            } else {
                // Authentication was successful

                // Get user attributes:
                $user_data = $openid->getAttributes();

                // Check to make sure that the user is logging in using a @ctemc.org account or email exception:
                $this->load->model('datamod');
                if ($this->config->item('domain_restriction') == '' || (preg_match($this->config->item('domain_restriction'), $user_data['contact/email'])) || $this->datamod->checkAllowedEmailException($user_data['contact/email'])) {
                    //echo "Welcome, " . " ` . $user_data['namePerson/first'] . ' ' . $user_data['namePerson/last'];

                    $fname = $user_data['namePerson/first'];
                    $lname = $user_data['namePerson/last'];
                    $email = $user_data['contact/email'];

                    // Load user ID if it exists
                    $user_id = $this->datamod->getUserId($email);
                    if ($user_id == false) {
                        $this->datamod->addUser($fname . " " . $lname, $email);
                        $user_id = $this->datamod->getUserId($email);//@todo addUser should return id
                    }
                    //check for admin permissions
                    if (in_array($user_data['contact/email'],$this->config->item('admin_users'))) //check against imported admin_users.config file
                        $admin = 'true';
                    else
                        $admin = 'false';

                    //set session info
                    $this->session->set_userdata(array('auth' => 'true', 'admin' => $admin, 'fname' => $fname, 'lname' => $lname,'email' => $email, 'id' => $user_id));

                    //if ($this->datamod->getPrivKey($user_id) == false)
                        //redirect(base_url('secretsanta/survey'));
                    redirect(base_url('/profile'));
                } else {
                    $this->login_failure('Please log in using an @ctemc.org account or contact an administrator.');
                }

            }
        }
    }

    private function login_failure($message = 'Login failure')
    {
        //echo $message;
        render("landing",array("icon"=>"&#xf071;","header"=>"Login failure","subheader"=>$message));
    }

    public function timeout(){
        render("landing",array("icon"=>"&#xf071;","header"=>"Oops! You don't have permission to view this page.","subheader"=>"Your session has expired, or you are not logged in. Please <a href='/login'>login</a> to continue."));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        render("landing",array("icon"=>"&#xf058;","header"=>"Logout success!","subheader"=>"You have successfully been logged out of your account. Come back soon!"));
    }

}