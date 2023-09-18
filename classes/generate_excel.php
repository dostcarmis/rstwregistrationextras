<?php
include "db_connection.php";
include "phpexcel/PHPExcel.php";

$listDates = array("2017-10-10", "2017-11-07", "2017-11-08");

if (isset($_REQUEST["val-excel-id"])) {
	$objWorksheet = array();
	$eventID = $_POST["val-excel-id"];

    // Get date range
    $eventDateRange = $conn->query("SELECT event_from, event_to
                                    FROM er_events 
                                    WHERE event_id = '" . $eventID . "'
                                    LIMIT 1");

    if (mysqli_num_rows($eventDateRange)) {
        $listDates = array();

        while ($eventData = mysqli_fetch_object($eventDateRange)) {
            $dateFrom = new DateTime($eventData->event_from);
            $dateTo = new DateTime($eventData->event_to);
            $dateTo = $dateTo->modify( '+1 day' ); 
        }

        $interval = new DateInterval('P1D');
        $period = new DatePeriod($dateFrom, $interval ,$dateTo);

        foreach ($period as $key => $value) {
            $listDates[] = $value->format('Y-m-d');    
        }
    }

	// Create new PHPExcel object
	$phpExcel = new PHPExcel();
	// Set document properties
	$phpExcel->getProperties()->setCreator("e-Registration")
							  ->setLastModifiedBy("e-Registration");

	// Error reporting 
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');
	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');

	foreach ($listDates as $key => $dates) {
		$data = array();

		$qryVisitors = $conn->query("SELECT vis.vis_id, vis.vis_code, vis.vis_fname, vis.vis_mname, 
											vis.vis_lname, vis.vis_email, vis.vis_gsm, 
											vis.vis_address, vis.vis_age, vis.vis_company, 
											vis.created_at, gender.gender_name, civil.civil_name, 
											region.region_name, class.class_name 
							     	 FROM er_visitors AS vis 
							     	 INNER JOIN er_genders AS gender 
							     	 ON gender.gender_id = vis.gender_id 
							     	 INNER JOIN er_civilstatus AS civil 
							     	 ON civil.civil_id = vis.civil_id 
							     	 INNER JOIN er_regions AS region 
							     	 ON region.region_id = vis.region_id 
							     	 INNER JOIN er_classifications AS class 
							     	 ON class.class_id = vis.class_id 
							     	 WHERE vis.event_id = '" . $eventID . "' 
							     	 AND DATE(vis.created_at) = '" . $dates . "' 
							     	 ORDER BY vis.created_at ASC");

		if (mysqli_num_rows($qryVisitors)) {
			while ($_data = mysqli_fetch_object($qryVisitors)) {
				$barcode = $_data->vis_code;
				$name = $_data->vis_fname . " " . $_data->vis_mname . " " . $_data->vis_lname;
				$email = $_data->vis_email;
				$mobile = $_data->vis_gsm;
				$age = $_data->vis_age;
				$gender = $_data->gender_name;
				$civilStatus = $_data->civil_name;
				$province = $_data->region_name;
				$classification = $_data->class_name;
				$dateCreated = strtotime($_data->created_at);
				$timestamp = date("Y-m-d", $dateCreated);
				$data[] = array( $barcode, $name, $email, $mobile, $age, $gender, 
								 $civilStatus, $province, $classification, $timestamp );
			}
		}

		$qryVisitors = $conn->query("SELECT vis.vis_id, vis.vis_code, vis.vis_fname, vis.vis_mname, 
											vis.vis_lname, vis.vis_email, vis.vis_gsm, 
											vis.vis_address, vis.vis_age, vis.vis_company, 
											counter.created_at, gender.gender_name, civil.civil_name, 
											region.region_name, class.class_name 
							     	 FROM er_visitors AS vis 
							     	 INNER JOIN er_genders AS gender 
							     	 ON gender.gender_id = vis.gender_id 
							     	 INNER JOIN er_civilstatus AS civil 
							     	 ON civil.civil_id = vis.civil_id 
							     	 INNER JOIN er_regions AS region 
							     	 ON region.region_id = vis.region_id 
							     	 INNER JOIN er_classifications AS class 
							     	 ON class.class_id = vis.class_id 
							     	 INNER JOIN er_counter_visitors AS counter 
							     	 ON counter.vis_id = vis.vis_id
							     	 WHERE vis.event_id = '" . $eventID . "' 
							     	 AND DATE(counter.created_at) = '" . $dates . "' 
							     	 ORDER BY counter.created_at ASC");

		if (mysqli_num_rows($qryVisitors)) {
			while ($_data = mysqli_fetch_object($qryVisitors)) {
				$barcode = $_data->vis_code;
				$name = $_data->vis_fname . " " . $_data->vis_mname . " " . $_data->vis_lname;
				$email = $_data->vis_email;
				$mobile = $_data->vis_gsm;
				$age = $_data->vis_age;
				$gender = $_data->gender_name;
				$civilStatus = $_data->civil_name;
				$province = $_data->region_name;
				$classification = $_data->class_name;
				$dateCreated = strtotime($_data->created_at);
				$timestamp = date("Y-m-d", $dateCreated);
				$data[] = array( $barcode, $name, $email, $mobile, $age, $gender, 
								 $civilStatus, $province, $classification, $timestamp );
			}
		}

		// Add some data
		$phpExcel->setActiveSheetIndex($key)
				 ->setCellValue('A1', 'BARCODE')
		         ->setCellValue('B1', 'NAME')
		         ->setCellValue('C1', 'EMAIL')
		         ->setCellValue('D1', 'PHONE NUMBER')
		         ->setCellValue('E1', 'AGE')
		         ->setCellValue('F1', 'GENDER')
		         ->setCellValue('G1', 'CIVIL STATUS')
		         ->setCellValue('H1', 'PROVINCE')
		         ->setCellValue('I1', 'CLASSIFICATION')
		         ->setCellValue('J1', 'DATE');

		$phpExcel->setActiveSheetIndex($key)
	             ->fromArray(
				     $data,  // The data to set
				     NULL,        // Array values with this value will not be set
				     'A2'         // Top left coordinate of the worksheet range where
				                  //    we want to set these values (default is A1)
				 );

		// Rename worksheet
		$phpExcel->getActiveSheet()->setTitle($dates);

		if ($key < count($listDates) - 1) {
			$objWorksheet1 = $phpExcel->createSheet();
			$objWorksheet1->setTitle($dates);
		}
	}

	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="visitors-'. date('Y-m-d') .'.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
}

?>