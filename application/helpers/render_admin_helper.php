<?php

/**
 * helper to inject additional data and framework views to view to be rendered
 * includes an additional admin sitebar
 * @param $view
 * @param null $data
 * @param null $title
 */
function render_admin($view, $data = null, $title = null)
{
    $CI = & get_instance();
    $CI->load->model('datamod');

    //consolidate data from admin_config file
    $g_vars = $CI->datamod->getGlobalVar(); //get all global vars

    $data['site_name']= $g_vars['site_name'];
    $data['partner_date'] = new DateTime(date('Y').'-'.$g_vars['evt_partner_date'][0]."-".$g_vars['evt_partner_date'][1]); //generate partner assignment date
    $data['gift_date'] = new DateTime(date('Y').'-'.$g_vars['evt_gift_date'][0].'-'.$g_vars['evt_gift_date'][1]);//generate gift exchange date

    $CI->load->view('header', array('title' => $title, 'site_name' => $data['site_name']));
    $CI->load->view('navbar', array('site_name' => $data['site_name']));
    $CI->load->view('admin/sidebar'); //load the admin dependency
    $CI->load->view($view, $data);
    if (!in_array($view, array("index", "landing"))) { //load extra footer content if not on home page
        $CI->load->view('footer');
    }
    $CI->load->view('footer_global'); //always load the global footer (analytics, closing tags, etc)
}
