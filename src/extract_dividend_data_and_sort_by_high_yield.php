<?php

$tools_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tools';
$config_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config';
$data_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';

include_once $tools_dir . DIRECTORY_SEPARATOR . 'cli-progress-bar.php';
include_once $tools_dir . DIRECTORY_SEPARATOR . 'file-methods.php';
include_once $tools_dir . DIRECTORY_SEPARATOR . 'normalize-data.php';

$details = get_json_file($data_dir . DIRECTORY_SEPARATOR . 'details.json');

foreach ($tickerNameList as $tickerName) {
    $tickers = get_json_file($config_dir . DIRECTORY_SEPARATOR . "{$tickerName}.json");

    $data = array_filter($details, function ($ticker) use ($tickers) {
        return false !== array_search($ticker, $tickers);
    }, ARRAY_FILTER_USE_KEY);

    uasort($data, function ($a, $b) {
        $a_div_yield = $a['key_stats']['dividendYield'];
        $b_div_yield = $b['key_stats']['dividendYield'];
        if ($a_div_yield == $b_div_yield) {
            return 0;
        }
        return ($b_div_yield < $a_div_yield) ? -1 : 1;
    });

    $data = array_map(function ($ticker_details) {
        $o = array();

        $o['last_update'] = date("F j, Y - g:i a", $ticker_details['last_quote_update']);

        // company
        $o['company_name'] = $ticker_details['company']['companyName'];
        $o['sector'] = $ticker_details['company']['sector'];

        // key stats
        $o['pe_ratio'] = $ticker_details['key_stats']['peRatio'];
        $o['dividend'] = array(
            'ttm_rate' => array_key_exists('ttmDividendRate', $ticker_details['key_stats'])? $ticker_details['key_stats']['ttmDividendRate'] : null,
            'yield' => array_key_exists('dividendYield', $ticker_details['key_stats'])? round(100 * $ticker_details['key_stats']['dividendYield'], 2) . '%' : null,
            'next_date' => array_key_exists('nextDividendDate', $ticker_details['key_stats'])? $ticker_details['key_stats']['nextDividendDate'] : null,
            'ex_date' => array_key_exists('exDividendDate', $ticker_details['key_stats'])? $ticker_details['key_stats']['exDividendDate'] : null,
        );

        // quote
        $o['latest_price'] = $ticker_details['quote']['latestPrice'];

        return $o;
    }, $data);

    $tickerName = str_replace('_tickers', '', $tickerName);

    set_json_file($data_dir . DIRECTORY_SEPARATOR . "sort_by_high_dividend_yield_{$tickerName}.json", $data);

    show_status(1, 1, "Sort by high dividend yield {$tickerName} updated.");
}
