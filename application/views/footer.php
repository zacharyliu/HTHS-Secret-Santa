<html>
<head>
<!--<script src="/application/views/main.css" type="text/css"></script>-->
</head>
<body>
<div style="margin-top:10px;float:right;"><span class="footer"><?php if ($app_disp == 1) echo '<a class="footer" onclick="erase()">clear all data</a> | '.$version.' | ';?>rendered in <?php echo $this->benchmark->elapsed_time();?> secs</span>
</div>
</body>
</html>