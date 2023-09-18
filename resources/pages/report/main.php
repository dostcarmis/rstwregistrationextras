<?php
include  "../layout/main_layout.php";

$qry = $conn->query("SELECT event_title, event_id 
			     	 FROM er_events 
			     	 ORDER BY event_id ASC");

layout_start();
?>

<div class="col-md-12">
	<div class="col-md-12" style="margin-top: 20px;">
		<div class="col-md-2"></div>
		<div class="col-md-8 form-group">
			<label for="sel-event">Select Event:</label>
			<select id="sel-event" class="form-control">
				<?php
					if (mysqli_num_rows($qry)) {
						while ($data = mysqli_fetch_object($qry)) {
							echo '<option value="' . $data->event_id . '">' . $data->event_title . '</option>';
						}
					}
				?>
			</select>
		</div>
		<div class="col-md-2"></div>
	</div>
	<div class="col-md-12" style="margin-top: 20px;">
		<div class="col-md-2"></div>
		<div class="col-md-4">
			<form id="submit-pdf" action="#" method="POST">
				<input type="hidden" id="val-pdf-id" name="val-pdf-id">
				<input id="btn-pdf" class="btn btn-danger btn-block" type="submit" value="Generate as PDF File">
			</form>
		</div>
		<div class="col-md-4">
			<form id="submit-excel" action="../../../classes/generate_excel.php" method="POST">
				<input type="hidden" id="val-excel-id" name="val-excel-id">
				<input id="btn-excel" class="btn btn-success btn-block" type="submit" value="Generate as Excel File">
			</form>
		</div>
		<div class="col-md-2"></div>
	</div>
</div>

<?php
layout_end();
?>