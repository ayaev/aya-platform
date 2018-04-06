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
 * File:    get-components.php - components query service for AJAX requests of AYA event detail pages
 * Version: 2.2
 * Date:    2018-01-30
 */

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
