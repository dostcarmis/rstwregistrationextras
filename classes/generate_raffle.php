<?php
	include "db_connection.php";

	$currentDate = date('Y-m-d');
	$data = array();
	$barcode = "";
	$name = "";
	$dateTime = "";

	$dateCreated = strtotime($currentDate);
	$_date = date("Y-m-d", $dateCreated);

	$qry = $conn->query("SELECT vis_code, vis_fname, vis_lname, created_at  
					     FROM er_visitors 
					     WHERE created_at LIKE '%$_date%'");

	if (mysqli_num_rows($qry)) {
		while ($list = mysqli_fetch_object($qry)) {
			$barcode = $list->vis_code;
			$name = $list->vis_fname . " " . $list->vis_lname;
			$dateTime = $list->created_at;
			$tempArray = array("barcode" => $barcode, 
							   "name" => $name, 
							   "dateTime" => $dateTime);
			$data[] = json_encode($tempArray);
		}
	}

	$_data = json_encode($data);

	print_r($_data);
?>