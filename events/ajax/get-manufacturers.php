<?php
/**********
 * File:    get-manufacturers.php - manufacturers query service for AJAX requests of AYA event detail pages
 * Version: 2.2
 * Date:    2018-02-25
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

header('Content-type: application/json');
require_once('../db-initialization.php');

try
{
  $manufacturers = $db->prepare('SELECT ManufacturerID, Name, Keywords
                                 FROM aya_vehicles_manufacturers
                                 WHERE Deleted = FALSE
                                 ORDER BY Name ASC');
  $manufacturers->execute();
  $ayaManufacturers = $manufacturers->fetchAll(PDO::FETCH_ASSOC);
  $manufacturers = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$db = null;

echo json_encode($ayaManufacturers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
