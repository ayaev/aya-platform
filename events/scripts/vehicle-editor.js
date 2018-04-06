/**********
 * File:    vehicle-editor.js - functions for AYA vehicle editor
 * Version: 2.32
 * Date:    2018-01-30
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

var color = $('#vehicle-color');
var components = $('#vehicle-components');
var componentsPlaceholder = 'Nur in Klassen mit Preisbegrenzung angeben!';
var deleteButton = $(delete_);
var displayName = 'Fahrzeug';
var manufacturerSelector = $('#vehicle-manufacturer');
var model = $('#vehicle-model');
var registrationNumber = $('#vehicle-registration-number');
var saveButton = $(save);
var vehicleSelector = $('#vehicle-selector');

function clearInputs() {
  color.prop('placeholder', 'Firespark Red').val('');
  components.prop('placeholder', componentsPlaceholder).val('');
  manufacturerSelector.selectpicker('val', '');
  model.prop('placeholder', 'Golf V R32').val('');
  registrationNumber.prop('placeholder', 'A YA ' + new Date().getFullYear()).val('');
}

function getPayload() {
  return {
    Color: color.val(),
    Components: components.val(),
    ManufacturerID: manufacturerSelector.selectpicker('val'),
    Model: model.val(),
    RegistrationNumber: registrationNumber.val(),
    VehicleID: vehicleSelector.selectpicker('val')
  };
}

function loadedCallback() {
  attachEventHandler(".form-control");

  $.ajax({
    dataType: 'script',
    url: scriptsFolder + 'bootstrap-tooltip.js'
  });
  $.ajax({
    dataType: 'script',
    url: scriptsFolder + 'bootstrap-typeahead.js'
  });

  components.on('keypress', function(event) {
    if (event.which == '13') {
      return false;
    }
  }).on('paste', function(event) {
      // TODO: Implement prevention
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

  deleteButton.click(function() {
    deleteButton.prop('disabled', 'disabled');

    $.ajax({
      cache: false,
      data: {
        VehicleID: vehicleSelector.val()
      },
      method: 'POST',
      url: ajaxFolder + 'delete-vehicle.php'
    }).done(function(response) {
      // TODO: Refactor this & unify it with bootstrap-dialog.js!
      if (response > 0) {
        var currentSelection = $('#vehicle-selector option:selected');
        vehicleSelector.selectpicker('val', currentSelection.siblings('[value!=""]').first().val());
        currentSelection.remove();
        vehicleSelector.selectpicker('refresh');
        vehicleSelector.trigger('changed.bs.select');

        deleteButton.prop('disabled', false);

        var resultPanel = $(result);
        resultPanel.removeClass('alert-danger').addClass('alert-success');
        resultPanel.html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Fahrzeug erfolgreich gelöscht.');

        hideModal();
      } else {
        resultPanel.removeClass('alert-success').addClass('alert-danger');

        var errorReason = 'Bitte Angaben prüfen';
        var isDisabled = 'disabled';
        switch (response) {
          case 'IN_USE':
            errorReason = 'Fahrzeug bereits angemeldet';
            break;

          default:
            isDisabled = false;
            break;
        }
        resultPanel.html('<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Fehler bei der Löschung. ' + errorReason + '!');

        deleteButton.prop('disabled', isDisabled);
      }

      resultPanel.slideDown();
    });
  });

  manufacturerSelector.selectpicker()
    .on('loaded.bs.select', function(event, clickedIndex, newValue, oldValue) {
      var that = $(this);
      $.getJSON(ajaxFolder + 'get-manufacturers.php', function(response) {
        that.html('');
        $.each(response, function(key, value) {
          that.append('<option ' + ((value.Keywords != null) && (value.Keywords.length > 0) ? 'data-tokens="' + value.Keywords + '" ' : '')
            + 'value="' + value.ManufacturerID + '">' + value.Name + '</option>');
        });
        that.selectpicker('refresh');
      }).done(function() {
        that.selectpicker('val', that[0].dataset.initialManufacturer);
      });
    }).on('changed.bs.select', function(event, clickedIndex, newValue, oldValue) {
      enableSaveOnModified(event.target.value, this.dataset.initialManufacturer);
    });

  vehicleSelector.selectpicker()
    .on('loaded.bs.select', function(event, clickedIndex, newValue, oldValue) {
      var that = $(this);
      that.selectpicker('val', that[0].dataset.initialVehicle);
      enableDeleteOnNonDefaults(that);
    }).on('changed.bs.select', function(event, clickedIndex, newValue, oldValue) {
      clearInputs();
      var that = $(this);
      that[0].dataset.initialVehicle = event.target.value;
      enableDeleteOnNonDefaults(that);

      if (that.selectpicker('val') == 0) {
        mode = 'post';
        saveButton.text(createLabel);
      } else {
        mode = 'put';
        getVehicle();
        saveButton.text(updateLabel);
      }

      enableSaveOnModified(event.target.value, this.dataset.initialVehicle);
    }).on('refreshed.bs.select', function(event, clickedIndex, newValue, oldValue) {
      enableDeleteOnNonDefaults($(this));
    });
};

function getVehicle() {
  $.ajax({
    cache: false,
    data: {
      VehicleID: vehicleSelector.selectpicker('val')
    },
    method: 'POST',
    url: ajaxFolder + 'get-vehicle.php'
  }).done(function(response) {
    if (response.result !== false) {
      manufacturerSelector.selectpicker('val', response.ManufacturerID)[0].dataset.initialManufacturer = response.ManufacturerID;
      model.prop('placeholder', response.Model).val(response.Model);
      color.prop('placeholder', response.Color).val(response.Color);
      registrationNumber.prop('placeholder', response.RegistrationNumber).val(response.RegistrationNumber);

      if (response.Components === null) {
        components.prop('placeholder', componentsPlaceholder).val('');
      } else {
        components.prop('placeholder', response.Components).val(response.Components);
      }
    }
  });
}
