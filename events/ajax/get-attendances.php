<?php
/**********
 * File:    get-attendances.php - get attendances of AYA event detail pages
 * Version: 2.5
 * Date:    2018-01-05
 * Author:  Martin Baranski, TroubleZone.Net Productions
 * Licence: Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
 *          (see: https://creativecommons.org/licenses/by-sa/4.0/ for details)
 **********/

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once('../db-initialization.php');

if (!$isAdmin)
{
  header('Location: /events/listing.php', true, 303);
  die('Only administrators beyond this point! Sorry.');
}

require_once('../export/PHPExcel.php');

try
{
  $event = $db->prepare('SELECT Name, Date
                         FROM aya_events
                         WHERE Deleted = FALSE
                           AND EventID = :id');
  $event->bindValue(':id', $_GET['EventID'], PDO::PARAM_INT);
  $event->execute();
  $ayaEvent = $event->fetch(PDO::FETCH_ASSOC);
  $event = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

//$export = PHPExcel_IOFactory::load('../export/Attendances.xlsx');
$export = new PHPExcel();
$export->getProperties()
         ->setCreator("Martin Baranski / TroubleZone.Net Productions for AYA e. V.")
         ->setLastModifiedBy("Martin Baranski / TroubleZone.Net Productions")
         ->setTitle("Teilnehmerliste für " . $ayaEvent['Name'] . " am " . $ayaEvent['Date'])
         ->setSubject("Teilnehmerliste für " . $ayaEvent['Name'])
         ->setDescription("AYA-Teilnehmerliste für " . $ayaEvent['Name'])
         ->setKeywords("AYA Wettbewerb")
         ->setCategory("Teilnehmerliste");
$export->setActiveSheetIndex(0)->setTitle('Aufkleber');

$export->addSheet(new PHPExcel_Worksheet($export, "Teilnehmer"));
$export->setActiveSheetIndexByName("Teilnehmer")
         ->setCellValue('B2', 'Klasse')
         ->setCellValue('C2', 'Name')
         ->setCellValue('D2', 'Vorname')
         ->setCellValue('E2', 'Nickname')
         ->setCellValue('F2', 'Fahrzeug')
         ->setCellValue('G2', 'Farbe')
         ->setCellValue('H2', 'Kennzeichen')
         ->setCellValue('I2', 'Handy-Nummer')
         ->setCellValue('J2', 'Team-Name')
         ->setCellValue('K2', 'E-Mail')
         ->setCellValue('L2', 'Komponenten')
         ->setCellValue('M2', 'Einbaumangel')
         ->setCellValue('N2', 'Notiz')
         ->setCellValue('O2', 'AYA-Mitglied')
         ->getStyle('B2:O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$export->setActiveSheetIndexByName("Teilnehmer")->getStyle('B2:O2')->getFont()->setBold(true);

try
{
  $attendees = $db->prepare("SELECT P.pf_vor_nachname_ AS LastName, P.pf_vorname AS FirstName, U.username AS Nickname,
                             CONCAT(M.Name, ' ', V.Model) AS Vehicle, V.Color, V.RegistrationNumber,
                             P.pf_handynr AS PhoneNumber, P.pf_teamname AS TeamName, U.user_email AS MailAddress, V.Components, V.InstallFlaws,
                             A.Remark, A.ClassID, C.Name AS ClassName,
                             CASE
                               WHEN U.group_id > 7 THEN 'ja'
                               ELSE 'nein'
                             END AS IsAyaMember
                             FROM aya_attendees A
                             JOIN aya_classes C
                               ON A.ClassID = C.ClassID
                             JOIN aya_vehicles V
                               ON A.VehicleID = V.VehicleID
                             JOIN aya_vehicles_manufacturers M
                               ON V.ManufacturerID = M.ManufacturerID
                             JOIN phpbb_users U
                               ON A.phpBBUserID = U.user_id
                             JOIN phpbb_profile_fields_data P
                               ON U.user_id = P.user_id
                             WHERE A.Deleted = FALSE
                               AND A.EventID = :id
                             ORDER BY A.ClassID ASC, U.username ASC");
  $attendees->bindValue(':id', $_GET['EventID'], PDO::PARAM_INT);
  $attendees->execute();

  $previousClassID = 0;
  $rowIndex = 3;
  while ($attendee = $attendees->fetch(PDO::FETCH_ASSOC))
  {
    if ($previousClassID != $attendee['ClassID'])
    {
      $previousClassID = $attendee['ClassID'];
      $rowIndex = $attendee['ClassID'] * 20 + 3 - 20;  // *20 = each class' attendee limit, +3 = table header offset, -20 = placement offset
      $export->setActiveSheetIndexByName("Teilnehmer")->setCellValue(('B' . $rowIndex), $attendee['ClassName']);
    }

    $export->setActiveSheetIndexByName("Teilnehmer")
             ->setCellValue(('C' . $rowIndex), $attendee['LastName'])
             ->setCellValue(('D' . $rowIndex), $attendee['FirstName'])
             ->setCellValue(('E' . $rowIndex), $attendee['Nickname'])
             ->setCellValue(('F' . $rowIndex), $attendee['Vehicle'])
             ->setCellValue(('G' . $rowIndex), $attendee['Color'])
             ->setCellValue(('H' . $rowIndex), $attendee['RegistrationNumber'])
             ->setCellValue(('I' . $rowIndex), $attendee['PhoneNumber'])
             ->setCellValue(('J' . $rowIndex), $attendee['TeamName'])
             ->setCellValue(('K' . $rowIndex), $attendee['MailAddress'])
             ->setCellValue(('L' . $rowIndex), str_replace(array("\r\n", "\r", "\n"), ' ', $attendee['Components']))
             ->setCellValue(('M' . $rowIndex), $attendee['InstallFlaws'])
             ->setCellValue(('N' . $rowIndex), $attendee['Remark'])
             ->setCellValue(('O' . $rowIndex), $attendee['IsAyaMember'])->getStyle(('O' . $rowIndex))->getAlignment()
               ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $rowIndex++;
  }

  $attendances = null;
}
catch (PDOException $exception)
{
  print 'Error: ' . $exception->getMessage() . '<br />';
}

$db = null;

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Teilnehmerliste - ' . date('Y-m-d', strtotime($ayaEvent['Date'])) . ' - ' . $ayaEvent['Name'] . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($export, 'Excel2007');
$writer->save('php://output');
exit;
?>
