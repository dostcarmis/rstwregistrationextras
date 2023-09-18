<?php

session_start();
include_once("../config.php");
require('pdf_generator.php');
require('../dbcon.php');
include 'preview/pr_purchase_request.php';
include 'preview/pr_canvass.php';
include 'preview/pr_abstract.php';
include 'preview/pr_purchase_order.php';
include 'preview/pr_job_order.php';
include 'preview/pr_obligation_request.php';
include 'preview/pr_disbursement_voucher.php';
include 'preview/pr_inspection_acceptance.php';

$togglePreview = false;

if (isset($_SESSION['uU_Log']) || !isset($_SESSION['emp_Log'])) {
	$togglePreview = true;
} else if (!isset($_SESSION['uU_Log']) || isset($_SESSION['emp_Log'])) {
	$togglePreview = true;
} else {
	$togglePreview = false;
}

if ($togglePreview && isset($_REQUEST['print'])) {
	$pid = $_REQUEST['print'];

	if (isset($_REQUEST['what'])) {
		$document = $_REQUEST['what'];
	}

	switch($document) {
		case "pr"://print document for purchase request
			generatePR($pid, $conn, $dir);
			break;
		case "canvass":
			generateCanvass($pid, $conn, $dir);
			break;
		case "abstract":
			$chairman = $_POST["chairman"];
			$viceChairman = $_POST["vice"];
			$member1 = $_POST["member1"];
			$member2 = $_POST["member2"];
			$toggleAlternateMember = $_POST["altmember"];
			$alternateMember = "";
			$regionalDirector = $_POST["regional"];
			$endUser = $_POST["enduser"];

			if ($toggleAlternateMember == "Yes") {
				$alternateMember = $_POST["alternate"];
			} else {
				$alternateMember = "";
			}

			generateAbstract($pid, $conn, $chairman, $viceChairman,
							 $member1, $member2, $alternateMember,
							 $regionalDirector, $endUser, $toggleAlternateMember, $dir);
			break;
		case "po":
			generatePO($pid, $conn, $dir);
			break;
		case "jo":
			generateJO($pid, $conn, $dir);
			break;
		case "ors":
			generateORS($pid, $conn, $dir);
			break;
		case "dv":
			generateDV($pid, $conn, $dir);
			break;
		case "iar":
			generateIAR($pid, $conn, $dir);
			break;
		default:
			exit();
			break;
			//end hw_Document_Attributes(hw_document)	nt for purchase request
	}
} else {
	header("Location:  ../index.php");
}

?>