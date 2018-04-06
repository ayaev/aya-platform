/**********
 * File:    event.js - jQuery functions for the Bootstrap progress bars of AYA event detail pages
 * Version: 1.0
 * Date:    2016-02-27
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

$(document).ready(function() {
  $('.progress-bar').each(function(index) {
    var progressBar = $(this);
    progressBar.css('width', function() {
      return ($(this).attr('aria-valuenow') + '%');
    });

    progressBar.one("transitionend msTransitionEnd otransitionend oTransitionEnd webkitTransitionEnd", function(event) {
      progressBar.removeClass('active');
    });
  });
});
