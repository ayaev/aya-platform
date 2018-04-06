/**********
 * File:    bootstrap-typeahead.js - jQuery functions for the type-aheads of AYA event detail pages
 * Version: 1.0
 * Date:    2016-02-27
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

$(document).ready(function() {
  $('#vehicle-color').typeahead({
    source: function(query) {
      $.getJSON('ajax/get-colors.php', function(data) {
        return data;
      })
    }
  });
});
