<?php

require 'vendor/autoload.php';

// https://stackoverflow.com/a/21988713
$df = fopen('russianvocab.csv', 'a');
fprintf($df, chr(0xEF).chr(0xBB).chr(0xBF));

$urlBase1 = 'https://app.memrise.com/course/173195/top-10000-words-part-1';
$urlBase2 = 'https://app.memrise.com/course/337724/top-10000-words-part-2';

for ($i = 1; $i <= 50; $i++) 
{
  $url = "{$urlBase1}/{$i}/";

  writeToCsv($url, $df);
}

for ($i = 1; $i <= 43; $i++) 
{
  $url = "{$urlBase2}/{$i}/";

  writeToCsv($url, $df);
}

function writeToCsv($url, $df)
{
  // https://www.freecodecamp.org/news/web-scraping-with-php-crawl-web-pages/
  $httpClient = new \GuzzleHttp\Client();
  $response = $httpClient->get($url);
  $htmlString = (string) $response->getBody();
  //add this line to suppress any warnings
  libxml_use_internal_errors(true);
  $doc = new DOMDocument();
  $doc->loadHTML($htmlString);
  $xpath = new DOMXPath($doc);

  $vocab = $xpath->evaluate('//div[@class="thing text-text"]//div[@class="col_a col text"]/div[@class="text"]');
  $eng = $xpath->evaluate('//div[@class="thing text-text"]//div[@class="col_b col text"]/div[@class="text"]');

  foreach ($vocab as $key => $rus) 
  {
    fputcsv($df, [$rus->textContent, $eng[$key]->textContent]);
  }
}