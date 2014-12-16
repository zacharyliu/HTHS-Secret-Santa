<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Login
 */
class Login extends CI_Controller
{
    /**
     * controller index
     */
    public function index()
    {
        require(APPPATH . 'classes/CAS.php');

        phpCAS::setDebug();

        // initialize phpCAS
        phpCAS::client(CAS_VERSION_2_0,'fed.princeton.edu',443,'cas');

        // no SSL validation for the CAS server
        phpCAS::setNoCasServerValidation();

        // force CAS authentication
        phpCAS::forceAuthentication();

        if (true) {
            // The user has logged in and the user's info is ready
            if (true) {
                // Authentication was successful

                // Get user attributes:
                $netID = phpCAS::getUser();
                $ds = ldap_connect("ldap.princeton.edu");
                $r = ldap_bind($ds);
                $sr = ldap_search($ds, "uid=$netID,o=Princeton University,c=US", "sn=*");
                $info = ldap_get_entries($ds, $sr);

                $user_data = array(
                    "namePerson/first" => $info[0]["givenname"][0],
                    "namePerson/last" => $info[0]["sn"][0],
                    "contact/email" => $netID . "@princeton.edu"
                );

                // Check to make sure that the user is logging in using a @ctemc.org account or email exception:
                $this->load->model('datamod');
                $domain_restriction = $this->datamod->getGlobalVar('domain_restriction');
                if ($domain_restriction == '' || (preg_match($domain_restriction, $user_data['contact/email'])) || $this->datamod->checkAllowedEmailException($user_data['contact/email'])) {
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
                    if (in_array($user_data['contact/email'],$this->datamod->getGlobalVar('admin_users'))) //check against imported admin_users.config file
                        $admin = 'true';
                    else
                        $admin = 'false';

                    //set session info
                    $this->session->set_userdata(array('auth' => 'true', 'admin' => $admin, 'fname' => $fname, 'lname' => $lname,'email' => $email, 'id' => $user_id));

                    //if ($this->datamod->getPrivKey($user_id) == false)
                        //redirect(base_url('secretsanta/survey'));
                    redirect(base_url('/profile'));
                } else {
                    $this->login_failure('Please log in using an authorized email account or contact an administrator.');
                }

            }
        }
    }

    /**
     * page to render if login fails
     * @param string $message
     */
    private function login_failure($message = 'Login failure')
    {
        //echo $message;
        render("landing",array("icon"=>"&#xf071;","header"=>"Login failure","subheader"=>$message));
    }

    /**
     * login timeout
     */
    public function timeout(){
        render("landing",array("icon"=>"&#xf071;","header"=>"Oops! You don't have permission to view this page.","subheader"=>"Your session has expired, or you are not logged in. Please <a href='/login'>login</a> to continue."));
    }

    /**
     * logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        render("landing",array("icon"=>"&#xf058;","header"=>"Logout success!","subheader"=>"You have successfully been logged out of your account. Come back soon!"));
    }

}