<html>
<head>
<title><?php
$string = 'HTHS Secret Santa';
echo (isset($title) && $title != '') ? ($title . ' - ' . $string) : $string;
?></title>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36741494-2']);
  _gaq.push(['_setDomainName', 'secretsanta.tk']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('/css/main.css')?>">
</head>

<body>
<div style="width:100%;height:50px;margin:0;padding:0;">

	<?php
	$link = 'index'; // url link
	$src = '/img/secretsanta.png'; // image url
	echo '<a href="'. $link .'"><img style="border:none;" src="'. $src .'" /></a>';
	?>

	<span style="width:auto;margin:20px;padding:0px;float:right;text-align:right">
	<?php 
	if ($this->session->userdata('auth') == 'true')
	echo $this->session->userdata('name').'&nbsp;|&nbsp';
	if ($this->session->userdata('admin') == 'true')
	echo '<a href="'.base_url("admin").'">admin panel</a>&nbsp|&nbsp;';
	if ($this->session->userdata('auth') == 'true') {
    echo '<a href="'.base_url("profile").'">profile</a>'.'&nbsp;|&nbsp;'.'<a href="'.base_url('login/logout').'">logout</a>';
	}
	else {
	echo '<a class="subheader" href="'.base_url("login").'">login/register</a>&nbsp';
    }?>
	
	</br>
	<a class="subheader" href="<?php echo base_url("/secretsanta/about")?>">How do I Secret Santa?</a> 
	
	</span>
</div>
</br> </br>  </br>
<hr>
</body>