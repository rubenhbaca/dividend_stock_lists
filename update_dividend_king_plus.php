<?php

$tickerNameList = array(
    'dividend_kings_plus_tickers',
);

include_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'fetch_data.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'extract_dividend_data_and_sort_by_high_yield.php';