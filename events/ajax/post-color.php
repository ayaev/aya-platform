<?php
/**********
 * File:    post-color.php - create color for AYA admin
 * Version: 1.1
 * Date:    2018-01-07
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

require_once('../db-initialization.php');

if (!$isAdmin)
{
  die('Only administrators beyond this point! Sorry.');
}
  
try
{
  $check = $db->prepare('SELECT COUNT(*)
                         FROM aya_vehicles_colors
                         WHERE ColorID != :id
                           AND Name LIKE :name');
  $check->bindValue(':id', $_POST['ColorID'], PDO::PARAM_INT);
  $check->bindValue(':name', $_POST['Name'], PDO::PARAM_STR);
  $check->execute();
  $exists = $check->fetchColumn();
  $check = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

if ($exists < 1)
{
  if (!empty($_POST['Name']))
  {
    try
    {
      $insert = $db->prepare('INSERT
                              INTO aya_vehicles_colors (Name)
                              VALUES (:name)');
      $insert->bindValue(':name', $_POST['Name'], PDO::PARAM_STR);
      $insert->execute();
      echo $insert->rowCount();
      $insert = null;
    }
    catch (PDOException $exception)
    {
      print 'Error: ' . $exception->getMessage() . '<br />';
    }
  }
  else
  {
    echo 'MISSING_DATA';
  }
}
else
{
  echo 'ALREADY_EXISTS';
}

$db = null;
?>
