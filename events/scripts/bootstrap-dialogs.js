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
 * File:    bootstrap-dialogs.js - jQuery functions for the Bootstrap dialogs of AYA event detail pages
 * Version: 3.56
 * Date:    2018-04-07
 */

var ajaxFolder = 'ajax/';
var fragmentsFolder = 'fragments/';
var scriptsFolder = 'scripts/';

var delete_ = '#delete';
var editor = '-editor';
var inputs_ = '-inputs'; // "input" is used by bootstrap-select library
var fragmentTarget = '#fragment-modal';
var mode = ''; // TODO: Remove this, but in conjunction with updated vehicle-editor.js (depends on modifying put/post for AJAX save call)
var result = '#result';
var save = '#save';

var createLabel = 'Hinzufügen';
var updateLabel = 'Aktualisieren';

var defaultResponses = {
  AlreadyDeleted: 'bereits gelöscht',
  AlreadyExists: 'existiert bereits',
  MissingData: 'fehlende Angaben',
  NonExistent: 'existiert nicht',
  Unchanged: 'keine Änderung',
  Unknown: 'Unbekannter Fehler'
};

function attachEventHandler(selector) {
  $(selector).each(function() {
    $(this).on('input', function(event) {
      var initialValue = '';
      if (event.target.type === "date" || event.target.type === "textarea" || event.target.type === "time") {
        initialValue = event.target.defaultValue;
      } else if (event.target.value !== '') {
        initialValue = event.target.placeholder;
      }
      enableSaveOnModified(event.target.value, initialValue);
    });
  });
}

function enableDeleteOnNonDefaults(selector) {
  // val == 0 -> 'Please select' option
  // original: $('#selector ').siblings()
  $(delete_).prop('disabled', ((selector.selectpicker('val') == 0) || (selector.children('option:selected').siblings().length < 3) ? 'disabled' : false));
}

function enableSaveOnModified(currentValue, initialValue) {
  $(save).prop('disabled', (currentValue !== initialValue ? false : 'disabled')).text(mode === 'post' ? createLabel : updateLabel);
}

function enableSelectionByRowClick(panelId) {
  $(panelId + ' > tbody > tr').click(function(event) {
    if (event.target.type !== 'checkbox') {
      $(this).find('td > input:checkbox').prop('checked', function(index, value) {
        return !value;
      });
    }
  });
}

function fragmentLoader(fragment, ajaxMode, source, finishCallback) {
  $(fragmentTarget).load(fragmentsFolder + fragment + editor + '.php',
    getRequestData(fragment, source),
    function() {
      var saveButton = $(save);
      mode = ajaxMode;
      if (mode === 'put') {
        saveButton.text(updateLabel);
      }
      $.ajax({
        dataType: 'script',
        url: scriptsFolder + fragment + editor + '.js'
      }).done(function() {
        if (typeof(loadedCallback) !== 'undefined' && $.isFunction(loadedCallback)) {
          loadedCallback();
        }
      });

      saveButton.click(function() {
        saveButton.prop('disabled', 'disabled');

        $.ajax({
          cache: false,
          data: getPayload(),
          method: 'POST',
          url: ajaxFolder + mode + '-' + fragment + '.php'
        }).done(function(response) {
          // TODO: Update source parameter (contains panel) with submitted data
          showMessage(displayName, mode, response, finishCallback); // displayName is defined in each *-editor.js
          saveButton.prop('disabled', (response > 0));
        });
      });

      $('#' + fragment + editor + '-dialog').modal('show');
    });
}

function getRequestData(fragment, source) {
  switch (fragment) {
    case 'attendance':
      return {
        [ 'EventID' ]: source
      };

    case 'flaw':
      let flaw = $(source + ' input:checked').first();
      return {
        [ 'ClassID' ]: flaw.data('class-id'),
        [ 'UserID' ]: flaw.data('user-id'),
        [ 'VehicleID' ]: flaw.data('vehicle-id')
      };

    default:
      return {
        [ fragment.charAt(0).toUpperCase() + fragment.slice(1) + 'ID' ]: $(source + ' input:checked').first().data(fragment + '-id')
      };
  }
}

function hideModal(callback) {
  setTimeout(function() {
    $(fragmentTarget + ' > div.modal')
      .modal('hide')
      .on('hidden.bs.modal', function(event) {
        if (callback && typeof(callback) === 'function') {
          callback();
        }
      });
  }, 2000);
}

function showMessage(displayName, mode, response, finishCallback) {
  var resultPanel = $(result);
  var isSuccess = response > -1;
  var isUnchanged = response == 0;

  resultPanel.attr('class', 'alert aya-alert-ajax alert-' + (isUnchanged ? 'info' : (isSuccess ? 'success' : 'danger')));
  var icon = '<span class="glyphicon glyphicon-';
  var info = '-sign" aria-hidden="true"></span> ';
  if (isSuccess) {
    icon += 'ok';
  } else if (isUnchanged) {
    icon += 'info';
    info += 'Hinweis: ';
  } else {
    icon += 'exclamation';
    info += 'Fehler: ';
  }

  var message = icon + info + displayName + ' ' + (isSuccess && !isUnchanged ? 'erfolgreich' : 'nicht') + ' ';
  switch (mode) {
    case 'delete':
      message += 'gelöscht';
      break;

    case 'post':
      message += 'angelegt';
      break;

    case 'put':
      message += 'aktualisiert';
      break;

    default:
      message += 'möglich';
      break;
  }

  if (!isSuccess || isUnchanged)
  {
    message += ' (';

    if (typeof(responseCallback) !== 'undefined' && $.isFunction(responseCallback)) {
      message += responseCallback(response);
    } else {
      switch (response) {
        case '0':
          message += defaultResponses.Unchanged;
          break;

        case 'ALREADY_DELETED':
          message += defaultResponses.AlreadyDeleted;
          break;

        case 'ALREADY_EXISTS':
          message += defaultResponses.AlreadyExists;
          break;

        case 'MISSING_DATA':
          message += defaultResponses.MissingData;
          break;

        case 'NON_EXISTENT':
          message += defaultResponses.NonExistent;
          break;

        default:
          message += defaultResponses.Unknown;
          break;
      }
    }

    message += ')';
  }

  resultPanel.html(message + '.' + (isSuccess && !isUnchanged ? '' : ' Bitte korrigieren!'));
  resultPanel.slideDown();

  if (isSuccess && !isUnchanged) {
    hideModal(finishCallback);
  }
}

$(document).ready(function() {
  var attendance = 'attendance';

  $('.' + attendance).each(function() {
    var that = $(this);
    that.click(function() {
      that.prop('disabled', 'disabled');
      fragmentLoader('attendance', 'post', that.data('event-id'), null);
      that.prop('disabled', false);
    });
  });

  $('#' + attendance + editor).click(function() {
    fragmentLoader('attendance', 'put', null, null);
  });

  $('#vehicle' + editor).click(function() {
    fragmentLoader('vehicle', 'put', null, null);
  });

  // TODO: Refactor this!
  $('tbody').each(function() {
    $(this).on('click', 'tr', function(event) {
      if (event.target.type === 'checkbox') {
        return;
      }

      $(this).find('th > input').prop('checked', function(index, value) {
        return !value;
      });
    });
  });
});
