<?php

$tools_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tools';
$config_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config';
$data_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';

include_once $tools_dir . DIRECTORY_SEPARATOR . 'cli-progress-bar.php';
include_once $tools_dir . DIRECTORY_SEPARATOR . 'file-methods.php';

$api_config = get_json_file($config_dir . DIRECTORY_SEPARATOR . 'iex_api_config.json');
$api_baseURL = ($api_config['test_mode'] ? $api_config['test_url'] : $api_config['live_url']) . "/{$api_config['version']}";
$api_baseToken = "{$api_config['token_template']}" . ($api_config['test_mode'] ? $api_config['test_publishable_token'] : $api_config['publishable_token']);

$details = get_json_file($data_dir . DIRECTORY_SEPARATOR . 'details.json');
$data_usage = 0;

foreach ($tickerNameList as $tickerName) {
    $tickers = get_json_file($config_dir . DIRECTORY_SEPARATOR . "{$tickerName}.json");
    $ticker_count = count($tickers);
    $tickerName = str_replace('_tickers', '', $tickerName);

    foreach ($tickers as $key => $ticker) {
        $update_company = true;
        $update_key_stats = true;
        $update_quote = true;

        if ($details && array_key_exists($ticker, $details)) {
            $ticker_details = $details[$ticker];

            if (time() < $ticker_details['last_company_update'] + $api_config['cache_company']) {
                $update_company = false;
            }

            if (time() < $ticker_details['last_key_stats_update'] + $api_config['cache_key_stats']) {
                $update_key_stats = false;
            }

            if (time() < $ticker_details['last_quote_update'] + $api_config['cache_quote']) {
                $update_quote = false;
            }
        } else {
            $ticker_details = array();
        }

        if($update_company){
            $ticker_details['last_company_update'] = time();
            $ticker_details['company'] = json_decode(file_get_contents("{$api_baseURL}/stock/{$ticker}/company{$api_baseToken}"), true);
            $data_usage += 1;
        }

        if($update_key_stats){
            $ticker_details['last_key_stats_update'] = time();
            $ticker_details['key_stats'] = json_decode(file_get_contents("{$api_baseURL}/stock/{$ticker}/stats{$api_baseToken}"), true);
            $data_usage += 1;
        }

        if($update_quote){
            $ticker_details['last_quote_update'] = time();
            $ticker_details['quote'] = json_decode(file_get_contents("{$api_baseURL}/stock/{$ticker}/quote{$api_baseToken}"), true);
            $data_usage += 5;
        }

        $details[$ticker] = $ticker_details;
        set_json_file($data_dir . DIRECTORY_SEPARATOR . 'details.json', $details);

        show_status($key + 1, $ticker_count, $key + 1 == $ticker_count ? "{$tickerName} - Data used: {$data_usage}" : $ticker);
    }
}