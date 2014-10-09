<?php

function render($view, $data = null, $title = null)
{
    $CI = & get_instance();

    //consolidate data from admin_config file
    $data['site_name']= $CI->config->item('site_name');
    $data['partner_date'] = new DateTime(date('Y').'-'.$CI->config->item('evt_partner_month')."-".$CI->config->item('evt_partner_day')); //generate partner assignment date
    $data['gift_date'] = new DateTime(date('Y').'-'.$CI->config->item('evt_gift_month').'-'.$CI->config->item('evt_gift_day'));//generate gift exchange date

    $CI->load->view('header', array('title' => $title, $data));
    $CI->load->view('navbar', array('site_name' => $data['site_name']));
    $CI->load->view($view, $data);
    if (!in_array($view,array("index","landing"))){//load extra footer content if not on home page
        //file_exists('version.php') && include 'version.php';
        //$vars['app_disp'] = 1;
        //$vars['version'] = isset($version) ? ('v' . ($version / 1000) . 'a') : ('v0000a');
        $CI->load->view('footer');
    }
    $CI->load->view('footer_global');//always load the global footer (analytics, closing tags, etc)
}
