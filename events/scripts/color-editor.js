/**********
 * File:    color-editor.js - functions for AYA admin color editor
 * Version: 1.4
 * Date:    2018-01-04
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

var displayName = 'Farbe';

function getPayload() {
  return {
    ColorID: $('#color-form').data('color-id'),
    Name: $('#color-name').val()
  };
}
