function removeRowCost(that){
	$(that).parent().remove();
}

function moreRowCost()
{

	$.post(base_url+'admin/costs/print_row/',{data:true,key:on_this_page_rows},function(data){
		$("#add_more_rows_in_me").append(data);
		on_this_page_rows++;
	});
}
function showTotal(that)
{
	var qty = $(that).parent().parent().find(".qty").val();
	var cost = $(that).parent().parent().find(".cost").val();
	var total =parseInt(qty) * parseFloat(cost);
	var total = total.toFixed(2)
	$(that).parent().parent().find(".total").val(total);
}
function removeFile(that)
{
	$(that).parent().parent().remove();
	$(".do_i_remove_file").val(1);
}