/**********
 * File:    event-editor.js - functions for AYA admin event editor
 * Version: 1.31
 * Date:    2018-01-30
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

var displayName = 'Wettbewerb';
var locationSelector = $('#location-selector');

function getPayload() {
  return {
    ClassLimits: function() {
      var limits = {};
      $("input[id^='class-limit-']").each(function() {
        limits[this.id.slice(12)] = parseInt(this.value, 10);
      });

      return JSON.stringify(limits);
    },
    Date: $('#event-date').val() + ' ' + $('#event-time').val(),
    Description: $('#event-description').val(),
    EventID: $('#event-form')[0].dataset.eventId,
    LocationID: locationSelector.selectpicker('val'),
    Name: $('#event-name').val()
  };
}

function loadedCallback() {
  attachEventHandler(".form-control");

  locationSelector.selectpicker()
    .on('changed.bs.select', function(event, clickedIndex, newValue, oldValue) {
      enableSaveOnModified(event.target.value, this.dataset.initialLocation);
    });
}
