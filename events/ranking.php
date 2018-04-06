<?php
/**********
 * File:    ranking.php - AYA ranking page
 * Version: 1.0
 * Date:    2018-01-07
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

require_once('db-initialization.php');

$title = 'AYA — Rangliste 1.0';
require_once('fragments/header.php');

$showDistance = false;
$showDistanceShortened = false;
$showListingLink = true;
$showMap = false;
require_once('fragments/navigation.php');
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
      <div class="panel panel-aya">
        <div class="panel-heading">
          <span class="glyphicon glyphicon-king"></span> Rangliste — Stand: <?=date('d.m.Y, H:i');?>
        </div>
        <div class="panel-body panel-scrollable-xl">
          <div class="table-responsive">
            <table id="install-flaws" class="table table-hover table-striped">
              <thead>
                <tr>
                  <th class="text-center">Klasse</th>
                  <th class="text-center">Rang</th>
                  <th class="text-center">Nickname</th>
                  <th class="text-center">Team-Name</th>
                  <th class="text-center">Punkte</th>
                </tr>
              </thead>
              <tbody>
              <tr>
                <td class="text-center" colspan="5">
                  <h3>Kommt, sobald wir die bisherige &bdquo;Zettelwirtschaft&ldquo; ins Internet-Zeitalter überführen.<br />
                  Sonst müssten die jeweiligen Organisatoren vor Ort doppelt eintragen... 😏</h3>
                </td>
              </tr>
<?php
try
{
  $ranking = $db->prepare('SELECT R.RankingID, C.Name AS ClassName, U.username AS Username, P.pf_teamname AS TeamName, R.Points
                           FROM aya_ranking R
                           JOIN aya_classes C
                             ON R.ClassID = C.ClassID
                           JOIN phpbb_users U
                             ON R.phpBBUserID = U.user_id
                           JOIN phpbb_profile_fields_data P
                             ON R.phpBBUserID = P.user_id
                           WHERE R.Deleted = FALSE
                           ORDER BY C.SortKey ASC, R.Points DESC');
  $ranking->execute();

  $i = 0;
  $previousClass = '';
  while ($rank = $ranking->fetch(PDO::FETCH_ASSOC))
  {
    if ($previousClass != $rank['ClassName'])
    {
      $i = 1;
    }

    echo '<tr id="rankID-' . $rank['RankingID'] . '">
            <td class="text-center">' . ($previousClass == $rank['ClassName'] ? '' : $rank['ClassName']) . '</td>
            <td class="text-center">' . $i++ . '.</td>
            <td class="text-center">' . $rank['Username'] . '</td>
            <td class="text-center">' . $rank['TeamName'] . '</td>
            <td class="text-center">' . $rank['Points'] . '</td>
          </tr>';
    $previousClass = $rank['ClassName'];
  }

  $ranking = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}
?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-1"></div>
  </div>
</div>
<?php
$db = null;
require_once('fragments/footer.php');
?>
</body>
</html>
