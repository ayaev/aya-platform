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
 * File:    delete-vehicle.php - delete vehicles of AYA event detail pages
 * Version: 3.1
 * Date:    2017-12-22
 */

require_once('../db-initialization.php');

try
{
  $check = $db->prepare('SELECT COUNT(*)
                         FROM aya_attendees
                         WHERE VehicleID = :id
                           AND DATEDIFF(ConfirmationDate, :date) > -1');
  $check->bindValue(':id', $_POST['VehicleID'], PDO::PARAM_INT);
  $check->bindValue(':date', (date('Y') . '-01-01'), PDO::PARAM_STR);
  $check->execute();
  $InUse = $check->fetchColumn();
  $check = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

if ($InUse < 1)
{
  try
  {
    $delete = $db->prepare('UPDATE aya_vehicles
                            SET Deleted = TRUE
                            WHERE VehicleID = :id');
    $delete->bindValue(':id', $_POST["VehicleID"], PDO::PARAM_INT);
    $delete->execute();

    echo $delete->rowCount();

    $delete = null;
  }
  catch (PDOException $exception)
  {
    print 'Error: ' . $exception->getMessage() . '<br />';
  }
}
else
  echo 'IN_USE';

$db = null;
?>
