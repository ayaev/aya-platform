<?php
/**********
 * File:    get-colors.php - color query service for AJAX requests of AYA event detail pages
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
  // Bootstrap-TypeAhead requires lower-case field names
  $colors = $db->prepare('SELECT ColorID AS id, Name AS name
                          FROM aya_vehicles_colors
                          WHERE Deleted = FALSE
                          ORDER BY Name ASC');
  $colors->execute();
  $ayaColors = $colors->fetchAll(PDO::FETCH_ASSOC);
  $colors = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$db = null;

echo json_encode($ayaColors, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
