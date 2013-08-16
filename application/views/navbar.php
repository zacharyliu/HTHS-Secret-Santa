<div style="width:100%;height:50px;margin:0;padding:0;">

    <?php
    $link = 'index'; // url link
    $src = '/img/secretsanta.png'; // image url
    echo '<a  href="' . $link . '"><img class=imgHoverable style="border:none;" src="' . $src . '" /></a>';
    ?>

    <span style="width:auto;margin:20px;padding:0px;float:right;text-align:right">
	<?php
    if ($this->session->userdata('auth') == 'true')
        echo '<span class="subheader">' . $this->session->userdata('name') . '&nbsp;|&nbsp</span>';
    if ($this->session->userdata('admin') == 'true')
        echo '<a class="subheader" href="' . base_url("admin") . '">admin panel</a>&nbsp|&nbsp;';
    if ($this->session->userdata('auth') == 'true') {
        echo '<a class="subheader" href="' . base_url("profile") . '">profile</a>' . '&nbsp;|&nbsp;' . '<a class="subheader" href="' . base_url('login/logout') . '">logout</a>';
    } else {
        echo '<a class="subheader" href="' . base_url("login") . '">Login/Register</a>&nbsp';
    }?>

        </br>
        <a class="subheader" href="<?php echo base_url("/secretsanta/about") ?>">How do I Secret Santa?</a>

	</span>
</div>
</br> </br>  </br>
<hr>