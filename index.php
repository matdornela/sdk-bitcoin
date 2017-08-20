<?php 
require_once 'include/DbHandler.php';

$db = new DbHandler();

// insert a new ticker in Database
$db->insertTicker();
$date1 = '2017-08-18';
$date2 = '2017-08-20';

//return ticker by period every hour 
$db->getTickerByPeriodEveryHour($date1,$date2);

$date = "2017-08-20 02:53";
// return a ticker inserted by date
$dateTicker =  $db->getTickerByDate($date);
print_r($dateTicker);

?>

