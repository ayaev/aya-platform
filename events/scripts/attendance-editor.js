/**********
 * File:    attendance-editor.js - functions for AYA attendance editor
 * Version: 2.54
 * Date:    2018-03-19
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

var attendanceSelector = $('#attendance-selector');
var classSelector = $('#class-selector');
var displayName = 'Teilnahme';
var inputsPanel = $('#' + editor.slice(1) + inputs_);
var remark = $('#attendee-remark');
var remarkPlaceholder = 'Muss leider 15:30 wieder los.';
var resultPanel = $(result);
var saveButton = $(save);
var vehicleSelector = $('#vehicle-selector');

function addAttendance(attendance) {
  $('.list-group[data-class-id=\'' + attendance.ClassId + '\']')
    .fadeOut(200, function() {
      var attendee = '<li class="list-group-item" data-attendee-id="' + attendance.Id + '">';
      if (attendance.TeamName.length < 1) {
        attendee += attendance.NickName;
      } else {
        attendee += '<div class="row"><div class="col-md-7">' + attendance.NickName + '</div><div class="col-md-5"><span class="label" title="'
          + attendance.TeamName + '">' + attendance.TeamName + '</span></div></div>';
      }
      attendee += '</li>';
      $(this).append(attendee).slideDown();
    });
}

function clearInputs() {
  classSelector.selectpicker('val', '');
  remark.prop('placeholder', remarkPlaceholder).val('');
  vehicleSelector.selectpicker('val', '');
}

function deleteAttendance(attendanceId) {
  $('.list-group-item[data-attendee-id=\'' + attendanceId + '\']')
    .fadeOut(200, function() {
      $(this).slideUp().remove();
    });
}

function finishCallback() {
  var attendance = {
    ClassId: classSelector.selectpicker('val'),
    Id: attendanceSelector.selectpicker('val'),
    NickName: $('#attendee-name-nick').val(),
    TeamName: $('#attendee-name-team').val()
  };
  switch (mode) {
    case 'delete':
      deleteAttendance(attendance.Id);
      break;

    case 'post':
      addAttendance(attendance);
      break;

    case 'put':
      deleteAttendance(attendance.Id);
      addAttendance(attendance);
      break;

    default:
      break;
  }
}

function getAttendance() {
  $.ajax({
    cache: false,
    data: {
      AttendeeID: attendanceSelector.selectpicker('val')
    },
    method: 'POST',
    url: ajaxFolder + 'get-attendance.php'
  }).done(function(response) {
    if (response.result !== false) {
      vehicleSelector.selectpicker('val', response.VehicleID)[0].dataset.initialVehicle = response.VehicleID;
      classSelector.selectpicker('val', response.ClassID)[0].dataset.initialClass = response.ClassID;

      if (response.Remark === null) {
        remark.prop('placeholder', remarkPlaceholder).val('');
      } else {
        remark.prop('placeholder', response.Remark).val(response.Remark);
      }
    }
  });
}

function getPayload() {
  return {
    AttendeeID: attendanceSelector.selectpicker('val'),
    ClassID: classSelector.selectpicker('val'),
    EventID: attendanceSelector[0].selectedOptions[0].dataset.eventId,
    VehicleID: vehicleSelector.selectpicker('val'),
    Remark: remark.val()
  };
}

function loadedCallback() {
  attachEventHandler(".form-control");

  $.ajax({
    dataType: 'script',
    url: scriptsFolder + 'bootstrap-tooltip.js'
  });

  $('[data-toggle="tooltip"]').tooltip({
    animation: true,
    container: 'body',
    html: false,
    placement: 'auto top',
    selector: false,
    title: '',
    trigger: 'hover focus',
    viewport: {
      padding: 0,
      selector: 'body'
    }
  });

  // TODO: Disable delete button upon 0 active attendances (Disable drop-down, if loaded with 0 attendances, too!)
  attendanceSelector.selectpicker()
    .on('loaded.bs.select', function(event, clickedIndex, newValue, oldValue) {
      var that = $(this);
      that.selectpicker('val', this.dataset.initialAttendance);
      getAttendance();
      enableDeleteOnNonDefaults(that);
    }).on('changed.bs.select', function(event, clickedIndex, newValue, oldValue) {
      var that = $(this);
      enableDeleteOnNonDefaults(that);
      clearInputs();

      if (that.selectpicker('val') == 0) {
        setEditorMode(createLabel, updateLabel);
      } else {
        getAttendance();
        setEditorMode(updateLabel, createLabel);
      }

      enableSaveOnModified(event.target.value, this.dataset.initialAttendance);
    }).on('refreshed.bs.select', function(event, clickedIndex, newValue, oldValue) {
      enableDeleteOnNonDefaults($(this));
    });

  classSelector.selectpicker()
    .on('changed.bs.select', function(event, clickedIndex, newValue, oldValue) {
      if (event.target.selectedOptions[0].dataset.priceLimited === 'true') {
        $.ajax({
          cache: false,
          data: {
            VehicleID: vehicleSelector.selectpicker('val')
          },
          method: 'POST',
          url: ajaxFolder + 'get-components.php'
        }).done(function(response) {
          if (response.result === false) {
            showMessage(displayName, null, 'MISSING_COMPONENTS', null);
          }
        });
      } else {
        resultPanel.slideUp();
      }
      enableSaveOnModified(event.target.value, (vehicleSelector[0].value != vehicleSelector[0].dataset.initialVehicle ? -1 : this.dataset.initialClass));
    });

  vehicleSelector.selectpicker()
    .on('changed.bs.select', function(event, clickedIndex, newValue, oldValue) {
      classSelector.selectpicker('val', '');
      resultPanel.slideUp();
      enableSaveOnModified(event.target.value, this.dataset.initialVehicle);
    });

  if ($('#attendee-phone-number').val().length < 15) {
    saveButton.prop('disabled', 'disabled');
    showMessage(displayName, null, 'MISSING_PHONE', null);
  }
}

function responseCallback(response) {
  switch (response) {
    case '0':
      return defaultResponses.Unchanged;

    case 'ALREADY_ATTENDING':
      return 'Fahrzeug bereits angemeldet';

    case 'CLASS_FULL':
      return 'Klasse belegt';

    case 'MISSING_COMPONENTS':
      return 'keine Komponenten-Infos';

    case 'MISSING_DATA':
      return defaultResponses.MissingData;

    case 'MISSING_PHONE':
      return 'keine Handy-Nummer';

    default:
      return defaultResponses.Unknown;
  }
}

function setEditorMode(newLabel, oldLabel) {
  mode = newLabel === createLabel ? 'post' : 'put';
  inputsPanel.text(inputsPanel.text().replace(oldLabel.toLowerCase(), newLabel.toLowerCase()));
}

/*
var deleteButton = $(delete_);
  deleteButton.click(function() {
    deleteButton.prop('disabled', 'disabled');

    var attendances = [];
    $('[id^=attendeeID-]').each(function() {
      if (this.checked) {
        attendances.push(this.id.slice(11));
      }
    });
    $.ajax({
      cache: false,
      data: {
        Attendances: attendances
      },
      method: 'POST',
      url: ajaxFolder + 'delete-attendances.php'
    }).done(function(response) {
      if (response > 0) {
        showMessage(response + ' Teilnahme' + (response > 1 ? 'n' : '') + ' erfolgreich gelöscht.', true);
        hideModal();
      } else {
        var errorReason = 'Bitte Angaben prüfen';
        var isDisabled = 'disabled';
        switch (response) {
          case 'WRONG_COUNT':
            errorReason = 'Nicht alle Teilnahmen löschbar';
            break;

          default:
            isDisabled = false;
            break;
        }
        showMessage('Fehler bei der Löschung. ' + errorReason + '!', false);

        deleteButton.prop('disabled', isDisabled);
      }
    });
  });
*/