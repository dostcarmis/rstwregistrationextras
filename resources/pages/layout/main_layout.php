<?php
include "../../../classes/db_connection.php";

function layout_start() {
?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>RSTW e-Registration Extras</title>
		<link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../assets/css/main.css">
	</head>
	<body>
		<div class="container">
			<div class="row">
<?php
}

function layout_end() {
?>

			</div>
		</div>
		<script type="text/javascript" src="../../assets/js/jquery-2.1.3.min.js"></script>
		<script type="text/javascript" src="../../assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../../assets/js/main.js"></script>
	</body>
	</html>
<?php
}
?>