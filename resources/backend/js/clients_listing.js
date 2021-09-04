$(function(){
	'use strict';
	$('#example23').DataTable({
		dom: 'Bfrtip',
		"ordering": false,
		buttons: [
			{
				extend: 'copyHtml5',
				exportOptions: {
					//columns: [ 0,1,2,3,4]
				}
			
			},
			{
				extend: 'csvHtml5',
				exportOptions: {
					//columns: [ 0,1,2,3,4]
				}
			
			},
			{
				extend: 'excelHtml5',
				exportOptions: {
					//columns: [ 0,1,2,3,4]
				}
			
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					//columns: [ 0,1,2,3,4]
				}
			
			},
			{
				extend: 'print',
				exportOptions: {
					//columns: [ 0,1,2,3,4]
				}
			
			},
		],
		
	});
});