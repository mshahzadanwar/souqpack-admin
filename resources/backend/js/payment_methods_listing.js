$(function(){
	'use strict';
	$('#example23').DataTable({
		dom: 'Bfrtip',
		"ordering": false,
		buttons: [
			{
				extend: 'copyHtml5',
				exportOptions: {
					//columns: [ 0,1,2]
				}
			
			},
			{
				extend: 'csvHtml5',
				exportOptions: {
					//columns: [ 0,1,2]
				}
			
			},
			{
				extend: 'excelHtml5',
				exportOptions: {
					//columns: [ 0,1,2]
				}
			
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					//columns: [ 0,1,2]
				}
			
			},
			{
				extend: 'print',
				exportOptions: {
					//columns: [ 0,1,2]
				}
			
			},
		],
		
	});
});

$(".js-switch").change(function(ev,that){
	var todo = 1;
	if($(ev.target).is(':checked'))
	{
		todo = 0;
	}

	var id = $(ev.target).attr("data-id");

	$.post(base_url+"admin/payment_methods/status/",{id:id,status:todo},function(data){});
});

	