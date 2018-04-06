<?php
/**********
 * File:    get-attendance.php - attendance query service for AJAX requests of AYA event detail pages
 * Version: 1.0
 * Date:    2018-01-30
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

header('Content-type: application/json');
require_once('../db-initialization.php');

try
{
  $attendance = $db->prepare('SELECT VehicleID, ClassID, Remark
                              FROM aya_attendees
                              WHERE Deleted = FALSE
                                AND AttendeeID = :id');
  $attendance->bindValue(':id', $_POST['AttendeeID'], PDO::PARAM_INT);
  $attendance->execute();
  $ayaAttendance = $attendance->fetch(PDO::FETCH_ASSOC);
  $attendance = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$db = null;

echo json_encode((empty($ayaAttendance) ? array('result' => false) : $ayaAttendance),
                 JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
