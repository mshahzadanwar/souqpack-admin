function toggleMe(that)
{
	$(that).parent().parent().find(".card-body").toggle();
	var already = $(that).find("span").html();
	if(already=="+") already="-"; else already = "+";
	$(that).find("span").html(already);
}

var inter;

function assignDesigner(order_id)
{
	$("#myModal").modal("show");
	$("#myModal").find("#submit_type").val("designer");

	$.post(base_url+"admin/corders/get_online_drivers",{order_id:order_id},function(data){
			var htmlData = data;
			$(".driver_modal_body").html(htmlData);
		});
	inter = setInterval(function(){
		$.post(base_url+"admin/corders/get_online_drivers",{order_id:order_id},function(data){
			var htmlData = data;
			$(".driver_modal_body").html(htmlData);
		});
	},60000);

}




function assignProduction(order_id)
{
	$("#myModal").modal("show");
	$("#myModal").find("#submit_type").val("production");
	$.post(base_url+"admin/corders/get_online_productions",{order_id:order_id},function(data){
		var htmlData = data;
		$(".driver_modal_body").html(htmlData);
	});
}
function closeModal()
{
	clearInterval(inter);
}
function reDeliver()
{
	$("#reDeliverModal").modal("show");
}
function handleAdminChangeStatus()
{
	var v = $("#handleAdminChange").val();
	if(v==2) {
		assignDesigner();
		return false;
	}
	console.log($("#handleAdminChangeForm"));
	$("#handleAdminChangeForm").submit();

}
function handleProductionChangeStatus()
{
	var v = $("#handleProuctionChange").val();
	if(v==2) {
		$("#readyToDeliver").modal("show");
		return false;
	}
	console.log($("#handleProuctionChangeForm"));
	$("#handleProuctionChangeForm").submit();

}
function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}