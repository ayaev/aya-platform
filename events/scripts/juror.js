/**********
 * File:    juror.js - jQuery functions for the AYA juror pages
 * Version: 2.2
 * Date:    2018-01-04
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

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
