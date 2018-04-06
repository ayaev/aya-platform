/*
 * Copyright 2016-2018 Martin Baranski, TroubleZone.Net Productions
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
 * File:    location-distance.js - JavaScript functions for the distance calculations of AYA pages
 * Version: 1.6
 * Date:    2017-06-11
 */

// shortDistanceResult must be set inside HTML/PHP file which includes this file
function initializeDistances() {
  var userLocation = $('#aya-user').data('location');

  $('.aya-event').each(function() {
    var that = $(this);
    var ayaLocation = $("address", that).text();

    var distanceService = new google.maps.DistanceMatrixService;
    distanceService.getDistanceMatrix({
      origins: [ userLocation ],
      destinations: [ ayaLocation ],
      travelMode: google.maps.TravelMode.DRIVING,
      unitSystem: google.maps.UnitSystem.METRIC,
      avoidHighways: false,
      avoidTolls: true
    }, function(response, status) {
      if (status !== google.maps.DistanceMatrixStatus.OK) {
        alert('Distance query for \'' + ayaLocation + '\' / \'' + userLocation + '\' failed: ' + status);
      } else {
        var result = response.rows[0].elements[0];
        var distance = result.distance.text;
        var duration = result.duration.text;

        if (shortDistanceResult) {
          var time = (result.duration.value / 3600);
          var hours = Math.floor(time);
          var minutes = Math.floor((time - hours) * 60);
          if ((hours === 0) && (minutes === 0)) {
            minutes = 1;
          }

          duration = (hours > 0 ? (hours + ' h') : '') + ((hours > 0) && (minutes > 0) ? ', ' : '') + (minutes > 0 ? (minutes + ' min') : '');
        }

        $("span.distance", that).html(distance + ' (ca. ' + duration + ')');
      }
    });
  });
}
