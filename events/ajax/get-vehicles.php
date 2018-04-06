<?php
/**********
 * File:    get-vehicles.php - vehicles data query service for AJAX requests of AYA event detail pages
 * Version: 2.2
 * Date:    2018-01-04
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

header('Content-type: application/json');
require_once('../db-initialization.php');

try
{
  $vehicles = $db->prepare('SELECT VehicleID, ManufacturerID, Model, Color, RegistrationNumber, Components
                            FROM aya_vehicles
                            WHERE Deleted = FALSE
                              AND phpBBUserID = :id
                            ORDER BY RegistrationNumber ASC');
  $vehicles->bindValue(':id', $phpBBUserID, PDO::PARAM_INT);
  $vehicles->execute();
  $ayaVehicles = $vehicles->fetchAll(PDO::FETCH_ASSOC);
  $vehicles = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$db = null;

echo json_encode($ayaVehicles, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
