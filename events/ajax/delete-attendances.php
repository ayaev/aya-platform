<?php
/*
 * Copyright 2016-2018 Martin Arndt, TroubleZone.Net Productions
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
 */

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
