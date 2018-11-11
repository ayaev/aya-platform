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

require_once('db-initialization.php');

if (!$isJuror)
{
  header('Location: /events/listing.php', true, 303);
  die('Only jurors beyond this point! Sorry.');
}

$title = 'AYA — Jurorenbereich 1.5';
require_once('fragments/header.php');

$showDistance = false;
$showDistanceShortened = false;
$showListingLink = true;
$showMap = false;
require_once('fragments/navigation.php');

try
{
  $event = $db->prepare('SELECT EventID, Name, Date
                         FROM aya_events
                         WHERE Deleted = FALSE
                           AND DATEDIFF(Date, CURDATE()) < 6
                         ORDER BY Date DESC
                         LIMIT 1');
  $event->execute();
  $ayaEvent = $event->fetch(PDO::FETCH_ASSOC);
  $event = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
      <div class="panel panel-aya">
        <div class="panel-heading">
          <span class="glyphicon glyphicon-minus-sign "></span> Einbaumängel — <?=$ayaEvent['Name'] . ' ('
            . date('d.m.Y, H:i', strtotime($ayaEvent['Date'])) . ')';?>
        </div>
        <div class="panel-body panel-scrollable-xl">
          <div class="table-responsive">
            <table id="flaws" class="table table-hover">
              <thead>
                <tr>
                  <th class="text-center"><span class="glyphicon glyphicon-check"></span></th>
                  <th class="text-center">Klasse</th>
                  <th class="text-center">Realname</th>
                  <th class="text-center">Kennzeichen</th>
                  <th class="text-center">Fahrzeug</th>
                  <th class="text-center">Mängelfrei</th>
                </tr>
              </thead>
              <tbody>
<?php
try
{
  $vehicles = $db->prepare("SELECT C.ClassID, A.phpBBUserID, A.VehicleID, C.Name AS ClassName,
                            CONCAT(P.pf_vor_nachname_, ', ', P.pf_vorname) AS RealName, V.RegistrationNumber,
                            CONCAT(M.Name, ' ', V.Model) AS VehicleName,
                            CASE WHEN LENGTH(V.InstallFlaws) > 0 THEN true ELSE false END AS IsFlawed,
                            CHAR_LENGTH(V.InstallFlaws) AS Flawed
                            FROM aya_attendees A
                            JOIN aya_classes C
                              ON A.ClassID = C.ClassID
                            JOIN phpbb_profile_fields_data P
                              ON A.phpBBUserID = P.user_id
                            JOIN aya_vehicles V
                              ON A.VehicleID = V.VehicleID
                            JOIN aya_vehicles_manufacturers M
                              ON V.ManufacturerID = M.ManufacturerID
                            JOIN aya_events E
                              ON A.EventID = E.EventID
                            WHERE A.Deleted = FALSE
                              AND A.EventID = :id
                              AND DATEDIFF(E.Date, CURDATE()) < 6
                            ORDER BY C.SortKey ASC, RealName ASC");
  $vehicles->bindValue(':id', $ayaEvent['EventID'], PDO::PARAM_INT);
  $vehicles->execute();

  while ($vehicle = $vehicles->fetch(PDO::FETCH_ASSOC))
  {
    echo '<tr class="alert-' . ($vehicle['IsFlawed'] ? 'danger' : 'success') . '">
            <td class="text-center">
              <input data-class-id="' . $vehicle['ClassID'] . '" data-user-id="' . $vehicle['phpBBUserID'] . '" data-vehicle-id="'
                . $vehicle['VehicleID'] . '" type="checkbox" />
            </td>
            <td class="text-center">' . $vehicle['ClassName'] . '</td>
            <td class="text-center">' . $vehicle['RealName'] . '</td>
            <td class="text-center">' . $vehicle['RegistrationNumber'] . '</td>
            <td class="text-center">' . $vehicle['VehicleName'] . '</td>
            <td class="text-center">
              <span class="glyphicon glyphicon-' . ($vehicle['IsFlawed'] ? 'remove' : 'ok') . '-sign" aria-hidden="true"></span>
            </td>
          </tr>';
  }

  $vehicles = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}
?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="panel-footer">
          <span class="glyphicon glyphicon-list-alt"></span> Optionen:
          <button id="flaws-update" class="btn btn-aya-default" type="button"><span class="glyphicon glyphicon-edit"></span> Aktualisieren</button>
        </div>
      </div>
    </div>
    <div class="col-md-1"></div>
  </div>
</div>
<?php
$db = null;
require_once('fragments/footer.php');
?>
</body>
</html>
