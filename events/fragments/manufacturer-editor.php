<?php
/**********
 * File:    manufacturer-editor.php - manufacturer editor for AYA admin
 * Version: 1.8
 * Date:    2018-03-18
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

require_once('../db-initialization.php');

if (!$isAdmin)
{
  die('Only administrators beyond this point! Sorry.');
}

try
{
  $manufacturer = $db->prepare('SELECT ManufacturerID, Name, Keywords
                                FROM aya_vehicles_manufacturers
                                WHERE Deleted = FALSE
                                  AND ManufacturerID = :id');
  $manufacturer->bindValue(':id', (empty($_POST['ManufacturerID']) ? 0 : $_POST['ManufacturerID']), PDO::PARAM_INT);
  $manufacturer->execute();
  $ayaManufacturer = $manufacturer->fetch(PDO::FETCH_ASSOC);
  $manufacturer = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

echo '<div id="manufacturer-editor-dialog" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal" type="button" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Herstellerverwaltung</h3>
      </div>
      <div class="modal-body">
        <form id="manufacturer-form" data-manufacturer-id="' . (empty($ayaManufacturer['ManufacturerID']) ? 0 : $ayaManufacturer['ManufacturerID'])
          . '" data-toggle="validator">
          <fieldset>
            <legend>Herstellerdaten</legend>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon aya-label aya-label-event">Herstellername</div>
                    <input id="manufacturer-name" autocomplete="off" class="form-control aya-typeahead-vehicle" data-autoSelect="true" data-delay="0"
                           data-items="5" data-minLength="0" data-provide="typeahead" data-showHintOnFocus="true" data-toggle="tooltip" maxlength="15"
                           placeholder="' . (empty($ayaManufacturer['Name']) ? 'VW' : $ayaManufacturer['Name']) . '" required="required"
                           title="Ohne &bdquo;GmbH&rdquo; u. ä. Zusätze." type="text"
                           value="' . (empty($ayaManufacturer['Name']) ? '' : $ayaManufacturer['Name']) . '" />
                    <div class="input-group-addon">
                      <span class="glyphicon glyphicon-asterisk form-control-feedback" aria-hidden="true"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon aya-label aya-label-event">Suchbegriffe</div>
                    <input id="manufacturer-keywords" autocomplete="off" class="form-control aya-typeahead-vehicle" data-autoSelect="true"
                           data-delay="0" data-items="5" data-minLength="0" data-provide="typeahead" data-showHintOnFocus="true" data-toggle="tooltip"
                           maxlength="50" placeholder="' . (empty($ayaManufacturer['Keywords']) ? 'VW Volkswagen' : $ayaManufacturer['Keywords']) . '"
                           required="required" title="Mit Leerzeichen getrennte Liste." type="text"
                           value="' . (empty($ayaManufacturer['Keywords']) ? '' : $ayaManufacturer['Keywords']) . '" />
                    <div class="input-group-addon">
                      <span class="glyphicon glyphicon-asterisk form-control-feedback" aria-hidden="true"></span>
                    </div>
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
        <button id="save" class="btn btn-aya" type="button">Hinzufügen</button>
      </div>
    </div>
  </div>
</div>';

$db = null;
?>
