$(function(){
	'use strict';

	$('.example23_table').DataTable({

		dom: 'Bfrtip',
		columnDefs: [{
        "targets": 1,
        "type": 'date',
     	}],
		// "ordering": false,
		//"aaSorting": [[ 1, "DESC" ]],
		buttons: [
			{
				extend: 'copyHtml5'
			
			},
			{
				extend: 'csvHtml5'
			
			},
			{
				extend: 'excelHtml5'
			
			},
			{
				extend: 'pdfHtml5'
			
			},
			{
				extend: 'print'
			
			},
		],
		
	});
});