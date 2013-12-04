<?php

class Isitsecretsantatime extends CI_Controller
{
    public function index()
    {
        $data['answer'] = (time() > strtotime("December 8, 2013 EST")) ? 'YES' : 'NO';
        render('isitsecretsantatime', $data);
    }
}