<?php
/*
 * Copyright 2016-2018 Martin Arndt, TroubleZone.Net Productions
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
 */

require_once('../db-initialization.php');

try
{
  $update = $db->prepare('UPDATE aya_vehicles
                          SET InstallFlaws = :flaws
                          WHERE phpBBUserID = :userId
                            AND VehicleID = :vehicleId');
  $update->bindValue(':flaws', (empty($_POST['InstallFlaws'])
                                      ? null
                                      : str_replace(array("\r\n", "\r", "\n"), ' ', $_POST['InstallFlaws'])),
                     PDO::PARAM_STR);
  $update->bindValue(':userId', $phpBBUserID, PDO::PARAM_INT);
  $update->bindValue(':vehicleId', $_POST['VehicleID'], PDO::PARAM_INT);
  $update->execute();

  echo $update->rowCount();
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$update = null;
$db = null;
?>
