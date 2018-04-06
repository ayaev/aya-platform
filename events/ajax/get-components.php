<?php
/**********
 * File:    get-components.php - components query service for AJAX requests of AYA event detail pages
 * Version: 2.2
 * Date:    2018-01-30
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

header('Content-type: application/json');
require_once('../db-initialization.php');

try
{
  $components = $db->prepare('SELECT Components
                              FROM aya_vehicles
                              WHERE Deleted = FALSE
                                AND VehicleID = :id');
  $components->bindValue(':id', $_POST['VehicleID'], PDO::PARAM_INT);
  $components->execute();
  $ayaComponents = $components->fetchColumn();
  $components = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$db = null;

echo json_encode((empty($ayaComponents) ? array('result' => false) : $ayaComponents),
                 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
