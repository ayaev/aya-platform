<?php
/**********
 * File:    color-editor.php - color editor for AYA admin
 * Version: 1.7
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
  $color = $db->prepare('SELECT ColorID, Name
                         FROM aya_vehicles_colors
                         WHERE Deleted = FALSE
                           AND ColorID = :id');
  $color->bindValue(':id', (empty($_POST['ColorID']) ? 0 : $_POST['ColorID']), PDO::PARAM_INT);
  $color->execute();
  $ayaColor = $color->fetch(PDO::FETCH_ASSOC);
  $color = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

echo '<div id="color-editor-dialog" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal" type="button" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Farbverwaltung</h3>
      </div>
      <div class="modal-body">
        <form id="color-form" data-color-id="' . (empty($ayaColor['ColorID']) ? 0 : $ayaColor['ColorID']) . '" data-toggle="validator">
          <fieldset>
            <legend>Farbdaten</legend>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon aya-label">Farbname</div>
                    <input id="color-name" autocomplete="off" class="form-control aya-typeahead-vehicle" data-autoSelect="true" data-delay="0"
                           data-items="5" data-minLength="0" data-provide="typeahead" data-showHintOnFocus="true" data-toggle="tooltip" maxlength="20"
                           placeholder="' . (empty($ayaColor['Name']) ? 'Firespark Red' : $ayaColor['Name']) . '" required="required"
                           title="Ohne &bdquo;Metallic&rdquo; u. ä. Zusätze." type="text"
                           value="' . (empty($ayaColor['Name']) ? '' : $ayaColor['Name']) . '" />
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
