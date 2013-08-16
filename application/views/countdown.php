<?php
$now = time();
$target = mktime(
    $targetDate['hour'],
    $targetDate['minute'],
    $targetDate['second'],
    $targetDate['month'],
    $targetDate['day'],
    $targetDate['year']
);

$diffSecs = $target - $now;

$date = array();
$date['secs'] = $diffSecs % 60;
$date['mins'] = floor($diffSecs / 60) % 60;
$date['hours'] = floor($diffSecs / 60 / 60) % 24;
$date['days'] = floor($diffSecs / 60 / 60 / 24) % 7;
$date['weeks'] = floor($diffSecs / 60 / 60 / 24 / 7);

foreach ($date as $i => $d) {
    $d1 = $d % 10;
    $d2 = ($d - $d1) / 10;
    $date[$i] = array(
        (int)$d2,
        (int)$d1,
        (int)$d
    );
}
?>
<html>
<head>
    <script language="Javascript" type="text/javascript" src="js/jquery.js"></script>
    <script language="Javascript" type="text/javascript"
            src="<?php echo base_url('js/jquery.lwtCountdown-1.0.js'); ?>"></script>
    <link rel="stylesheet" type="text/css"
          href="<?php echo ($theme == 'light') ? base_url('css/timer/light.css') : base_url('css/timer/dark.css'); ?>"></link>
</head>

<body>
<!-- Countdown dashboard start -->
<div id="countdown_dashboard">
    <div class="dash weeks_dash">
        <span class="dash_title">weeks</span>

        <div class="digit"><?php echo $date['weeks'][0] ?></div>
        <div class="digit"><?php echo $date['weeks'][1] ?></div>
    </div>

    <div class="dash days_dash">
        <span class="dash_title">days</span>

        <div class="digit"><?php echo $date['days'][0] ?></div>
        <div class="digit"><?php echo $date['days'][1] ?></div>
    </div>

    <div class="dash hours_dash">
        <span class="dash_title">hours</span>

        <div class="digit"><?php echo $date['hours'][0] ?></div>
        <div class="digit"><?php echo $date['hours'][1] ?></div>
    </div>

    <div class="dash minutes_dash">
        <span class="dash_title">minutes</span>

        <div class="digit"><?php echo $date['mins'][0] ?></div>
        <div class="digit"><?php echo $date['mins'][1] ?></div>
    </div>

    <div class="dash seconds_dash">
        <span class="dash_title">seconds</span>

        <div class="digit"><?php echo $date['secs'][0] ?></div>
        <div class="digit"><?php echo $date['secs'][1] ?></div>
    </div>

</div>
<!-- Countdown dashboard end -->

<script language="javascript" type="text/javascript">
    jQuery(document).ready(function () {
        $('#countdown_dashboard').countDown({
            targetDate: {
                'day':        <?php echo $targetDate['day']?>,
                'month':    <?php echo $targetDate['month']?>,
                'year':    <?php echo $targetDate['year']?>,
                'hour':    <?php echo $targetDate['hour']?>,
                'min':        <?php echo $targetDate['minute']?>,
                'sec':        <?php echo $targetDate['second']?>
            }
        });
    });
</script>
</body>
</html>
