// Report JS
$(function() {
	function generatePDF(id) {
		$("#val-pdf-id").val(id);
		$("#submit-pdf").submit();
	}

	function generateExcel(id) {
		$("#val-excel-id").val(id);
		$("#submit-excel").submit();
	}

	$("#btn-pdf").unbind('click').click(function() {
		var id = $("#sel-event").val();

		generatePDF(id)
	});

	$("#btn-excel").unbind('click').click(function() {
		var id = $("#sel-event").val();

		generateExcel(id);
	});
});


// Raffle Draw JS
$(function() {
	var raffle;
	var toggle = 0;
	var listData = [];
	var interval, ctr;
	var winnersStr = "";

	function randomizeData(max) {
		var randomNumber = 0;
		randomNumber = Math.floor(Math.random() * max)

		return randomNumber;
	}

	function asyncGetData() {
		$.post('generate_raffle.php', {
		    
		}).done(function(data) {
			var jsonData = $.parseJSON(data);

			listData = [];

			$.each(jsonData, function(i, value) {
				listData.push(value);
			});

			$("#visitor-count").val(listData.length);

		}).fail(function(xhr, status, error) {
			asyncGetData();
		});
	}

	function generateRaffle() {
		$("#btn-start").attr("disabled", "disabled");

		$.post('../../../classes/generate_raffle.php', {
		    
		}).done(function(data) {
			var jsonData = $.parseJSON(data);
			var counter = 0;
			var counter2 = 0;
			winnersStr = "";
			ctr = 0;
			
			$("#btn-start").removeAttr("disabled");

			$.each(jsonData, function(i, value) {
				listData.push(value);
			});

			$("#visitor-count").val(listData.length);

			interval = setInterval(function() {
				var randomKey = randomizeData(listData.length);
				displayData(listData[randomKey]);

				if (counter == 250) {
					asyncGetData();
					counter = 0;
				}

				if (counter2 == 90000) {
					displayWinners(listData[randomKey]);
					counter2 = 0;
				}

				counter++;
				counter2++;
			}, 20);

			
		}).fail(function(xhr, status, error) {
			generateRaffle();
		});
	}

	function displayData(data) {
		var jsonList = $.parseJSON(data);
		$("#raffle").html('<p>' +
							  'Barcode: <div class="color-font-1">' + jsonList.barcode + '</div><br>' +
							  'Name: <div class="color-font-1">' + jsonList.name + '</div><br>' +
							  'Timestamp: <div class="color-font-1">' + jsonList.dateTime + '</div>' +
						  '</p');
	}

	function displayWinners(data) {
		var jsonList = $.parseJSON(data);
		ctr++;
		winnersStr += 	'<p>-----------'+ ctr + '-------------</p>' +
						'<strong>' +
							'<p>Barcode: ' + jsonList.barcode + '</p>' +
							'<p>Name: ' + jsonList.name + '</p><br>' +
						'</strong>'; 
		$("#winner-list").html(winnersStr);
	}

	$("#btn-start").unbind("click").click(function() {
		listData = [];
		$(this).html("Stop");

		if (toggle == 1) {
			toggle = 0;

			$(this).html("Raffle").removeClass("btn-danger").addClass("btn-success");
			clearInterval(interval);
		} else if (toggle == 0) {
			toggle = 1;

			generateRaffle(toggle);
			$(this).html("Stop").removeClass("btn-success").addClass("btn-danger");
		}
	});

});