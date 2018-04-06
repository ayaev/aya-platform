<?php
/**********
 * File:    get-vehicle.php - vehicle query service for AJAX requests of AYA event detail pages
 * Version: 2.2
 * Date:    2017-12-22
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

header('Content-type: application/json');
require_once('../db-initialization.php');

try
{
  $vehicle = $db->prepare('SELECT M.ManufacturerID, V.Model, V.Color, V.RegistrationNumber, V.Components
                           FROM aya_vehicles V
                           JOIN aya_vehicles_manufacturers M
                             ON V.ManufacturerID = M.ManufacturerID
                           WHERE V.Deleted = FALSE
                             AND V.VehicleID = :id');
  $vehicle->bindValue(':id', $_POST['VehicleID'], PDO::PARAM_INT);
  $vehicle->execute();
  $ayaVehicle = $vehicle->fetch(PDO::FETCH_ASSOC);
  $vehicle = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$db = null;

echo json_encode((empty($ayaVehicle) ? array('result' => false) : $ayaVehicle),
                 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
