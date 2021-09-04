$(document).ready(function(){
	$("input").prop("disabled",true);
	$("textarea").prop("disabled",true);
});
function rejectItem(id)
{
	$(".enable_me").prop("disabled",false);
	$(".rejectItem").modal("show");
	$(".rejectItem").find("#item_id").val(id);
}