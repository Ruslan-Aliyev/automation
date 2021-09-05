<?php

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

require_once(__DIR__ . '/vendor/autoload.php');

$host = 'http://localhost:4444/wd/hub';

$options = new ChromeOptions();

$options->addArguments(array(
            '--disable-infobars', '--ignore-certificate-errors', '--test-type', '--allow-running-insecure-content', '--window-size=1920,1080',
    ));

$caps = DesiredCapabilities::chrome();
$caps->setCapability(ChromeOptions::CAPABILITY, $options);

$driver = RemoteWebDriver::create(
	$host, 
	$caps
);

$driver->get("http://localhost/" . $argv[1]);

$driver->wait()->until(WebDriverExpectedCondition:: elementTextIs(WebDriverBy::tagName('h3'), 'Main Configuration'));

$driver->findElement(WebDriverBy::id("jform_site_name"))->sendKeys($argv[1]); 
$driver->findElement(WebDriverBy::id("jform_admin_email"))->sendKeys("ruslan@redweb.dk"); 
$driver->findElement(WebDriverBy::id("jform_admin_user"))->sendKeys("ruslan"); 
$driver->findElement(WebDriverBy::id("jform_admin_password"))->sendKeys("ruslan"); 
$driver->findElement(WebDriverBy::id("jform_admin_password2"))->sendKeys("ruslan"); 

$driver->findElement(WebDriverBy::className("btn-primary"))->click();

$driver->wait()->until(WebDriverExpectedCondition:: elementTextIs(WebDriverBy::tagName('h3'), 'Database Configuration'));

$driver->findElement(WebDriverBy::id("jform_db_user"))->sendKeys("root"); 
$driver->findElement(WebDriverBy::id("jform_db_pass"))->sendKeys("ruslan"); 
$driver->findElement(WebDriverBy::id("jform_db_name"))->sendKeys($argv[1]); 

$driver->findElement(WebDriverBy::className("btn-primary"))->click();

$driver->wait()->until(WebDriverExpectedCondition:: elementTextIs(WebDriverBy::tagName('h3'), 'Finalization'));

$driver->findElement(WebDriverBy::id("jform_sample_file1"))->click(); 

$driver->findElement(WebDriverBy::className("btn-primary"))->click();

$driver->wait(300, 500)->until(WebDriverExpectedCondition:: elementTextIs(WebDriverBy::tagName('h3'), 'Congratulations! Joomla! is now installed.'));

$driver->quit();
