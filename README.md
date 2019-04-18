<h1>Dividend Stock Lists</h1>

You will find 6 lists which hold dividend paying stocks. Each list configuration can be located in the `config` folder. General dividend information on each ticker can be found in the `data` folder. Details on each ticker can be found in the `details.json` file in the `data` folder.

Here are each list with their descriptions.

1) <strong>High Dividend Payers</strong> - List of 390 5%+ Yielding High Dividend Stocks

2) <strong>Monthly Dividend Payers</strong> - Monthly dividend stocks are securities that pay out a dividend every month, instead of quarterly or annually.

3) <strong>Dividend Achievers</strong> - The NASDAQ Dividend Achievers Index is made up of 264 stocks with 10+ consecutive years of dividend increases that meet certain minimum size and liquidity requirements.

4) <strong>Dividend Aristocrats</strong> - The Dividend Aristocrats are a select group of 57 S&P 500 stocks with 25+ years of consecutive dividend increases.

5) <strong>Dividend Kings</strong> - Stocks with 50 or more consecutive years of dividend increases.

6) <strong>Dividend Kings Plus</strong> - 11 dividend-paying stocks in the S&P 500 with 55+ Years of Payout Growth.

<hr>

<h2>Setup Requirements</h2>

<ol>
    <li>
        FREE IEX Cloud Account at <a href="https://iexcloud.io" target="_blank">https://iexcloud.io</a>
    </li>
    <li>
        PHP 7+ must be install on your Windows, Linux or OSX computer.
    </li>
</ol>

<small>IEX Cloud gives you FREE 500,000 messages per month.</small>

<hr>

<h2>API Data Weighting Usage</h2>

Using our default cache settings, using our update scripts every day, data weight usage is calculated at 22,392 per month.

<hr>

<h2>Endpoints Used</h2>

<ul>
    <li>
        Company
        <ul>
            <li>cache: 1 year</li>
            <li>data use: 1 per year</li>
        </ul>
    </li>
    <li>
        Quote
        <ul>
            <li>cache: 1 day</li>
            <li>data use: ~30 per month</li>
        </ul>
    </li>
    <li>
        Key Stats
        <ul>
            <li>cache: 1 month</li>
            <li>data use: 5 per month</li>
        </ul>
    </li>
</ul>

<small>Note: cache settings can be changed by editing the `iex_api_config.json` file located in the `config` folder. Each value is in seconds.</small>

<hr>

<h2>Configuration</h2>

Copy `config/iex_api_config.example.json` to `config/iex_api_config.json`. Make sure to set your IEX publishable test key and live key.

<hr>

<h2>Update List</h2>

To update all lists, you can run the following command

    php update_all.php

To updated a specific list, you can run one of the following commands

    php update_high_dividend.php
    php update_monthly_dividend.php
    php update_dividend_achievers.php
    php update_dividend_aristocrat.php
    php update_dividend_kings.php
    php update_dividend_king_plus.php