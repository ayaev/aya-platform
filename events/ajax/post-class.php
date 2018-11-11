<?php
/*
 * Copyright 2016-2018 Martin Arndt, TroubleZone.Net Productions
 *
 * Licensed under the EUPL, Version 1.2 only (the "Licence");
 * You may not use this work except in compliance with the Licence.
 * You may obtain a copy of the Licence at:
 *
 * https://joinup.ec.europa.eu/software/page/eupl
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the Licence is distributed on an "AS IS" basis,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the Licence for the specific language governing permissions and limitations under the Licence.
 *
 * File:    post-class.php - create class for AYA admin
 * Version: 1.4
 * Date:    2018-01-07
 */

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
      $insert = $db->prepare('INSERT
                              INTO aya_classes (Name, PriceLimited, SortKey)
                              VALUES (:name, :limited, :key)');
      $insert->bindValue(':name', $_POST['Name'], PDO::PARAM_STR);
      $update->bindValue(':limited', ($_POST['PriceLimited'] === 'true' ? 1 : 0), PDO::PARAM_INT); // PDO fails if using real BOOL -.-
      $insert->bindValue(':key', $_POST['SortKey'], PDO::PARAM_INT);
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
