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
 * File:    juror.js - jQuery functions for the AYA juror pages
 * Version: 2.2
 * Date:    2018-01-04
 */

$(document).ready(function() {
  $('#install-flaws > tbody > tr').click(function() {
    var flawedVehicle = $(this);

    $(fragmentTarget).load(fragmentsFolder + 'flaw-editor.php', {
      ClassID: flawedVehicle.data('class-id'),
      UserID: flawedVehicle.data('user-id'),
      VehicleID: flawedVehicle.attr('id').slice(10)
    }, function() {
      var flawsEditor = 'aya-flaw-editor'
      var finishFlaws = $('#finish-flaws');
      var vehicleFlaws = $('#flaw-editor-components');

      finishFlaws.click(function() {
        finishFlaws.prop('disabled', 'disabled');

        $.ajax({
          cache: false,
          data: {
            InstallFlaws: vehicleFlaws.val(),
            UserID: vehicleFlaws.data('user-id'),
            VehicleID: vehicleFlaws.data('vehicle-id')
          },
          method: 'POST',
          url: ajaxFolder + 'put-flaws.php'
        }).done(function(response) {
          if (response > -1) {
            showMessage('Mängel erfolgreich aktualisiert.', true);
            hideModal();
          } else {
            showMessage('Fehler bei der Mängelaktualisierung. Bitte Angaben prüfen!', false);
          }

          finishFlaws.prop('disabled', false);
        });
      });

      $('#' + flawsEditor + '-dialog').modal('show');
    });
  });

  $("[class$=-input-components]")
    .on('keypress', function(event) {
      if (event.which == '13') {
        return false;
      }
    }).on('paste', function(event) {
      
    });
});
