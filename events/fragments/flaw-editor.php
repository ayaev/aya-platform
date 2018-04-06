<?php
/**********
 * File:    flaw-editor.php - flaw editor dialog of AYA administration pages
 * Version: 2.4
 * Date:    2018-01-13
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

require_once('../db-initialization.php');

echo '<div id="aya-flaw-editor-dialog" class="modal fade" role="dialog" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button class="close" data-dismiss="modal" type="button" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title">Fahrzeugverwaltung</h3>
            </div>
            <div class="modal-body">
              <form id="aya-flaw-editor-form">
                <fieldset>
                  <legend>Fahrzeugdaten</legend>
                  <div class="form-inline">
                    <div class="form-group">';

try
{
  $vehicle = $db->prepare("SELECT CONCAT(P.pf_vor_nachname_, ', ', P.pf_vorname) AS RealName, C.Name AS ClassName, V.VehicleID, V.RegistrationNumber,
                           V.InstallFlaws, CONCAT(M.Name, ' ', V.Model) AS VehicleName
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
  $vehicle->bindValue(':userId', $_POST['UserID'], PDO::PARAM_INT);
  $vehicle->bindValue(':vehicleId', $_POST['VehicleID'], PDO::PARAM_INT);
  $vehicle->execute();
  $ayaVehicle = $vehicle->fetch(PDO::FETCH_ASSOC);
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$vehicle = null;
$db = null;

echo '<div class="input-group">
                        <span class="input-group-addon aya-label aya-label-vehicle">Name</span>
                        <input id="flaw-editor-name-full" class="form-control aya-input aya-input-vehicle" readonly="readonly" type="text"
                               value="' . $ayaVehicle['RealName'] . '" />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon aya-label aya-label-vehicle">Klasse</span>
                        <input id="flaw-editor-name-class" class="form-control aya-input aya-input-vehicle" readonly="readonly" type="text"
                               value="' . $ayaVehicle['ClassName'] . '" />
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="form-inline">
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon aya-label aya-label-vehicle">Fahrzeug</span>
                        <input id="flaw-editor-name-vehicle" class="form-control aya-input aya-input-vehicle" readonly="readonly" type="text"
                               value="' . $ayaVehicle['VehicleName'] . '" />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon aya-label aya-label-vehicle">Kennzeichen</span>
                        <input id="flaw-editor-registration-number" class="form-control aya-input aya-input-vehicle" readonly="readonly" type="text"
                               value="' . $ayaVehicle['RegistrationNumber'] . '" />
                      </div>
                    </div>
                  </div>
                </fieldset>
                <br />
                <fieldset>
                  <legend>Einbaumängel</legend>
                  <div class="form-inline">
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon aya-label aya-label-components">Mängel</span>
                        <textarea id="flaw-editor-components" class="form-control" data-user-id="'
                          . $_POST['UserID'] . '" data-vehicle-id="' . $_POST['VehicleID'] . '"
                                  placeholder="' . (empty($ayaVehicle['InstallFlaws']) ? 'loses Kabel unter Hutablage; Isolation vom Power-Cap fehlt'
                                    : $ayaVehicle['InstallFlaws']) . '"
                                  rows="5" type="text">' . (empty($ayaVehicle['InstallFlaws']) ? '' : $ayaVehicle['InstallFlaws']) . '</textarea>
                      </div>
                    </div>
                  </div>
                  <br />
                  <div id="result" class="alert aya-alert-ajax" role="alert"></div>
                </fieldset>
              </form>
            </div>
            <div class="modal-footer">
              <button class="btn btn-aya-default" data-dismiss="modal" type="button">Abbrechen</button>
              <button id="save" class="btn btn-aya" data-mode="update" type="button">Aktualisieren</button>
            </div>
          </div>
        </div>
      </div>';
?>
