<?php

$tickerNameList = array(
    'high_dividend_tickers',
    'monthly_dividend_tickers',
    'dividend_achievers_tickers',
    'dividend_aristocrat_tickers',
    'dividend_kings_tickers',
    'dividend_kings_plus_tickers',
);

include_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'fetch_data.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'extract_data.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'sort_by_high_yield.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'sort_by_pe_desc.php';