<html>

<head>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/css/main.css')?>"></link>
<script src="<?php echo base_url('/js/jquery.js')?>"></script>
<script src="<?php echo base_url('/js/jquery.quovolver.js')?>"></script>
<script>
	$(document).ready(function() {
		
		$('blockquote').quovolver(500,6000);
		
	});
	</script>
</head>
<body>
<div style="margin:0px 30px 0px 30px;">
<div style="width:550px;height:100px;margin-top:20px;margin-bottom:75px;margin-left:auto;margin-right:auto;">
<blockquote>
	<p>Kindness in words creates confidence. Kindness in thinking creates profoundness. Kindness in giving creates love."</p>
	<cite>&ndash; Lao Tzu</cite>
</blockquote>
<blockquote>
	<p>I have found that among its other benefits, giving liberates the soul of the giver."</p>
	<cite>&ndash; Maya Angelou</cite>
</blockquote>
<blockquote>
	<p>If you wait until you can do everything for everybody, instead of something for somebody, you’ll end up not doing nothing for nobody."</p>
	<cite>&ndash; Malcom Bane</cite>
</blockquote>
<blockquote>
	<p>To know even one life has breathed easier because you have lived. This is to have succeeded."</p>
	<cite>&ndash; Ralph Waldo Emerson</cite>
</blockquote>
<blockquote>
	<p>The best way to find yourself is to lose yourself in the service of others."</p>
	<cite>&ndash; Ralph Waldo Emerson</cite>
</blockquote>
<blockquote>
	<p>The value of a man resides in what he gives and not in what he is capable of receiving."</p>
	<cite>&ndash; Albert Einstein</cite>
</blockquote>
<blockquote>
	<p>Make all you can, save all you can, give all you can."</p>
	<cite>&ndash; John Wesley</cite>
</blockquote>
<blockquote>
	<p>No person was ever honored for what he received. He was honored for what he gave."</p>
	<cite>&ndash; Calvin Coolidge</cite>
</blockquote>
<blockquote>
	<p>Sometimes a small thing you do can mean everything in another person's life."</p>
</blockquote>
</div>
<p>Welcome to the signup page for the 2012 HTHS Secret Santa gift exchange. To register, click <a href="<?php echo base_url('login')?>"> here</a>. Exchange partners will be assigned randomly, and encrypted using an asymetric RSA encryption scheme for privacy, making it impossible for anyone but yourself to know your partner.  To avoid disappointment, please be sure that you are committed to giving a gift before signing up. poopy.</p>


<p>Registration ends: Friday, 12/14</p>
<p>Partner Assignments: Friday, 12/14</p>
<p>Gift Exchange:</p> Friday, 12/21
<p>There are currently xxxxxxx</p>

<h1 style="text-align:center;">Time Until Gift Exchange</h1>
<div style="margin:0 auto 0 auto;"><?php echo $timer;?></div>
</div>
</body>

</html>