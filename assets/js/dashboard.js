function generate_chart(id,labels,data,label="My First dataset"){
	if ($("#"+id).length > 0) {
		var ctx = document.getElementById(id).getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'line',
				data: {
					labels: labels,
					datasets: [{
						label:label,
						data: data,
						backgroundColor: [
							'rgba(36, 109, 248, .2)'
						],
						borderColor: [
							'rgba(36, 109, 248, 1 )'
						],
						borderWidth: 1
					}]
			},
			options: {
				title: {
					display: true
				},
				gridLines: {
					display: true
				},
				legend: {
					display: true
				},
				tooltips: {
					mode: 'index',
					intersect: true
				},
				responsive: true,
				scales: {
					xAxes: [{
						stacked: true,
					}],
					yAxes: [{
						stacked: true,
					}]
				}
			}
		});
	}
}
$(document).ready(function() {
	/*---------------------------------------------
		Dashboard
	---------------------------------------------*/
	
	
	
	

	$('.upload-profile-photo .file-input').change(function(){
	    var curElement = $(this).parent().parent().find('.image');
	    var reader = new FileReader();
	    reader.onload = function (e) {
	        // get loaded data and render thumbnail.
	        curElement.attr('src', e.target.result);
	    };
	    // read the image file as a data URL.
	    reader.readAsDataURL(this.files[0]);
	});



	$('.send-file .file-input').change(function(){
	    var curElement = $(this).parent().parent().find('.image');
	    var reader = new FileReader();

	    reader.onload = function (e) {
	        // get loaded data and render thumbnail.
	        curElement.attr('src', e.target.result);
	    };

	    // read the image file as a data URL.
	    reader.readAsDataURL(this.files[0]);
	});
    /*-------------------------------------
      tooltip
    -------------------------------------*/

    $('.user-number i').tooltip();







})

