<?php

function render($view, $data = null, $title = null)
{
    $CI = & get_instance();

    $CI->load->view('header', array('title' => $title));
    $CI->load->model('messagesmod');
    $CI->load->view('navbar', array('newMessageCount' => $CI->messagesmod->newMessageCount()));
    $CI->load->view($view, $data);
    if (!in_array($view,array("index","landing"))){//load extra footer content if not on home page
        file_exists('version.php') && include 'version.php';
        //$vars['app_disp'] = 1;
        //$vars['version'] = isset($version) ? ('v' . ($version / 1000) . 'a') : ('v0000a');
        $CI->load->view('footer');
    }
    $CI->load->view('footer_global');//always load the global footer (analytics, closing tags, etc)
}
