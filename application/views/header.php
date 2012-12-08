<html>
<head>
<title><?php
$string = 'HTHS Secret Santa';
echo (isset($title) && $title != '') ? ($title . ' - ' . $string) : $string;
?></title>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36741494-1']);
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

	<a class="title" href="<?php echo base_url("index")?>">2012 HTHS Secret Santa</a> 

	<span style="width:auto;margin:0px;padding:0px;float:right;">
		<a href="<?php echo base_url("login")?>">login/register</a>&nbsp;
	</span>
		
	</br>
	<a class="subheader" href="<?php echo base_url("about")?>">About</a> 
	
</div>
</body>