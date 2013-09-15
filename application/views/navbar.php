<!--NAVBAR-->
<div class="navbar navbar-inverse navbar-fixed-top ">
    <button type="button" id="nav-btn" class="navbar-toggle" data-type="open" data-toggle="collapse" data-target=".bs-navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="/"><img id="title-icon" src="../img/app-icon.png" height="45px" width="45px" /><span id="title">HTHS Secret Santa</span></a>

    <div class="navbar-collapse collapse" style="overflow: hidden;">
        <ul class="nav navbar-nav navbar-left">
            <li><a href="/">Home</a></li>
            <li><a href="/about">About</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php
            if ($this->session->userdata('auth') == 'true') {
                echo '<li><a style="background: rgba(0,0,0,0)">Hi ' . $this->session->userdata('name') . '!</a></li>';
                if ($this->session->userdata('admin') == 'true')
                    echo '<li><a href="' . base_url("admin") . '">Admin Panel</a>';
                echo '<li><a href="/profile">Profile</a></li>' . '<li><a href="/login/logout">Logout</a>';
            } else {
                echo '<li><a href="/login">Login / Register</a></li>';
            }?>
        </ul>
    </div>
</div>

           <!-- <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                </ul>
                <ul class="nav pull-right">
                    <?php
                    if ($this->session->userdata('auth') == 'true') {
                        echo '<li><a style="background: rgba(0,0,0,0)">Welcome back, ' . $this->session->userdata('name') . '.</a></li>';
                        if ($this->session->userdata('admin') == 'true')
                            echo '<li><a href="' . base_url("admin") . '">Admin Panel</a>';
                        echo '<li><a href="/profile">Profile</a></li>' . '<li><a href="/login/logout">Logout</a>';
                    } else {
                        echo '<li><a href="/login">Login / Register</a></li>';
                    }?>

                </ul>
            </div>
            <!--/.nav-collapse
        </div>
    </div>
</div>
<!--<div style="width:100%;height:50px;margin:0;padding:0;">

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
<hr>-->