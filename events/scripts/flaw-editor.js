/**********
 * File:    flaw-editor.js - functions for AYA juror flaw editor
 * Version: 1.1
 * Date:    2018-07-04
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

var displayName = 'Mängel';

function getPayload() {
  return {
    InstallFlaws: $('#flaw-details').val(),
    UserID: $('#flaw-user').data('user-id'),
    VehicleID: $('#flaw-vehicle').data('vehicle-id')
  };
}

function loadedCallback() {
  attachEventHandler(".form-control");
}
