<?php
/**********
 * File:    get-events.php - events query service for AJAX requests of AYA pages
 * Version: 1.5
 * Date:    2018-02-25
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

header('Content-type: application/json');
require_once('../db-initialization.php');

try
{
  $showDeleted = $_POST['ShowDeleted'] === 'true';
  $query = 'SELECT E.EventID, ' . ($showDeleted ? 'E.Deleted, ' : '') . 'E.Name, E.Date, ';
  if (!$showDeleted)  // Address is shown on listing page only
  {
    $query .= "CONCAT(CONCAT_WS(' ', L.Street, L.StreetNumber), ', ', CONCAT_WS(' ', L.ZIP, L.City)) AS Address";
  }
  else
  {
    $query .= 'E.LastUpdate, L.City';
  }
  $query .= ' FROM aya_events E JOIN aya_locations L ON E.LocationID = L.LocationID WHERE ';
  if (!$showDeleted)
  {
    $query .= 'E.Deleted = FALSE';
  }
  else
  {
    $query .= 'YEAR(E.Date) = ' . (is_numeric($_POST['EventYear']) ? $_POST['EventYear'] : date('Y'));
  }
  $query .= ' ORDER BY DATEDIFF(E.Date, CURDATE()) < 0 ASC, ' . (!$showDeleted ? 'E.Deleted ASC, ' : '') . 'E.Date ' . ($showDeleted ? 'DESC' : 'ASC');
  $events = $db->prepare($query);
  $events->execute();
  $ayaEvents = $events->fetchAll(PDO::FETCH_ASSOC);
  $events = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$db = null;

echo json_encode($ayaEvents, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
