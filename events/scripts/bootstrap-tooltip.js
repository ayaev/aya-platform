/**********
 * File:    bootstrap-tooltip.js - jQuery functions for the tooltips of AYA event detail pages
 * Version: 1.0
 * Date:    2016-02-27
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip({
    animation: true,
    container: 'body',
    html: false,
    placement: 'auto top',
    selector: false,
    title: '',
    trigger: 'focus hover',
    viewport: {
      padding: 0,
      selector: 'body'
    }
  });
});
