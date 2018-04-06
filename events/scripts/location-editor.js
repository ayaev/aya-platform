/**********
 * File:    location-editor.js - functions for AYA admin location editor
 * Version: 1.7
 * Date:    2018-02-25
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

var displayName = 'Austragungsort';

function getPayload() {
  return {
    City: $('#location-city').val(),
    Coordinates: parseFloat($('#location-latitude').val()).toFixed(4) + ',' + parseFloat($('#location-longitude').val()).toFixed(4),
    Description: $('#location-description').val(),
    HostUrl: $('#location-host-url').val(),
    LocationID: $('#location-form').data('location-id'),
    Name: $('#location-name').val(),
    Street: $('#location-street').val(),
    StreetNumber: $('#location-street-number').val(),
    Zip: $('#location-zip').val()
  };
}
