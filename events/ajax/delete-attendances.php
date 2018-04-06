<?php
/**********
 * File:    delete-attendances.php - delete attendances of AYA event detail pages
 * Version: 1.2
 * Date:    2018-01-04
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

require_once('../db-initialization.php');

$attendances = count($_POST['Attendances']);
$query = 'UPDATE aya_attendees
          SET Deleted = TRUE
          WHERE phpBBUserID = :id
            AND AttendeeID IN (';

for ($i = 0; $i < $attendances; $i++)
{
  $query .= ($i > 0 ? ', ' : '') . ':attendeeId' . ($i + 1);
}

$query .= ')';

$delete = $db->prepare($query);
$delete->bindValue(':id', $phpBBUserID, PDO::PARAM_INT);

for ($i = 0; $i < $attendances; $i++)
{
  $delete->bindValue((':attendeeId' . ($i + 1)), $_POST['Attendances'][$i], PDO::PARAM_INT);
}

$delete->execute();

$rows = $delete->rowCount();
echo ($attendances === $rows ? $rows : 'WRONG_COUNT');

$delete = null;
$db = null;
?>
