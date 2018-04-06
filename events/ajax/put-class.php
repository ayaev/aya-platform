<?php
/**********
 * File:    put-class.php - update class for AYA admin
 * Version: 1.5
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
                         FROM aya_classes
                         WHERE ClassID != :id
                           AND Name LIKE :name');
  $check->bindValue(':id', $_POST['ClassID'], PDO::PARAM_INT);
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
  if (!empty($_POST['Name']) && !empty($_POST['SortKey']))
  {
    try
    {
      $update = $db->prepare('UPDATE aya_classes
                              SET Name = :name, PriceLimited = :limited, SortKey = :key
                              WHERE ClassID = :id');
      $update->bindValue(':name', $_POST['Name'], PDO::PARAM_STR);
      $update->bindValue(':limited', ($_POST['PriceLimited'] === 'true' ? 1 : 0), PDO::PARAM_INT); // PDO fails if using real BOOL -.-
      $update->bindValue(':key', $_POST['SortKey'], PDO::PARAM_INT);
      $update->bindValue(':id', $_POST['ClassID'], PDO::PARAM_INT);
      $update->execute();
      echo $update->rowCount();
      $update = null;
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
