/**********
 * File:    admin.js - jQuery functions for the AYA administration pages
 * Version: 2.18
 * Date:    2018-02-25
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

$(document).ready(function() {
  var panelActionCreate = '-create';
  var panelActionUpdate = '-update';
  var panelActionDelete = '-delete';
  var panelActionExport = '-export';
  var panelClasses = '#classes';
  var panelColors = '#colors';
  var panelEvents = '#events';
  var panelEventsYearSelector = $(panelEvents + '-year');
  var panelLocations = '#locations';
  var panelManufacturers = '#manufacturers';

  var enableSelectionByRowClick = function(tableId) {
    $(tableId + ' > tbody > tr').click(function() {
      $(this).find('td > input:checkbox').prop('checked', function(index, value) {
        return !value;
      });
    });
  }

  //$('.table.table-hover.table-striped').each(function() {
  //  var table = $(this);
  //  table.find('tbody > tr').click(function() {
  //    $(this).find('td > input:checkbox').prop('checked', function(index, value) {
  //      return !value;
  //    });
  //  });
  //});

  enableSelectionByRowClick(panelClasses);
  enableSelectionByRowClick(panelColors);
  enableSelectionByRowClick(panelLocations);
  enableSelectionByRowClick(panelManufacturers);

  function deleteItems(panel, itemIDSource, finishCallback) {
    var confirmed = false;
    if (confirmed) {
      var deletableItems = [];
      $(panel + ' input:checked').each(function() {
        deletableItems.push($(this).data(itemIDSource));
      });

      $.ajax({
        cache: false,
        data: {
          Items: deletableItems
        },
        method: 'POST',
        url: ajaxFolder + 'delete-' + itemIDSource.split('-')[0] + 's.php'
      }).done(function(response) {
        if (response > 0) {
          showMessage(displayName, 'delete', response, (response > 0), finishCallback);
          hideModal(finishCallback);
        } else {
          switch (response) {
            case 'WRONG_COUNT':
              errorReason = 'Nicht alle Teilnahmen löschbar';
              break;

            default:
              break;
          }
          showMessage(displayName, 'delete' + errorReason + '!', false);
        }
      });
    }
  }

  $(panelClasses + panelActionCreate).click(function() {
    fragmentLoader('class', 'post', null, null);
  });

  $(panelClasses + panelActionDelete).click(function() {
    $(panelClasses + ' input:checked').each(function() {
      $(this).data('class-id');
    });
    // ...
  });

  $(panelClasses + panelActionUpdate).click(function() {
    fragmentLoader('class', 'put', panelClasses, null);
  });

  $(panelColors + panelActionCreate).click(function() {
    fragmentLoader('color', 'post', null, null);
  });

  $(panelColors + panelActionDelete).click(function() {
    $(panelColors + ' input:checked').each(function() {
      $(this).data('color-id');
    });
    // ...
  });

  $(panelColors + panelActionUpdate).click(function() {
    fragmentLoader('color', 'put', panelColors, null);
  });

  $(panelEvents + panelActionCreate).click(function() {
    fragmentLoader('event', 'post', null, null);
  });

  $(panelEvents + panelActionDelete).click(function() {
    deleteItem(panelEvents, 'event-id', null);
  });

  $(panelEvents + panelActionUpdate).click(function() {
    fragmentLoader('event', 'put', panelEvents, null);
  });

  $(panelEvents + panelActionExport).click(function() {
    var exportEvents = $(this);
    exportEvents.prop('disabled', 'disabled');

    $(panelEvents + ' input:checked').each(function() {
      window.open(ajaxFolder + 'get-attendances.php?EventID=' + $(this).data('event-id'), '_blank');
      /* If the running webserver has enough power (and most importantly memory), try this instead:
      var eventId = $(this).data('event-id');
      $.ajax({
        data: {
          EventID: eventId
        },
        dataType: 'native',
        url: ajaxFolder + 'get-attendances.php',//?EventID=' + eventId,
        xhrFields: {
          responseType: 'blob'
        },
        success: function(blob) {
          console.log(JSON.stringify(blob.size));
          var link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = "Teilnehmerliste - " + new Date() + " - " + TITLE + ".xlsx";
          link.click();
        }
      });*/
    });

    exportEvents.prop('disabled', false);
  });

  panelEventsYearSelector.selectpicker()
    .on('loaded.bs.select', function(event, clickedIndex, newValue, oldValue) {
      var that = $(this);
      $.ajax({
        cache: false,
        data: {
          'EventYear': that.selectpicker('val'),
          'ShowDeleted': true
        },
        method: 'POST',
        url: ajaxFolder + 'get-events.php'
      }).done(function(response) {
        let events = $(panelEvents + ' > tbody');
        events.html('');
        let isBreakShown = false;
        $.each(response, function(key, value) {
          let isFinished = false;//(date('Y-m-d H:i:s') > date($event["Date"]));
          if (isFinished && !isBreakShown) {
            events.append('<tr><td colspan="5"><fieldset><legend><p class="text-center">Archivierte Veranstaltungen</p></legend></fieldset></td></tr>');
            isBreakShown = true;
          }

          events.append('<tr><td class="text-center"><input data-event-id="' + value.EventID + '" type="checkbox" /></td>'
            + '<td class="text-center text-nowrap">' + (value.Deleted ? '<del>' : '') + value.Date + (value.Deleted ? '</del>' : '') + '</td>'
            + '<td class="text-center">' + (value.Deleted ? '<del>' : '') + value.Name + (value.Deleted ? '</del>' : '') + '</td>'
            + '<td class="text-center">' + (value.Deleted ? '<del>' : '') + value.City + (value.Deleted ? '</del>' : '') + '</td>'
            + '<td class="text-center text-nowrap">' + (value.Deleted ? '<del>' : '') + value.LastUpdate + (value.Deleted ? '</del>' : '')
            + '</td></tr>');
        });
        that.selectpicker('refresh');
      }).done(function() {
        enableSelectionByRowClick(panelEvents);
        that.selectpicker('val', that[0].dataset.initialEventYear);
      });
    }).on('changed.bs.select', function(event, clickedIndex, newValue, oldValue) {
      //TODO: Check if needed: enableSaveOnModified(event.target.value, this.dataset.initialEventYear);
    });

  $(panelLocations + panelActionCreate).click(function() {
    fragmentLoader('location', 'post', null, null);
  });

  $(panelLocations + panelActionDelete).click(function() {
    $(panelLocations + ' input:checked').each(function() {
      $(this).data('location-id');
    });
    // ...
  });

  $(panelLocations + panelActionUpdate).click(function() {
    fragmentLoader('location', 'put', panelLocations, null);
  });

  $(panelManufacturers + panelActionCreate).click(function() {
    fragmentLoader('manufacturer', 'post', null, null);
  });

  $(panelManufacturers + panelActionDelete).click(function() {
    $(panelManufacturers+ ' input:checked').each(function() {
      $(this).data('manufacturer-id');
    });
    // ...
  });

  $(panelManufacturers + panelActionUpdate).click(function() {
    fragmentLoader('manufacturer', 'put', panelManufacturers, null);
  });

  $("[class$=-input-components]")
    .on('keypress', function(event) {
      if (event.which == '13') {
        return false;
      }
    }).on('paste', function(event) {
      
    });
});
