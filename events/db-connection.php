<?php
/**********
 * File:    db-connection.php - database connection configuration
 * Version: 3.0
 * Date:    2017-12-25
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

try
{
  $db = new PDO('mysql:host=IP.AD.DR.ESS;dbname=DATABASE;charset=utf8',
                'USERNAME',
                'PASSWORD',
                array(PDO::ATTR_EMULATE_PREPARES   => false,
                      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'"));
}
catch (PDOException $exception)
{
  die('Error: ' . $exception->getMessage() . '<br />');
}
?>
