  <?php
/**********
 * File:    navigation.php - navigation toolbar for AYA pages
 * Version: 1.16
 * Date:    2018-01-15
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

echo '<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggle collapsed" data-target="#aya-navbar-collapse" data-toggle="collapse" type="button" aria-expanded="false">
        <span class="sr-only">Menü</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="//www.aya-forum.de/">
        <img alt="Are You Authentic? e. V." src="/styles/aya_prosilver/theme/images/logo_forum.png" />
      </a>
    </div>
    <div id="aya-navbar-collapse" class="collapse navbar-collapse">
      <div class="btn-group" role="group" aria-label="...">';

if ($showListingLink)
{
  echo '<a class="btn btn-aya-default navbar-btn" href="/events/listing.php" role="button">
  <span class="glyphicon glyphicon-chevron-left"></span> Alle Wettbewerbe
</a>';
}

echo '<div id="aya-vehicle-menu" class="btn-group" role="group">
  <a class="btn btn-aya-default ' . ($phpBBUserID < 2 ? 'disabled' : 'dropdown-toggle" data-toggle="dropdown') . '" href="#" role="button">
    Meine Daten <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    <li>
      <a id="attendance-editor" href="#">
        <span class="glyphicon glyphicon-calendar"></span> Teilnahmen
      </a>
    </li>
    <li>
      <a id="vehicle-editor" href="#">
        <span class="glyphicon glyphicon-bed"></span> Fahrzeuge
      </a>
    </li>
  </ul>
</div>
<a class="btn btn-aya-default navbar-btn" href="/events/ranking.php" role="button">
  <span class="glyphicon glyphicon-king"></span> Rangliste
</a>';

if ($isJuror)
{
  echo '<a class="btn btn-aya-default navbar-btn" href="/events/juror.php" role="button">
  <span class="glyphicon glyphicon-list-alt"></span> Jurorenbereich
</a>';
}

if ($isAdmin)
{
  echo '<a class="btn btn-aya-default navbar-btn" href="/events/admin.php" role="button">
  <span class="glyphicon glyphicon-wrench"></span> Administration
</a>';
}

echo '</div>
      <p class="navbar-text navbar-right">
        <a id="aya-user" data-user-id="' . $phpBBUserID . '" href="/'
             . ($phpBBUserID < 2 ? 'ucp.php?mode=login">Foren-Anmeldung' : 'memberlist.php?mode=viewprofile&amp;u=' . $phpBBUserID . '"
           data-location="' . $ayaUserLocation . '">Willkommen, ' . $ayaUsername) . '</a>
      </p>
    </div>
  </div>
</nav>';
?>
