<?php

$tools_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tools';
$data_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';

include_once $tools_dir . DIRECTORY_SEPARATOR . 'cli-progress-bar.php';
include_once $tools_dir . DIRECTORY_SEPARATOR . 'file-methods.php';

foreach ($tickerNameList as $tickerName) {
    $tickerName = str_replace('_tickers', '', $tickerName);

    $data = get_json_file($data_dir . DIRECTORY_SEPARATOR . "{$tickerName}.json");

    uasort($data, function ($a, $b) {
        $a_div_yield = $a['dividend']['yield'];
        $b_div_yield = $b['dividend']['yield'];
        if ($a_div_yield == $b_div_yield) {
            return 0;
        }
        return ($b_div_yield < $a_div_yield) ? -1 : 1;
    });

    $data = array_values($data);

    set_json_file($data_dir . DIRECTORY_SEPARATOR . "sort_by_high_dividend_yield" . DIRECTORY_SEPARATOR . "{$tickerName}.json", $data);

    show_status(1, 1, "Sort by high dividend yield {$tickerName} updated.");
}