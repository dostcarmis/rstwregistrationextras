<?php
include  "../layout/main_layout.php";

layout_start();
?>

<div class="col-md-12">
	<div class="col-md-12">
		<div class="col-md-1"></div>
		<div class="col-md-6">
			<h3>
				Number of Visitor/s: 
				<input id="visitor-count" class="color-font-1 form-control" disabled="disabled" 
					   style="background-color: #fff;font-size: 85%;">
			</h3>
		</div>
		<div class="col-md-5" style="margin-top: 40px;">
			<h3 class="color-font-1"><strong>Winners: (Every 30mins.)</strong></h3>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-1"></div>
		<div class="col-md-6">
			<div id="raffle"></div>
		</div>
		<div class="col-md-5">
			<div class="well" id="winner-list"></div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-1"></div>
		<div class="col-md-6">
			<button id="btn-start" class="btn btn-success btn-block">Raffle</button>
		</div>
		<div class="col-md-5"></div>
	</div>

</div>

<?php
layout_end();
?>