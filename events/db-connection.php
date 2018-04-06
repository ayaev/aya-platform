<?php
/*
 * Copyright 2016-2018 Martin Baranski, TroubleZone.Net Productions
 *
 * Licensed under the EUPL, Version 1.2 only (the "Licence");
 * You may not use this work except in compliance with the Licence.
 * You may obtain a copy of the Licence at:
 *
 * https://joinup.ec.europa.eu/software/page/eupl
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the Licence is distributed on an "AS IS" basis,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the Licence for the specific language governing permissions and limitations under the Licence.
 *
 * File:    db-connection.php - database connection configuration
 * Version: 3.0
 * Date:    2017-12-25
 */

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
