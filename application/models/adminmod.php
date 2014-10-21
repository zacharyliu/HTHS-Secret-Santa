<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Class Adminmod
 */
class Adminmod extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->current_year = intval(date('Y'));
        if (ENVIRONMENT != 'development') die("Must be run in development environment.");
    }

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


    public function listTemplateGroups()
    {
        $data = false;
        foreach ($this->db->get('groups_template')->result() as $row) {
            $row->exists = $this->__checkGroupExists($row->code);
            $data[] = $row;
        }
        return $data;
    }

    public function newTemplateGroup($code, $name, $description, $privacy)
    {
        $this->db->insert('groups_template', array('code' => $code, 'name' => $name, 'description' => $description, 'private' => $privacy));
        return $code; //$this->db->insert_id();
    }


    public function deleteTemplateGroup($code)
    {
        $this->db->delete('groups_template', array('code' => $code));
        return true;
    }

    public function editTemplateGroup($code, $name, $description, $privacy)
    {
        $this->db->update('groups_template', array('name' => $name, 'description' => $description, 'private' => $privacy), array('code' => $code));
        if ($this->__checkGroupExists($code)) {
            $this->__editGroup($code, $name, $description, $privacy);
        }
        return true;
    }

    public function createTemplateGroup($code)
    {
        $template = $this->db->get_where('groups_template', array('code' => $code))->result();
        $template = $template[0];
        $this->db->insert('groups', array('code' => $template->code, 'name' => $template->name, 'description' => $template->description, 'private' => $template->private, 'deleteable' => 0, 'year' => $this->current_year));
    }

    private function __checkGroupExists($code, $year = NULL)
    {
        if ($year == null) $year = $this->current_year;
        $query = $this->db->get_where('groups', array('code' => $code, 'year' => $year));

        if ($query->num_rows() == 0) return false;
        else return true;
    }

    private function __editGroup($code, $name, $description, $privacy, $year = NULL)
    {
        if ($year == null) $year = $this->current_year;
        return $this->db->update('groups', array('name' => $name, 'description' => $description, 'private' => $privacy), array('code' => $code, 'year' => $year));
    }

    public function getAllowedEmails()
    {
        $results = $this->db->get('allowed_emails')->result();
        $array = array();
        foreach ($results as $result) {
            array_push($array, $result->email);
        }
        return $array;
    }

    public function addAllowedEmail($email)
    {
        return $this->db->insert('allowed_emails', array('email' => $email));
    }

    private function addSlashesDoubleQuote($string)
    {
        return str_replace('"', '\"', $string);
    }

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