/**********
 * File:    class-editor.js - functions for AYA admin class editor
 * Version: 1.3
 * Date:    2018-01-30
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

var displayName = 'Klasse';

function getPayload() {
  return {
    ClassID: $('#class-form')[0].dataset.classId,
    Name: $('#class-name').val(),
    PriceLimited: $('#class-price-limited').prop('checked'),
    SortKey: $('#class-sort-key').val()
  };
}
