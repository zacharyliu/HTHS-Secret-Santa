<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Countdown
{

    function generate($targetDate, $theme)
    {
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
        $out = '<script src="' . base_url('js/jquery.lwtCountdown-1.0.js') . '"></script>';
        $out .= '<link rel="stylesheet" type="text/css" href="' . (($theme == 'light') ? base_url('css/timer/light.css') : base_url('css/timer/dark.css')) . '"></link>';
        $out .= '<!-- Countdown dashboard start -->
		<div style="" id="countdown_dashboard">
			<div class="dash weeks_dash">
				<span class="dash_title">weeks</span>
				<div class="digit">' . $date['weeks'][0] . '</div>
				<div class="digit">' . $date['weeks'][1] . '</div>
			</div>

			<div class="dash days_dash">
				<span class="dash_title">days</span>
				<div class="digit">' . $date['days'][0] . '</div>
				<div class="digit">' . $date['days'][1] . '</div>
			</div>

			<div class="dash hours_dash">
				<span class="dash_title">hours</span>
				<div class="digit">' . $date['hours'][0] . '</div>
				<div class="digit">' . $date['hours'][1] . '</div>
			</div>

			<div class="dash minutes_dash">
				<span class="dash_title">minutes</span>
				<div class="digit">' . $date['mins'][0] . '</div>
				<div class="digit">' . $date['mins'][1] . '</div>
			</div>

			<div class="dash seconds_dash">
				<span class="dash_title">seconds</span>
				<div class="digit">' . $date['secs'][0] . '</div>
				<div class="digit">' . $date['secs'][1] . '</div>
			</div>

		</div>
		<!-- Countdown dashboard end -->';

        $out .= '<script language="javascript" type="text/javascript">
			jQuery(document).ready(function() {
				$("#countdown_dashboard").countDown({
					targetDate: {
						"day": 		' . $targetDate["day"] . ',
						"month": 	' . $targetDate["month"] . ',
						"year": 	' . $targetDate["year"] . ',
						"hour": 	' . $targetDate["hour"] . ',
						"min": 		' . $targetDate["minute"] . ',
						"sec": 		' . $targetDate["second"] . '
					}
				});
			});
		</script>';
        return $out;
    }
}

?>


