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

$userID = $_POST['UserID'];
$vehicleID = $_POST['VehicleID'];

echo '<div id="flaw-editor-dialog" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal" type="button" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Mängelverwaltung</h3>
      </div>
      <div class="modal-body">
        <form id="flaw-form">
          <fieldset>
            <legend>Fahrzeugdaten</legend>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon aya-label aya-label-vehicle">Name</div>';

try
{
  $vehicle = $db->prepare("SELECT CONCAT(P.pf_vor_nachname_, ', ', P.pf_vorname) AS RealName, C.Name AS ClassName, V.VehicleID, V.RegistrationNumber,
                           V.InstallFlaws, V.Components, CONCAT(M.Name, ' ', V.Model) AS VehicleName
                           FROM aya_vehicles V
                           JOIN phpbb_profile_fields_data P
                             ON V.phpBBUserID = P.user_id
                           JOIN aya_classes C
                             ON C.ClassID = :classId
                           JOIN aya_vehicles_manufacturers M
                             ON V.ManufacturerID = M.ManufacturerID
                           WHERE V.Deleted = FALSE
                             AND V.phpBBUserID = :userId
                             AND V.VehicleID = :vehicleId");
  $vehicle->bindValue(':classId', $_POST['ClassID'], PDO::PARAM_INT);
  $vehicle->bindValue(':userId', $userID, PDO::PARAM_INT);
  $vehicle->bindValue(':vehicleId', $vehicleID, PDO::PARAM_INT);
  $vehicle->execute();
  $ayaVehicle = $vehicle->fetch(PDO::FETCH_ASSOC);
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$vehicle = null;
$db = null;

echo '
                    <input id="flaw-user" class="form-control" readonly="readonly" data-user-id="' . $userID . '" type="text"
                           value="' . $ayaVehicle['RealName'] . '" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon aya-label aya-label-vehicle">Klasse</div>
                    <input id="flaw-class" class="form-control" readonly="readonly" type="text" value="' . $ayaVehicle['ClassName'] . '" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon aya-label aya-label-vehicle">Fahrzeug</div>
                    <input id="flaw-vehicle" class="form-control" readonly="readonly" data-vehicle-id="' . $vehicleID . '" type="text"
                           value="' . $ayaVehicle['VehicleName'] . '" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon aya-label aya-label-vehicle">Kennzeichen</div>
                    <input id="flaw-registration-number" class="form-control" readonly="readonly" type="text" value="'
                      . $ayaVehicle['RegistrationNumber'] . '" />
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Einbaudaten</legend>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon aya-label aya-label-vehicle">Komponenten<br />inkl. UVP in €</div>
                    <textarea id="flaw-components" class="form-control aya-monospace" rows="5" readonly="readonly">'
                      . (empty($ayaVehicle['Components']) ? '' : $ayaVehicle['Components']) .
                    '</textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon aya-label aya-label-vehicle">Mängel</div>
                    <textarea id="flaw-details" class="form-control" placeholder="' . (empty($ayaVehicle['InstallFlaws'])
                      ? 'loses Kabel unter Hutablage; Isolation vom Power-Cap fehlt' : $ayaVehicle['InstallFlaws']) . '"
                              rows="3" type="text">' . (empty($ayaVehicle['InstallFlaws']) ? '' : $ayaVehicle['InstallFlaws']) .
                    '</textarea>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
        </form>
        <div id="result" class="alert aya-alert-ajax" role="alert"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-aya-default" data-dismiss="modal" type="button">Abbrechen</button>
        <button id="save" class="btn btn-aya" disabled="disabled" type="button">Hinzufügen</button>
      </div>
    </div>
  </div>
</div>';
?>
