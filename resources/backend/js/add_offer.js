function date_applies_checked(that){
	if($(that).is(':checked'))
	{
		$(".date_applies").show();

	}
	else{
		$(".date_applies").hide();

	}
}
function getProducts(that,product)
{

	$.post(base_url+'admin/offers/view_products_section/'+that.value,{id:that.value}+'/'+product,function(data){
		$(".add_products_view_inside_me").html(data);
	});
}