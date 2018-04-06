<?php
/**********
 * File:    delete-vehicle.php - delete vehicles of AYA event detail pages
 * Version: 3.1
 * Date:    2017-12-22
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

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
