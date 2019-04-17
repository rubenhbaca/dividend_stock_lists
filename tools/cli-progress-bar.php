<?php
/**
 * show a status bar in the console
 *
 * <code>
 * for($x=1;$x<=100;$x++){
 *
 *     show_status($x, 100);
 *
 *     usleep(100000);
 *
 * }
 * </code>
 *
 * @param   int     $done    how many items are completed
 * @param   int     $total   how many items are to be done total
 * @param   string  $message message to show before the progressbar
 * @param   int     $size    optional size of the status bar
 * @return  void
 *
 */

function show_status($done, $total, $message = "", $size = 30)
{

    static $start_time;

    // if we go over our bound, just ignore it
    if ($done > $total) {
        return;
    }

    if (empty($start_time)) {
        $start_time = time();
    }

    $now = time();

    $perc = (double) ($done / $total);

    $bar = floor($perc * $size);

    $status_bar = "\r[";
    $status_bar .= str_repeat("=", $bar);
    if ($bar < $size) {
        $status_bar .= ">";
        $status_bar .= str_repeat(" ", $size - $bar);
    } else {
        $status_bar .= "=";
    }

    $disp = number_format($perc * 100, 0);

    $status_bar .= "] $disp%  $done/$total";

    $rate = ($now - $start_time) / $done;
    $left = $total - $done;
    $eta = round($rate * $left, 2);
    $elapsed = $now - $start_time;

    $eta_string = formatSecs($eta);
    $elapsed_string = formatSecs($elapsed);

    $status_bar .= " remaining: {$eta_string} elapsed: {$elapsed_string}";

    if (!empty($message)) {
        $message = " {$message}";
    }

    echo "$status_bar{$message}  ";

    flush();

    // when done, send a newline
    if ($done == $total) {
        echo "\n";
    }

}

function formatSecs($eta){
    $eta_sec = $eta;
    $eta_min = $eta_sec / 60;
    $eta_hour = $eta_min / 60;
    $eta_day = $eta_hour / 24;

    $eta_sec = round($eta_sec);
    $eta_min = round($eta_min);
    $eta_hour = round($eta_hour);
    $eta_day = round($eta_day);

    $eta_sec -= $eta_min * 60;
    $eta_min -= $eta_hour * 60;
    $eta_hour -= $eta_day * 24;

    if($eta_sec <= 0) $eta_sec = 0;
    if($eta_min <= 0) $eta_min = 0;
    if($eta_hour <= 0) $eta_hour = 0;
    if($eta_day <= 0) $eta_day = 0;

    $eta_string = "";

    if (0 < $eta_day) {
        $eta_string .= number_format($eta_day) . " day" . (1 < $eta_day ? "s " : " ");
    }

    $eta_sec = numberWithLeadingZero($eta_sec);
    $eta_min = numberWithLeadingZero($eta_min);
    $eta_hour = numberWithLeadingZero($eta_hour);

    $eta_string .= "{$eta_hour}:{$eta_min}:{$eta_sec}";

    return $eta_string;
}

function numberWithLeadingZero($num){
    if(9 < $num) return $num;

    return "0{$num}";
}