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
 *
 * File:    location-editor.js - functions for AYA admin location editor
 * Version: 1.7
 * Date:    2018-02-25
 */

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
