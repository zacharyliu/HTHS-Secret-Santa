<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Class Adminmod
 */
class Adminmod extends CI_Model
{

    /**
     * class constructor
     */
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->current_year = intval(date('Y'));
    }

    /**
     * set global variable
     * prevents setting variable if it doesn't exist
     * must be passed in one pair at a time
     * @param string $key
     * @param mixed $val
     * @return bool
     */
    public function setGlobalVar($key,$val) {
        if (is_array($val) || is_object($val)){
            $val = serialize($val);
        }
        //check if var exists
        $query = $this->db->get_where('globalvars', array("key" => $key));
        if ($query->num_rows() == 0) return false;

        $this->db->where('key', $key)->update('globalvars', array('val' => $val));
        return true;
    }

    /**
     * runs group pairing for group
     * @param $code     group code
     * @return int
     */
    public function pairCustom($code)
    {
        $this->db->trans_start();

        $this->db->from('pairs');
        $this->db->where(array('code' => $code, 'year' => $this->current_year));
        $query = $this->db->get();

        // Refuse to run on a group already paired
        if ($query->num_rows() != 0) return false;

        $members = $this->datamod->getMembers($code);
        shuffle($members); //randomize array
        $total = count($members);
        for ($i = 0; $i < $total; $i++) {
            $give = $members[$i];
            $receive = ($i + 1 < $total ? $members[$i + 1] : $members[0]); //loop back to first element if i+1 > total # of members
            $this->addPair($code, $give, $receive, $this->current_year);
        }

        $this->db->where(array('code' => $code, 'year' => $this->current_year))->update('groups', array('leaveable'=>0));
        $this->db->trans_complete();

        return $total;
    }

    /**
     * add a new pair to pairs table
     * @param string    $code     group code
     * @param int       $give     user id of giver
     * @param int       $receive  user id of receiver
     * @param int       $year     year of exchange
     * @return bool
     */
    public function addPair($code, $give, $receive, $year)
    {
        $data = array('code' => $code, 'give' => $give, 'receive' => $receive, 'year' => $year);
        // Check if the pair is already in the database
        $this->db->where($data);
        $query = $this->db->get('pairs');
        if ($query->num_rows() == 0) {
            // Not yet in the database, insert them
            $this->db->insert('pairs', $data);
            return true;
        } else {
            return false;
        }
    }


    /**
     * list all template groups
     * @return array|bool
     */
    public function listTemplateGroups()
    {
        $data = false;
        foreach ($this->db->get('groups_template')->result() as $row) {
            $row->exists = $this->__checkGroupExists($row->code);
            $data[] = $row;
        }
        return $data;
    }

    /**
     * insert a new template group into the template groups table
     * @param string $code
     * @param string $name
     * @param string $description
     * @param bool   $privacy
     * @return string
     */
    public function newTemplateGroup($code, $name, $description, $privacy)
    {
        $this->db->insert('groups_template', array('code' => $code, 'name' => $name, 'description' => $description, 'private' => $privacy));
        return $code; //$this->db->insert_id();
    }

    /**
     * delete a template group
     * @param string $code
     * @return bool
     */
    public function deleteTemplateGroup($code)
    {
        $this->db->delete('groups_template', array('code' => $code));
        return true;
    }

    /**
     * edit a template group
     * @param string $code
     * @param string $name
     * @param string $description
     * @param bool   $privacy
     * @return bool
     */
    public function editTemplateGroup($code, $name, $description, $privacy)
    {
        $this->db->update('groups_template', array('name' => $name, 'description' => $description, 'private' => $privacy), array('code' => $code));
        if ($this->__checkGroupExists($code)) {
            $this->__editGroup($code, $name, $description, $privacy);
        }
        return true;
    }

    /**
     * create a joinable group from template
     * @param string $code
     */
    public function createTemplateGroup($code)
    {
        $template = $this->db->get_where('groups_template', array('code' => $code))->result();
        $template = $template[0];
        $this->db->insert('groups', array('code' => $template->code, 'name' => $template->name, 'description' => $template->description, 'private' => $template->private, 'deleteable' => 0, 'year' => $this->current_year));
    }

    /**
     * check if a group exists
     * @param string $code
     * @param int    $year      current year
     * @return bool
     */
    private function __checkGroupExists($code, $year = NULL)
    {
        if ($year == null) $year = $this->current_year;
        $query = $this->db->get_where('groups', array('code' => $code, 'year' => $year));

        if ($query->num_rows() == 0) return false;
        else return true;
    }

    /**
     * edit a group
     * @param string $code
     * @param string $name
     * @param string $description
     * @param string $privacy
     * @param int $year
     * @return mixed
     */
    private function __editGroup($code, $name, $description, $privacy, $year = NULL)
    {
        if ($year == null) $year = $this->current_year;
        return $this->db->update('groups', array('name' => $name, 'description' => $description, 'private' => $privacy), array('code' => $code, 'year' => $year));
    }

    /**
     * retrieve a list of whitelisted emails
     * @return array
     */
    public function getAllowedEmails()
    {
        $results = $this->db->get('allowed_emails')->result();
        $array = array();
        foreach ($results as $result) {
            array_push($array, $result->email);
        }
        return $array;
    }

    /**
     * add a new whitelisted email
     * @param $email
     * @return mixed
     */
    public function addAllowedEmail($email)
    {
        return $this->db->insert('allowed_emails', array('email' => $email));
    }

    /**
     * process a string for email sending
     * @param $string
     * @return mixed
     */
    private function addSlashesDoubleQuote($string)
    {
        return str_replace('"', '\"', $string);
    }

    /**
     * send a new email
     * @param string $to
     * @param string $subjectTemplate
     * @param string $messageTemplate
     * @param $vars
     */
    public function sendMail($to, $subjectTemplate, $messageTemplate, $vars)
    {
        $this->load->library('email');
        // The following two functions are major security holes
        // although the addSlashesDoubleQuote() function lowers the risk considerably
        extract($vars); // TODO: use different way of loading variables to avoid risky extract() function
        $subject = '';
        eval('$subject = "' . $this->addSlashesDoubleQuote($subjectTemplate) . '";'); // TODO: use better template system to avoid eval() security risk
        $message = '';
        eval('$message = "' . $this->addSlashesDoubleQuote($messageTemplate) . '";'); // TODO: use better template system to avoid eval() security risk

        $this->email->from($this->config->item('email_from_name'),
            $this->config->item('email_from_email'));
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->send();
    }

}