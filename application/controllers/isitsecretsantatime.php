<?php

/**
 * Class Isitsecretsantatime
 */
class Isitsecretsantatime extends CI_Controller
{
    /**
     * controller index
     */
    public function index()
    {
        $this->load->model("datamod");
        $gift_date = $this->datamod->getGlobalVar('evt_gift_date');
        $data['answer'] = new DateTime() > new DateTime(date('Y').'-'.$gift_date[0].'-'.$gift_date[1]) ? 'YES' : 'NO';
        render('isitsecretsantatime', $data);
    }
}