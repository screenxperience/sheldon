<?php
$app_sqlhost = 'localhost';
$app_sqluser = 'sqluser';
$app_sqlpasswd = 'sqlpasswd';
$app_sqldb = 'sheldon';

$app_org = 'EF1';

$app_regex = array(
'number' => '0-9',
'loweruml' => 'a-zöäüß',
'uppernumber' => 'A-Z0-9',
'upperuml' => 'A-ZÖÄÜ',
'loweruppernumber' => 'a-zA-Z0-9',
'lowerupperuml' => 'a-zA-ZöäüÖÄÜß',
'lowerupperumlnumber' => 'a-zA-ZöäüÖÄÜß0-9',
'loweruppernumbersz' => 'a-zA-Z0-9\s\-\.\:\?\!\&\/\(\)\#\+\,',
'lowerupperumlsz' => 'a-zA-ZöäüÖÄÜß\s\-\.\:\?\!\&\/\(\)\#\+\,',
'lowerupperumlnumbersz' => 'a-zA-ZöäüÖÄÜß0-9\s\-\.\:\?\!\&\/\(\)\#\+\,',
'macaddress' => 'A-Z0-9\:',
'email' => '^[a-zA-Z0-9]{1,}+\@{1}+[a-zA-Z]{1,}+\.{1}+[a-zA-Z]{1,}$',
'url' => 'a-zA-Z0-9\?\&\=\.\:\/\_\-',
'date' => '^[0-9]{4}+\-{1}+[0-9]{2}+\-{1}+[0-9]{2}$');

date_default_timezone_set('Europe/Berlin');
?>
