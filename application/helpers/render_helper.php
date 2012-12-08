<?php

function render($content, $title = '') {
    $CI = &get_instance();
    
    $CI->load->view('header', array('title' => $title));
    $CI->load->view('index', array('content' => $content));
    
    $version = 0;
    $vars['app_disp'] = 1; 
    $vars['version'] = 'v'.($version/1000).'a';
    $CI->load->view('footer',$vars);	
}
