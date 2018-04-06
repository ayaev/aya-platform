<?php
/**********
 * File:    put-flaws.php - update flaws of AYA administration pages
 * Version: 1.2
 * Date:    2018-01-04
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

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
