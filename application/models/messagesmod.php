<?php

class Messagesmod extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->current_year = intval(date('Y'));
    }

    public function listThreads($user_id) {
        return $this->db->select('message_id, year, code, message, read, MAX(timestamp)')
            ->from('messages')
            ->where('user_id', $user_id)
            ->group_by('year, code')
            ->get()->result();
    }

    public function markAsRead($user_id, $year = null, $code)
    {
        if ($year == null) $year = $this->current_year;
        return $this->db->update('messages', array('read' => true), array('user_id' => $user_id, 'year' => $year, 'code' => $code));
    }

    public function newMessageCount($user_id)
    {
        return $this->db->select('*')
            ->from('messages')
            ->where(array('user_id' => $user_id, 'read' => false))
            ->group_by('year, code')
            ->get()->num_rows();
    }

    public function getThread($user_id, $year = null, $code) {
        if ($year == null) $year = $this->current_year;
        return $this->db->select('*')
            ->from('messages')
            ->where(array('user_id' => $user_id, 'year' => $year, 'code' => $code))
            ->order_by('timestamp', 'desc')
            ->get()->result();
    }

    public function send($to_id, $year = null, $code, $message) {
        if ($year == null) $year = $this->current_year;
        $success = $this->db->insert('messages', array('user_id' => $to_id, 'year' => $year, 'code' => $code, 'message' => $message));

        if (!$success) return false;

        return $this->sendNotificationEmail($to_id);
    }

    private function sendNotificationEmail($to_id)
    {
        $this->load->library('email');
        $subject = "You have a new Secret Santa private message!";
        // TODO: get recipient name
        $message = "Hello $name,

        You have just received a new private message on the Secret Santa site!

        Log in to your profile now to check your messages.";

        $this->email->from($this->config->item('email_from_name'),
            $this->config->item('email_from_email'));
        $this->email->to($to); // TODO: get recipient email
        $this->email->subject($subject);
        $this->email->message($message);

        return $this->email->send();
    }

}