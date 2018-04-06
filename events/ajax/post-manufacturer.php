<?php
/**********
 * File:    post-manufacturer.php - create manufacturer for AYA pages
 * Version: 1.4
 * Date:    2018-02-25
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
                         FROM aya_vehicles_manufacturers
                         WHERE ManufacturerID != :id
                           AND Name LIKE :name');
  $check->bindValue(':id', $_POST['ManufacturerID'], PDO::PARAM_INT);
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
                              INTO aya_vehicles_manufacturers (Name, Keywords)
                              VALUES (:name, :keywords)');
      $insert->bindValue(':name', $_POST['Name'], PDO::PARAM_STR);
      $insert->bindValue(':keywords', $_POST['Keywords'], PDO::PARAM_STR);
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
