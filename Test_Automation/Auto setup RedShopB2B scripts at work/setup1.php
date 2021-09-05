<?php

/*
google-chrome --version : Google Chrome 68.0.3440.106
https://www.seleniumhq.org/download/ https://chromedriver.storage.googleapis.com/2.41/chromedriver_linux64.zip
https://blog.programster.org/getting-started-with-selenium-testing-using-php selenium-server-standalone-3.8.1.jar
*/

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

require_once(__DIR__ . '/vendor/autoload.php');

$host = 'http://localhost:4444/wd/hub'; // this is the default

$options = new ChromeOptions();

$options->addArguments(array(
            '--disable-infobars', '--ignore-certificate-errors', '--test-type', '--allow-running-insecure-content', '--window-size=1920,1080',
    ));

//$options->setBinary('/google-chrome');

$caps = DesiredCapabilities::chrome();
$caps->setCapability(ChromeOptions::CAPABILITY, $options);

$driver = RemoteWebDriver::create(
	$host, 
	$caps
);

// ------- TEST GOOGLE SEARCH -------
//$driver->get("http://www.google.com");

/*
# enter text into the search field
$driver->findElement(WebDriverBy::id('lst-ib'))->click();
sleep(1);
$driver->findElement(WebDriverBy::id('lst-ib'))->sendKeys('programster selenium');
sleep(1);

# Click the search button
$driver->findElement(WebDriverBy::name('btnK'))->click();
*/

//$element = $driver->findElement(WebDriverBy::name("q"));    

//if($element) {
//	$element->sendKeys("TestingBot");
//	$element->submit();
//}

//print $driver->getTitle();
//sleep(30);
// -------(TEST GOOGLE SEARCH)-------

$driver->get("http://localhost/Joomla-" . $argv[1]);

$driver->wait()->until(WebDriverExpectedCondition:: elementTextIs(WebDriverBy::tagName('h3'), 'Main Configuration'));

$driver->findElement(WebDriverBy::id("jform_site_name"))->sendKeys("Joomla-" . $argv[1]); 
$driver->findElement(WebDriverBy::id("jform_admin_email"))->sendKeys("ruslan@redweb.dk"); 
$driver->findElement(WebDriverBy::id("jform_admin_user"))->sendKeys("ruslan"); 
$driver->findElement(WebDriverBy::id("jform_admin_password"))->sendKeys("ruslan"); 
$driver->findElement(WebDriverBy::id("jform_admin_password2"))->sendKeys("ruslan"); 

$driver->findElement(WebDriverBy::className("btn-primary"))->click();

// Default wait (= 30 sec)
$driver->wait()->until(WebDriverExpectedCondition:: elementTextIs(WebDriverBy::tagName('h3'), 'Database Configuration'));

$driver->findElement(WebDriverBy::id("jform_db_user"))->sendKeys("root"); 
$driver->findElement(WebDriverBy::id("jform_db_pass"))->sendKeys("ruslan"); 
$driver->findElement(WebDriverBy::id("jform_db_name"))->sendKeys("Joomla-" . $argv[1]); 

$driver->findElement(WebDriverBy::className("btn-primary"))->click();

$driver->wait()->until(WebDriverExpectedCondition:: elementTextIs(WebDriverBy::tagName('h3'), 'Finalization'));

$driver->findElement(WebDriverBy::id("jform_sample_file1"))->click(); 

$driver->findElement(WebDriverBy::className("btn-primary"))->click();

// Wait for at most 300s and retry every 500ms if it the title is not correct.
$driver->wait(300, 500)->until(WebDriverExpectedCondition:: elementTextIs(WebDriverBy::tagName('h3'), 'Congratulations! Joomla! is now installed.'));

$driver->quit();
