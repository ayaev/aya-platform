<?php
/**********
 * File:    post-vehicle.php - create vehicle for AYA event
 * Version: 3.6
 * Date:    2018-01-14
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

require_once('../db-initialization.php');

try
{
  $check = $db->prepare('SELECT COUNT(*)
                         FROM aya_vehicles
                         WHERE Deleted = FALSE
                           AND VehicleID != :id
                           AND RegistrationNumber LIKE :number');
  $check->bindValue(':id', $_POST['VehicleID'], PDO::PARAM_INT);
  $check->bindValue(':number', $_POST['RegistrationNumber'], PDO::PARAM_STR);
  $check->execute();
  $exists = $check->fetchColumn();
  $check = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

if ($exists < 1)
{
  if (!empty($_POST['Model']) && !empty($_POST['Color']) && !empty($_POST['RegistrationNumber']))
  {
    try
    {
      $insert = $db->prepare('INSERT
                              INTO aya_vehicles (phpBBUserID, ManufacturerID, Model, Color, RegistrationNumber, Components)
                              VALUES (:userId, :manufacturerID, :model, :color, :number, :components)');
      $insert->bindValue(':userId', $phpBBUserID, PDO::PARAM_INT);
      $insert->bindValue(':manufacturerID', $_POST['ManufacturerID'], PDO::PARAM_INT);
      $insert->bindValue(':model', $_POST['Model'], PDO::PARAM_STR);
      $insert->bindValue(':color', $_POST['Color'], PDO::PARAM_STR);
      $insert->bindValue(':number', $_POST['RegistrationNumber'], PDO::PARAM_STR);
      $insert->bindValue(':components', (empty($_POST['Components']) ? null : str_replace(array("\r\n", "\r", "\n"), ' ', $_POST['Components'])),
                         PDO::PARAM_STR);
      $insert->execute();
      echo $insert->rowCount();
      $insert = null;
    }
    catch (PDOException $exception)
    {
      print 'Error: ' . $exception->getMessage() . '<br />';
    }
  }
  else
  {
    echo 'MISSING_DATA';
  }
}
else
{
  echo 'ALREADY_EXISTS';
}

$db = null;
?>
