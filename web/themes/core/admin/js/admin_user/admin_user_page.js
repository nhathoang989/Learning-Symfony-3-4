$(function () {
	$('#reservation').daterangepicker();
	$('#reservation').on('apply.daterangepicker', function(e) {
		// code goes here
		console.log(e);
	});
})