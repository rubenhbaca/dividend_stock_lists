<?php

$tools_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tools';
$config_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config';
$data_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';

include_once $tools_dir . DIRECTORY_SEPARATOR . 'cli-progress-bar.php';
include_once $tools_dir . DIRECTORY_SEPARATOR . 'file-methods.php';
include_once $tools_dir . DIRECTORY_SEPARATOR . 'normalize-data.php';

include_once __DIR__ . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'Details.php';

$details = new Details(get_json_file($data_dir . DIRECTORY_SEPARATOR . 'details.json'));

foreach ($tickerNameList as $tickerName) {
    $tickers = get_json_file($config_dir . DIRECTORY_SEPARATOR . "{$tickerName}.json");

    $data = array_map(function ($ticker) use ($details) {
        $o = new stdClass();

        $o->last_update = date("F j, Y - g:i a", $details->getSection($ticker, 'last_quote_update'));

        // company
        $o->company_name = $details->getProperty($ticker, 'company', 'companyName');
        $o->sector = $details->getProperty($ticker, 'company', 'sector');
        $o->symbol = $details->getProperty($ticker, 'company', 'symbol');

        // quote
        $o->latest_price = $details->getProperty($ticker, 'quote', 'latestPrice');
        $o->pe_ratio = $details->getProperty($ticker, 'quote', 'peRatio');

        // key stats        
        $o->ttm_eps = $details->getProperty($ticker, 'key_stats', 'ttmEPS');

        $o->dividend = new stdClass();
        $o->dividend->ttm_rate = $details->getProperty($ticker, 'key_stats', 'ttmDividendRate');
        $o->dividend->yield = round(100 * $details->getProperty($ticker, 'key_stats', 'dividendYield'), 2) . '%';
        $o->dividend->next_date = $details->getProperty($ticker, 'key_stats', 'nextDividendDate');
        $o->dividend->ex_date = $details->getProperty($ticker, 'key_stats', 'exDividendDate');

        // calculate payout ratio
        $ttmDividendRate = $o->dividend->ttm_rate;
        $ttmEps = $o->ttm_eps;
        $o->dividend->payout_ratio = !empty($ttmEps) && !empty($ttmDividendRate)? round(100 * ($ttmDividendRate / $ttmEps), 2) : null;

        return $o;
    }, $tickers);

    uasort($data, function ($a, $b) {
        $a_div_yield = $a->dividend->yield;
        $b_div_yield = $b->dividend->yield;
        if ($a_div_yield == $b_div_yield) {
            return 0;
        }
        return ($b_div_yield < $a_div_yield) ? -1 : 1;
    });

    $data = array_values($data);
    
    $tickerName = str_replace('_tickers', '', $tickerName);

    set_json_file($data_dir . DIRECTORY_SEPARATOR . "sort_by_high_dividend_yield_{$tickerName}.json", $data);

    show_status(1, 1, "Sort by high dividend yield {$tickerName} updated.");
}
