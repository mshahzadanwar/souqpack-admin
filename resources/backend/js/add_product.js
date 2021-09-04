function removeDescpSection(that){
	$(that).parent().parent().remove();
}
function removeVariation(that)
{
	$(that).parent().parent().remove();
}
function moreDescp(that,mlang)
{

	$.post(base_url+'admin/products/view_description_section/'+mlang,{data:true},function(data){
		$("#add_more_descps_in_me"+mlang).append(data);
	});
}
function moreImage(that,mlang)
{

	$.post(base_url+'admin/products/view_more_image/'+mlang,{data:true},function(data){
		$("#add_more_images_in_me"+mlang).append(data);
		$('.dropify').dropify();
	});
}

function moreUnit(that)
{

	$.post(base_url+'admin/products/view_more_unit',{data:true},function(data){
		$("#add_more_units_in_me").append(data);
	});
}
function moreOption(that,var_t)
{

	$.post(base_url+'admin/products/more_option/'+var_t,{data:true},function(data){
		$("#add_more_option_in_me"+var_t).append(data);
	});
}


function moreVariation(that,mlang)
{

	$.post(base_url+'admin/products/view_variation_section/'+mlang,{data:true},function(data){
		$("#add_more_variation_in_me"+mlang).append(data);
		$('.dropify').dropify();
	});
}

function morecVariation(that)
{

	$.post(base_url+'admin/products/view_cvariation_section',{data:true},function(data){
		$("#add_more_cvariation_in_me").append(data);
		$('.dropify').dropify();
	});
}
function removeOption(that)
{
	$(that).parent().remove();
}