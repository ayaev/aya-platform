<?php
/**********
 * File:    footer.php - footer for AYA pages
 * Version: 1.29
 * Date:    2018-04-06
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/
?>
<div id="fragment-modal"></div>
<footer class="footer">
  <p class="text-muted text-center">
    Made with <span title="blood">💧&#xFE0E;</span>, <span title="sweat">💦&#xFE0E;</span> & <span title="love">💗&#xFE0E;</span> 2016-<?=date('y');?> for AYA e. V. by <a href="//www.troublezone.net/">Martin Baranski</a>.
  </p>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous" async>></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"
        integrity="sha256-19J3rT3tQdidgtqqdQ3xNu++Gd7EoP/ag/0x1lHi0xY=" crossorigin="anonymous" async></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"
        integrity="sha256-LOnFraxKlOhESwdU/dX+K0GArwymUDups0czPWLEg4E=" crossorigin="anonymous" async></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"
        integrity="sha256-dHf/YjH1A4tewEsKUSmNnV05DDbfGN3g7NMq86xgGh8=" crossorigin="anonymous" async></script>

<script src="scripts/bootstrap-dialogs.js"></script>
<?php
if ($isEventPage)
  echo '<script src="scripts/event.js"></script>';

if (($phpBBUserID > 1) && $showDistance && !$showMap)
{
  echo '<script src="https://maps.googleapis.com/maps/api/js?callback=initializeDistances" async defer></script>
<script src="scripts/location-distance.js"></script>';
}

if ($showMap)
{
  echo '<script src="https://maps.googleapis.com/maps/api/js?callback=initializeLocationMap" async defer></script>
<script src="scripts/location-map.js"></script>';
}

if ($isAdmin)
  echo '<script src="scripts/admin.js"></script>';

if ($isJuror)
  echo '<script src="scripts/juror.js"></script>';
?>

<script>
<?php
if ($showDistance)
{
  echo 'var shortDistanceResult = ' . ($showDistanceShortened ? 'true' : 'false') . ';';
}

/* Commented to prevent output to clients until properly implemented
echo '
$(document).ready(function() {
  // Bootstrap Validator for inputs
  /*var errorMessageEmpty = \'Dies ist ein Pflichtfeld.\';
  var errorMessageFormat = \'Das Eingabeformat stimmt nicht.\';
  var errorMessageLength = \'Die Eingabe ist zu lang: max. 30 Zeichen.\';
  $(\'#ayaRegistrationForm\')
    .on(\'init.field.fv\', function(event, data) {
      // data.fv      --> The FormValidation instance
      // data.field   --> The field name
      // data.element --> The field element

      var $icon      = data.element.data('fv.icon'),
          options    = data.fv.getOptions(),                      // Entire options
          validators = data.fv.getOptions(data.field).validators; // The field validators

      if (validators.notEmpty && options.icon && options.icon.required) {
        $icon.addClass(options.icon.required).show();
      }
    })
    .formValidation({
      framework: \'bootstrap\',
      icon: {
        required: \'glyphicon glyphicon-asterisk\',
        valid: \'glyphicon glyphicon-ok\',
        invalid: \'glyphicon glyphicon-remove\',
        validating: \'glyphicon glyphicon-refresh\'
      },
      fields: {
        firstName: {
          validators: {
            stringLength: {
              max: 30,
              message: errorMessageLength
            },
            notEmpty: {
              message: errorMessageEmpty
            }
          }
        },
        lastName: {
          validators: {
            stringLength: {
              max: 30,
              message: \'The description must be less than 300 characters long\'
            }
          }
        },
        price: {
                    validators: {
                        notEmpty: {
                            message: \'The price is required\'
                        },
                        numeric: {
                            message: \'The price must be a number\'
                        }
                    }
                },
                quantity: {
                    validators: {
                        notEmpty: {
                            message: \'The quantity is required\'
                        },
                        integer: {
                            message: \'The quantity must be a number\'
                        }
                    }
                }
            }
    })
    .on(\'status.field.fv\', function(event, data) {
      var $icon      = data.element.data(\'fv.icon\'),
      options    = data.fv.getOptions(),                      // Entire options
      validators = data.fv.getOptions(data.field).validators; // The field validators

      if (validators.notEmpty && options.icon && options.icon.required) {
        $icon.removeClass(options.icon.required).addClass('fa');
      }
    });* /
});';
*/
?>
</script>
