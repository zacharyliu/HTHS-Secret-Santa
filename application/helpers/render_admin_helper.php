<?

function render_admin($view, $data = null, $title = null)
{
    $CI = & get_instance();

    $CI->load->view('header', array('title' => $title));
    $CI->load->view('navbar');
    $CI->load->view('admin/sidebar'); //load the admin dependency
    $CI->load->view($view, $data);
    if (!in_array($view, array("index", "landing"))) { //load extra footer content if not on home page
        $CI->load->view('footer');
    }
    $CI->load->view('footer_global'); //always load the global footer (analytics, closing tags, etc)
}
