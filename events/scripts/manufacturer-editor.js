/**********
 * File:    manufacturer-editor.js - functions for AYA admin manufacturer editor
 * Version: 1.2
 * Date:    2018-01-04
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

var displayName = 'Hersteller';

function getPayload() {
  return {
    ManufacturerID: $('#manufacturer-form').data('manufacturer-id'),
    Name: $('#manufacturer-name').val(),
    Keywords: $('#manufacturer-keywords').val()
  };
}
